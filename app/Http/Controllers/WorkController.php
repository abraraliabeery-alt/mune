<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index(Request $request)
    {
        $works = Work::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('works.index', [
            'works' => $works,
        ]);
    }

    public function show(string $slug)
    {
        $work = Work::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with('media')
            ->firstOrFail();

        return view('works.show', [
            'work' => $work,
        ]);
    }
}
