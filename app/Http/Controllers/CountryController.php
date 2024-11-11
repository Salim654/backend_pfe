<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return response()->json($countries, 200);
    }
    //show all countrys
    public function showAll()
    {
        $countries = Country::paginate(3);
        return view('admin.countrys', compact('countries'));
    }
    public function delete($id)
    {
        $country = Country::find($id);
        $country->delete();
        return redirect()->route('admin.countrys')->with('message', 'Country deleted successfully');
    }
    public function store(Request $request)
    {
        $request->validate([
            'country' => 'required|string|max:255',
        ]);

        Country::create([
            'country' => $request->country,
        ]);

        return redirect()->route('admin.countrys')->with('message', 'Country added successfully');
    }

    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.edit-country', compact('country'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'country' => 'required|string|max:255',
        ]);

        $country = Country::findOrFail($id);
        $country->update([
            'country' => $request->country,
        ]);

        return redirect()->route('admin.countrys')->with('message', 'Country updated successfully');
    }
    
}
