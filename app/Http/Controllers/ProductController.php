<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private const CATEGORIES = ['Hot', 'Iced', 'Desserts'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $products = Product::query()->with(['createdBy', 'updatedBy'])->orderByDesc('created_at')->paginate(10);

        return view('products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('products.create', [
            'categories' => self::CATEGORIES,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $validated = $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', self::CATEGORIES)],
            'price' => ['required', 'numeric', 'min:0'],
            'description_en' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'image_file' => ['nullable', 'file', 'image', 'max:5120'],
            'is_available' => ['nullable'],
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        unset($validated['image_file']);

        $validated['name'] = (string) ($validated['name_en'] ?? '');
        $validated['description'] = (string) ($validated['description_en'] ?? '');

        if ($request->user()) {
            $validated['created_by_user_id'] = $request->user()->id;
            $validated['updated_by_user_id'] = $request->user()->id;
        }

        $product = Product::create($validated);

        return redirect()->route('products.show', $product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //

        return view('products.show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //

        return view('products.edit', [
            'product' => $product,
            'categories' => self::CATEGORIES,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //

        $validated = $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', self::CATEGORIES)],
            'price' => ['required', 'numeric', 'min:0'],
            'description_en' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'image_file' => ['nullable', 'file', 'image', 'max:5120'],
            'is_available' => ['nullable'],
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        if (!$request->filled('image_url') && !$request->hasFile('image_file')) {
            unset($validated['image_url']);
        }

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        unset($validated['image_file']);

        $validated['name'] = (string) ($validated['name_en'] ?? '');
        $validated['description'] = (string) ($validated['description_en'] ?? '');

        if ($request->user()) {
            $validated['updated_by_user_id'] = $request->user()->id;
        }

        $product->update($validated);

        return redirect()->route('products.show', $product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //

        $product->delete();

        return redirect()->route('products.index');
    }
}
