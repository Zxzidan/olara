<?php

namespace App\Http\Controllers;

use App\Models\DropoffPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DropoffController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = DropoffPartner::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->boolean('open_only')) {
            $query->where('is_open', true);
        }

        if ($request->boolean('premium_only')) {
            $query->where('is_premium_partner', true);
        }

        if ($request->filled('material')) {
            $mat = $request->input('material');
            $query->where('accepted_materials', 'like', "%{$mat}%");
        }

        $partners = $query->get();
        $selectedCategory = $request->input('category');

        return view('dropoff.index', compact('partners', 'user', 'selectedCategory'));
    }
}
