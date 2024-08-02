<?php

namespace App\Interfaces;

use App\Models\Plant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface PlantRepositoryInterface
{
    public function find(string $field, string $operator, $value): Plant;
    public function findAll(): Collection;
    public function create(Request $request): Plant;
    public function update(Request $request, string $common_name): Plant;
    public function delete(int $îd);
}
