<?php

namespace App\Http\Controllers;

use App\Models\Factureprod;
use Illuminate\Http\Request;

class FactureprodController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/factures/{facture_id}/factureprods",
     *      operationId="indexFactureprods",
     *      security={{"sanctum":{}}},
     *      tags={"Elements facture_products for Facture"},
     *      summary="Get all  elements  in  facture_product for a specific facture",
     *      description="Returns a list of facture_products  associated with a facture",
     *      @OA\Parameter(
     *          name="facture_id",
     *          in="path",
     *          required=true,
     *          description="ID of the facture",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="List of facture_products",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items()
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Facture not found"
     *      )
     * )
     */
    public function index($facture_id)
    {

        $factureprods = Factureprod::with(['product', 'taxe'])
            ->where('factures_id', $facture_id)
            ->get();

        $transformed = $factureprods->map(function ($factureprod) {
            return [
                'id' => $factureprod->id,
                'quantity' => $factureprod->quantity,
                'discount' => $factureprod->discount,
                'product_id' => $factureprod->product_id,
                'factures_id' => $factureprod->factures_id,
                'taxe_id' => $factureprod->taxe_id,
                'product_name' => $factureprod->product->designation ?? null,
                'unit_price' => $factureprod->product->price ?? null,
                'tax_shortname' => $factureprod->taxe ? $factureprod->taxe->short_name : null,
                'tax_value' => $factureprod->taxe ? $factureprod->taxe->value : null,
                'tax_value_type' => $factureprod->taxe ? $factureprod->taxe->value_type : null,
            ];
        });

        return response()->json($transformed);
    }

    /**
     * @OA\Post(
     *      path="/api/factures/{facture_id}/factureprods",
     *      operationId="storeFactureprod",
     *      security={{"sanctum":{}}},
     *      tags={"Elements facture_products for Facture"},
     *      summary="Create a new specific a element in  facture_product ",
     *      description="Create a new element in  facture_product associated with a facture",
     *      @OA\Parameter(
     *          name="facture_id",
     *          in="path",
     *          required=true,
     *          description="ID of the facture",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Created facture_product",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function store(Request $request, $facture_id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'discount' => 'required|numeric',
            'product_id' => 'required|exists:products,id',
            'taxe_id' => 'nullable|exists:taxes,id',
        ]);

        $factureprod = Factureprod::create([
            'quantity' => $request->input('quantity'),
            'discount' => $request->input('discount'),
            'product_id' => $request->input('product_id'),
            'factures_id' => $facture_id,
            'taxe_id' => $request->input('taxe_id'),
        ]);

        return response()->json($factureprod, 200);
    }

    /**
     * @OA\Get(
     *      path="/api/factures/{facture_id}/factureprods/{id}",
     *      operationId="showFactureprod",
     *      security={{"sanctum":{}}},
     *      tags={"Elements facture_products for Facture"},
     *      summary="Get a specific a element facture_product from specific facture   by ID",
     *      description="Returns a single facture_product",
     *      @OA\Parameter(
     *          name="facture_id",
     *          in="path",
     *          required=true,
     *          description="ID of the facture",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the facture_product",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="facture_product found",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="facture_product not found"
     *      )
     * )
     */
    public function show($facture_id, $id)
    {
        $factureprod = Factureprod::where('id', $id)
            ->where('factures_id', $facture_id)
            ->firstOrFail();

        return response()->json($factureprod);
    }

    /**
     * @OA\Put(
     *      path="/api/factures/{facture_id}/factureprods/{id}",
     *      operationId="updateFactureprod",
     *      security={{"sanctum":{}}},
     *      tags={"Elements facture_products for Facture"},
     *      summary="Update a element facture_product from specific facture  by ID",
     *      description="Update a specific facture_product",
     *      @OA\Parameter(
     *          name="facture_id",
     *          in="path",
     *          required=true,
     *          description="ID of the facture",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the facture_product",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Updated facture_product",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="facture_product not found"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function update(Request $request, $facture_id, $id)
    {
        $factureprod = Factureprod::where('id', $id)
            ->where('factures_id', $facture_id)
            ->firstOrFail();

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric',
            'product_id' => 'required|exists:products,id',
            'taxe_id' => 'nullable|exists:taxes,id',
        ]);

        $factureprod->update($request->all());

        return response()->json($factureprod);
    }

       /**
     * @OA\Delete(
     *      path="/api/factures/{facture_id}/factureprods/{id}",
     *      operationId="deleteFactureprod",
     *      tags={"Elements facture_products for Facture"},
     *      security={{"sanctum":{}}},
     *      summary="Delete a element facture_product from specific facture  by ID",
     *      description="Delete a specific facture_product",
     *      @OA\Parameter(
     *          name="facture_id",
     *          in="path",
     *          required=true,
     *          description="ID of the facture",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the element facture_product",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="facture_product deleted successfully",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Factureprod not found"
     *      )
     * )
     */
    public function destroy($facture_id, $id)
    {
        $factureprod = Factureprod::where('id', $id)
            ->where('factures_id', $facture_id)
            ->firstOrFail();

        $factureprod->delete();

        return response()->json(['message' => 'Factureprod deleted successfully']);
    }   
}