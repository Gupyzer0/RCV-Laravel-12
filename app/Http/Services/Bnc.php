<?php

namespace App\Http\Services;

use Exception;
use App\Llave;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

/**
 * Permite el manejo de todo lo relacionado al BNC
 * 
 * $clientGUID => Obtener el clientGUID del .env -> es el ID del cliente.
 * $workingKey => La working key tiene un límite de tiempo por ende es se tiene una función 
 * que permite obtenerla desde la base de datos y solo actualizarla (osea realizar el login contra '/api/Auth/LogOn')
 * cuando sea necesario. Para esto es necesario usar la $masterKey.
 */
class Bnc
{
    private $masterKey;
    private $workingKey;
    private $clientGUID;

    public function __construct()
    {
        $this->clientGUID = config('services.bnc.client_guid');
        $this->masterKey = config('services.bnc.master_key');
        $this->workingKey = $this->obtenerWorkingKey();
    }

    /**
     * Valida un pago movil usando la referencia
     * 
     * @return mixed Retorna falso si la peticion falla de cualquier manera y
     * un objeto de retornar una respuesta correcta.
     */
    public function validarPagoMovilReferencia($referencia, $fecha, $cantidad)
    {
        if (!$this->workingKey || !$this->clientGUID) {
            throw new Exception('Las credenciales del BNC no estan configuradas');
        }

        // Creando petición y formateandola para ser usada con la API del BNC
        $soliVpos = [
            "ClientID" => config('services.bnc.rif_cliente'),
            "AccountNumber" => config('services.bnc.account_number'),
            "Reference" => $referencia,
            "Amount" => $cantidad,
            "DateMovement" => $fecha,
            "ChildClientID" => "",
            "BranchID" => ""
        ];

        $jsonSolicitud = $this->formatear_peticion($soliVpos, $this->workingKey);

        // Llamada a la API -> TODO: usar Guzzle ...
        $gurl = config('services.bnc.api_url') . "/api/Position/Validate";
        $gResult = $this->gPost($gurl, $jsonSolicitud);

        // Manejo de respuesta
        if (isset($gResult['status']) && $gResult['status'] === 'OK') {
            $respuesta = json_decode($this->decrypt($gResult['value'], $this->workingKey));

            if ($respuesta->MovementExists == true) {
                return $respuesta;
            } else {
                return false;
            }
        } else {
            // TODO: crear mejor mensaje de error y logueo para todos los posibles errores listados en 
            // la documentacion del BNC
            $mensaje = $gResult['message'] ?? 'Error desconocido';
            $raw_response = $gResult['raw_response'] ?? 'Error desconocido';
            throw new Exception('Error al momento de contactar al BNC: ' . $mensaje . '. raw_response: ' . $raw_response);
        }
    }

    /**
     * Realiza un pago movil
     */
    public function realizarPagoMovil($cantidad, $codigoBanco, $telefono, $cedula, $descripcion, $nombreBeneficiario, $operationRef='', $email = '', $ChildClientID='', $BranchID='')
    {
        if (!$this->workingKey || !$this->clientGUID) {
            throw new Exception('Las credenciales del BNC no estan configuradas');
        }

        // Creando petición y formateandola para ser usada con la API del BNC
        $soliVpos = [
            "Amount" => $cantidad, // * -> EJ 1568.88
            "BeneficiaryBankCode" => $codigoBanco, // * -> EJ 191
            "BeneficiaryCellPhone" => $telefono, // * -> EJ 584242207524
            "BeneficiaryEmail" => $email,
            "BeneficiaryID" => $cedula, // * -> EJ V23000760
            "BeneficiaryName" => $nombreBeneficiario, // * -> EJ Leo Prueba
            "Description" => $descripcion, // * -> EJ Prueba de pago movil
            "OperationRef" => $operationRef, // Referencia interna, se rellena para evitar una operación duplicada
            "ChildClientID" => $ChildClientID,
            "BranchID" => $BranchID
        ];

        $jsonSolicitud = $this->formatear_peticion($soliVpos, $this->workingKey);

        // Llamada a la API -> TODO: usar Guzzle ...
        $gurl = config('services.bnc.api_url') . "/api/MobPayment/SendP2P";
        $gResult = $this->gPost($gurl, $jsonSolicitud);

        // Manejo de respuesta
        if (isset($gResult['status']) && $gResult['status'] === 'OK') {
            $respuesta = array(
                'status' => $gResult['status'],
                'message' => $gResult['message'],
                'value' => $gResult['value'],
                'value_decrypted' => json_decode($this->decrypt($gResult['value'], $this->workingKey), true),
                'validation' => $gResult['validation'],
            );
            return $respuesta;
        } else {
            // TODO: crear mejor mensaje de error y logueo para todos los posibles errores listados en 
            // la documentacion del BNC
            $mensaje = $gResult['message'] ?? 'Error desconocido';
            // $raw_response = $gResult['raw_response'] ?? 'Error desconocido'; por los momentos sin el raw response ... 
            throw new Exception('Error al momento de contactar al BNC: ' . $mensaje);
        }
    }

