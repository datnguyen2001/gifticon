<?php

namespace App\Http\Controllers\shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getDataOrder(Request $request, $status)
    {
        try {
            $titlePage = 'Quản lý đơn hàng';
            $page_menu = 'order';
            $page_sub = 'order';

            $listData = OrderModel::query();
            if ($status !== 'all') {
                $listData = $listData->where('status', $status);
            }
            $listData = $listData->orderBy('updated_at', 'desc')->paginate(10);
            foreach ($listData as $item) {
                $item->status_name = $this->checkStatusOrder($item);
            }
            $order_all = OrderModel::count();
            $order_pending = OrderModel::where('status', 0)->count();
            $order_paid = OrderModel::where('status', 1)->count();

            return view('admin.order.index', compact('titlePage', 'page_menu', 'listData', 'page_sub', 'status', 'order_all','order_paid','order_pending'));
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
                    $this->sendVoucherZalo();
                }
                $order->save();

                return \redirect()->route('admin.order.index', [$status_id])->with(['success' => 'Xét trạng thái đơn hàng thành công']);

        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function checkStatusOrder($item)
    {

        if ($item->status == 0) {
            $val_status = 'Chưa thanh toán';
        } else {
            $val_status = 'Đã thanh toán';
        }

        return $val_status;
    }

}
