<?php

namespace App\Http\Controllers\shop;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use App\Models\ShopProductLocationModel;
use App\Models\ShopProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shopID = Auth::guard('shop')->id();
        $listData = ShopProductModel::select('id', 'name', 'src', 'display')
            ->where('shop_id', $shopID)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('shop.product.index', compact('listData'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CategoryModel::all();

        return view('shop.product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'array|required',
            'location.*' => 'string|max:255',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:category,id',
        ]);
        try {
            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('products', 'public');
                $filePath = 'storage/' . $filePath;
            }
            $shopID = Auth::guard('shop')->id();
            $slug = Str::slug($request->input('name'), '-');

            $product = ShopProductModel::create([
                'shop_id' => $shopID,
                'category_id' => $request->input('category_id'),
                'name' => $request->input('name'),
                'slug' => $slug,
                'price' => $request->input('price'),
                'display' => $request->has('display') ? 1 : 0,
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'src' => $filePath,
                'describe' => $request->input('describe'),
                'guide' => $request->input('guide'),
            ]);

            // Create product locations
            $locations = $request->input('location', []);
            foreach ($locations as $location) {
                ShopProductLocationModel::create([
                    'product_id' => $product->id,
                    'location' => $location,
                ]);
            }

            return redirect()->route('shop.product.index')->with('success', 'Sản phẩm đã được thêm thành công!');
        } catch (\Exception $e) {
            return back()->with(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $data = ShopProductModel::find($id);
            $locations = ShopProductLocationModel::where('product_id', $id)->get();
            $categories = CategoryModel::all();

            if (empty($data)) {
                return back()->with(['error' => 'Dữ liệu không tồn tại']);
            }

            return view('shop.product.edit', compact('data', 'locations', 'categories'));
        } catch (\Exception $exception) {
            return back()->with(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'array',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:category,id',
        ]);

        try {
            $product = ShopProductModel::findOrFail($id);
            $slug = Str::slug($request->input('name'), '-');
            // Update product details
            $product->update([
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
                'price' => $request->input('price'),
                'display' => $request->has('display') ? 1 : 0,
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'describe' => $request->input('describe'),
                'guide' => $request->input('guide'),
                'slug' => $slug
            ]);

            // Handle file upload
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('products', 'public');
                $product->update(['src' => 'storage/' . $filePath]);
            }

            // Update locations
            $locations = $request->input('location', []);
            ShopProductLocationModel::where('product_id', $id)->delete();

            foreach ($locations as $location) {
                ShopProductLocationModel::create([
                    'product_id' => $id,
                    'location' => $location,
                ]);
            }

            return redirect()->route('shop.product.index')->with('success', 'Sản phẩm đã được cập nhật thành công!');
        } catch (\Exception $e) {
            return back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = ShopProductModel::findOrFail($id);

            ShopProductLocationModel::where('product_id', $id)->delete();

            $product->delete();

            return redirect()->route('shop.product.index')->with('success', 'Sản phẩm đã được xóa thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

}
