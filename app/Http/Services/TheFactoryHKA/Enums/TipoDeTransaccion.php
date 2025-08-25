<?php

namespace App\Http\Services\TheFactoryHKA\Enums;

/**
 * Tipo de Transacción
 */
abstract class TipoDeTransaccion {
     const registro = '01';
     const complemento = '02';
     const anulacion = '03';
     const ajuste = '04';
}
