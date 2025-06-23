<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Services",
 *     description="Service resource endpoints"
 * )
 */
class ServiceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/services",
     *     summary="Get list of all services",
     *     tags={"Services"},
     *     @OA\Response(
     *         response=200,
     *         description="List of services"
     *     )
     * )
     */
    public function index()
    {
        $services = cache()->rememberForever('services', function () {
            return Service::all();
        });
        return response()->json($services);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/services/{id}",
     *     summary="Get a single service by ID",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Service ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service found"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service not found"
     *     )
     * )
     */
    public function show(Service $service)
    {
        return response()->json($service);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/services",
     *     summary="Create a new service",
     *     tags={"Services"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="Tuyxona"),
     *             @OA\Property(property="description", type="string", maxLength=1000, example="To'y uchun joy bezatish xizmati")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Service created"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        $service = Service::create($request->all());

        return response()->json($service, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/services/{id}",
     *     summary="Update an existing service",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Service ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Karnay-surnay"),
     *             @OA\Property(property="description", type="string", example="Xashamli musiqa xizmati")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service updated"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:1000',
        ]);

        $service->update($request->all());

        return response()->json($service);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/services/{id}",
     *     summary="Delete a service",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Service ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Service could not be deleted"
     *     )
     * )
     */
    public function destroy(Service $service)
    {
        try {
            $service->delete();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Service could not be deleted'], 500);
        }
        return response()->json(['message' => 'Service deleted successfully']);
    }
}
