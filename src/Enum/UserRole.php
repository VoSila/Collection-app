<?php

namespace App\Enum;

enum UserRole: string
{
    use EnumToArrayTrait;

    case User = 'ROLE_USER';
    case Admin = 'ROLE_ADMIN';
}
