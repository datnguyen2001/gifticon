<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\MemberShipModel;
use App\Models\ProductReviewModel;
use App\Models\ShopProductLocationModel;
use App\Models\ShopProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductController extends Controller
{
    public function detail($slug)
    {
        $product = ShopProductModel::where('slug', $slug)->first();
        $productLocations = ShopProductLocationModel::where('product_id', $product->id)->get();
        $memberShip = MemberShipModel::get();

        $reviews = ProductReviewModel::with('user')->where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $star = $this->starReview($product);
        $star_five = ProductReviewModel::where('product_id', $product->id)->where('star', 5)->count();
        $star_four = ProductReviewModel::where('product_id', $product->id)->where('star', 4)->count();
        $star_three = ProductReviewModel::where('product_id', $product->id)->where('star', 3)->count();
        $star_two = ProductReviewModel::where('product_id', $product->id)->where('star', 2)->count();
        $star_one = ProductReviewModel::where('product_id', $product->id)->where('star', 1)->count();
        $percent_5 = 0;
        $percent_4 = 0;
        $percent_3 = 0;
        $percent_2 = 0;
        $percent_1 = 0;
        if ($star_five > 0){
            $percent_5 = round(($star_five / count($star)) * 100,0);
        }
        if ($star_four > 0){
            $percent_4 = round(($star_four / count($star)) * 100,0);
        }
        if ($star_three > 0){
            $percent_3 = round(($star_three / count($star)) * 100,0);
        }
        if ($star_two > 0){
            $percent_2 = round(($star_two / count($star)) * 100,0);
        }
        if ($star_one > 0){
            $percent_1 = round(($star_one / count($star)) * 100,0);
        }

        return view('web.product.detail', compact('product', 'productLocations', 'memberShip','reviews',
        'percent_1','percent_2','percent_3','percent_4','percent_5'));
    }

    public function storeReviews(Request $request)
    {
        $user = session('jwt_token') ? \Tymon\JWTAuth\Facades\JWTAuth::setToken(session('jwt_token'))->authenticate() : null;

        if (!$user) {
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập để tiếp tục'], 401);
            }
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:5',
        ], [
            'rating.required' => 'Vui lòng chọn đánh giá',
            'rating.integer' => 'Đánh giá phải là một số nguyên từ 1 đến 5',
            'content.required' => 'Vui lòng nhập nội dung đánh giá',
            'content.string' => 'Nội dung đánh giá phải là một chuỗi ký tự',
            'content.min' => 'Nội dung đánh giá phải có ít nhất 5 ký tự',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        ProductReviewModel::create([
            'user_id' => $user->id,
            'product_id' => $request->get('product_id'),
            'content' => $request->get('content'),
            'star' => $request->get('rating'),
        ]);

        return redirect()->back()->with('success', 'Đánh giá csản phẩm thành công');
    }


    public function loadReviews(Request $request)
    {
        $product_id = $request->get('product_sp_id');

        $reviews = ProductReviewModel::with('user')->where('product_id', $product_id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json([
            'reviews' => $reviews->items(),
            'has_more' => $reviews->hasMorePages()
        ]);
    }

    public function starReview($product)
    {
        $product->star = 0;
        $star = ProductReviewModel::where('product_id', $product->id)->orderBy('created_at','desc')->get();
        if (!$star->isEmpty()) {
            $total_score =  ProductReviewModel::where('product_id', $product->id)->sum('star');
            $total_votes = count($star);
            $product->star = round($total_score/$total_votes, 1);
        }
        return $star;
    }
}
