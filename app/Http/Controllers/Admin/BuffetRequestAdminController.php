<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuffetRequest;
use Illuminate\Http\Request;

class BuffetRequestAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->string('q')->toString());
        $status = trim($request->string('status')->toString());

        $query = BuffetRequest::query()->with('createdBy')->orderByDesc('id');

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('public_code', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('company_name', 'like', "%{$q}%")
                    ->orWhere('customer_name', 'like', "%{$q}%");
            });
        }

        return view('admin.buffet.index', [
            'requests' => $query->paginate(25),
            'q' => $q,
            'status' => $status,
        ]);
    }

    public function show(Request $request, BuffetRequest $buffet)
    {
        $buffet->load('createdBy');

        return view('admin.buffet.show', [
            'buffet' => $buffet,
        ]);
    }

    public function updateStatus(Request $request, BuffetRequest $buffet)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,contacted,quoted,confirmed,closed,cancelled'],
        ]);

        $buffet->update([
            'status' => $validated['status'],
        ]);

        return back()->with('status', __('messages.admin_buffet_saved'));
    }

    public function saveQuote(Request $request, BuffetRequest $buffet)
    {
        $validated = $request->validate([
            'quote_amount' => ['nullable', 'numeric', 'min:0'],
            'quote_message' => ['nullable', 'string', 'max:2000'],
        ]);

        $hasAny = ($validated['quote_message'] ?? null) || ($validated['quote_amount'] ?? null) !== null;

        $buffet->update([
            'quote_amount' => $validated['quote_amount'] ?? null,
            'quote_message' => $validated['quote_message'] ?? null,
            'quoted_at' => $hasAny ? now() : null,
            'status' => $hasAny ? 'quoted' : $buffet->status,
        ]);

        return back()->with('status', __('messages.admin_buffet_saved'));
    }
}
