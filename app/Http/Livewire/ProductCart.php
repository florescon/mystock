<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\ProductWarehouse;
use App\Models\Service;
use App\Models\Customer;
use Livewire\Component;

class ProductCart extends Component
{
    use LivewireAlert;

    /** @var array<string> */
    public $listeners = [
        'productSelected', 'discountModalRefresh',
        'serviceSelected',
        'warehouseSelected' => 'updatedWarehouseId',
        'refreshComponent' => '$refresh',
        'renderComponent' => 'render',
    ];

    public $cart_instance;

    public $global_discount = 0;

    public $global_tax = 0;

    public $discountModal = false;

    public $shipping_amount;

    public $quantity = [];

    public $price;

    public $check_quantity = [];

    public ?int $warehouse_id = 1;

    public $discount_type;

    public $item_discount;

    public $data;

    public $total_with_shipping;


    public function rules(): array
    {
        return [
            'global_discount'         => 'numeric|min:0|max:100',
            'global_tax'              => 'numeric|min:0|max:100',
            'shipping_amount'         => 'numeric|min:0',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount($cartInstance, $data = null)
    {
        $this->cart_instance = $cartInstance;

        $this->shipping_amount = 0.00;

        $this->discount_type = [];
        $this->item_discount = [];

        if ($data) {
            $this->data = $data;

            $this->global_discount = $data->discount_percentage;
            $this->global_tax = $data->tax_percentage;
            $this->shipping_amount = $data->shipping_amount;
            $this->warehouse_id = $data->warehouse_id;

            $cart_items = Cart::instance($this->cart_instance)->content();

            foreach ($cart_items as $index => $cart_item) {
                $this->check_quantity[$cart_item->id] = [$cart_item->options->stock];
                $this->quantity[$cart_item->id] = $cart_item->qty;
                $this->discount_type[$cart_item->id] = $cart_item->options->product_discount_type;
                $this->item_discount[$cart_item->id] = ($cart_item->options->product_discount_type === 'fixed')
                    ? $cart_item->options->product_discount
                    : round(100 * $cart_item->options->product_discount / $cart_item->price);
            }
        }
    }

    public function productSelected($product): void
    {
        if (empty($product)) {
            $this->alert('error', __('Something went wrong!'), ['position' => 'top']);

            return;
        }

        $cart = Cart::instance($this->cart_instance);
        $exists = $cart->search(fn ($cartItem) => $cartItem->id === $product['id']);

        if ($exists->isNotEmpty()) {
            $this->alert('error', __('Product already added to cart!'), ['position' => 'top']);

            return;
        }

        $productWarehouse = ProductWarehouse::where('product_id', $product['id'])
            ->where('warehouse_id', $this->warehouse_id)
            ->first();

        if($this->warehouse_id){
            if($productWarehouse->qty < 1){
                $this->alert('error', __('There are no stock'), ['position' => 'top']);
                return;
            }
        }
        
        $cartItem = $this->createCartItem($product, $productWarehouse);

        $cart->add($cartItem);
        $this->updateQuantityAndCheckQuantity($product['id'], $productWarehouse->qty);

        $this->alert('success', __('Added product'), ['position' => 'top']);
        $this->emit('renderIndex');
    }

    public function serviceSelected($getService): void
    {
        $service = $getService[0];
        $customer = $getService ? (int) $getService[1] : null;
        $qty = $getService[2];
        $days = $getService[3] ?? null;
        $hour = $getService[4] ?? null;

        if (empty($service)) {
            $this->alert('error', __('Something went wrong!'), ['position' => 'top']);

            return;
        }

        $cart = Cart::instance($this->cart_instance);
        $exists = $cart->search(fn ($cartItem) => (($cartItem->id === $service['uuid'])) && ($cartItem->options->customer_id === $customer));

        if ($exists->isNotEmpty()) {
            $this->alert('error', __('Service already added to cart!'), ['position' => 'top']);

            return;
        }
        
        $cartItem = $this->createCartItemService($service, $customer, $qty, $days, $hour);

        $cart->add($cartItem);

        $this->quantity[$service['id']] = $qty;

        $this->alert('success', __('Added service'), ['position' => 'top']);
        $this->emit('renderIndex');
    }

    public function calculate($product): array
    {
        $productWarehouse = ProductWarehouse::where('product_id', $product['id'])
            ->where('warehouse_id', $this->warehouse_id)
            ->first();

        return $this->calculatePrices($product, $productWarehouse);
    }

    private function calculatePrices($product, $productWarehouse)
    {
        $price = $productWarehouse->price;
        $unit_price = $price;
        $product_tax = 0.00;
        $sub_total = $price;

        if ($product['tax_type'] === 1) {
            $tax = $price * $product['order_tax'];
            $price += $tax;
            $product_tax = $tax;
            $sub_total = $price;
        } elseif ($product['tax_type'] === 2) {
            $tax = $price * $product['order_tax'];
            $unit_price -= $tax;
            $product_tax = $tax;
        }

        return ['price' => $price, 'unit_price' => $unit_price, 'product_tax' => $product_tax, 'sub_total' => $sub_total];
    }

    private function calculatePricesService($service)
    {
        $price = $service['price'];
        $unit_price = $price;
        $service_tax = 0.00;
        $sub_total = $price;

        if ($service['tax_type'] === 1) {
            $tax = $price * $service['order_tax'];
            $price += $tax;
            $service_tax = $tax;
            $sub_total = $price;
        } elseif ($service['tax_type'] === 2) {
            $tax = $price * $service['order_tax'];
            $unit_price -= $tax;
            $service_tax = $tax;
        }

        return ['price' => $price, 'unit_price' => $unit_price, 'product_tax' => $service_tax, 'sub_total' => $sub_total];
    }

    private function updateQuantityAndCheckQuantity($productId, $quantity)
    {
        $this->check_quantity[$productId] = $quantity;
        $this->quantity[$productId] =  ($this->check_quantity[$productId] < 1) ? 0 : 1;
    }

    private function createCartItem($product, $productWarehouse)
    {
        $calculation = $this->calculate($product);

        return [
            'id'      => $product['id'],
            'name'    => $product['name'],
            'qty'     => 1,
            'price'   => $productWarehouse->price,
            'weight'  => 1,
            'options' => array_merge($calculation, [
                'product_discount'      => 0.00,
                'product_discount_type' => 'fixed',
                'service_type'          => null,
                'code'                  => $product['code'],
                'stock'                 => $productWarehouse->qty,
                'unit'                  => $product['unit'],
            ]),
        ];
    }

    private function createCartItemService($service, ?int $customer = null, $qty, $days, $hour)
    {
        $serviceModel = Service::where('id', $service['id'])->first();
        $serviceType = $serviceModel->service_type->name;

        $customerName = $customer ? Customer::findOrFail($customer)->name : null;

        $calculation = $this->calculatePricesService($service);

        return [
            'id'      => $service['uuid'],
            'name'    => $service['name'],
            'qty'     => $qty ?? 1,
            'price'   => $service['price'],
            'weight'  => 1,
            'options' => array_merge($calculation, [
                'sub_total'             => $service['price'] * $qty ?? 1,
                'product_discount'      => 0.00,
                'product_discount_type' => 'fixed',
                'customer_id'           => $customer,
                'customer_name'         => $customerName,
                'service_type'          => $serviceType,
                'code'                  => $service['code'],
                'stock'                 => null,
                'unit'                  => null,
                'days'                  => $days,
                'hour'                  => $hour,
            ]),
        ];
    }

    public function updatePrice($row_id, $product_id)
    {
        $validatedData = $this->validate(
            ['price.*' => 'integer|not_in:0'],
        );

        Cart::instance($this->cart_instance)->update($row_id, [
            'price' => $this->price[$product_id],
        ]);

        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        Cart::instance($this->cart_instance)->update($row_id, [
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

    public function updatedGlobalTax()
    {
        Cart::instance($this->cart_instance)->setGlobalTax((int) $this->global_tax);
    }

    public function updatedGlobalDiscount()
    {
        Cart::instance($this->cart_instance)->setGlobalDiscount((int) $this->global_discount);
    }

    public function updatedTotalShipping()
    {
        Cart::instance($this->cart_instance)->total() + (float) $this->shipping_amount;
    }

    public function updatedShippingAmount($value)
    {
        $this->shipping_amount = $value;
    }

    public function discountModal($product_id, $row_id): void
    {
        $this->updateQuantity($row_id, $product_id);

        $this->discountModal = true;
    }

    public function updateQuantity($row_id, $product_id)
    {
        if(is_numeric($product_id)){
            if ($this->cart_instance === 'sale' || $this->cart_instance === 'purchase_return') {
                if ($this->check_quantity[$product_id] < $this->quantity[$product_id]) {
                    $this->alert('error', __('Quantity is greater than in stock!').' '.__('Assigned').': '.$this->check_quantity[$product_id], ['position' => 'top']);
            
                    $this->quantity[$product_id] = $this->check_quantity[$product_id];
                    // return;
                }
            }

            if ($this->quantity[$product_id] < 1) {

                $this->quantity[$product_id] = $this->check_quantity[$product_id];

                $this->alert('error', __('Quantity must be greater than zero!'), ['position' => 'top']);

                return;
            }
        }

        Cart::instance($this->cart_instance)->update($row_id, $this->quantity[$product_id]);

        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        Cart::instance($this->cart_instance)->update($row_id, [
            'options' => [
                'sub_total'             => $cart_item->price * $cart_item->qty,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $cart_item->options->product_tax,
                'unit_price'            => $cart_item->options->unit_price,
                'customer_id'           => is_numeric($product_id) ? null : $cart_item->options->customer_id,
                'customer_name'         => is_numeric($product_id) ? null : $cart_item->options->customer_name,
                'product_discount'      => $cart_item->options->product_discount,
                'product_discount_type' => $cart_item->options->product_discount_type,
                'service_type'          => is_numeric($product_id) ? null : $cart_item->options->service_type,
            ],
        ]);
    }

    public function removeItem($row_id)
    {
        Cart::instance($this->cart_instance)->remove($row_id);

        $this->emit('renderIndex');
    }

    public function updatedDiscountType($value, $name)
    {
        $this->item_discount[$name] = 0;
    }

    public function productDiscount($row_id, $product_id): void
    {
        $cart_item = Cart::instance($this->cart_instance)->get($row_id);

        if ($this->discount_type[$product_id] === 'fixed') {
            Cart::instance($this->cart_instance)
                ->update($row_id, [
                    'price' => $cart_item->price + $cart_item->options->product_discount - $this->item_discount[$product_id],
                ]);

            $discount_amount = $this->item_discount[$product_id];

            $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount);
        } elseif ($this->discount_type[$product_id] === 'percentage') {
            $discount_amount = ($cart_item->price + $cart_item->options->product_discount) * $this->item_discount[$product_id] / 100;

            Cart::instance($this->cart_instance)
                ->update($row_id, [
                    'price' => $cart_item->price + $cart_item->options->product_discount - $discount_amount,
                ]);

            $this->updateCartOptions($row_id, $product_id, $cart_item, $discount_amount);
        }
        $this->alert('success', __('Product discount set successfully!'), ['position' => 'top']);

        $this->discountModal = false;
    }

    public function updateCartOptions($row_id, $product_id, $cart_item, $discount_amount)
    {
        Cart::instance($this->cart_instance)->update($row_id, [
            'options' => [
                'sub_total'             => $cart_item->price * $cart_item->qty,
                'code'                  => $cart_item->options->code,
                'stock'                 => $cart_item->options->stock,
                'unit'                  => $cart_item->options->unit,
                'product_tax'           => $cart_item->options->product_tax,
                'unit_price'            => $cart_item->options->unit_price,
                'product_discount'      => $discount_amount,
                'product_discount_type' => $cart_item->options->product_discount_type,
            ],
        ]);
    }

    public function updatedWarehouseId(?int $value = 1)
    {
        $this->warehouse_id = $value;
    }

    public function render()
    {
        $this->updatedGlobalTax();
        $this->updatedGlobalDiscount();
        $this->updatedTotalShipping();

        $cart_items = Cart::instance($this->cart_instance)->content();

        foreach ($cart_items as $index => $cart_item) {
            $this->discount_type[$cart_item->id] = $cart_item->options->product_discount_type;
            $this->item_discount[$cart_item->id] = ($cart_item->options->product_discount_type === 'fixed')
                ? $cart_item->options->product_discount
                : round(100 * $cart_item->options->product_discount / $cart_item->price);
        }

        return view('livewire.product-cart', [
            'cart_items' => $cart_items,
        ]);
    }
}
