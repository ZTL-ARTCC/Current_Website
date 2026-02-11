<?php

namespace App\Enums;

enum SessionVariables: string {
    case VATSIM_AUTH_STATE = 'vatsimauthstate';
    case REALOPS_PILOT_REDIRECT = 'pilot_redirect';
    case REALOPS_PILOT_REDIRECT_PATH = 'pilot_redirect_path';
}
