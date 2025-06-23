<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Venue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Venues",
 *     description="Endpoints for managing venues (to‘yxona, choyxona va hokazo joylar)"
 * )
 */
class VenueController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/venues",
     *     summary="List all venues",
     *     tags={"Venues"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index()
    {
        $venues = cache()->rememberForever('venues', function () {
            return Venue::all();
        });
        return response()->json($venues);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/venues/{id}",
     *     summary="Get venue by ID",
     *     tags={"Venues"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Venue ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Venue retrieved successfully"
     *     ),
     *     @OA\Response(response=404, description="Venue not found")
     * )
     */
    public function show(Venue $venue)
    {
        return response()->json($venue);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/venues",
     *     summary="Create a new venue",
     *     tags={"Venues"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "location", "capacity", "price"},
     *             @OA\Property(property="name", type="string", example="To'yxona Alisher"),
     *             @OA\Property(property="location", type="string", example="Toshkent, Yunusobod"),
     *             @OA\Property(property="capacity", type="integer", example=250),
     *             @OA\Property(property="price", type="number", format="float", example=15000.00)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Venue created"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price'    => 'required|numeric|min:0',
        ]);

        $venue = Venue::create($request->all());

        return response()->json($venue, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/venues/{id}",
     *     summary="Update an existing venue",
     *     tags={"Venues"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Venue ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Yangi To'yxona"),
     *             @OA\Property(property="location", type="string", example="Chilonzor, Tashkent"),
     *             @OA\Property(property="capacity", type="integer", example=300),
     *             @OA\Property(property="price", type="number", format="float", example=18000)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Venue updated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, Venue $venue)
    {
        $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'location' => 'sometimes|required|string|max:255',
            'capacity' => 'sometimes|required|integer|min:1',
            'price'    => 'sometimes|required|numeric|min:0',
        ]);

        $venue->update($request->all());

        return response()->json($venue);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/venues/{id}",
     *     summary="Delete a venue",
     *     tags={"Venues"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Venue ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Venue deleted successfully"),
     *     @OA\Response(response=500, description="Venue could not be deleted")
     * )
     */
    public function destroy(Venue $venue)
    {
        try {
            $venue->delete();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Venue could not be deleted'], 500);
        }
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
