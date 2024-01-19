<div>
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Income') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="flex flex-wrap -mx-2 mb-3">
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="income.reference" :value="__('Reference')" />
                        <x-input wire:model.lazy="income.reference" id="income.reference" type="text" required />
                        <x-input-error :messages="$errors->get('income.reference')" class="mt-2" />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="income.date" :value="__('Date')" />
                        <x-input-date wire:model.lazy="income.date" id="income.date" name="income.date"
                            required />
                        <x-input-error :messages="$errors->get('income.date')" class="mt-2" />
                    </div>

                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="income.category_id" :value="__('Income Category')" />
                        <select required
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            id="category_expense" name="category_expense" wire:model="income.category_id">
                            @foreach ($this->expensecategories as $expensecategory)
                                <option value="{{ $expensecategory->id }}">
                                    {{ $expensecategory->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('income.category_id')" class="mt-2" />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="income.warehouse_id" :value="__('Warehouse')" />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            id="warehouse_expense" name="warehouse_expense" wire:model="income.warehouse_id">
                            <option value=""></option>
                            @foreach ($this->warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('income.warehouse_id')" class="mt-2" />
                    </div>
                    <div class="xl:w-1/3 lg:w-1/2 sm:w-full px-3">
                        <x-label for="income.amount" :value="__('Amount')" required />
                        <x-input wire:model.lazy="income.amount" id="income.amount" type="text" required />
                        <x-input-error :messages="$errors->get('income.amount')" class="mt-2" />
                    </div>
                    <div class="w-full px-4 mb-4">
                        <x-label for="income.details" :value="__('Description')" />
                        <textarea
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            rows="6" wire:model.lazy="income.details" id="income.details"></textarea>
                        <x-input-error :messages="$errors->get('income.details')" class="mt-2" />
                    </div>
                </div>
                <div class="w-full px-3 py-3">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
