<?php

namespace App\Http\Controllers\shop;

use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use Illuminate\Http\Request;

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
            $listData = $listData->with([
                'orderProducts' => function ($query) {
                    $query->select('id', 'order_id', 'product_id', 'message','quantity');
                },
                'orderProducts.orderReceivers' => function ($query) {
                    $query->select('id', 'order_id', 'order_product_id', 'phone');
                }
            ])
                ->orderBy('updated_at', 'desc')
                ->paginate(10);

            foreach ($listData as $item) {
                $item->status_name = $this->checkStatusOrder($item);
            }
            $order_all = OrderModel::count();
            $order_pending = OrderModel::where('status_id', 0)->count();
            $order_paid = OrderModel::where('status_id', 1)->count();

            return view('shop.order.index', compact('titlePage', 'page_menu', 'listData', 'page_sub', 'status', 'order_all','order_paid','order_pending'));
        }catch (\Exception $exception){
            dd($exception);
        }
    }

    public function statusOrder($order_id, $status_id)
    {
        try {
            $order = OrderModel::find($order_id);
                $order->status = $status_id;
                if ($status_id == 1) {
                    $this->sendVoucherZalo($order);
                }
                $order->save();

                return \redirect()->route('admin.order.index', [$status_id])->with(['success' => 'Xét trạng thái đơn hàng thành công']);

        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function checkStatusOrder($item)
    {

        if ($item->status_id == 0) {
            $val_status = 'Chưa thanh toán';
        } else {
            $val_status = 'Đã thanh toán';
        }

        return $val_status;
    }

}
