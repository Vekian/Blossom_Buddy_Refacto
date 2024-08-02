<?php

namespace App\Services;

use App\Interfaces\LoggingServiceInterface;
use Illuminate\Support\Facades\Log;

class LoggingService implements LoggingServiceInterface {
    public function log(string $message): void
    {
        Log::info($message);
    }
}