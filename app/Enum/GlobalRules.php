<?php

namespace App\Enum;

enum GlobalRules: int
{
    case TAX_PERCENTAGE = 10;
    case ALLOWED_EDITING_TIME = 1; // hours
}