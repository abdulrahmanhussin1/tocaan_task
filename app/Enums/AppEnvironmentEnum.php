<?php

namespace App\Enums;

enum AppEnvironmentEnum: string
{
    case LOCAL = 'local';
    case PRODUCTION = 'production';
    case STAGING = 'staging';
    case TESTING = 'testing';
}
