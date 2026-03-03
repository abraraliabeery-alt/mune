<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyAccount;
use Illuminate\Http\Request;

class LoyaltyAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->string('q')->toString());

        $query = LoyaltyAccount::query()->orderByDesc('points')->orderBy('phone');

        if ($q !== '') {
            $query->where('phone', 'like', "%{$q}%");
        }

        return view('admin.loyalty.index', [
            'accounts' => $query->paginate(25),
            'q' => $q,
        ]);
    }
}
