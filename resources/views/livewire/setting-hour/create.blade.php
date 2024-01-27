<div>
    <x-modal wire:model="createModal">
        <x-slot name="title">
            {{ __('Create Hour') }}
        </x-slot>

        <x-slot name="content">

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit.prevent="create">
                <div class="flex flex-wrap -mx-2 mb-3">
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="hour.hour" :value="__('Hour')" required />
                        <x-input id="hour" class="block mt-1 w-full" required type="text"
                            wire:model.lazy="hour.hour" />
                        <x-input-error :messages="$errors->get('hour.hour')" for="hour" class="mt-2" />
                    </div>
                    <div class="md:w-1/2 sm:w-full px-3">
                        <x-label for="hour.is_am" :value="__('Is Am')" required />
                        <x-input id="is_am" class="block mt-1 w-full" type="text"
                            wire:model.lazy="hour.is_am" />
                        <x-input-error :messages="$errors->get('hour.is_am')" for="is_am" class="mt-2" />
                    </div>
                </div>
                <div class="w-full my-5 px-3">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Create') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
