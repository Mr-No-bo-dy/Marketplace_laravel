<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubcategoryRequest;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the Subcategories.
     *
     * @return View
     */
    public function index(): View
    {
        $subcategoryModel = new Subcategory();

        $subcategories = $subcategoryModel->readAllSubcategories();

        return view('admin.subcategories.index', compact('subcategories'));
    }

    /**
     * Display Subcategory creation form
     *
     * @return View
     */
    public function create(): View
    {
        $categories = Category::all(['id_category', 'name']);

        return view('admin.subcategories.create', compact('categories'));
    }

    /**
     * Create Subcategory
     *
     * @param SubcategoryRequest $request
     * @return RedirectResponse
     */
    public function store(SubcategoryRequest $request): RedirectResponse
    {
        Subcategory::create($request->validated());

        return redirect()->route('admin.subcategory');
    }

    /**
     * Display Subcategory update form
     *
     * @param int $idSubcategory
     * @return View|RedirectResponse
     */
    public function edit(int $idSubcategory): View|RedirectResponse
    {
        $categories = Category::all(['id_category', 'name']);
        $subcategory = Subcategory::findOrFail($idSubcategory);

        return view('admin.subcategories.update', compact('categories', 'subcategory'));
    }

    /**
     * Update Subcategory
     *
     * @param SubcategoryRequest $request
     * @return RedirectResponse
     */
    public function update(SubcategoryRequest $request): RedirectResponse
    {
        $idSubcategory = $request->validate(['id_subcategory' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_subcategory'];
        $subcategory = Subcategory::findOrFail($idSubcategory);
        $subcategory->fill($request->validated());
        if ($subcategory->isDirty()) {
            $subcategory->save();
        }

        return redirect()->route('admin.subcategory');
    }

    /**
     * Delete Subcategory & all its Products
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $idSubcategory = $request->validate(['id_subcategory' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_subcategory'];
        Subcategory::findOrFail($idSubcategory)->delete();

        return back();
    }

    /**
     * Restore Subcategory & all its Products
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function restore(Request $request): RedirectResponse
    {
        $idSubcategory = $request->validate(['id_subcategory' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_subcategory'];
        Subcategory::onlyTrashed()->findOrFail($idSubcategory)->restore();

        return back();
    }
}
