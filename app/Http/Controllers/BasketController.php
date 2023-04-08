<?php

namespace App\Http\Controllers;

use App\Mail\SaveOrder;
use App\Models\Basket;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class BasketController extends Controller
{

    private $basket;

    public function __construct()
    {
        $this->getBasket();
        $products = $this->basket->products;
    }

    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $products = $this->basket->products;
        return view('basket.index', compact('products'));
    }

    public function checkout(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('basket.checkout');
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function add(Request $request, $id): RedirectResponse
    {
        $quantity = $request->input('quantity') ?? 1;
        $this->basket->increase($id, $quantity);
        return back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function plus($id): RedirectResponse
    {
        $this->basket->increase($id);
        return redirect()->route('basket.index');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function minus($id): RedirectResponse
    {
        $this->basket->decrease($id);
        return redirect()->route('basket.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
     * @throws ValidationException
     */
    public function saveOrder(Request $request): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        $user_id = auth()->check() ? auth()->user()->id : null;

        $order = Order::create(
            $request->except(['_token', '_method']) + ['amount' => $this->getAmount(), 'user_id' => $user_id],
        );

        foreach ($this->basket->products as $product) {
            $order->items()->create([
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->pivot->quantity,
                'cost' => $product->price * $product->pivot->quantity,
            ]);
        }

        $user = User::where(["email" => $request["email"]])->first();

        Mail::to($user)->send(new SaveOrder($order));

        $this->basket->delete();

        session()->flash('success', 'Ваш заказ оформлен, проверьте указанный Вами E-mail');
        return redirect(route('basket.success'))->with('order_id', $order->id);
    }

    public function getAmount()
    {
        $amount = 0.0;
        foreach ($this->basket->products as $product) {
            $amount = $amount + $product->price * $product->pivot->quantity;
        }
        return $amount;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|View|Factory|Redirector|Application|RedirectResponse
     */
    public function success(Request $request): \Illuminate\Foundation\Application|View|Factory|Redirector|Application|RedirectResponse
    {
        if ($request->session()->exists('order_id')) {
            $order_id = $request->session()->pull('order_id');
            $order = Order::findOrFail($order_id);
            return view('basket.success', compact('order'));
        } else {
            return redirect(route('basket.index'));
        }
    }

    private function getBasket(): void
    {
        $basket_id = request()->cookie('basket_id');
        if (!empty($basket_id)) {
            try {
                $this->basket = Basket::findOrFail($basket_id);
            } catch (ModelNotFoundException $e) {
                $this->basket = Basket::create();
            }
        } else {
            $this->basket = Basket::create();
        }
        Cookie::queue('basket_id', $this->basket->id, 525600);
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function remove($id): RedirectResponse
    {
        $this->basket->remove($id);
        return redirect()->route('basket.index');
    }

    public function clear(): RedirectResponse
    {
        $this->basket->delete();
        return redirect()->route('basket.index');
    }
}
