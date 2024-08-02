<?php

namespace App\Interfaces;

interface LoggingServiceInterface {
    public function log(string $message): void;
}