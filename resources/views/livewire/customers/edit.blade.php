<div>
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit') }} 
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="flex flex-wrap">
                    <div class="w-full sm:w-1/2 px-3 mb-6">
                        <x-label for="name" :value="__('Name')" required />
                        <x-input id="name" class="block mt-1 w-full" type="text"
                            wire:model.lazy="customer.name" required />
                        <x-input-error :messages="$errors->get('customer.name')" class="mt-2" />
                    </div>

                    <div class="w-full sm:w-1/2 px-3 mb-6">
                        <x-label for="phone" :value="__('Phone')" required />
                        <x-input id="phone" class="block mt-1 w-full" required type="text"
                            wire:model.lazy="customer.phone" />
                        <x-input-error :messages="$errors->get('customer.phone')" class="mt-2" />
                    </div>
                    <x-accordion title="{{ __('Details') }}" >
                        <div class="flex flex-wrap">
                        
                            <div class="w-full sm:w-1/2 px-3 mb-6">
                                <x-label for="email" :value="__('Email')" />
                                <x-input id="email" class="block mt-1 w-full" type="email"
                                    wire:model.lazy="customer.email" />
                                <x-input-error :messages="$errors->get('customer.email')" class="mt-2" />
                            </div>

                            <div class="w-full sm:w-1/2 px-3 mb-6">
                                <x-label for="address" :value="__('Address')" />
                                <x-input id="address" class="block mt-1 w-full" type="text"
                                    wire:model.lazy="customer.address" />
                                <x-input-error :messages="$errors->get('customer.address')" class="mt-2" />
                            </div>

                            <div class="w-full sm:w-1/2 px-3 mb-6">
                                <x-label for="city" :value="__('City')" />
                                <x-input id="city" class="block mt-1 w-full" type="text"
                                    wire:model.lazy="customer.city" />
                                <x-input-error :messages="$errors->get('customer.city')" class="mt-2" />
                            </div>

                            <div class="w-full sm:w-1/2 px-3 mb-6">
                                <x-label for="tax_number" :value="__('Tax Number')" />
                                <x-input id="tax_number" class="block mt-1 w-full" type="text"
                                    wire:model.lazy="customer.tax_number" />
                                <x-input-error :messages="$errors->get('customer.tax_number')" for="" class="mt-2" />
                            </div>

                            <div class="md:w-1/2 sm:w-full px-3">
                                <x-label for="blood_type" :value="__('Blood Type')" />

                                <select
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                    name="blood_type" id="blood_type" wire:model.lazy="customer.blood_type">
                                    <option>@lang('Select Blood Type')</option>
                                    @foreach (\App\Enums\BloodType::cases() as $status)
                                        <option value="{{ $status->value }}">
                                            {{ __($status->name) }}
                                        </option>
                                    @endforeach
                                </select>

                                <x-input-error :messages="$errors->get('customer.blood_type')" class="mt-2" />
                            </div>

                        </div>
                    </x-accordion>

                    <div class="w-full px-3 my-4">
                        <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                            {{ __('Update') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>