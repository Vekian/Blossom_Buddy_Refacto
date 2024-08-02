<?php

namespace App\Services;

use App\Interfaces\LoggingServiceInterface;
use App\Interfaces\PlantServiceInterface;

class PlantServiceLoggingDecorator implements PlantServiceInterface
{
    public function __construct(private PlantService $plantService, private LoggingServiceInterface $loggingService)
    {
        
    }

    public function fetchAndStorePlants()
    {
        $this->loggingService->log('Fetching plants...');
        $this->plantService->fetchAndStorePlants();
        $this->loggingService->log('Plants fetched and stored successfully.');
    }
}
