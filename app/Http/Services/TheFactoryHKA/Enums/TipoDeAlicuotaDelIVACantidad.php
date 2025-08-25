<?php

namespace App\Http\Services\TheFactoryHKA\Enums;

/**
 * Porcentaje asociado con cada tipo de Alícuotas del IVA
 */
abstract class TipoDeAlicuotaDelIVACantidad {
     const alicuota_reducida = '8';
     const alicuota_general = '16';
     const alicuota_adicional = '31';
     const exento = '0';
     const percibido = '0';
     const igtf = '3';
}