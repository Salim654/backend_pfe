<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use App\Models\Organization;
use App\Models\Accountant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $countrysnb = Country::count();
        $ogrnb = Organization::count();
        $accs = Accountant::count();
        $usersnb = User::where('role', 'user')->count();
        return view('admin.dashboard', compact('countrysnb', 'ogrnb', 'usersnb', 'accs'));
    }
    
   
}
