<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class OrderController extends Controller
{
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $orders = Order::orderBy('status', 'asc')->paginate(5);
        $statuses = Order::STATUSES;
        return view('admin.order.index', compact('orders', 'statuses'));
    }

    /**
     * @param Order $order
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function show(Order $order): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $statuses = Order::STATUSES;
        return view('admin.order.show', compact('order', 'statuses'));
    }

    /**
     * @param Order $order
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function edit(Order $order): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $statuses = Order::STATUSES;
        return view('admin.order.edit', compact('order', 'statuses'));
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
     */
    public function update(Request $request, Order $order): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $order->update($request->except(['_token', '_method']));
        session()->flash('Заказ был успешно обновлен');
        return redirect(route('admin.order.show', ['order' => $order->id]));
    }
}
