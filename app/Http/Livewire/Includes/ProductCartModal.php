<?php

namespace App\Http\Livewire\Includes;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ProductCartModal extends Component
{
    use LivewireAlert;

    public $listeners = [
        'showProductCartModal',
    ];

    public $cart_item;
    public $idCart;
    public $rowId;

    public $discount_type;
    public $item_discount;

    public $showProductCartModal = false;

    public function mount()
    {
        $this->discount_type = [];
        $this->item_discount = [];
    }

    public function productDiscount($row_id, $product_id): void
    {
        // dd($this->item_discount);

        $cart_item = Cart::instance('sale')->get($row_id);

        // dd($this->item_discount[$product_id]);

        $discount_amount = ($cart_item->price + $cart_item->options->product_discount) * (float) $this->item_discount[$product_id] / 100;

        // dd($discount_amount);

        Cart::instance('sale')
            ->update($row_id, [
                'price' => $cart_item->price + $cart_item->options->product_discount - $discount_amount,
            ]);

        $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount);

        $this->alert('success', __('Product discount set successfully!'), ['position' => 'top']);

        $this->showProductCartModal = false;

        $this->emit('renderComponent');
    }

    public function updateCartOptions($row_id, $product_id, $cart_item, $discount_amount)
    {
        // dd($row_id);
        Cart::instance('sale')->update($row_id, [
            'options' => [
                'sub_total'             => $cart_item->price * $cart_item->qty,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $cart_item->options->product_tax,
                'unit_price'            => $cart_item->price,
                'customer_id'           => is_numeric($product_id) ? null : $cart_item->options->customer_id,
                'customer_name'         => is_numeric($product_id) ? null : $cart_item->options->customer_name,
                'product_discount'      => $cart_item->options->product_discount,
                'product_discount_type' => $cart_item->options->product_discount_type,
                'service_type'          => is_numeric($product_id) ? null : $cart_item->options->service_type,
                'days'                  => is_numeric($product_id) ? null : $cart_item->options->days,
                'hour'                  => is_numeric($product_id) ? null : $cart_item->options->hour,
            ],
        ]);
    }

    public function showProductCartModal($idCart, $rowId)
    {
        $this->idCart = $this->cart_item['id'];
        $this->rowId = $this->cart_item['rowId'];

        $this->showProductCartModal = true;
    }

    public function render()
    {
        return view('livewire.includes.product-cart-modal');
    }
}
