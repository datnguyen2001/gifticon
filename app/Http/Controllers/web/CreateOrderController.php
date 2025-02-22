<?php

namespace App\Http\Controllers\web;

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
use Illuminate\Support\Facades\Response;
use Milon\Barcode\DNS1D;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateOrderController extends Controller
{
    public function indexBuyNow(Request $request)
    {
        $user = JWTAuth::user();
        $productID = $request->query('productID');
        $product = ShopProductModel::where('id', $productID)->first();

        return view('web.create-order.index-buy-now', compact('user', 'product'));
    }

    public function downloadExcel()
    {
        $filePath = public_path('assets/excel/example.xlsx');
        return Response::download($filePath);
    }

    public function addToCartSubmit(Request $request)
    {
        try {
            $productID = $request->input('product_id');
            $product = ShopProductModel::findOrFail($productID);
            $productPrice = $product->price;
            $maxQuantity = $product->quantity;

            $buyFor = $request->input('buy_for');

            if ($buyFor == '2') {
                $rules = [
                    'receivers' => 'required|array|min:1',
                    'receivers.*.phone' => 'required|regex:/^\d{9,15}$/|distinct',
                    'receivers.*.quantity' => 'required|integer|min:1|max:' . $maxQuantity, // Ensure receiver quantity doesn't exceed max stock
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
            $receivers = $request->input('receivers', []);
            if ($buyFor == '2') {
                $totalQuantity = 0;
                foreach ($receivers as $receiver) {
                    $receiverQuantity = intval($receiver['quantity'] ?? 0);
                    $totalQuantity += $receiverQuantity;
                }

                if ($totalQuantity > $maxQuantity) {
                    return redirect()->back()->withInput()->with('error', 'Tổng số lượng không được vượt quá số lượng tồn kho.');
                }

                $totalPrice = $totalQuantity * $productPrice;
            } else {
                $quantity = intval($request->input('quantity', 0));
                if ($quantity > $maxQuantity) {
                    return redirect()->back()->withInput()->with('error', 'Số lượng không được vượt quá số lượng sản phẩm có sẵn.');
                }
                $totalPrice = $quantity * $productPrice;
            }

            if($buyFor == '1'){
                $cart = CartModel::where('user_id', JWTAuth::user()->id)
                    ->where('product_id', $productID)
                    ->where('buy_for', $buyFor)
                    ->where('type', 1)
                    ->first();

                if ($cart) {
                    // Update existing cart
                    $cart->quantity += $request->input('quantity', 0);
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
            }elseif ($buyFor == '2') {
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

            return redirect()->back()->with('success', 'Thêm sản phẩm vào giỏ hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function buyNowSubmit(Request $request)
    {
        try {
            $user = JWTAuth::user();
            // Set the 'is_selected' column to false for all carts of the current user
            CartModel::where('user_id', $user->id)->update(['is_selected' => false]);

            $productID = $request->input('product_id');
            $product = ShopProductModel::findOrFail($productID);
            $productPrice = $product->price;
            $maxQuantity = $product->quantity;

            $buyFor = $request->input('buy_for');

            if ($buyFor == '2') {
                $rules = [
                    'receivers' => 'required|array|min:1',
                    'receivers.*.phone' => 'required|regex:/^\d{9,15}$/|distinct',
                    'receivers.*.quantity' => 'required|integer|min:1|max:' . $maxQuantity, // Ensure receiver quantity doesn't exceed max stock
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
            $receivers = $request->input('receivers', []);
            if ($buyFor == '2') {
                $totalQuantity = 0;
                foreach ($receivers as $receiver) {
                    $receiverQuantity = intval($receiver['quantity'] ?? 0);
                    $totalQuantity += $receiverQuantity;
                }

                if ($totalQuantity > $maxQuantity) {
                    return redirect()->back()->withInput()->with('error', 'Tổng số lượng không được vượt quá số lượng tồn kho.');
                }

                $totalPrice = $totalQuantity * $productPrice;
            } else {
                $quantity = intval($request->input('quantity', 0));
                if ($quantity > $maxQuantity) {
                    return redirect()->back()->withInput()->with('error', 'Số lượng không được vượt quá số lượng sản phẩm có sẵn.');
                }
                $totalPrice = $quantity * $productPrice;
            }

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
                $shop = ShopModel::find($shopProduct->shop_id);
                if ($shopProduct) {
                    $shopID = $shopProduct->shop_id;
                    $unitPrice = $shopProduct->price;
                    $productQuantity = $shopProduct->quantity;
                }

                $barcodeGenerator = new DNS1D();
                $voucherID = random_int(1000000000, 9999999999);
                $barCodeDataString = (string) $voucherID;
                $barcode = $barcodeGenerator->getBarcodeSVG($barCodeDataString, 'C128', 2, 50);
                if ($cart['buy_for'] == '1') {
                    $orderProductData = [
                        'order_id' => $order->id,
                        'product_id' => $cart['product_id'],
                        'message' => $cart['message'] ?? null,
                        'quantity' => $cart['quantity'],
                        'buy_for' => $cart['buy_for'],
                        'shop_id' => $shopID,
                        'unit_price' => $unitPrice,
                        'receiver_phone' => $user->phone ?? null,
                        'barcode' => $barcode,
                        'voucher_id' => $voucherID,
                        'start_date' => $shopProduct->start_date,
                        'end_date' => $shopProduct->end_date,
                        'commission_money' => $cart['quantity'] * $unitPrice * ($shop->commission_percentage / 100),
                    ];

                    OrderProductModel::create($orderProductData);

                    $newQuantity = $productQuantity - $cart['quantity'];
                    $shopProduct->update(['quantity' => $newQuantity]);

                    $notification = new NotificationModel();
                    $notification->name = "Bạn có đơn hàng mới";
                    $notification->content = "Bạn vừa mua '.$shopProduct->name.' từ của hàng '.$shop->name.' của chúng tôi. Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi.";
                    $notification->sender_id = $shopID;
                    $notification->receiver_id = $user->id;
                    $notification->save();

                } elseif ($cart['buy_for'] == '2'){
                    $cart = CartModel::where('id', $cart['id'])->first();

                    if ($cart && $cart->buy_for == '2') {
                        $cartReceivers = CartReceiverModel::where('cart_id', $cart['id'])->get();
                        $totalQuantity = $cartReceivers->sum('quantity');
                        foreach ($cartReceivers as $receiver) {
                            $orderProductData = [
                                'order_id' => $order->id,
                                'product_id' => $cart['product_id'],
                                'message' => $cart['message'] ?? null,
                                'quantity' => $receiver->quantity,
                                'buy_for' => $cart['buy_for'],
                                'shop_id' => $shopID,
                                'unit_price' => $unitPrice,
                                'receiver_phone' => $receiver->phone,
                                'barcode' => $barcode,
                                'voucher_id' => $voucherID,
                                'start_date' => $shopProduct->start_date,
                                'end_date' => $shopProduct->end_date,
                                'commission_money' => $receiver->quantity * $unitPrice * ($shop->commission_percentage / 100)
                            ];
                        }
                        $orderProduct = OrderProductModel::create($orderProductData);

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
                CartReceiverModel::where('cart_id', $cart['id'])->delete();

            }

            return redirect()->route('home')->with('success', 'Mua hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
