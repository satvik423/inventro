<?php

namespace App\Http\Controllers;

use App\Exports\ProductExcelExport;
use App\Http\Requests\ProductStoreRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\ProcessProductImport;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $results = Product::with('category');
        $categories = Category::all();
        $incart = Cart::where('user_id', auth()->id())->get();
        $inCartProductIds = $incart->pluck('product_id')->toArray();

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

        return view('products.index', compact('products', 'categories', 'role', 'inCartProductIds'));
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

    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->back()->with('success', 'Product removed successfully!');
    }


    public function import(Request $request)
    {
        Log::info('Import method called');

        try {
            $validatedData = $request->validate([
                'import_file' => 'required|file|mimetypes:text/plain,text/csv,application/vnd.ms-excel'
            ]);

            Log::info('Validation passed', ['validatedData' => $validatedData]);

            // Store the file
            $file = $request->file('import_file');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('imports', $fileName);

            Log::info('File stored successfully', ['file_path' => $filePath]);

            // Dispatch job to process the import
            ProcessProductImport::dispatch($filePath);
            Log::info('Import job dispatched');

            return back()->with('success', 'Product import is in progress.');
        } catch (ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);

            return back()->withErrors([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ])->withInput();
        }
    }
}
