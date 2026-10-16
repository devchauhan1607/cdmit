<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Catalog', [
            'categories' => Category::orderBy('type')->orderBy('display_order')->get(),
            'technologies' => Technology::orderBy('category')->orderBy('display_order')->get(),
        ]);
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'], 'type' => ['required', 'in:service,post'],
            'description' => ['nullable', 'string', 'max:500'], 'display_order' => ['integer', 'min:0'],
        ]);
        $data['slug'] = Str::slug($data['name'].'-'.$data['type']);
        Category::create($data);

        return back()->with('success', 'Category created.');
    }

    public function destroyCategory(Category $category)
    {
        abort_if($category->services()->exists() || $category->posts()->exists(), 422, 'Category is in use.');
        $category->delete();

        return back()->with('success', 'Category removed.');
    }

    public function storeTechnology(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:technologies,name'],
            'category' => ['required', 'string', 'max:80'], 'description' => ['nullable', 'string', 'max:500'],
            'display_order' => ['integer', 'min:0'],
        ]);
        $data['slug'] = Str::slug($data['name']);
        Technology::create($data);

        return back()->with('success', 'Technology added.');
    }

    public function destroyTechnology(Technology $technology)
    {
        abort_if($technology->caseStudies()->exists(), 422, 'Technology is used by a case study.');
        $technology->delete();

        return back()->with('success', 'Technology removed.');
    }
}
