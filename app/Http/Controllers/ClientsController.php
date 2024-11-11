<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ClientsController extends Controller
{
  
    /**
     * @OA\Post(
     *      path="/api/clients",
     *      operationId="storeClient",
     *      tags={"Clients"},
     *      summary="Create a new client",
     *      description="Creates a new client record in the database.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Client details",
     *          @OA\JsonContent(
     *              required={"name","identification","email","phone","adresse"},
     *              @OA\Property(property="name", type="string", example="Salim Yaa"),
     *              @OA\Property(property="identification", type="string", example="ID123456"),
     *              @OA\Property(property="email", type="string", format="email", example="yaa@exa.com"),
     *              @OA\Property(property="phone", type="string", example="12345678"),
     *              @OA\Property(property="adresse", type="string", example="123 Main St, City, Country"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Client created successfully",
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required',
                'identification' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'adresse' => 'required',
            ]);
            $user = auth()->user();
            $organizationId = $user->organization ? $user->organization->id : null;
            $client = Client::create([
                'name' => $request->input('name'),
                'identification' => $request->input('identification'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'adresse' => $request->input('adresse'),
                'organization_id' => $organizationId, 
            ]);

            return response()->json([
                'client' => $client,
                'message' => 'Client created successfully',
            ], 200);
        }
    /**
     * @OA\Get(
     *      path="/api/clients/{id}",
     *      operationId="showClient",
     *      tags={"Clients"},
     *      summary="Get a specific client by ID",
     *      description="Retrieves a specific client's details.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the client",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Client details",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Client not found"
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
            $client = $organization->clients()->find($id);
            if (!$client) {
                return response()->json(['message' => 'Client not found'], 404);
            }
            return response()->json(['client' => $client], 200);
        }
        

    /**
     * @OA\Get(
     *      path="/api/clients",
     *      operationId="getClientsForUser",
     *      tags={"Clients"},
     *      summary="Get all clients for the authenticated user",
     *      description="Retrieves all clients associated with the authenticated user's organization.",
     *      @OA\Parameter(
     *          name="recherche",
     *          in="query",
     *          required=false,
     *          description="Search query to filter clients by name or identification",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="List of clients",
     *      ),
     *      security={{"sanctum":{}}},
     * )
     */
    
    public function getClientsForUser(Request $request)
    {
   
        $user = auth()->user();
        $organization = $user->organization;   
        if (!$organization) {
            return response()->json(['clients' => []], 200);
        }
        $searchQuery = $request->input('recherche');
        
        // Load clients associated with the user's organization
        $clients = $organization->clients()
            ->when($searchQuery, function ($query) use ($searchQuery) {
                // Apply search filter to the client list
                return $query->where('name', 'like', "%$searchQuery%")
                    ->orWhere('identification', 'like', "%$searchQuery%");
            })
            ->get();
        return response()->json(['clients' => $clients], 200);
    }
    

    /**
     * @OA\Put(
     *      path="/api/clients/{id}",
     *      operationId="updateClient",
     *      tags={"Clients"},
     *      summary="Update a specific client by ID",
     *      description="Updates an existing client record in the database.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the client",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Client details",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="Salim Yaa"),
     *              @OA\Property(property="identification", type="string", example="ID123456"),
     *              @OA\Property(property="email", type="string", format="email", example="yaa@exa.com"),
     *              @OA\Property(property="phone", type="string", example="12345678"),
     *              @OA\Property(property="adresse", type="string", example="123 Main St, City, Country"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Client updated successfully",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Client not found"
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
        $client = $organization->clients()->find($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $request->validate([
            'name' => 'required',
            'identification' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'adresse' => 'required',
        ]);
    
        $client->update([
            'name' => $request->input('name'),
            'identification' => $request->input('identification'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'adresse' => $request->input('adresse'),
        ]);

        return response()->json(['client' => $client, 'message' => 'Client updated successfully'], 200);
    }
    
        /**
     * @OA\Delete(
     *      path="/api/clients/{id}",
     *      operationId="deleteClient",
     *      tags={"Clients"},
     *      summary="Delete a specific client by ID",
     *      description="Deletes a specific client record from the database.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the client",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Client deleted successfully",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Client not found"
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
        $client = $organization->clients()->find($id);
        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }
        $client->delete();
        return response()->json(['message' => 'Client deleted successfully'], 200);
    }
    
}
