<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Tva;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Create a new product",
     *     description="Create a new product record for the authenticated user's organization",
     *     operationId="createProduct",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product data",
     *         @OA\JsonContent(
     *             required={"reference","designation","category_id","brand_id","price","tva_id"},
     *             @OA\Property(property="reference", type="string", example="PRD001"),
     *             @OA\Property(property="designation", type="string", example="Product Description"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="brand_id", type="integer", example=1),
     *             @OA\Property(property="price", type="number", format="float", example=100.5),
     *             @OA\Property(property="tva_id", type="integer", example=1),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="reference", type="string", example="PRD001"),
     *             @OA\Property(property="designation", type="string", example="Product Description"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="brand_id", type="integer", example=1),
     *             @OA\Property(property="price", type="number", format="float", example=100.5),
     *             @OA\Property(property="tva_id", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"reference": {"The reference field is required."}})
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'reference' => 'required',
            'designation' => 'required',
            'category_id' => 'required|exists:categorys,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric',
            'tva_id' => 'required|exists:tvas,id',
        ]);

        $user = auth()->user();
        $organizationId = $user->organization ? $user->organization->id : null;

        $product = Product::create([
            'organization_id' => $organizationId,
            'reference' => $request->reference,
            'designation' => $request->designation,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'tva_id' => $request->tva_id,
        ]);

        return response()->json($product, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Get all products",
     *     description="Get all products for the authenticated user's organization",
     *     operationId="getProducts",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="reference", type="string", example="PRD001"),
     *                 @OA\Property(property="designation", type="string", example="Product Description"),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="brand_id", type="integer", example=1),
     *                 @OA\Property(property="price", type="number", format="float", example=100.5),
     *                 @OA\Property(property="tva_id", type="integer", example=1),
     *             )
     *         )
     *     )
     * )
     */
    public function getProductsForUser()
    {
        $user = auth()->user();
        $organization = $user->organization;

        if (!$organization) {
            return response()->json(['Products' => []], 200);
        }

        $products = $organization->products()->get();
        
        return response()->json($products, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Get product by ID",
     *     description="Get a specific product by its ID",
     *     operationId="getProductById",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Product ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="reference", type="string", example="PRD001"),
     *             @OA\Property(property="designation", type="string", example="Product Description"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="brand_id", type="integer", example=1),
     *             @OA\Property(property="price", type="number", format="float", example=100.5),
     *             @OA\Property(property="tva_id", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $category = Category::findOrFail($product->category_id);
        $brand = Brand::findOrFail($product->brand_id);
        $tva = Tva::findOrFail($product->tva_id);

        $newProduct = [
            'reference' => $product->reference,
            'designation' => $product->designation,
            'category_id' => $category->category,
            'brand_id' => $brand->name, 
            'price' => $product->price,
            'tva_id' => $tva->value,
        ];
    
        return response()->json($newProduct, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Update a product",
     *     description="Update a specific product record for the authenticated user's organization",
     *     operationId="updateProduct",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Product ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product data",
     *         @OA\JsonContent(
     *             required={"reference","designation","category_id","brand_id","price","tva_id"},
     *             @OA\Property(property="reference", type="string", example="PRD001"),
     *             @OA\Property(property="designation", type="string", example="Product Description"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="brand_id", type="integer", example=1),
     *             @OA\Property(property="price", type="number", format="float", example=100.5),
     *             @OA\Property(property="tva_id", type="integer", example=1),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="reference", type="string", example="PRD001"),
     *             @OA\Property(property="designation", type="string", example="Product Description"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="brand_id", type="integer", example=1),
     *             @OA\Property(property="price", type="number", format="float", example=100.5),
     *             @OA\Property(property="tva_id", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"reference": {"The reference field is required."}})
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

        $product = $organization->products()->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'reference' => 'required',
            'designation' => 'required',
            'category_id' => 'required|exists:categorys,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric',
            'tva_id' => 'required|exists:tvas,id',
        ]);

        $product->update([
            'reference' => $request->reference,
            'designation' => $request->designation,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'tva_id' => $request->tva_id,
        ]);

        return response()->json($product, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Delete a product",
     *     description="Delete a specific product record for the authenticated user's organization",
     *     operationId="deleteProduct",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Product ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product not found")
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

        $product = $organization->products()->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}

