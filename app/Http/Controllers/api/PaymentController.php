<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CartModel;
use App\Models\CartReceiverModel;
use App\Models\NotificationModel;
use App\Models\OrderModel;
use App\Models\OrderProductModel;
use App\Models\ShopModel;
use App\Models\ShopProductModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentController extends Controller
{
    public function index()
    {
        try {
            $user = JWTAuth::user();

            $carts = CartModel::where('user_id', $user->id)
                ->where('is_selected', true)
                ->with('product:id,src,name,start_date,end_date')
                ->get();

            $totalPayment = $carts->sum('total_price');

            return response()->json([
                'status' => 200,
                'message' => 'Lấy thông tin thanh toán thành công',
                'data' => $carts,
                'total_payment' => $totalPayment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Có lỗi lấy thông tin thanh toán',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function createOrder(Request $request)
    {
        try {
            $user = JWTAuth::user();
            // Set the 'is_selected' column to false for all carts of the current user
            CartModel::where('user_id', $user->id)->update(['is_selected' => false]);

            $productID = $request->input('product_id');
            $product = ShopProductModel::findOrFail($productID);
            $productPrice = $product->price;
            $maxQuantity = $product->quantity;

            $type = $request->get('type');
            $buyFor = $request->input('buy_for');

            if ($buyFor == '2') {
                $rules = [
                    'receivers' => 'required|min:1',
                    'receivers.*.phone' => 'required|regex:/^\d{9,15}$/|distinct',
                    'receivers.*.quantity' => 'required|integer|min:1|max:' . $maxQuantity,
                ];
                $messages = [
                    'receivers.required' => 'Thông tin người nhận phải được điền đủ',
                    'receivers.*.phone.required' => 'Số điện thoại không được để trống cho tất cả người nhận',
                    'receivers.*.phone.regex' => 'Số điện thoại phải có từ 9 đến 15 chữ số.',
                    'receivers.*.quantity.required' => 'Số lượng không được để trống cho tất cả người nhận',
                    'receivers.*.phone.distinct' => 'Số điện thoại của các người nhận phải khác nhau.',
                    'receivers.*.quantity.max' => 'Số lượng của mỗi người nhận không được vượt quá số lượng còn lại của sản phẩm.',
                ];
            } else {
                $rules = [
                    'quantity' => 'required|integer|min:1|max:' . $maxQuantity, // Ensure the quantity doesn't exceed the stock
                ];
                $messages = [
                    'quantity.required' => 'Vui lòng nhập số lượng.',
                    'quantity.integer' => 'Số lượng phải là một số hợp lệ.',
                    'quantity.min' => 'Số lượng phải lớn hơn 0.',
                    'quantity.max' => 'Số lượng không được vượt quá số lượng còn lại của sản phẩm.',
                ];
            }

            $request->validate($rules, $messages);

            $totalPrice = 0;
            $totalQuantity = 0;
            $receivers = json_decode($request->input('receivers'), true);
            if ($buyFor == '2') {
                foreach ($receivers as $receiver) {
                    $receiverQuantity = intval($receiver['quantity'] ?? 0);
                    $totalQuantity += $receiverQuantity;
                }

                if ($totalQuantity > $maxQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tổng số lượng không được vượt quá số lượng tồn kho.'
                    ], 400);
                }

                $totalPrice = $totalQuantity * $productPrice;
            } else {
                $totalQuantity = intval($request->input('quantity', 0));
                if ($totalQuantity > $maxQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Số lượng không được vượt quá số lượng sản phẩm có sẵn.'
                    ], 400);
                }
                $totalPrice = $totalQuantity * $productPrice;
            }

            if($type == 1){
                if($buyFor == '1'){
                    $cart = CartModel::where('user_id', JWTAuth::user()->id)
                        ->where('product_id', $productID)
                        ->where('buy_for', $buyFor)
                        ->where('type', 1)
                        ->first();
                    if ($cart) {
                        // Update existing cart
                        $cart->quantity += $totalQuantity;
                        $cart->total_price += $totalPrice;
                        $cart->message = $request->input('note', null);
                        $cart->save();
                    } else {
                        // Create new cart entry
                        $cart = CartModel::create([
                            'user_id' => JWTAuth::user()->id,
                            'product_id' => $productID,
                            'quantity' => $request->input('quantity', 0),
                            'buy_for' => $buyFor,
                            'total_price' => $totalPrice,
                            'message' => $request->input('note', null),
                            'type' => 1,
                        ]);
                    }
                }elseif ($buyFor == '2'){
                    $cart = CartModel::create([
                        'user_id' => JWTAuth::user()->id,
                        'product_id' => $productID,
                        'quantity' => $request->input('quantity', 0),
                        'buy_for' => $buyFor,
                        'total_price' => $totalPrice,
                        'message' => $request->input('note', null),
                        'type' => 1,
                    ]);
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
            }elseif ($type == 2){
                $cartBuyNow = CartModel::where('user_id', JWTAuth::user()->id)
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
            }

            return response()->json([
                'success' => true,
                'message' => 'Thêm sản phẩm vào giỏ hàng thành công!',
                'data' => $cart
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
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
            $cartIDs = json_decode($request->input('cart_ids'));
            foreach ($cartIDs as $cartID) {
                $cart = CartModel::where('id', $cartID)->first();

                $shopProduct = ShopProductModel::where('id', $cart->product_id)->first();
                $shop = ShopModel::find($shopProduct->shop_id);

                if ($shopProduct) {
                    $shopID = $shopProduct->shop_id;
                    $unitPrice = $shopProduct->price;
                    $productQuantity = $shopProduct->quantity;
                }
                if ($cart->buy_for == '1') {
                    $orderProductData = [
                        'order_id' => $order->id,
                        'product_id' => $cart->product_id,
                        'message' => $cart->message ?? null,
                        'quantity' => $cart->quantity,
                        'buy_for' => $cart->buy_for,
                        'shop_id' => $shopID,
                        'unit_price' => $unitPrice,
                        'receiver_phone' => $user->phone ?? null,
                        'barcode' => 'ABC123',
                    ];
                    OrderProductModel::create($orderProductData);

                    $newQuantity = $productQuantity - $cart->quantity;
                    $shopProduct->update(['quantity' => $newQuantity]);

                    $notification = new NotificationModel();
                    $notification->name = "Bạn có đơn hàng mới";
                    $notification->content = "Bạn vừa mua '.$shopProduct->name.' từ của hàng '.$shop->name.' của chúng tôi. Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi.";
                    $notification->sender_id = $shopID;
                    $notification->receiver_id = $user->id;
                    $notification->save();

                } elseif ($cart->buy_for == '2') {
                    $cart = CartModel::where('id', $cartID)->first();

                    if ($cart && $cart->buy_for == '2') {
                        $cartReceivers = CartReceiverModel::where('cart_id', $cartID)->get();
                        $totalQuantity = $cartReceivers->sum('quantity');
                        foreach ($cartReceivers as $receiver) {
                            OrderProductModel::create([
                                'order_id' => $order->id,
                                'product_id' => $cart->product_id,
                                'message' => $cart->message ?? null,
                                'quantity' => $receiver->quantity,
                                'buy_for' => $cart->buy_for,
                                'shop_id' => $shopID,
                                'unit_price' => $unitPrice,
                                'barcode' => 'ABC123',
                                'receiver_phone' => $receiver->phone
                            ]);
                        }
                        $newQuantity = $productQuantity - $totalQuantity;
                        $shopProduct->update(['quantity' => $newQuantity]);

                        $notification = new NotificationModel();
                        $notification->name = "Bạn có đơn hàng mới";
                        $notification->content = "Bạn vừa mua '.$shopProduct->name.' từ của hàng '.$shop->name.' của chúng tôi. Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi.";
                        $notification->sender_id = $shopID;
                        $notification->receiver_id = $user->id;
                        $notification->save();
                    }
                }
                CartModel::where('id', $cart['id'])->delete();
                CartReceiverModel::where('cart_id', $cartID)->delete();
            }
            return response()->json([
                'success' => true,
                'message' => 'Mua hàng thành công!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
