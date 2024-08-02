<?php

namespace App\Http\Controllers;

use App\Exceptions\PlantNotFoundException;
use App\Models\Plant;
use App\Repositories\PlantRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Blossom Buddy - API Documentation",
 *      description="Blossom Buddy app, backend documentation", 
 * )
 */
class PlantController extends Controller
{

    public function __construct( 
        private PlantRepository $plantRepository
    ){

    }

    /**
     * @OA\Get(
     *      path="/api/plants",
     *      operationId="getPlantsList",
     *      tags={"Plants"},
     *      summary="Get list of plants",
     *      description="Returns list of plants",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Plant")
     *          )
     *       )
     *     )
     */
    public function index(): JsonResponse
    {
        try {
            $plants = $this->plantRepository->findAll();
        } catch( PlantNotFoundException $e) {
            return response()->json(['message' => 'Can\'t find any plants'], 404);
        }
        return response()->json($plants);
    }


    /**
     * @OA\Post(
     *     path="/api/plants",
     *     operationId="storePlant",
     *     tags={"Plants"},
     *     summary="Store a new plant",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Plant")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Plant created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $plant = $this->plantRepository->create($request);
        } catch (PlantNotFoundException $e) {
            return response()->json(['message' => 'Can\'t create plant'], 404);
        }
        
        return response()->json($plant, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/plants/{common_name}",
     *     operationId="getPlantByCommonName",
     *     tags={"Plants"},
     *     summary="Get plant by common name",
     *     description="Returns a single plant",
     *     @OA\Parameter(
     *         name="common_name",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *         description="The common name of the plant"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Plant")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plant not found"
     *     )
     * )
     */
    public function show(string $common_name): JsonResponse
    {
        try {
            $plant = $this->plantRepository->find('common_name', 'LIKE', '%' . $common_name . '%');
        } catch (PlantNotFoundException $e) {
            return response()->json(['message' => 'Plant not found'], 404);
        }
        
        return response()->json($plant);
    }

    /**
     * @OA\Put(
     *     path="/api/plants/{common_name}",
     *     operationId="updatePlantByCommonName",
     *     tags={"Plants"},
     *     summary="Update an existing plant",
     *     description="Updates a plant by common_name",
     *     @OA\Parameter(
     *         name="common_name",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *         description="common_name of the plant to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Plant")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Plant")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plant not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Content"
     *     )
     * )
     */
    public function update(Request $request, string $common_name): JsonResponse
    {
        try {
            $plant = $this->plantRepository->update($request, $common_name);
        } catch (PlantNotFoundException $e) {
            return response()->json(['message' => 'Plant not found'], 404);
        }

        return response()->json($plant);
    }
    
    /**
     * @OA\Delete(
     *     path="/api/plants/{id}",
     *     operationId="deletePlant",
     *     tags={"Plants"},
     *     summary="Delete an existing plant",
     *     description="Delete a plant by id if it exists",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *         description="id of the plant to delete"
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Plant successfully deleted",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plant not found"
     *     ),
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->plantRepository->delete($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Plant not found'], 404);
        }

        return response()->json(null, 204);
    }
}
