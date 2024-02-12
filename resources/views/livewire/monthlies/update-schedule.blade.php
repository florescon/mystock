<div>
    <!-- Edit Modal -->
    <x-modal wire:model="editModal">
        <x-slot name="title">
            {{ __('Edit Schedule') }} - {{ $monthlie ? optional($monthlie->customer)->name : '' }}
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
            </div>

            <x-validation-errors class="mb-4" :errors="$errors" />

            @if($selectedDays || $quantitySelectDays)
                <button class=" w-full bg-red-100 hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 mt-3 border border-gray-400 rounded shadow ml-1" wire:click.prevent="resetSchedule">
                    Limpiar
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
                                <option value="" class="text-center">{{ __('Select Hour') }}</option>
                                @foreach ($hours as $hour)
                                    <option class="{{ $hour->is_am ? 'text-blue-600' : 'text-red-400' }}"
                                        value="{{ $hour->full_label }}">
                                            {{ $hour->hour.' '.$hour->label_is_am }}
                                    </option>
                                @endforeach
                            </select>
                            @if($hour !== null)
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow ml-1" wire:click.prevent="setHour">
                                  <i class="fa-solid fa-hand-point-left"></i>
                                </button>
                            @endif
                          </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-center rounded-md mb-4 py-4">
                    <div class="flex items-center max-w-md mx-auto bg-white rounded-lg " >
                        <div class="w-full">
                          <div class="flex">
                            <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg text-center dark:bg-gray-700 dark:border-gray-600 dark:text-white pt-3">
                                @foreach ($this->days as $day)

                                <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                                   <li class="pb-2 sm:pb-3">
                                      <div class="flex items-center rtl:space-x-reverse">
                                         <div class="flex-1 min-w-0">
                                            <div class="flex items-center ps-3">
                                                <input id="vue-checkbox-{{ $day->value }}" type="checkbox" value="{{ $day->name }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" wire:model="selectedDays.{{ $loop->index + 1 }}">
                                                <label for="vue-checkbox-{{ $day->value }}" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __($day->name) }}</label>
                                            </div>
                                         </div>
                                         <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            <div class="flex items-center ">
                                                <select name="quantitySelectDays" wire:model="quantitySelectDays.{{ $loop->index +1 }}" class="rounded-lg mx-4 text-xl ">
                                                    <option value="" class="text-center">{{ __('Hour') }}</option>
                                                    @foreach ($hours as $hour)
                                                        <option class="{{ $hour->is_am ? 'text-blue-600' : 'text-red-400' }}"
                                                            value="{{ $hour->full_label }}">
                                                                ~ {{ $hour->hour.' '.$hour->label_is_am }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                         </div>
                                      </div>
                                   </li>
                                </ul>

                                @endforeach    
                            </ul>
                          </div>

                            <div class="mt-12 mb-9 text-center">
                                <label class="border-dashed border-4 border-red-300 py-2 px-9">
                                  <input type="checkbox" class="accent-pink-500" wire:model="mix"> &nbsp; Mixto
                                </label>
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
