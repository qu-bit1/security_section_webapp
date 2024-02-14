<?php

namespace App\Enums;

enum RolesEnum: string {
    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';
    case USER = 'user';
}
