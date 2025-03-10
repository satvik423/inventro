<?php

namespace App\Http\Controllers;

use App\Exports\ProductExcelExport;
use App\Exports\ProductPDFExport;
use App\Http\Requests\ProductStoreRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $results = Product::with('category');
        $categories = Category::all();

        // Apply filters only if search or category is provided
        if ($request->filled('category')) {
            $results->where('category_id', $request->category);
        }
        if ($request->filled('search')) {
            $results->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('sort')) {
            $results->orderBy('price', $request->sort);
        }

        $role = User::where('id', auth()->id())->value('role');
        $products = $results->paginate(10);

        return view('products.index', compact('products', 'categories', 'role'));
    }

    public function store(ProductStoreRequest $request)
    {
        Product::create($request->validated());
        return redirect()->back()->with('success', 'Product added successfully!');
    }

    public function export(Request $request)
    {
        $query = Product::with('category');

        // Apply filters before exporting
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Fetch filtered data
        $filteredProducts = $query->get();

        // Check export type
        if ($request->export == 'pdf') {
            $pdf = Pdf::loadView('exports.products_pdf', compact('filteredProducts'));
            return $pdf->download('filtered_products.pdf');
        }

        return Excel::download(new ProductExcelExport($filteredProducts), 'filtered_products.xlsx');
    }
}