    /**
     * Obtiene la working key desde la base de datos y si esta esta vencida, 
     * obtiene una nueva. La llave se refresca todos los dias a las 12 am.
     */
    private function obtenerWorkingKey()
    {
        $llave = Llave::where('descripcion', 'workingkey bnc')->first();
        
        // Verificar si la llave no tiene fecha de expiracion
        if($llave->expiracion === null) {
            return $this->actualizar_working_key($llave)->llave;
        }

        // Verificar si la llave esta en el pasado
        if($llave->expiracion->isPast()) {
            return $this->actualizar_working_key($llave)->llave;
        }

        // Si no sucede ninguno de los 2 casos anteriores simplemente retornamos la llave

        return $llave->llave;
    }

    /**
     * Usada para actualizar la working key actual
     */
    private function actualizar_working_key($llave) {

        // Creando peticion
        $soliVpos = [
            "ClientGUID" => $this->clientGUID,
        ];

        $jsonSolicitud = $this->formatear_peticion($soliVpos, $this->masterKey);

        // Ejecutando petición
        $gurl = config('services.bnc.api_url') . "/api/Auth/LogOn";
        $gResult = $this->gPost($gurl, $jsonSolicitud);
        
        // Manejo de respuesta y decodificando la nueva working key
        if (isset($gResult['status']) && $gResult['status'] === 'OK') {
            $respuesta = json_decode($this->decrypt($gResult['value'], $this->masterKey));
            $workingKey = $respuesta->WorkingKey;

            // Almacenando nueva llave de trabajo en DB.
            // Una hora_de_creacion diferente al updated_at es signo de que la llave fue modificada manualmente por "x" razon.
            // así que prefiero trabajar con esa columna extra por si acaso.
            $llave->update(
                [
                    'llave' => $workingKey,
                    'hora_de_creacion' => Carbon::now(),
                    'expiracion' => Carbon::tomorrow(), // La llave del BNC se vence siempre a la media noche
                ]
            ); 
        } else {
            $mensaje_error = $gResult['message'] ?? 'Error al actualizar la llave de trabajo';
            Log::error('Fallo al obtener workingKey del BNC: ' . $mensaje_error);
            throw new Exception('Fallo al obtener una llave del trabajo del BNC');
        }

        return $llave;
    }

    /**
     * Encripta un arreglo de parametros usando la workingLey (o Master Key en caso de ser el Logon)
     * para luego formatear estos datos a un Json con el formato usado por el BNC para sus peticiones.
     * 
     * @param array $soliVpos Petición en formato de arreglo asociativo
     * @param string $masterKey WorkingKey o MasterKey si es LogOn
     */
    private function formatear_peticion($soliVpos, $masterKey)
    {
        $jsonVpos = json_encode($soliVpos);
        $vPos_value = $this->encrypt($jsonVpos, $masterKey);
        $vPos_referencia = $this->refere();
        $vPos_validation = $this->createHash($jsonVpos);
        $vPos_solicitud = array("ClientGUID" => config('services.bnc.client_guid'), "value" => $vPos_value, "Validation" => $vPos_validation, "Reference" => $vPos_referencia, "swTestOperation" => false);
        $jsonSolicitud = json_encode($vPos_solicitud);

        return $jsonSolicitud;
    }

