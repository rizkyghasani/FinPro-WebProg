<?php

namespace App\Http\Controllers;

// app/Http/Controllers/CategoryController.php

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // READ (Index)
    public function index()
    {
        $categories = Auth::user()->categories()->latest()->get();

        return view('categories.index', compact('categories'));
    }

    // CREATE (Show Form)
    public function create()
    {
        return view('categories.create');
    }

    // CREATE (Store Data)
    public function store(CategoryRequest $request)
    {
        Auth::user()->categories()->create($request->validated());

        return redirect()->route('categories.index')
                         ->with('success', __('Berhasil membuat Kategori baru!')); 
    }

    // EDIT (Show Form)
    public function edit(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('categories.edit', compact('category'));
    }

    // UPDATE
    public function update(CategoryRequest $request, Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $category->update($request->validated());

        return redirect()->route('categories.index')
                         ->with('success', __('Kategori berhasil diperbarui.'));
    }

    // DELETE
    public function destroy(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', __('Kategori berhasil dihapus.'));
    }
}