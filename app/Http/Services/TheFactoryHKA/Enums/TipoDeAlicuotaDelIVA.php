<?php

namespace App\Http\Services\TheFactoryHKA\Enums;

/**
 * Tipos de Alícuotas del IVA
 */
abstract class TipoDeAlicuotaDelIVA {
     const alicuota_reducida = 'R';
     const alicuota_general = 'G';
     const alicuota_adicional = 'A';
     const exento = 'E';
     const percibido = 'P';
     const igtf = 'IGTF';
}