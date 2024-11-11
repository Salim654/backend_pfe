<?php

namespace App\Http\Controllers;

use App\Models\Tva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TvaController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/tvas",
     *     summary="Store a new Tva",
     *     tags={"Tva"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"value"},
     *             @OA\Property(property="value", type="number", example=19.5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tva created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="organization_id", type="integer"),
     *             @OA\Property(property="value", type="number")
     *         )
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
            'value' => 'required|numeric',
        ]);
        $user = auth()->user(); 
        $organizationId = $user->organization ? $user->organization->id : null;
        $tva = Tva::create([
            'organization_id' => $organizationId,
            'value' => $request->value,
        ]);

        return response()->json($tva, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/tvas",
     *     summary="Get all Tvas for the authenticated user's organization",
     *     tags={"Tva"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of Tvas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="organization_id", type="integer"),
     *                 @OA\Property(property="country_id", type="integer"),
     *                 @OA\Property(property="country_name", type="string"),
     *                 @OA\Property(property="value", type="number")
     *             )
     *         )
     *     )
     * )
     */
    public function getTvasForUser()
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['Tvas' => []], 200);
        }
        $tvas = $organization->tvas()->get();
        $formatTvas = $tvas->map(function ($tva) {
            return [
                'id' => $tva->id,
                'organization_id' => $tva->organization_id,
                'country_id' => $tva->organization->country->id,
                'country_name' => $tva->organization->country->country, 
                'value' => $tva->value,
            ];
        });
        
        return response()->json($formatTvas, 200);
    }
    
    /**
     * @OA\Get(
     *     path="/api/tvas/{id}",
     *     summary="Get a specific Tva by ID",
     *     tags={"Tva"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tva details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="organization_id", type="integer"),
     *             @OA\Property(property="value", type="number")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tva or Organization not found"
     *     )
     * )
     */
    public function show($id)
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        $tva = $organization->tvas()->find($id);
        if (!$tva) {
            return response()->json(['message' => 'tva not found'], 404);
        }
        return response()->json(['tva' => $tva], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/tvas/{id}",
     *     summary="Update an existing Tva",
     *     tags={"Tva"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"value"},
     *             @OA\Property(property="value", type="number", example=20.0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tva updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="organization_id", type="integer"),
     *             @OA\Property(property="value", type="number")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tva or Organization not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        $tva = $organization->tvas()->find($id);

        if (!$tva) {
            return response()->json(['message' => 'tva not found'], 404);
        }
        $request->validate([
            'value' => 'required|numeric',
        ]);
        $tva->update($request->all());

        return response()->json($tva, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/tvas/{id}",
     *     summary="Delete a Tva",
     *     tags={"Tva"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tva deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tva deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tva or Organization not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        $tva = $organization->tvas()->find($id);
        if (!$tva) {
            return response()->json(['message' => 'tva not found'], 404);
        }
        $tva->delete();

        return response()->json(['message' => 'Tva deleted successfully'], 200);
    }
}
