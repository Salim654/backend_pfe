<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\Organization;

class AuthController extends Controller
{

    //web
    public function index()
    {
        return view('auth.login');
    }
    //web
    public function logadmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                $request->user()->tokens()->delete();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return view('auth.login')->with('error', 'Unauthorized. Only admins are allowed to log in.');
            }
        }
    
        return view('auth.login')->with('error', 'Invalid credentials');
    }
    //show users web
    public function showAll(Request $request)
    {
        $search = $request->input('search');
        $users = User::where('role', 'user')
                     ->when($search, function ($query, $search) {
                         return $query->where(function ($query) use ($search) {
                             $query->where('taxidentification', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                         });
                     })
                     ->paginate(3);
        $organizations = Organization::all();
        return view('admin.users', compact('users', 'organizations'));
    }
     
    
    //Add User web
    public function adduser(Request $request)
    {
        $attrs = $request->validate([
            'organization_id'=>'required',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'phone' => 'required|numeric',
            'adresse' => 'required',
            'taxidentification' => 'required',
        ]);

        $user = User::create([
            'organization_id'=>$attrs['organization_id'],
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'password' => bcrypt($attrs['password']),
            'phone' => $attrs['phone'],
            'adresse' => $attrs['adresse'],
            'role'=> 'user',
            'taxidentification' => $attrs['taxidentification'],
        ]);
        return redirect()->route('admin.users')->with('message', 'User added succefully');
    }
     //update User web
    public function update(Request $request, $id)
    {
        $request->validate([
            'organization_id'=>'required',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'adresse' => 'required',
            'taxidentification' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'organization_id'=>$request->organization_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'adresse' => $request->adresse,
            'taxidentification' => $request->taxidentification,
        ]);

        return redirect()->route('admin.users')->with('message', 'User updated successfully');
    }
    //change mdp by admin 
    public function changePassword(Request $request, $id)
{
    $attrs = $request->validate([
        'password' => 'required|min:6',
        'confirm_password' => 'required|same:password',
    ]);

    $user = User::findOrFail($id);
    $user->password = bcrypt($attrs['password']);
    $user->save();

    return redirect()->route('admin.users')->with('message', 'Password changed successfully');
}

     //delete User web
    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('admin.users')->with('message', 'User deleted successfully');
    }
    //logout web
    public function logoutad(Request $request)
    {
        $request->user()->tokens()->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.login');
    }
    
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Authenticate a financial manager ",
     *     description="Authenticates a financial manager and returns a Sanctum Bearer token upon successful login. The token can be used for subsequent authenticated requests.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials for authentication",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", description="User's email"),
     *             @OA\Property(property="password", type="string", description="User's password"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User authenticated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="object", description="Authenticated user's details"),
     *             @OA\Property(property="token", type="string", description="Sanctum Bearer token"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Invalid credentials"),
     *         )
     *     )
     * )
     */
    //login User
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'user') {
                return response([
                    'user' => $user,
                    'token' => $user->createToken('secret')->plainTextToken
                ], 200);
            } else {
                return response([
                    'message' => 'Unauthorized. Only users are allowed to log in.'
                ], 403);
            }
        }

        return response([
            'message' => 'Invalid credentials'
        ], 401);
    }
    /**
     * @OA\Post(
     *      path="/api/logout",
     *      operationId="logoutUser",
     *      tags={"Auth"},
     *      summary="Logout financial manager",
     *      description="Logs out the authenticated user by deleting their tokens.",
     *      @OA\Response(
     *          response=200,
     *          description="Logged out successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              example={"message": "Logged out successfully"}
     *          )
     *      ),
     *      security={{"sanctum":{}}}
     * )
     */

    //logout User
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'message' => 'Logged out successfully'
        ],200);
    }

    
        /**
     * @OA\Get(
     *      path="/api/user",
     *      operationId="getUserDetails",
     *      tags={"Auth"},
     *      summary="Get financial manager details",
     *      description="Retrieves the details of the authenticated user.",
     *      @OA\Response(
     *          response=200,
     *          description="User details",
     *          @OA\JsonContent(
     *              type="object",
     *              example={
     *                  "user": {
     *                      "id": 1,
     *                      "name": "salim yaa",
     *                      "email": "yaa@exax.com",
     *                      "phone": "12345678",
     *                      "adresse": "123 St",
     *                      "organization_name": "Organization"
     *                  }
     *              }
     *          )
     *      ),
     *      security={{"sanctum":{}}}
     * )
     */

    // user Details
    public function user()
    {
        $user = auth()->user();

        if ($user) {
            $userArray = $user->toArray();
            if ($user->organization) {
                $userArray['organization_name'] = $user->organization->name;
            }
            unset($userArray['organization']);
        } else {
            $userArray = null;
        }

        return response([
            'user' => $userArray
        ], 200);
    }
    
    /**
     * @OA\Put(
     *      path="/api/user",
     *      operationId="editUser",
     *      tags={"Auth"},
     *      summary="Edit financial manager details",
     *      description="Updates the details of the authenticated user.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="User details",
     *          @OA\JsonContent(
     *              required={"name","email","phone","adresse"},
     *              @OA\Property(property="name", type="string", example="salim yaa"),
     *              @OA\Property(property="email", type="string", example="yaa@exa.com"),
     *              @OA\Property(property="phone", type="string", example="1235678"),
     *              @OA\Property(property="adresse", type="string", example="123 St")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User updated successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              example={
     *                  "message": "User updated successfully",
     *                  "user": {
     *                      "id": 1,
     *                      "name": "salim yaa",
     *                      "email": "yaa@exa.com",
     *                      "phone": "12345678",
     *                      "adresse": "123 St",
     *                      "organization_name": "Organization"
     *                  }
     *              }
     *          )
     *      ),
     *      security={{"sanctum":{}}}
     * )
     */

    // Edit User
    public function userEdit(Request $request)
    {
        $user = auth()->user();

        $attrs = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required',
            'adresse' => 'required',
        ], [
            'email.unique' => 'The provided email address is already in use.',
        ]);

        $user->update([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'phone' => $attrs['phone'],
            'adresse' => $attrs['adresse'],
        ]);

        return response([
            'message' => 'User updated successfully',
            'user' => $user
        ], 200);
    }

}
