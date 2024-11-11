<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Client;
use Illuminate\Http\Request;

class FactureController extends Controller
{
  /**
     * @OA\Post(
     *      path="/api/factures",
     *      operationId="storeFacture",
     *      tags={"Factures(Invoices,Puschase Orders,Estimates)"},
     *      summary="Create a new facture",
     *      description="Creates a new facture record in the database. (0:Invoices,1:Estimates,2:Puschase Orders) ",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Facture details",
     *          @OA\JsonContent(
     *              required={"date","due_date","client_id","type"},
     *              @OA\Property(property="date", type="string", format="date", example="2024-06-20"),
     *              @OA\Property(property="due_date", type="string", format="date", example="2024-07-20"),
     *              @OA\Property(property="discount", type="number", format="float", example="10.5"),
     *              @OA\Property(property="client_id", type="integer", example="1"),
     *              @OA\Property(property="taxe_id", type="integer", example="2"),
     *              @OA\Property(property="type", type="integer", example="0", enum={0, 1, 2}),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Facture created successfully",
     *          @OA\JsonContent(type="object", example={"id": 1, "date": "2024-06-20", "due_date": "2024-07-20","client_id":1})
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'due_date' => 'required|date',
            'discount' => 'nullable|numeric',
            'client_id' => 'required|exists:clients,id',
            'taxe_id' => 'nullable|exists:taxes,id',
            'type' => 'required|in:0,1,2',
        ]);
        
        $facture = Facture::create([
            'date' => $request->input('date'),
            'due_date' => $request->input('due_date'),
            'discount' => $request->input('discount'),
            'client_id' => $request->input('client_id'),
            'taxe_id' => $request->input('taxe_id'),
            'reference' => 0,
            'type' => $request->input('type'),
        ]);

        $prefix = '';
        switch ($facture->type) {
            case 0:
                $prefix = 'INV';
                break;
            case 1:
                $prefix = 'EST';
                break;
            case 2:
                $prefix = 'PO';
                break;
        }
    
        $factureID = str_pad($facture->id, 4, '0', STR_PAD_LEFT);
        $facture->reference = $prefix . '-' . $factureID;
        $facture->save();

        return response()->json($facture, 200);
    }

        /**
     * @OA\Get(
     *      path="/api/invoices",
     *      operationId="getInvoicesForUser",
     *      tags={"Factures(Invoices,Puschase Orders,Estimates)"},
     *      summary="Get all invoices for the authenticated user",
     *      description="Retrieves all invoices associated with the authenticated user's organization.",
     *      @OA\Response(
     *          response=200,
     *          description="List of invoices",
     *          @OA\JsonContent(type="array", @OA\Items(type="object", example={"id": 1, "date": "2024-06-20","client_id":1}))
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
    public function getInvoicesForUser()
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
    
        // Retrieve the invoices related to the organization and filter by type
        $invoices = Facture::whereHas('client', function ($query) use ($organization) {
            // Query to filter invoices by the organization's clients
            $query->where('organization_id', $organization->id);
        })
        ->where('type', 0) 
        ->get();
    
        return response()->json($invoices, 200);
    }
    
        /**
     * @OA\Get(
     *      path="/api/estimates",
     *      operationId="getEstimatesForUser",
     *      tags={"Factures(Invoices,Puschase Orders,Estimates)"},
     *      summary="Get all estimates for the authenticated user",
     *      description="Retrieves all estimates associated with the authenticated user's organization.",
     *      @OA\Response(
     *          response=200,
     *          description="List of estimates",
     *          @OA\JsonContent(type="array", @OA\Items(type="object", example={"id": 2, "date": "2024-06-21","client_id":1}))
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
    public function getEstimatesForUser()
    {
        $user = auth()->user();
        $organization = $user->organization;

        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
    
        // Retrieve the invoices related to the organization and filter by type
        $estimates = Facture::whereHas('client', function ($query) use ($organization) {
            // Query to filter invoices by the organization's clients
            $query->where('organization_id', $organization->id);
        })
        ->where('type', 1) 
        ->get(); 
        return response()->json($estimates, 200);
    }
    
        /**
     * @OA\Get(
     *      path="/api/purchases",
     *      operationId="getPurchaseOrdersForUser",
     *      tags={"Factures(Invoices,Puschase Orders,Estimates)"},
     *      summary="Get all purchase orders for the authenticated user",
     *      description="Retrieves all purchase orders associated with the authenticated user's organization.",
     *      @OA\Response(
     *          response=200,
     *          description="List of purchase orders",
     *          @OA\JsonContent(type="array", @OA\Items(type="object", example={"id": 3, "date": "2024-06-22","client_id":1}))
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
    public function getPurchaseOrdersForUser()
    {
        $user = auth()->user();
        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
    
        // Retrieve the invoices related to the organization and filter by type
        $purchaseOrders = Facture::whereHas('client', function ($query) use ($organization) {
            // Query to filter invoices by the organization's clients
            $query->where('organization_id', $organization->id);
        })
        ->where('type', 2) 
        ->get(); 

        return response()->json($purchaseOrders, 200);
    }
    
    /**
     * @OA\Get(
     *      path="/api/factures/{id}",
     *      operationId="showFacture",
     *      tags={"Factures(Invoices,Puschase Orders,Estimates)"},
     *      summary="Get a specific facture by ID",
     *      description="Retrieves a specific facture and its associated products.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the facture",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Facture details",
     *          @OA\JsonContent(type="object", example={"id": 1, "date": "2024-06-20", "due_date": "2024-07-20","client_id":1})
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Facture not found"
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
    public function show($id)
    {
        $facture = Facture::find($id);
        if (!$facture) {
            return response()->json(['message' => 'Facture not found'], 404);
        }
        $produits = $facture->produits;
        
        // Map the produits to include their details such as quantity, discount, and related product and tax (if taxe_id is not null)
        $produitsData = $produits->map(function ($factureprod) {
            return [
                'quantity' => $factureprod->quantity,
                'discount' => $factureprod->discount,
                'product' => [
                    'id' => $factureprod->product->id,
                    'reference' => $factureprod->product->reference,
                    'designation' => $factureprod->product->designation,
                    'price' => $factureprod->product->price,
                    'tva'=>$factureprod->product->tva->value,
                    
                ],
                
                'taxe' => $factureprod->taxe ? [
                    'id' => $factureprod->taxe->id,
                    'value' => $factureprod->taxe->value,
                    'wording'=> $factureprod->taxe->wording,
                    'short_name'=> $factureprod->taxe->short_name,
                    'value_type'=> $factureprod->taxe->value_type,
                    'utilisation'=> $factureprod->taxe->utilisation,
                    'application'=> $factureprod->taxe->application,
                ] : null,
            ];
        });
        
        
        $response = [
            'facture' => [
                'id' => $facture->id,
                'reference' => $facture->reference,
                'date' => $facture->date,
                'due_date' => $facture->due_date,
                'type' => $facture->type,
                'discount' => $facture->discount,
                'client_id' => $facture->client_id,
                'client' => $facture->client->name,
                'clientad' => $facture->client->adresse,
                'organization_id' => $facture->client->organization->id,
                'organization' => $facture->client->organization->name,
                'organizationad' => $facture->client->organization->adresse,
                'taxe' => $facture->taxe ? [
                    'id' => $facture->taxe->id,
                    'value' => $facture->taxe->value,
                    'wording'=> $facture->taxe->wording,
                    'short_name'=> $facture->taxe->short_name,
                    'value_type'=> $facture->taxe->value_type,
                    'utilisation'=> $facture->taxe->utilisation,
                    'application'=> $facture->taxe->application,
                ] : null,
               
            ],
            'produits' => $produitsData,
        ];
    
        return response()->json($response, 200);
    }
    
    

  /**
     * @OA\Put(
     *      path="/api/factures/{id}",
     *      operationId="updateFacture",
     *      tags={"Factures(Invoices,Puschase Orders,Estimates)"},
     *      summary="Update a specific facture by ID",
     *      description="Updates an existing facture record in the database.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the facture",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Facture details",
     *          @OA\JsonContent(
     *              @OA\Property(property="date", type="string", format="date", example="2024-06-21"),
     *              @OA\Property(property="due_date", type="string", format="date", example="2024-07-21"),
     *              @OA\Property(property="discount", type="number", format="float", example="15.75"),
     *              @OA\Property(property="client_id", type="integer", example="2"),
     *              @OA\Property(property="taxe_id", type="integer", example="3"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Facture updated successfully",
     *          @OA\JsonContent(type="object", example={"id": 1, "date": "2024-06-21", "due_date": "2024-07-21","client_id":1 })
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Facture not found"
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
    public function update(Request $request, $id)
    {
        $facture = Facture::find($id);
        if (!$facture) {
            return response()->json(['message' => 'Facture not found'], 404);
        }

        // Validate input data
        $request->validate([
            'date' => 'required|date',
            'due_date' => 'required|date',
            'discount' => 'nullable|numeric',
            'client_id' => 'nullable|exists:clients,id',
            'taxe_id' => 'nullable|exists:taxes,id',
        ]);

        $facture->update($request->all());

        return response()->json($facture, 200);
    }
    /**
     * @OA\Delete(
     *      path="/api/factures/{id}",
     *      operationId="deleteFacture",
     *      tags={"Factures(Invoices,Puschase Orders,Estimates)"},
     *      summary="Delete a specific facture by ID",
     *      description="Deletes a specific facture record from the database.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the facture",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Facture deleted successfully",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Facture not found"
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
    public function destroy($id)
    {
        $facture = Facture::find($id);
        if (!$facture) {
            return response()->json(['message' => 'Facture not found'], 404);
        }
        $facture->delete();
        return response()->json(['message' => 'Facture deleted successfully'], 200);
    }
}
