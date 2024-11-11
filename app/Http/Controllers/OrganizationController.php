<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::all();
        return response()->json($organizations, 200);
    }
    
    public function showAll(Request $request)
    {
        $query = $request->input('search');
        $organizations = Organization::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('name', 'like', "%{$query}%"); 
        })->paginate(3); 
    
        $countries = Country::all();
        return view('admin.organizations', compact('organizations', 'countries'));
    }
    
    
        //Add organziation
        public function addorganization(Request $request)
        {
            $attrs = $request->validate([
                'country_id'=>'required',
                'name' => 'required',
                'adresse' =>'required',
            ]);
            //dd($attrs);
            $organization = Organization::create([
               'country_id'=>$attrs['country_id'],
               'name' => $attrs['name'],
                'adresse' => $attrs['adresse'],
           ]);
            return redirect()->route('admin.organizations')->with('message', 'Organization added successfully');
        }

        public function update(Request $request, $id)
        {
            $request->validate([
                'country_id' => 'required',
                'name' => 'required',
                'adresse' => 'required',
            ]);
    
    
            $organization = Organization::findOrFail($id);
            
            $organization->update([
                'country_id' => $request->country_id,
                'name' => $request->name,
               'adresse' => $request->adresse,
            ]);
            return redirect()->route('admin.organizations')->with('message', 'Organization updated successfully');
        }
        public function delete($id)
        {
            $organization = Organization::find($id);
            $organization->delete();
            return redirect()->route('admin.organizations')->with('message', 'Organization deleted successfully');;
        }

}
