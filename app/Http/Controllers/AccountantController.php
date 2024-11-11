<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Accountant;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class AccountantController extends Controller
{
    public function index(Request $request)
    {
        $query = Accountant::with('organizations');
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }
    
        $accountants = $query->paginate(3);
        $organizations = Organization::all();
        
        return view('admin.accountants', compact('accountants', 'organizations'));
    }
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'adresse' => 'required',
            'organizations' => 'required|array'
        ]);

        $accountant = Accountant::create($validated);
        $accountant->organizations()->attach($request->organizations);

        return redirect()->route('accountants.index')->with('message', 'Accountant added successfully!');
    }

    public function update(Request $request, Accountant $accountant)
    {
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'adresse' => 'required',
            'organizations' => 'required|array'
        ]);

        $accountant->update($validated);
        $accountant->organizations()->sync($request->organizations);

        return redirect()->route('accountants.index')->with('message', 'Accountant updated successfully!');
    }

    public function destroy(Accountant $accountant)
    {
        $accountant->delete();

        return redirect()->route('accountants.index')->with('message', 'Accountant deleted successfully!');
    }

    //api returning emails for accountants that are in same org

    public function getemailsaccountant()
    {
        $userOrganization = Auth::user()->organization;
        // Retrieve accountants in the same organization
        $accountants = Accountant::whereHas('organizations', function ($query) use ($userOrganization) {
            $query->where('organizations.id', $userOrganization->id); // Specify the table alias for the id column
        })->get();
        $emails = $accountants->pluck('email')->toArray();
        return response()->json(['emails' => $emails], 200);
    }
    
}
