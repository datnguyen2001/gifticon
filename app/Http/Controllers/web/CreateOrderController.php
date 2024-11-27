<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CartModel;
use App\Models\CartReceiverModel;
use App\Models\OrderModel;
use App\Models\OrderProductModel;
use App\Models\ShopProductModel;
use Carbon\Carbon;
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
                'type' => 1,
            ]);

            // Save receivers if "buy_for = 2"
            if ($buyFor == '2') {
                $totalQuantity = 0;
                foreach ($receivers as $receiver) {
                    CartReceiverModel::create([
                        'cart_id' => $cart->id,
                        'phone' => $receiver['phone'],
                        'quantity' => $receiver['quantity'],
                    ]);
                    $totalQuantity += $receiver['quantity'];
                }
                $cart->update([
                    'quantity' => $totalQuantity
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Thêm sản phẩm vào giỏ hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function buyNowSubmit(Request $request)
    {
        try {
            $user = JWTAuth::user();
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

            $cartBuyNow = CartModel::where('user_id', $user->id)
                ->where('type', 2)
                ->first();

            if ($cartBuyNow) {
                if($cartBuyNow->buy_for == '2'){
                    CartReceiverModel::where('cart_id', $cartBuyNow->id)->delete();
                }
                $cartBuyNow->delete();
            }

            // Create cart entry
            $cart = CartModel::create([
                'user_id' => JWTAuth::user()->id,
                'product_id' => $productID,
                'quantity' => $request->input('quantity', null),
                'buy_for' => $buyFor,
                'total_price' => $totalPrice,
                'message' => $request->input('note', null),
                'type' => 2,
                'is_selected' => true
            ]);

            // Save receivers if "buy_for = 2"
            if ($buyFor == '2') {
                $totalQuantity = 0;
                foreach ($receivers as $receiver) {
                    CartReceiverModel::create([
                        'cart_id' => $cart->id,
                        'phone' => $receiver['phone'],
                        'quantity' => $receiver['quantity'],
                    ]);
                    $totalQuantity += $receiver['quantity'];
                }
                $cart->update([
                    'quantity' => $totalQuantity
                ]);
            }

            return redirect()->route('order.index')->with('success', 'Mua hàng thành công, vui lòng thanh toán!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function confirmOrder(Request $request)
    {
        try {
            $user = JWTAuth::user();

            $today = Carbon::today()->format('dmY');
            $orderCountToday = OrderModel::where('order_code', 'like', "{$today}%")->count();
            $orderSuffix = str_pad($orderCountToday + 1, 2, '0', STR_PAD_LEFT);
            $orderCode = "{$today}_{$orderSuffix}";

            $order = OrderModel::create([
                'user_id' => $user->id,
                'order_code' => $orderCode,
                'total_price' => $request->input('total_price'),
                'status_id' => 1,
            ]);

            foreach ($request->input('carts') as $cart) {
                $shopProduct = ShopProductModel::where('id', $cart['product_id'])->first();
                if ($shopProduct) {
                    $shopID = $shopProduct->shop_id;
                    $unitPrice = $shopProduct->price;
                }

                if($cart['buy_for'] == '1'){
                    $orderProductData = [
                        'order_id' => $order->id,
                        'product_id' => $cart['product_id'],
                        'message' => $cart['message'] ?? null,
                        'quantity' => $cart['quantity'],
                        'buy_for' => $cart['buy_for'],
                        'shop_id' => $shopID,
                        'unit_price' => $unitPrice,
                        'receiver_phone' => $user->phone ?? null,
                        'barcode' => 'ABC123',
                    ];
                    OrderProductModel::create($orderProductData);
                } elseif ($cart['buy_for'] == '2'){
                    $cart = CartModel::where('id', $cart['id'])->first();

                    if ($cart && $cart->buy_for == '2') {
                        $cartReceivers = CartReceiverModel::where('cart_id', $cart['id'])->get();

                        foreach ($cartReceivers as $receiver) {
                            OrderProductModel::create([
                                'order_id' => $order->id,
                                'product_id' => $cart['product_id'],
                                'message' => $cart['message'] ?? null,
                                'quantity' => $receiver->quantity,
                                'buy_for' => $cart['buy_for'],
                                'shop_id' => $shopID,
                                'unit_price' => $unitPrice,
                                'barcode' => 'ABC123',
                                'receiver_phone' => $receiver->phone
                            ]);
                        }
                    }
                }

                CartModel::where('id', $cart['id'])->delete();
                CartReceiverModel::where('cart_id', $cart['id'])->delete();
            }

            return redirect()->route('home')->with('success', 'Mua hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
