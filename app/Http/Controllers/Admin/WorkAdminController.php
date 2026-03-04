<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Work;
use App\Models\WorkMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WorkAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->string('q')->toString());

        $query = Work::query()->with(['createdBy', 'updatedBy'])->orderByDesc('id');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('title_en', 'like', "%{$q}%")
                    ->orWhere('title_ar', 'like', "%{$q}%");
            });
        }

        return view('admin.works.index', [
            'works' => $query->paginate(20),
            'q' => $q,
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.works.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => ['required', 'string', 'max:255'],
            'title_ar' => ['required', 'string', 'max:255'],
            'body_en' => ['nullable', 'string', 'max:4000'],
            'body_ar' => ['nullable', 'string', 'max:4000'],
            'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'media_images.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'media_videos.*' => ['nullable', 'file', 'mimes:mp4,webm,mov', 'max:51200'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slugBase = Str::slug($validated['title_en']);
        $slug = $slugBase;
        $i = 2;
        while (Work::query()->where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$i;
            $i++;
        }

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('works', 'public');
        }

        $work = Work::query()->create([
            'slug' => $slug,
            'title_en' => $validated['title_en'],
            'title_ar' => $validated['title_ar'],
            'body_en' => $validated['body_en'] ?? null,
            'body_ar' => $validated['body_ar'] ?? null,
            'cover_image_path' => $coverPath,
            'is_published' => (bool) ($validated['is_published'] ?? false),
            'published_at' => (bool) ($validated['is_published'] ?? false) ? now() : null,
            'created_by_user_id' => $request->user()?->id,
            'updated_by_user_id' => $request->user()?->id,
        ]);

        $this->storeMedia($request, $work);

        return redirect()->route('admin.works.edit', ['work' => $work->id])
            ->with('status', __('messages.admin_works_saved'));
    }

    public function edit(Request $request, Work $work)
    {
        $work->load('media');

        return view('admin.works.edit', [
            'work' => $work,
        ]);
    }

    public function update(Request $request, Work $work)
    {
        $validated = $request->validate([
            'title_en' => ['required', 'string', 'max:255'],
            'title_ar' => ['required', 'string', 'max:255'],
            'body_en' => ['nullable', 'string', 'max:4000'],
            'body_ar' => ['nullable', 'string', 'max:4000'],
            'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'media_images.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'media_videos.*' => ['nullable', 'file', 'mimes:mp4,webm,mov', 'max:51200'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($work->cover_image_path) {
                Storage::disk('public')->delete($work->cover_image_path);
            }
            $work->cover_image_path = $request->file('cover_image')->store('works', 'public');
        }

        $isPublished = (bool) ($validated['is_published'] ?? false);

        $work->update([
            'title_en' => $validated['title_en'],
            'title_ar' => $validated['title_ar'],
            'body_en' => $validated['body_en'] ?? null,
            'body_ar' => $validated['body_ar'] ?? null,
            'cover_image_path' => $work->cover_image_path,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? ($work->published_at ?: now()) : null,
            'updated_by_user_id' => $request->user()?->id,
        ]);

        $this->storeMedia($request, $work);

        return back()->with('status', __('messages.admin_works_saved'));
    }

    public function destroy(Request $request, Work $work)
    {
        $work->load('media');

        foreach ($work->media as $media) {
            Storage::disk('public')->delete($media->path);
        }

        if ($work->cover_image_path) {
            Storage::disk('public')->delete($work->cover_image_path);
        }

        $work->delete();

        return redirect()->route('admin.works.index')->with('status', __('messages.admin_works_deleted'));
    }

    public function destroyMedia(Request $request, WorkMedia $media)
    {
        Storage::disk('public')->delete($media->path);
        $media->delete();

        return back()->with('status', __('messages.admin_works_media_deleted'));
    }

    private function storeMedia(Request $request, Work $work): void
    {
        $sort = ((int) $work->media()->max('sort_order')) + 1;

        foreach ((array) $request->file('media_images', []) as $img) {
            if (! $img) {
                continue;
            }
            $path = $img->store('works', 'public');
            WorkMedia::query()->create([
                'work_id' => $work->id,
                'type' => 'image',
                'path' => $path,
                'sort_order' => $sort,
            ]);
            $sort++;
        }

        foreach ((array) $request->file('media_videos', []) as $vid) {
            if (! $vid) {
                continue;
            }
            $path = $vid->store('works', 'public');
            WorkMedia::query()->create([
                'work_id' => $work->id,
                'type' => 'video',
                'path' => $path,
                'sort_order' => $sort,
            ]);
            $sort++;
        }
    }
}
