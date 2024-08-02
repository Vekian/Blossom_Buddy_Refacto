<?php

namespace App\Interfaces;

use App\Models\Plant;

interface WateringStrategyInterface {
    public function calculateDaysUntilNextWatering(Plant $plant, string $city);
}