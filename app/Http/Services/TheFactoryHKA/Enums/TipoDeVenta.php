<?php

namespace App\Http\Services\TheFactoryHKA\Enums;

/**
 * Tipo de Venta
 */
abstract class TipoDeVenta {
     const interna = 'Interna';
     const exportacion_incoterm = 'Exportación (INCOTERM)';
     const fob = 'FOB';
     const cif = 'CIF';
     const exw = 'EXW';
}