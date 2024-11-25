<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CartModel;
use App\Models\CartReceiverModel;
use App\Models\ShopProductModel;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateOrderController extends Controller
{
    public function indexCart(Request $request)
    {
        $user = JWTAuth::user();
        $productID = $request->query('productID');
        $product = ShopProductModel::where('id', $productID)->first();

        return view('web.create-order.index-cart', compact('user', 'product'));
    }

    public function indexBuyNow(Request $request)
    {
        $user = JWTAuth::user();
        $productID = $request->query('productID');
        $product = ShopProductModel::where('id', $productID)->first();

        return view('web.create-order.index-buy-now', compact('user', 'product'));
    }

    public function addToCartSubmit(Request $request)
    {
        try {
            $productID = $request->input('product_id');
            $product = ShopProductModel::findOrFail($productID);
            $productPrice = $product->price;

            $buyFor = $request->input('buy_for');

            if ($buyFor == '2') {
                $rules = [
                    'receivers' => 'required|array|min:1',
                    'receivers.*.phone' => 'required|regex:/^\d{9,15}$/',
                    'receivers.*.quantity' => 'required',
                ];
                $messages = [
                    'receivers.required' => 'Thông tin người nhận phải được điền đủ',
                    'receivers.*.phone.required' => 'Số điện thoại không được để trống cho tất cả người nhận',
                    'receivers.*.phone.regex' => 'Số điện thoại phải có từ 9 đến 15 chữ số.',
                    'receivers.*.quantity.required' => 'Số lượng không được để trống cho tất cả người nhận',
                ];
            } else {
                $rules = [
                    'quantity' => 'required|integer|min:1',
                ];
                $messages = [
                    'quantity.required' => 'Vui lòng nhập số lượng.',
                    'quantity.integer' => 'Số lượng phải là một số hợp lệ.',
                    'quantity.min' => 'Số lượng phải lớn hơn 0.',
                ];
            }

            $request->validate($rules, $messages);

            $totalPrice = 0;
            $receivers = $request->input('receivers', []);
            if ($buyFor == '2') {
                foreach ($receivers as $receiver) {
                    $receiverQuantity = intval($receiver['quantity'] ?? 0);
                    $totalPrice += $receiverQuantity;
                }
            } else {
                $quantity = intval($request->input('quantity', 0));
                $totalPrice = $quantity;
            }
            $totalPrice *= $productPrice;

            // Create cart entry
            $cart = CartModel::create([
                'user_id' => JWTAuth::user()->id,
                'product_id' => $productID,
                'quantity' => $request->input('quantity', null),
                'buy_for' => $buyFor,
                'total_price' => $totalPrice,
                'message' => $request->input('note', null),
            ]);

            // Save receivers if "buy_for = 2"
            if ($buyFor == '2') {
                foreach ($receivers as $receiver) {
                    CartReceiverModel::create([
                        'cart_id' => $cart->id,
                        'phone' => $receiver['phone'],
                        'quantity' => $receiver['quantity'],
                    ]);
                }
            }

            return redirect()->route('cart.index')->with('success', 'Thêm sản phẩm vào giỏ hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function buyNowSubmit(Request $request)
    {

    }
}
