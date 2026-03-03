<?php

namespace App\Http\Controllers;

use App\Models\BuffetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BuffetRequestController extends Controller
{
    public function create(Request $request)
    {
        return view('buffet.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'company_name' => ['nullable', 'string', 'max:120'],
            'people_count' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'event_at' => ['nullable', 'date'],
            'details' => ['nullable', 'string', 'max:2000'],
        ]);

        $buffet = DB::transaction(function () use ($validated, $request) {
            $publicCode = $this->generatePublicCode();

            return BuffetRequest::query()->create([
                'public_code' => $publicCode,
                'customer_name' => $validated['customer_name'] ?? null,
                'phone' => $validated['phone'],
                'company_name' => $validated['company_name'] ?? null,
                'people_count' => $validated['people_count'] ?? null,
                'event_at' => $validated['event_at'] ?? null,
                'details' => $validated['details'] ?? null,
                'status' => 'new',
                'created_by_user_id' => $request->user() ? $request->user()->id : null,
            ]);
        });

        return redirect()->route('buffet.show', ['publicCode' => $buffet->public_code]);
    }

    public function show(string $publicCode)
    {
        $buffet = BuffetRequest::query()
            ->with('createdBy')
            ->where('public_code', $publicCode)
            ->firstOrFail();

        return view('buffet.show', [
            'buffet' => $buffet,
        ]);
    }

    private function generatePublicCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (BuffetRequest::query()->where('public_code', $code)->exists());

        return $code;
    }
}
