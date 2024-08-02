<?php

namespace App\Strategies;

use App\Interfaces\WateringStrategyInterface;
use App\Services\WeatherService;

class DefaultWateringStrategy implements WateringStrategyInterface {

    public function __construct(private WeatherService $weatherService)
    {

    }
    public function calculateDaysUntilNextWatering($plant, $city)
    {
        // Extract watering benchmarks from the plant
        $wateringBenchmark = $plant->watering_general_benchmark;
        $unit = $wateringBenchmark['unit'];
        $value = $wateringBenchmark['value'];

        // Calculate the number of days until the next watering
        $daysUntilNextWatering = 0;
        if ($unit === 'days') {
            $range = explode('-', $value);
            $daysUntilNextWatering = (int) $range[0]; // Taking the lower bound of the range
        } elseif ($unit === 'week') {
            $range = explode('-', $value);
            $daysUntilNextWatering = (int) $range[0] * 7; // Convert weeks to days
        }

        // Determine the number of days to pass to the weather service
        $daysForWeatherService = $daysUntilNextWatering >= 5 ? 5 : $daysUntilNextWatering;

        // Use the weather service to get the forecast for the city
        $weatherData = $this->weatherService->getWeatherForecast($city, $daysForWeatherService);

        // We will convert days into hours to be able to delay the job
        $hoursUntilNextWatering = $daysUntilNextWatering * 24;

        // For each day in the forecast, calculate a coefficient and apply it to the days until next watering
        foreach ($weatherData as $day) {
            $humidity = $day['avghumidity'];

            // For eache 10% above 70%, we add 10% to daysUntilNextWatering
            if ($humidity > 70) {
                $tranchesAbove70 = floor(($humidity - 70) / 10) + 1;
                $hoursUntilNextWatering += $hoursUntilNextWatering * (0.1 * $tranchesAbove70);
            }
            // For each 10% under 40%, we remove 10% to daysUntilNextWatering
            elseif ($humidity < 40) {
                $tranchesBelow40 = floor((40 - $humidity) / 10) - 1;
                $hoursUntilNextWatering -= $hoursUntilNextWatering * (0.1 * $tranchesBelow40);
            }
        }

        return $hoursUntilNextWatering;
    }
}