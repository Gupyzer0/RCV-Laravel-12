<?php

namespace App\Http\Services\TheFactoryHKA\Enums;

/**
 * Tipo de identificación
 */
abstract class TipoDeIdentificacion {
     const persona_natural = 'V';
     const persona_juridica = 'J';
     const extranjero_residencido_en_venezuela = 'E';
     const agente_registrado_con_pasaporte = 'P';
     const ente_gubernamental = 'G';
     const comunal = 'C';
}