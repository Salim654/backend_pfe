<?php

namespace App\Http\Controllers;

use App\Models\Taxe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaxeController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/taxes",
     *     summary="Create a new tax record",
     *     description="Create a new tax record for the authenticated user's organization",
     *     operationId="createTax",
     *     tags={"Taxes"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="wording", type="string", example="Tax Description"),
     *             @OA\Property(property="short_name", type="string", example="Tax"),
     *             @OA\Property(property="value", type="number", example=15.5),
     *             @OA\Property(property="value_type", type="integer", example=1),
     *             @OA\Property(property="application", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tax created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="wording", type="string", example="Tax Description"),
     *             @OA\Property(property="short_name", type="string", example="Tax"),
     *             @OA\Property(property="value", type="number", example=15.5),
     *             @OA\Property(property="value_type", type="integer", example=1),
     *             @OA\Property(property="application", type="integer", example=1),
     *             @OA\Property(property="organization_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation error message")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'wording' => 'required|string',
            'short_name' => 'required|string',
            'value' => 'required|numeric',
            'value_type' => 'required|numeric',
            'application' => 'required|numeric',
        ]);
        $user = auth()->user();
        $organizationId = $user->organization ? $user->organization->id : null;

        $taxe = Taxe::create([
            'wording' => $request->wording,
            'short_name' => $request->short_name,
            'value' => $request->value,
            'value_type' => $request->value_type,
            'application' => $request->application,
            'organization_id' => $organizationId,
        ]);
        return response()->json($taxe, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/taxes",
     *     summary="Get taxes for user",
     *     description="Get all tax records for the authenticated user's organization",
     *     operationId="getTaxesForUser",
     *     tags={"Taxes"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Taxes retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="wording", type="string", example="Tax Description"),
     *                 @OA\Property(property="short_name", type="string", example="Tax"),
     *                 @OA\Property(property="value", type="number", example=15.5),
     *                 @OA\Property(property="value_type", type="integer", example=1),
     *                 @OA\Property(property="application", type="integer", example=1),
     *                 @OA\Property(property="organization_id", type="integer", example=1)
     *             )
     *         )
     *     )
     * )
     */
    public function getTaxesForUser()
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['taxes' => []], 200);
        }

        $taxes = $organization->taxes()->get();
        $formattedTaxes = $taxes->map(function ($taxe) {
            return [
                'id' => $taxe->id,
                'wording' => $taxe->wording,
                'short_name' => $taxe->short_name,
                'value' => $taxe->value,
                'value_type' => $taxe->value_type,
                'application' => $taxe->application,
                'organization_id' => $taxe->organization_id,
            ];
        });
        return response()->json($formattedTaxes, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/taxes/{id}",
     *     summary="Get a specific tax",
     *     description="Get a specific tax record for the authenticated user's organization",
     *     operationId="getTax",
     *     tags={"Taxes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Tax ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tax retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="wording", type="string", example="Tax Description"),
     *             @OA\Property(property="short_name", type="string", example="Tax"),
     *             @OA\Property(property="value", type="number", example=15.5),
     *             @OA\Property(property="value_type", type="integer", example=1),
     *             @OA\Property(property="application", type="integer", example=1),
     *             @OA\Property(property="organization_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tax not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tax not found")
     *         )
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
        $taxe = $organization->taxes()->find($id);
        if (!$taxe) {
            return response()->json(['message' => 'Tax not found'], 404);
        }
        return response()->json(['taxe' => $taxe], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/taxes/{id}",
     *     summary="Update a tax",
     *     description="Update a specific tax record for the authenticated user's organization",
     *     operationId="updateTax",
     *     tags={"Taxes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Tax ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="wording", type="string", example="Updated Tax Description"),
     *             @OA\Property(property="short_name", type="string", example="UpdTax"),
     *             @OA\Property(property="value", type="number", example=20.5),
     *             @OA\Property(property="value_type", type="integer", example=1),
     *             @OA\Property(property="application", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(     *         response=200,
     *         description="Tax updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="wording", type="string", example="Updated Tax Description"),
     *             @OA\Property(property="short_name", type="string", example="UpdTax"),
     *             @OA\Property(property="value", type="number", example=20.5),
     *             @OA\Property(property="value_type", type="integer", example=1),
     *             @OA\Property(property="application", type="integer", example=1),
     *             @OA\Property(property="organization_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tax not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tax not found")
     *         )
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
        $taxe = $organization->taxes()->find($id);

        if (!$taxe) {
            return response()->json(['message' => 'Tax not found'], 404);
        }
        $request->validate([
            'wording' => 'sometimes|required|string',
            'short_name' => 'sometimes|required|string',
            'value' => 'sometimes|required|numeric',
            'value_type' => 'sometimes|required|numeric',
            'application' => 'sometimes|required|numeric',
        ]);
        $taxe->update($request->all());

        return response()->json($taxe, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/taxes/{id}",
     *     summary="Delete a tax",
     *     description="Delete a specific tax record for the authenticated user's organization",
     *     operationId="deleteTax",
     *     tags={"Taxes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Tax ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tax deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tax deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tax not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tax not found")
     *         )
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
        $taxe = $organization->taxes()->find($id);

        if (!$taxe) {
            return response()->json(['message' => 'Tax not found'], 404);
        }
        $taxe->delete();
        return response()->json(['message' => 'Tax deleted successfully'], 200);
    }
}

           
