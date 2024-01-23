<div>
    <!-- Button trigger Discount Modal -->
    <button type="button" wire:click="showProductCartModal('{{ $cart_item['id'] }}', '{{ $cart_item['rowId'] }}')"
        class="border border-red-500 text-red-500 hover:text-reg-800">
        <i class="bi bi-percent text-black"></i>
    </button>
    <!-- Discount Modal -->
    <x-modal wire:model="showProductCartModal">
        <x-slot name="title">
            <div class="text-center text-xl">
                {{ $cart_item['name'] }}
                <x-badge type="success">
                    {{ $cart_item['options']['code'] }}
                </x-badge>
            </div>
        </x-slot>
        <x-slot name="content">
            <form wire:submit.prevent="productDiscount('{{ $cart_item['rowId'] }}', '{{ $cart_item['id'] }}')">
                <!-- Validation Errors -->
                <x-validation-errors class="mb-4" :errors="$errors" />
                <div class="grid grid-cols-2 gap-4 my-4">
                    <div>
                        <label>{{ __('Discount Type') }}<span class="text-red-500">*</span></label>
                        <select 
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            required>
                            <option value="percentage">{{ __('Percentage') }}</option>
                        </select>
                    </div>
                    <div>
                        <label>{{ __('Discount(%)') }} <span class="text-red-500">*</span></label>
                        <x-input wire:model.defer="item_discount.{{ $cart_item['id'] }}" type="text"
                            value="{{ $cart_item['id'] }}" min="0" max="100" />
                    </div>
                </div>
                <div class="w-full">
                    <x-button primary type="submit" class="w-full text-center">
                        {{ __('Save changes') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
