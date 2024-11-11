<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CategoryController extends Controller
{
        /**
     * @OA\Post(
     *      path="/api/categorys",
     *      operationId="storeCategory",
     *      tags={"Categories"},
     *      summary="Create a new category",
     *      description="Creates a new category record in the database.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Category details",
     *          @OA\JsonContent(
     *              required={"category","reference","description"},
     *              @OA\Property(property="category", type="string", example="Electronics"),
     *              @OA\Property(property="reference", type="string", example="ELEC123"),
     *              @OA\Property(property="description", type="string", example="Category for electronic items"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Category created successfully",
     *          @OA\JsonContent(type="object", example={"id": 1, "organization_id": 1, "category": "Electronics", "reference": "ELEC123", "description": "Category for electronic items"})
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'reference' => 'required',
            'description' => 'required',
        ]);
        $user = auth()->user(); 
        $organizationId = $user->organization ? $user->organization->id : null;
        $category = Category::create([
            'organization_id' => $organizationId, 
            'category' => $request->category,
            'reference' => $request->reference,
            'description' => $request->description,
        ]);

        return response()->json($category, 200);
    }
   /**
     * @OA\Get(
     *      path="/api/categorys",
     *      operationId="getCategoriesForUser",
     *      tags={"Categories"},
     *      summary="Get all categories for the authenticated user",
     *      description="Retrieves all categories associated with the authenticated user's organization.",
     *      @OA\Response(
     *          response=200,
     *          description="List of categories",
     *          @OA\JsonContent(type="array", @OA\Items(type="object", example={"id": 1, "organization_id": 1, "category": "Electronics", "reference": "ELEC123", "description": "Category for electronic items"}))
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
    public function getCategoriesForUser()
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['categorys' => []], 200);
            }
        $categories = $organization->categorys()->get();
        return response()->json($categories, 200);
    }
    /**
     * @OA\Get(
     *      path="/api/categorys/{id}",
     *      operationId="showCategory",
     *      tags={"Categories"},
     *      summary="Get a specific category by ID",
     *      description="Retrieves a specific category's details.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the category",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Category details",
     *          @OA\JsonContent(type="object", example={"id": 1, "organization_id": 1, "category": "Electronics", "reference": "ELEC123", "description": "Category for electronic items"})
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Category not found"
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
    public function show($id)
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        $category = $organization->categorys()->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json(['category' => $category],200);
    }

        /**
     * @OA\Put(
     *      path="/api/categorys/{id}",
     *      operationId="updateCategory",
     *      tags={"Categories"},
     *      summary="Update a specific category by ID",
     *      description="Updates an existing category record in the database.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the category",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Category details",
     *          @OA\JsonContent(
     *              required={"category","reference","description"},
     *              @OA\Property(property="category", type="string", example="Electronics"),
     *              @OA\Property(property="reference", type="string", example="El123"),
     *              @OA\Property(property="description", type="string", example="Category for electronic items"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Category updated successfully",
     *          @OA\JsonContent(type="object", example={"id": 1, "organization_id": 1, "category": "Electronics", "reference": "El123", "description": "Category for electronic items"})
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Category not found"
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        $category = $organization->categorys()->find($id);

        if (!$category) {
            return response()->json(['message' => 'category not found'], 404);
        }
        $request->validate([
            'category' => 'required',
            'reference' => 'required',
            'description' => 'required',
        ]);
        $category->update($request->all());

        return response()->json($category, 200);
    }

        /**
     * @OA\Delete(
     *      path="/api/categorys/{id}",
     *      operationId="deleteCategory",
     *      tags={"Categories"},
     *      summary="Delete a specific category by ID",
     *      description="Deletes a specific category record from the database.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the category",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Category deleted successfully",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Category not found"
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
    public function destroy($id)
    {   
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        $category = $organization->categorys()->find($id);
        if (!$category) {
            return response()->json(['message' => 'category not found'], 404);
        }
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
