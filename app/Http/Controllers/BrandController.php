<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BrandController extends Controller
{
        /**
     * @OA\Post(
     *      path="/api/brands",
     *      operationId="storeBrand",
     *      tags={"Brands"},
     *      summary="Create a new brand",
     *      description="Creates a new brand record in the database.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Brand details",
     *          @OA\JsonContent(
     *              required={"name","description"},
     *              @OA\Property(property="name", type="string", example="Nike"),
     *              @OA\Property(property="description", type="string", example="Leading sports brand"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Brand created successfully",
     *          @OA\JsonContent(type="object", example={"id": 1, "organization_id": 1, "name": "Nike", "description": "Leading sports brand"})
     *      ),
     *      security={{"sanctum":{}}}
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
        $user = auth()->user(); 
        $organizationId = $user->organization ? $user->organization->id : null;
        $brand = Brand::create([
            'organization_id' => $organizationId,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json($brand, 200);
    }
        /**
     * @OA\Get(
     *      path="/api/brands",
     *      operationId="getBrandsForUser",
     *      tags={"Brands"},
     *      summary="Get all brands for the authenticated user",
     *      description="Retrieves all brands associated with the authenticated user's organization.",
     *      @OA\Response(
     *          response=200,
     *          description="List of brands",
     *          @OA\JsonContent(type="array", @OA\Items(type="object", example={"id": 1, "organization_id": 1, "name": "Nike", "description": "Leading sports brand"}))
     *      ),
     *      security={{"sanctum":{}}}
     * )
     */
    public function getBrandsForUser()
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['Brands' => []], 200);
            }
        $brands = $organization->brands()->get();
        return response()->json($brands, 200);
    }

        /**
     * @OA\Get(
     *      path="/api/brands/{id}",
     *      operationId="showBrand",
     *      tags={"Brands"},
     *      summary="Get a specific brand by ID",
     *      description="Retrieves a specific brand's details.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the brand",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Brand details",
     *          @OA\JsonContent(type="object", example={"id": 1, "organization_id": 1, "name": "Nike", "description": "Leading sports brand"})
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Brand not found"
     *      ),
     *      security={{"sanctum":{}}}
     * )
     */
    public function show($id)
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        $brand = $organization->brands()->find($id);
        if (!$brand) {
            return response()->json(['message' => 'brand not found'], 404);
        }
        return response()->json(['brand' => $brand],200);
    }

        /**
     * @OA\Put(
     *      path="/api/brands/{id}",
     *      operationId="updateBrand",
     *      tags={"Brands"},
     *      summary="Update a specific brand by ID",
     *      description="Updates an existing brand record in the database.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the brand",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Brand details",
     *          @OA\JsonContent(
     *              required={"name","description"},
     *              @OA\Property(property="name", type="string", example="Nike"),
     *              @OA\Property(property="description", type="string", example="Leading sports brand"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Brand updated successfully",
     *          @OA\JsonContent(type="object", example={"id": 1, "organization_id": 1, "name": "Nike", "description": "Leading sports brand"})
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Brand not found"
     *      ),
     *      security={{"sanctum":{}}}
     * )
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        $brand = $organization->brands()->find($id);

        if (!$brand) {
            return response()->json(['message' => 'brand not found'], 404);
        }
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $brand->update($request->all());

        return response()->json($brand, 200);
    }

        /**
     * @OA\Delete(
     *      path="/api/brands/{id}",
     *      operationId="deleteBrand",
     *      tags={"Brands"},
     *      summary="Delete a specific brand by ID",
     *      description="Deletes a specific brand record from the database.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the brand",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Brand deleted successfully",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Brand not found"
     *      ),
     *      security={{"sanctum":{}}}
     * )
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        $brand = $organization->brands()->find($id);
        if (!$brand) {
            return response()->json(['message' => 'brand not found'], 404);
        }
        $brand->delete();
        return response()->json(['message' => 'brand deleted successfully'], 200);
    }
}
