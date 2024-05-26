<?php

namespace App\Enum;

enum UserStatus: int
{
    use EnumToArrayTrait;

    case Active = 1;
    case Blocked = 2;
}
