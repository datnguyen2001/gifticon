<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CartModel;
use App\Models\CartReceiverModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class CartController extends Controller
{
    public function index()
    {
        try {
            $user = JWTAuth::user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng không tồn tại hoặc phiên làm việc đã hết hạn.'
                ], 401); // Unauthorized status
            }

            $cartsCheckSelect = CartModel::where('user_id', $user->id)->where('type', 1);
            if ($cartsCheckSelect->exists()) {
                $cartsCheckSelect->update(['is_selected' => false]);
            }

            $carts = CartModel::where('user_id', $user->id)->where('type', 1)
                ->with('product:id,src,name,start_date,end_date', 'receivers:cart_id,phone,quantity')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $carts
            ], 200); // OK status

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500); // Internal server error status
        }
    }

    public function detail($id)
    {
        $cart = CartModel::with([
            'product' => function ($query) {
                $query->select('id', 'name', 'start_date', 'end_date');
            },
            'receivers' => function ($query) {
                $query->select('id', 'cart_id', 'phone', 'quantity');
            }
        ])
            ->select('id', 'user_id', 'product_id', 'total_price', 'quantity', 'buy_for', 'message')
            ->find($id);

        if ($cart) {
            $cartDetail = [
                'data' => $cart,
            ];

            return response()->json($cartDetail);
        }

        return response()->json(['message' => 'Không tìm thấy giỏ hàng'], 404);
    }

    public function deleteCart($id)
    {
        try {
            $cart = CartModel::findOrFail($id);

            // Check if there are receivers associated with the cart and delete them if necessary
            if ($cart->buy_for == '2') {
                CartReceiverModel::where('cart_id', $cart->id)->delete();
            }

            // Delete the cart item
            $cart->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Xoá đơn hàng thành công!'
            ], 200); // OK status
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500); // Internal server error status
        }
    }

    public function addToCartSubmit(Request $request) {
        try {
            $user = JWTAuth::user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng không tồn tại hoặc phiên làm việc đã hết hạn.'
                ], 401);
            }
            // Set the 'is_selected' column to false for all carts of the current user
            CartModel::where('user_id', $user->id)->update(['is_selected' => false]);

            $selectedCartIds = json_decode($request->input('selected_cart_id'));

            if (empty($selectedCartIds) || count(array_filter($selectedCartIds)) === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.'
                ], 400);
            }

            foreach ($selectedCartIds as $selectedCartId) {
                $selectedCartId = explode(',', $selectedCartId);

                $cartItems = CartModel::where('user_id', $user->id)->whereIn('id', $selectedCartId)->where('type', 1)->get();
                if ($cartItems->isEmpty()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Không tìm thấy sản phẩm trong giỏ hàng của bạn.'
                    ], 404);
                }

                CartModel::where('user_id', $user->id)->whereIn('id', $selectedCartId)->update(['is_selected' => true]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Mua hàng thành công, vui lòng thanh toán!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
