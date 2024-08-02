<?php

namespace App\Repositories;

use App\Exceptions\PlantNotFoundException;
use App\Interfaces\PlantRepositoryInterface;
use App\Models\Plant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PlantRepository implements PlantRepositoryInterface {
    public function find(string $field, string $operator, $value): Plant
    {
        $plant = Plant::where($field, $operator, $value)->first();
        if (! $plant) {
            throw new PlantNotFoundException();
        }

        return $plant;
    }

    public function findAll(): Collection
    {
        $plants = Plant::all();

        if (!$plants) {
            throw new PlantNotFoundException();
        }

        return $plants;
    }

    public function create(Request $request): Plant
    {
        $plant = Plant::create($request->all());

        if ( ! $plant) {
            throw new PlantNotFoundException();
        }

        return $plant;
    }

    public function update(Request $request, string $common_name): Plant
    {
        $validatedData = $request->validate([
            'common_name' => 'sometimes|string|max:255',
            'watering_general_benchmark' => 'sometimes|array',
            'watering_general_benchmark.value' => 'sometimes|string',
            'watering_general_benchmark.unit' => 'sometimes|string',
        ]);

        $plant = $this->find('common_name', 'LIKE', '%' . $common_name . '%');

        if (isset($validatedData['common_name'])) {
            $plant->common_name = $validatedData['common_name'];
        }
    
        if (isset($validatedData['watering_general_benchmark'])) {
            $wateringBenchmark = $plant->watering_general_benchmark;
    
            if (isset($validatedData['watering_general_benchmark']['value'])) {
                $wateringBenchmark['value'] = $validatedData['watering_general_benchmark']['value'];
            }
    
            if (isset($validatedData['watering_general_benchmark']['unit'])) {
                $wateringBenchmark['unit'] = $validatedData['watering_general_benchmark']['unit'];
            }
    
            $plant->watering_general_benchmark = $wateringBenchmark;
        }
    
        $plant->save();
        return $plant;
    }

    public function delete(int $id)
    {
        $plant = $this->find('id', "=", $id);
        if (!$plant) {
            throw new PlantNotFoundException();
        }
        $plant->delete();

    }
}