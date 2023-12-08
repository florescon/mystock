<div>
    <!-- Create Modal -->
    <x-modal wire:model="createModal">
        <x-slot name="title">
            {{ __('Create Service') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit.prevent="create">

                <div class="w-full px-3 mb-4">
                    <x-label for="name" :value="__('Name')" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                        wire:model.lazy="service.name" />
                    <x-input-error :messages="$errors->get('service.name')" for="name" class="mt-2" />
                </div>
                <div class="w-full px-3 mb-4">
                    <x-label for="price" :value="__('Price')" />
                    <x-input id="price" class="block mt-1 w-full" type="text" name="price"
                        wire:model.lazy="service.price" />
                    <x-input-error :messages="$errors->get('service.price')" for="price" class="mt-2" />
                </div>
                <div class="w-full px-3 mb-4">
                    <x-label for="note" :value="__('Note')" />
                    <textarea id="note" class="block mt-1 w-full" type="text" name="note"
                        wire:model.lazy="service.note"></textarea>
                    <x-input-error :messages="$errors->get('service.note')" for="note" class="mt-2" />
                </div>

                <div class="w-full px-3 mb-4">
                    <label>{{ __('Service Type') }}</label>
                    <select wire:model.defer="service.service_type"
                        class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                        name="service_type">
                        <option value="">{{ __('Select Service Type') }}</option>
                        @foreach (\App\Enums\ServiceType::cases() as $status)
                            <option value="{{ $status->value }}">
                                {{ __($status->name) }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('service.service_type')" for="note" class="mt-2" />
                </div>

                <div class="w-full px-3 mb-4">
                    <x-label for="image" :value="__('Image')" />
                    <x-fileupload wire:model="image" :file="$image" accept="image/jpg,image/jpeg,image/png" />
                    <x-input-error :messages="$errors->get('image')" for="image" class="mt-2" />
                </div>
                <div class="w-full px-3 py-2">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Create') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Create Modal -->
</div>
