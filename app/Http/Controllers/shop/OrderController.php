<?php

namespace App\Http\Controllers\shop;

use App\Http\Controllers\Controller;
use App\Models\NotificationModel;
use App\Models\OrderModel;
use App\Models\OrderProductModel;
use App\Models\ShopProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{
    public function getDataOrder(Request $request, $status)
    {
        try {
            $titlePage = 'Quản lý đơn hàng';
            $page_menu = 'order';
            $page_sub = 'order';

            $search = $request->get('search');

            $listData = OrderModel::query();
            if ($status !== 'all') {
                $listData = $listData->where('status_id', $status);
            }
            if (!empty($search)) {
                $listData = $listData->where('order_code', 'LIKE', '%' . $search . '%');
            }
            $listData = $listData->orderBy('updated_at', 'desc')
                ->paginate(10);

            foreach ($listData as $item) {
                $item->status_name = $this->checkStatusOrder($item);
            }
            $order_all = OrderModel::count();
            $order_pending = OrderModel::where('status_id', 1)->count();
            $order_paid = OrderModel::where('status_id', 2)->count();
            $order_canceled = OrderModel::where('status_id', 3)->count();

            return view('admin.order.index', compact('titlePage', 'page_menu', 'listData', 'page_sub', 'status', 'order_all', 'order_paid', 'order_pending','order_canceled'));
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function getDataOrderShop(Request $request, $status)
    {
        try {
            $titlePage = 'Quản lý đơn hàng';
            $page_menu = 'order';
            $page_sub = 'order';

            $shop_id = Auth::guard('shop')->id();
            $query = OrderModel::whereHas('orderProducts', function ($q) use ($shop_id) {
                $q->where('shop_id', $shop_id);
            });
            if ($status !== 'all') {
                $query->where('status_id', $status);
            }

            if ($request->has('search') && $request->get('search')) {
                $barcode = $request->get('search');
                $query->whereHas('orderProducts', function ($q) use ($barcode) {
                    $q->where('barcode', 'LIKE', "%$barcode%");
                });
            }

            $listData = $query->with(['orderProducts.product'])->orderBy('updated_at', 'desc')
                ->paginate(20);

            foreach ($listData as $item) {
                $item->status_name = $this->checkStatusOrder($item);
            }

            $order_all = OrderModel::whereHas('orderProducts', function ($q) use ($shop_id) {
                $q->where('shop_id', $shop_id);
            })->count();
            $order_pending = OrderModel::whereHas('orderProducts', function ($q) use ($shop_id) {
                $q->where('shop_id', $shop_id);
            })->where('status_id', 1)->count();
            $order_paid = OrderModel::whereHas('orderProducts', function ($q) use ($shop_id) {
                $q->where('shop_id', $shop_id);
            })->where('status_id', 2)->count();
            $order_canceled = OrderModel::whereHas('orderProducts', function ($q) use ($shop_id) {
                $q->where('shop_id', $shop_id);
            })->where('status_id', 3)->count();

            return view('shop.order.index', compact('titlePage', 'page_menu', 'listData', 'page_sub', 'status', 'order_all', 'order_paid', 'order_pending','order_canceled'));
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function orderDetail($order_id)
    {
        try {
            $titlePage = 'Chi tiết đơn hàng';
            $page_menu = 'order';
            $page_sub = 'order';
            $listData = OrderModel::find($order_id);
            if ($listData) {
                $order_item = OrderProductModel::where('order_id', $order_id)->get();
                foreach ($order_item as $item) {
                    $product = ShopProductModel::find($item->product_id);
                    if ($product) {
                        $item->product_name = $product->name;
                        $item->product_image = $product->src;
                    }
                }
                $listData['status_name'] = $this->checkStatusOrder($listData);
                $listData['order_item'] = $order_item;

                return view('admin.order.detail', compact('titlePage', 'page_menu', 'listData', 'page_sub'));
            } else {
                return back()->withErrors(['error' => 'Đơn hàng không tồn tại']);
            }
        } catch (\Exception $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function statusOrder($order_id, $status_id)
    {
        try {
            $order = OrderModel::find($order_id);
            $order->status_id = $status_id;
            $orderItem = OrderProductModel::where('order_id',$order->id)->get();
            foreach ($orderItem as $item){
                if ($status_id == 2) {
//                $this->sendVoucherZalo($order);
                    $notification = new NotificationModel();
                    $notification->name = "Thay đổi trạng thái đơn hàng";
                    $notification->content = 'Đơn hàng '.$order->order_code.' của bạn đã được xác nhân. Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi.';
                    $notification->sender_id = $item->shop_id;
                    $notification->receiver_id = $order->user_id;
                    $notification->save();
                }

                if ($status_id == 3) {
                    $notification = new NotificationModel();
                    $notification->name = "Thay đổi trạng thái đơn hàng";
                    $notification->content = 'Đơn hàng '.$order->order_code.' của bạn đã bị hủy. Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi.';
                    $notification->sender_id = $item->shop_id;
                    $notification->receiver_id = $order->user_id;
                    $notification->save();
                }
            }

            $order->save();

            return \redirect()->route('admin.order.index', [$status_id])->with(['success' => 'Xét trạng thái đơn hàng thành công']);

        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function checkStatusOrder($item)
    {

        if ($item->status_id == 1) {
            $val_status = 'Chưa thanh toán';
        } elseif ($item->status_id == 2){
            $val_status = 'Đã thanh toán';
        } else {
            $val_status = 'Đã hủy';
        }

        return $val_status;
    }

}
