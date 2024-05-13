<?php

namespace App\Enum;

enum UserStatus: int
{
    use EnumToArrayTrait;

    case AwaitingActivation = 1;
    case Active = 2;
    case Blocked = 3;
}
