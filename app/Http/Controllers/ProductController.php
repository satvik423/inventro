<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')->paginate(10);

        $role = User::where('id', auth()->id())->value('role');
        // if (!in_array($role, ['admin', 'user'])) {
        //     abort(403, 'Unauthorized access');
        // }

        // Authorization via Policy (Optional)
        // if (!Gate::allows('view-products', $role)) {
        //     abort(403, 'Unauthorized');
        // }

        return view('products.index', compact('products', 'role'));
    }
    public function store(ProductStoreRequest $request)
    {
        Product::create($request->validated());
        return redirect()->back()->with('success', 'Product added successfully!');
    }
}
