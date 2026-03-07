<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Models\Order;
use App\Services\Admin\OrderManagementService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderManagementService $orderManagementService
    ) {
    }

    /**
     * 관리자 주문/배송 목록을 조회한다.
     *
     * @param  Request  $request
     * @return View
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'search',
            'order_status',
            'payment_status',
            'shipping_status',
            'date_from',
            'date_to',
        ]);

        $orders = $this->orderManagementService->paginateOrders($filters);
        $stats = $this->orderManagementService->getSummaryStats();

        return view('admin.orders.index', [
            'orders' => $orders,
            'stats' => $stats,
            'trashedOrdersCount' => Order::onlyTrashed()->count(),
            'orderStatusOptions' => Order::ORDER_STATUSES,
            'paymentStatusOptions' => Order::PAYMENT_STATUSES,
            'shippingStatusOptions' => Order::SHIPPING_STATUSES,
        ]);
    }

    /**
     * 관리자 주문 휴지통 목록을 조회한다.
     *
     * @param  Request  $request
     * @return View
     */
    public function trash(Request $request): View
    {
        $filters = $request->only([
            'search',
            'order_status',
            'payment_status',
            'shipping_status',
            'date_from',
            'date_to',
        ]);

        $orders = $this->orderManagementService->paginateTrashedOrders($filters);

        return view('admin.orders.trash', [
            'orders' => $orders,
            'orderStatusOptions' => Order::ORDER_STATUSES,
            'paymentStatusOptions' => Order::PAYMENT_STATUSES,
            'shippingStatusOptions' => Order::SHIPPING_STATUSES,
        ]);
    }

    /**
     * 관리자 주문 상세를 조회한다.
     *
     * @param  Order  $order
     * @return View
     */
    public function show(Order $order): View
    {
        $order->load(['items.product']);

        return view('admin.orders.show', [
            'order' => $order,
            'orderStatusOptions' => Order::ORDER_STATUSES,
            'paymentStatusOptions' => Order::PAYMENT_STATUSES,
            'shippingStatusOptions' => Order::SHIPPING_STATUSES,
        ]);
    }

    /**
     * 관리자 주문/배송 상태를 수정한다.
     *
     * @param  UpdateOrderRequest  $request
     * @param  Order  $order
     * @return RedirectResponse
     */
    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        $this->orderManagementService->updateOrder($order, $request->validated());

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', '주문 및 배송 상태가 업데이트되었습니다.');
    }

    /**
     * 관리자 주문을 삭제한다.
     *
     * @param  Order  $order
     * @return RedirectResponse
     */
    public function destroy(Order $order): RedirectResponse
    {
        $orderNumber = $order->order_number;

        $this->orderManagementService->deleteOrder($order);

        return redirect()
            ->route('admin.orders.index')
            ->with('success', "주문 {$orderNumber} 이(가) 삭제 처리되었습니다.");
    }

    /**
     * soft delete된 주문을 복구한다.
     *
     * @param  Order  $order
     * @return RedirectResponse
     */
    public function restore(Order $order): RedirectResponse
    {
        if (! $this->orderManagementService->restoreOrder($order)) {
            return redirect()
                ->route('admin.orders.trash')
                ->with('error', '복구할 수 없는 주문입니다.');
        }

        return redirect()
            ->route('admin.orders.trash')
            ->with('success', "주문 {$order->order_number} 이(가) 복구되었습니다.");
    }

    /**
     * soft delete된 주문을 영구 삭제한다.
     *
     * @param  Order  $order
     * @return RedirectResponse
     */
    public function forceDestroy(Order $order): RedirectResponse
    {
        $orderNumber = $order->order_number;

        if (! $this->orderManagementService->forceDeleteOrder($order)) {
            return redirect()
                ->route('admin.orders.trash')
                ->with('error', '영구 삭제할 수 없는 주문입니다.');
        }

        return redirect()
            ->route('admin.orders.trash')
            ->with('success', "주문 {$orderNumber} 이(가) 영구 삭제되었습니다.");
    }
}
