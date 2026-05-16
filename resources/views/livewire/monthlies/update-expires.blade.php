<div>
    <!-- Edit Modal -->
    <x-modal wire:model="editExpires" maxWidth="sm">
        <x-slot name="title">
            Editar expiración - {{ $monthlie ? optional($monthlie->customer)->name : '' }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <div class="w-full text-center border-dashed border-4 border-indigo-600 py-9">

                  <div class="px-4 sm:px-0  border-solid border-4 border-indigo-600 mx-6 mb-4">
                    <h3 class="text-base font-semibold leading-7 text-gray-900 pt-2">Actual Horario</h3>
                    <h3 class="text-base font-semibold leading-7 text-blue-500 pt-2">{{ $monthlie ? $monthlie->name : '' }}</h3>
                    <p class="mt-1 text-sm  text-red-500 pb-2">{{ $monthlie ? optional($monthlie->customer)->name : '' }}</p>
                  </div>
                @if($monthlie->with_days)
                    {!! implode('<br> ', $monthlie->with_days) !!}
                @endif
                <p class="text-xl">
                    Actualmente expira:
                </p>
                <p class="text-xl">
                    {{ $monthlie->expires_at_format }}
                </p>
            </div>

            <x-validation-errors class="mb-4" :errors="$errors" />

            @if($selectedDays || $quantitySelectDays)
                <button class=" w-full bg-red-100 hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 mt-3 border border-gray-400 rounded shadow ml-1" 
                    wire:click.prevent="resetSchedule"
                    >
                    <p>
                        Nueva fecha vence:
                    </p>
                    <p>
                        {{ $monthlie->expires_at->addDays($hour)->format('d-m-Y') }}
                    </p>
                </button>
            @else
                <div class="py-7">
                </div>
            @endif

            <form wire:submit.prevent="selectService">

            {{-- @json($selectedDays)<br> --}}
            {{-- @json($quantitySelectDays) --}}
                <div class="flex items-center justify-center bg-[url('images/gradient.png')] rounded-md mb-4 py-4 mt-4">
                    <div class="flex items-center max-w-md mx-auto bg-white rounded-lg " >
                        <div class="w-full">

                          <div class="flex">

                            <select name="hour" wire:model="hour" class="bg-transparent text-xl appearance-none outline-none">
                                <option value="" class="text-center">Dias a sumar</option>
                                <option class="text-blue-600"
                                    value="30">30
                                </option>
                                <option class="text-blue-600"
                                    value="60">60
                                </option>
                                <option class="text-blue-600"
                                    value="90">90
                                </option>
                                <option class="text-blue-600"
                                    value="120">120
                                </option>
                                <option class="text-blue-600"
                                    value="150">150
                                </option>
                                <option class="text-blue-600"
                                    value="180">180
                                </option>
                                <option class="text-blue-600"
                                    value="210">210
                                </option>
                                <option class="text-blue-600"
                                    value="240">240
                                </option>
                                <option class="text-blue-600"
                                    value="270">270
                                </option>
                                <option class="text-blue-600"
                                    value="300">300
                                </option>
                                <option class="text-blue-600"
                                    value="330">330
                                </option>
                                <option class="text-blue-600"
                                    value="365">365
                                </option>
                            </select>
                          </div>
                        </div>
                    </div>
                </div>

                <div class="w-full px-3 py-2">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>

            </form>
        </x-slot>
    </x-modal>
    <!-- End Edit Modal -->
</div>
