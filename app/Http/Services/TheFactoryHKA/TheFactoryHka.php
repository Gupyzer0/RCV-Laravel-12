<?php

namespace App\Http\Services\TheFactoryHKA;

use Exception;
use App\Models\Llave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use App\Http\Services\TheFactoryHKA\Enums\TipoDeDocumentoFiscal;
use App\Http\Services\TheFactoryHKA\Enums\TipoDeTransaccion;
use App\Http\Services\TheFactoryHKA\Enums\TipoDeVenta;
use App\Http\Services\TheFactoryHKA\Enums\TipoDePago;
use App\Http\Services\TheFactoryHKA\Enums\CodigoInternacionalDeMoneda;

class TheFactoryHka {
    protected $url;
    private $client;
    private $usuario;
    private $password;
    private $token = null;

    public function __construct() 
    {
        $this->url = config('services.the_factory_hka.api_url');
        $this->usuario = config ('services.the_factory_hka.TokenUsuario');
        $this->password = config ('services.the_factory_hka.TokenPassword');

        $this->client = new Client([
            'base_uri' => $this->url,
            'timeout'  => 15.0,
        ]);

        $this->token = $this->obtener_token();
    }

    /**
     * Crea un documento (factura, nota de credito | debito)
     */
    public function emision($data)
    {
        $respuesta = $this->gPost($this->url.'/api/Emision', $data);
        return $respuesta;
    }

    /**
     * Descarga un archivo por numero / tipo de documento
     * 
     * De momento no se incluye el parametro de serie ya que no se usa.
     */
    public function descargar_archivo($tipo_documento, $numero_documento)
    {
        // Creamos la petición para la descarga
        $data = [
            'tipoDocumento' => $tipo_documento,
            'numeroDocumento' => $numero_documento
        ];

        $respuesta = $this->gPost($this->url.'/api/DescargaArchivo', $data);

        // Verificamos la respuesta, si es diferente a 200 -> excepción
        if($respuesta['codigo'] != 200)
        {
            throw new Exception('No se pudo descargar el documento: ' . $respuesta['mensaje']);
        }
        // Decodificamos el archivo que viene como base64
        $b64decodificado = base64_decode($respuesta['archivo']);

        // Verificamos que se haya podido decodificar el documento
        if ($b64decodificado === false || empty($b64decodificado))
        {
            throw new Exception('Error al decodificar el PDF');
        }

        // Le damos nombre al archivo
        $nombre_archivo = 'documento_' . $numero_documento . '-' . time() . '.pdf';

        // Devolvemos una descarga . . .
        return Response::make($b64decodificado, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nombre_archivo . '"',
            'Content-Length' => strlen($b64decodificado),
        ]);
    }

    /**
     * Envio de documento via correo
     */
    public function enviar_archivo($tipo_documento, $numero_documento, $arr_correos)
    {
        // Creamos la petición
        $data = [
            'serie' => '', // Sino se envia vacio no funciona
            'tipoDocumento' => $tipo_documento,
            'numeroDocumento' => $numero_documento,
            'correos' => $arr_correos,
        ];

        $respuesta = $this->gPost($this->url.'/Correo/Enviar', $data);

        // Verificamos la respuesta, si es diferente a 200 -> excepción
        if($respuesta['codigo'] != 200)
        {
            throw new Exception('Problema al enviar correo electrónico: ' . $respuesta['mensaje']);
        }

        return $respuesta['mensaje'];
    }

    /**
     * Anula un documento
     */
    public function anulacion($tipo_documento, $numero_documento, $motivo_anulacion) {
        // Obtenemos fecha y hora actual
        $fecha_hora_actual = Carbon::now();
        
        $data = [
            'tipoDocumento' => $tipo_documento,
            'numeroDocumento' =>$numero_documento,
            'motivoAnulacion' => $motivo_anulacion,
            'fechaAnulacion' => $fecha_hora_actual->format('d/m/Y'), // dd/mm/aaaa
            'horaAnulacion' => $fecha_hora_actual->format('h:i:s a'), // 00:00:00 am|pm
        ];

        $respuesta = $this->gPost($this->url.'/Anular', $data);

        // Verificamos la respuesta, si es diferente a 200 -> excepción
        if($respuesta['codigo'] != 200)
        {
            throw new Exception('Problema al anular el documento: ' . $respuesta['mensaje']);
        }

        return $respuesta['mensaje'];
    }

    /**
     * Autenticacion
     */
    private function obtener_token() {
        // Verificar si existe el valor en la tabla de base de datos
        $llave = LLave::where('descripcion','the_factory_hka_jwt')->firstOr(function () {
            // Si no existe el registro, crear una excepcion ya que es necesario crearlo en la tabla manualmente
            throw new Exception('Es necesario crear el registro de llave en la tabla de base de datos para The Factory HKA');
        });

        // Verificar si el token tiene fecha de expiracion
        if($llave->expiracion === null) {
            return $this->actualizar_token($llave)->llave;
        }
        
        // Verificar si el token esta expirado
        if($llave->expiracion->isPast()) {
            return $this->actualizar_token($llave)->llave;
        }

        return $llave->llave;        
    }

    /**
     * Actualiza el token autenticandose contra la API
     */
    private function actualizar_token($llave) {
        $data = [
            'usuario' => $this->usuario,
            'clave' => $this->password,
        ];

        $respuesta = $this->gPost('/api/Autenticacion', $data);

        $llave->update([
            'llave' => $respuesta['token'],
            'expiracion' => new Carbon($respuesta['expiracion']),
        ]);
        
        return $llave;
    }

    /**
     * Función para realizar peticiones Post usando guzzle
     */
    private function gPost($endpoint, $data)
    {
        $headers = [];

        /**
         * Agregando token si esta listo, normalmente la peticion iria sin token
         * solamente al usar el metodo de autorizacion.
         */
        if($this->token != null) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        try {
            $response = $this->client->post($endpoint, [
                'json' => $data,
                'headers' => $headers,
            ]);

            $statusCode = $response->getStatusCode();
            $respuesta = json_decode($response->getBody()->getContents(), true);

            if ($statusCode >= 200 && $statusCode < 300) {
                return $respuesta;
            } else {
                Log::error("1-Error de API. $endpoint. Estatus: $statusCode. Respuesta: " . json_encode($respuesta));
                throw new Exception("Error de API. $endpoint. Estatus: $statusCode. Respuesta: " . json_encode($respuesta));
            }
        } catch (RequestException $e) {
            Log::error("2-Excepción de guzzle hacia el endpoint -> $endpoint: " . $e->getMessage());
            if ($e->hasResponse()) {
                Log::error("Error de API. Respuesta: " . $e->getResponse()->getBody()->getContents());
            }
            return null;
        } catch (\Exception $e) {
            Log::error("3-Excepción general durante una petición a la API hacia $endpoint: " . $e->getMessage());
            return null;
        }
    }
}
