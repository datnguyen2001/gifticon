<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CartModel;
use App\Models\CartReceiverModel;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CartController extends Controller
{
    public function index ()
    {
        $user = JWTAuth::user();

        $cartsCheckSelect = CartModel::where('user_id', $user->id)->where('type', 1);
        if ($cartsCheckSelect->exists()) {
            $cartsCheckSelect->update(['is_selected' => false]);
        }

        $carts = CartModel::where('user_id', $user->id)->where('type', 1)
            ->with('product:id,src,name,start_date,end_date', 'receivers:cart_id,phone,quantity')
            ->get();

        return view('web.cart.index', compact('carts'));
    }

    public function deleteCart(Request $request)
    {
        try {
            $cartID = $request->input('cart_id');

            $cart = CartModel::findOrFail($cartID);

            if ($cart->buy_for == '2') {
                CartReceiverModel::where('cart_id', $cart->id)->delete();
            }

            $cart->delete();

            return redirect()->route('cart.index')->with('success', 'Xoá đơn hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function payment(Request $request)
    {
        try {
            $selectedCartIds = $request->input('selected_cart_id');
            if (empty($selectedCartIds) || count(array_filter($selectedCartIds)) === 0) {
                return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
            }
            foreach ($selectedCartIds as $selectedCartId){
                $selectedCartId = explode(',', $selectedCartId);
                CartModel::whereIn('id', $selectedCartId)->update(['is_selected' => true]);
            }

            return redirect()->route('order.index')->with('success', 'Mua hàng thành công, vui lòng thanh toán!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

}
