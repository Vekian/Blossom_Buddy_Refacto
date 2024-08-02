<?php

namespace App\Strategies\Context;

use App\Interfaces\WateringStrategyInterface;
use App\Interfaces\WeatherServiceInterface;
use App\Models\Plant;
use App\Strategies\DefaultWateringStrategy;

class WateringStrategyContext
{
    private WateringStrategyInterface $strategy;

    public function __construct(string $method, WeatherServiceInterface $weatherService){
        $this->strategy = match ($method) {
            "default" => new DefaultWateringStrategy($weatherService),
        };
    }

    public function calculateDaysUntilNextWatering(Plant $plant, string $city) {
        return $this->strategy->calculateDaysUntilNextWatering($plant, $city);
    }
}