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
            $listData = $listData->orderBy('updated_at', 'desc')
                ->paginate(10);

            foreach ($listData as $item) {
                $item->status_name = $this->checkStatusOrder($item);
            }
            $order_all = OrderModel::count();
            $order_pending = OrderModel::where('status_id', 0)->count();
            $order_paid = OrderModel::where('status_id', 1)->count();

            return view('admin.order.index', compact('titlePage', 'page_menu', 'listData', 'page_sub', 'status', 'order_all', 'order_paid', 'order_pending'));
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
            $order_pending = OrderModel::where('status_id', 0)->count();
            $order_paid = OrderModel::where('status_id', 1)->count();

            return view('shop.order.index', compact('titlePage', 'page_menu', 'listData', 'page_sub', 'status', 'order_all', 'order_paid', 'order_pending'));
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
                $order_item = OrderItemModel::where('order_id', $order_id)->get();
                foreach ($order_item as $item) {
                    $product_attributes = ProductAttributesModel::find($item->product_attributes_id);
                    $item->product_name = ProductsModel::find($product_attributes->product_id);
                    $item->product_image = ProductInformationModel::find($item->product_name->product_infor_id);
                    $item->product_attribute = $product_attributes;
                }
                $listData['status_name'] = $this->checkStatusOrder($listData);
                $listData['order_item'] = $order_item;
                return view('admin.order.detail', compact('titlePage', 'page_menu', 'listData', 'page_sub', 'province', 'district', 'ward'));
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
            $order->status = $status_id;
            if ($status_id == 2) {
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