    /**
     * Función usada para encriptar los datos con la llave de trabajo "working key"
     *  del BNC ya que la API solo acepta los datos codificados con esta misma llave.
     */
    private function encrypt($data, $Masterkey)
    {
        $method = 'aes-256-cbc';
        $sSalt = chr(0x49) . chr(0x76) . chr(0x61) . chr(0x6e) . chr(0x20) . chr(0x4d) . chr(0x65) . chr(0x64) . chr(0x76) . chr(0x65) . chr(0x64) . chr(0x65) . chr(0x76);

        $pbkdf2 = hash_pbkdf2('SHA1', $Masterkey, $sSalt, 1000, 48, true);
        $key = substr($pbkdf2, 0, 32);
        $iv =  substr($pbkdf2, 32, strlen($pbkdf2));

        $string =  mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');
        $encrypted = base64_encode(openssl_encrypt($string, $method, $key, OPENSSL_RAW_DATA, $iv));
        return $encrypted;
    }

    /**
     * Función usada para desencriptar los datos con la llave de trabajo
     */
    private function decrypt($data, $Masterkey)
    {
        $method = 'aes-256-cbc';
        $sSalt = chr(0x49) . chr(0x76) . chr(0x61) . chr(0x6e) . chr(0x20) . chr(0x4d) . chr(0x65) . chr(0x64) . chr(0x76) . chr(0x65) . chr(0x64) . chr(0x65) . chr(0x76);

        $pbkdf2 = hash_pbkdf2('SHA1', $Masterkey, $sSalt, 1000, 48, true);
        $key = substr($pbkdf2, 0, 32);
        $iv =  substr($pbkdf2, 32, strlen($pbkdf2));

        $string = openssl_decrypt(base64_decode($data), $method, $key, OPENSSL_RAW_DATA, $iv);
        $decrypted = mb_convert_encoding($string, 'UTF-8', 'UTF-16LE');

        return $decrypted;
    }

    /**
     * Función para realizar peticiones Post usando CURL
     */
    private function gPost($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Tiempo para conectar (segundos)
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Tiempo total para la transferencia (segundos)

        $response = curl_exec($ch);

        // --- MANEJO DE ERRORES DE CURL ---
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            $error_code = curl_errno($ch);
            curl_close($ch);
            Log::error("cURL Error ({$error_code}): {$error_msg} for URL {$url}");
            return ['status' => 'error', 'message' => "Error de conexión cURL: ({$error_code}) {$error_msg}", 'statusCode' => 500]; // O un código más específico si curl_errno lo indica
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Obtener el código de estado HTTP

        if ($http_code >= 400) {
            Log::error("HTTP Error: {$http_code} for URL {$url}. Response body: " . $response);
            // No retornamos aquí, continuamos para intentar decodificar el cuerpo como JSON de la API
        }

        curl_close($ch); // Cerramos CURL después de obtener todo

        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("JSON Decode Error: " . json_last_error_msg() . " for response: " . $response);
            // Si falla la decodificación JSON, retornamos un error estructurado
            return ['status' => 'error', 'message' => "Error al decodificar la respuesta JSON de la API: " . json_last_error_msg(), 'statusCode' => 500, 'raw_response' => $response];
        }

        $decodedResponse['statusCode'] = $http_code; // Añadir el código HTTP a la respuesta decodificada
        return $decodedResponse; // Retorna el array decodificado (que incluye ahora el statusCode)
    }

    /**
     * Función auxiliar para formatear fecha a la fecha requerida por la API del BNC
     * TODO: Quizás sea mas sabio usar Carbon para esto . . .
     */
    private function refere()
    {
        $fecha = date('Y-m-d h:i:s', time());
        $fecha = strval($fecha);
        $fecha = str_replace("-", "", $fecha);
        $fecha = str_replace(":", "", $fecha);
        $fecha = str_replace(" ", "", $fecha);
        $result = $fecha;
        return $result;
    }

    /**
     * Crea hash para la data enviada
     */
    private function createHash($data){
        //$validation = hash('sha256', utf8_encode($data)); -> depreciado ... probando mb_convert_encoding
        $validation = hash('sha256', mb_convert_encoding($data, 'UTF-8', 'ISO-8859-1'));
        return $validation;
    }
}
