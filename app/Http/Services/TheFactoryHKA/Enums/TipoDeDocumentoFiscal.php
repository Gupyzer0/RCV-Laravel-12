<?php

namespace App\Http\Services\TheFactoryHKA\Enums;

/**
 * Tipo de Documento
 */
abstract class TipoDeDocumentoFiscal {
     const factura = '01';
     const nota_de_credito = '02';
     const nota_de_debito = '03';
     const nota_de_entrega_o_guia_de_despacho = '04';
     const comprobante_de_retencion_iva = '05';
     const comprobante_de_retencion_islr = '06';
}
