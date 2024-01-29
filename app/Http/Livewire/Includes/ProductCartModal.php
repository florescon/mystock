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

    /** @var array */
    protected $rules = [
        'item_discount.*'        => 'required|integer|between:1,99',
    ];

    protected $messages = [
        'item_discount.*' => 'Verifique el descuento',
    ];

    public function mount()
    {
        $this->discount_type = 'percentage';
        $this->item_discount = [];
    }

    public function productDiscount($row_id, $product_id): void
    {
        $cart_item = Cart::instance('sale')->get($row_id);

        // dd($cart_item->price);
        if($this->discount_type == 'percentage'){
            $this->validate();
        }
        else{
            $validatedFixed = $this->validate(
                ['item_discount.*' => 'integer|not_in:0|required|max:'.$cart_item->options->unit_price],
            );
        }

        // dd($this->item_discount);

        // dd((float) $this->item_discount[$product_id]);

        $discount = (float) $this->item_discount[$product_id];
        $discount_type = $this->discount_type;

        if($this->discount_type == 'percentage'){
            $discount_amount = ($cart_item->price + $cart_item->options->product_discount) * (float) $this->item_discount[$product_id] / 100;
        }
        else{
            $discount_amount = (float) $this->item_discount[$product_id];
        }

        // dd($discount_amount);

        Cart::instance('sale')
            ->update($row_id, [
                'price' => ($cart_item->price + $cart_item->options->product_discount) - $discount_amount,
            ]);

        // dd(Cart::instance('sale')->get($row_id));

        $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount, $discount, $discount_type);

        $this->alert('success', __('Product discount set successfully!'), ['position' => 'top']);

        $this->showProductCartModal = false;

        $this->emit('renderComponent');
    }

    public function updateCartOptions($row_id, $product_id, $cart_item, $discount_amount, $discount, $discount_type)
    {
        Cart::instance('sale')->update($row_id, [
            'options' => [
                'sub_total'             => $cart_item->price * $cart_item->qty,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $cart_item->options->product_tax,
                'unit_price'            => $cart_item->price + $discount_amount,
                'customer_id'           => is_numeric($product_id) ? null : $cart_item->options->customer_id,
                'customer_name'         => is_numeric($product_id) ? null : $cart_item->options->customer_name,
                'product_discount'      => $discount_amount,
                'product_discount_type' => $discount_type,
                'discount_get'          => $discount ?? $cart_item->options->discount_get,
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
