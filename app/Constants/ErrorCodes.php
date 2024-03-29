<?php

namespace App\Constants;

class ErrorCodes
{
    // Errores relacionados con la validación de datos
    const VALIDATION_ERROR = 'E001';
    const INVALID_INPUT = 'E002';
    const LIST_ERROR = 'E003';
    const CREATE_ERROR = 'E004';
    const SHOW_ERROR = 'E005';
    const UPDATE_ERROR = 'E006';

    // Errores relacionados con la autenticación
    const UNAUTHORIZED = 'E101';
    const FORBIDDEN = 'E102';

    // Otros errores generales
    const NOT_FOUND = 'E201';
    const INTERNAL_ERROR = 'E202';

    const ETIQUETA_NOT_FOUND        = 'E202';
    const ETIQUETA_OTHER_GROUP      = 'E203';
    const ETIQUETA_QUANTITY_DIFFERS = 'E204';
    const ETIQUETA_DESPACHADA_OK    = 'error-code.E205';
    const ETIQUETA_ENTREGADA_OK    = 'error-code.E206';

    const ETIQUETA_ESTADO_ERRONEO = 'E301';

}
