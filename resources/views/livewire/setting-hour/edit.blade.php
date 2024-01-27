<div>
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Hour') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />
            <form wire:submit.prevent="update">
                <div class="flex flex-col">
                    <div class="flex flex-col">
                        <x-label for="hour.hour" :value="__('Hour')" />
                        <x-input id="hour" class="block mt-1 w-full" required type="text"
                            wire:model.lazy="hour.hour" />
                    </div>
                    <div class="flex flex-col">
                        <x-label for="hour.is_am" :value="__('Is an')" />
                        <x-input id="is_am" class="block mt-1 w-full" type="text"
                            wire:model.lazy="hour.is_am" />
                    </div>
                </div>

                <div class="w-full my-5 px-3">
                    <x-button primary type="submit" class="w-full text-center" wire:click="update"
                        wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
