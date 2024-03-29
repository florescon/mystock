<div>
    <x-modal wire:model="createAssociate" maxWidth="5xl">
        <x-slot name="title">
            {{ __('Services') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-wrap justify-center bg-[url('images/tinypng.png')] rounded-md">
                <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
                    <select wire:model="perPage"
                        class="w-20 border border-gray-300 rounded-md shadow-sm py-2 px-4 bg-white text-sm leading-5 font-medium text-gray-700 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out">
                        @foreach ($paginationOptions as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @can('service_delete')
                        @if ($this->selected)
                            <x-button danger type="button" wire:click="deleteSelected" class="ml-3">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        @endif
                    @endcan
                    @if ($this->selectedCount)
                        <p class="text-sm leading-5 ml-3">
                            <span class="font-medium">
                                {{ $this->selectedCount }}
                            </span>
                            {{ __('Entries selected') }}
                        </p>
                    @endif
                </div>
                <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2 pr-6">
                    <div class="my-2">
                        <x-input wire:model.debounce.500ms="search" placeholder="{{ __('Search') }}" autofocus />
                    </div>
                </div>
            </div>

            <section class="text-gray-700 body-font ">
              <div class="container px-5 py-16 mx-auto">
                <div class="flex flex-wrap -m-4 text-center">
                @forelse($services as $service)
                  <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
                    <div
                        class="max-w-2xl mx-4 sm:max-w-sm md:max-w-sm lg:max-w-sm xl:max-w-sm sm:mx-auto md:mx-auto lg:mx-auto xl:mx-auto bg-white shadow-xl rounded-lg text-gray-900 border border-purple-600 cursor-pointer"
                        wire:click="showCustomerAssociate({{ $service->id }})"
                        >
                    {{-- <div
                        class="max-w-2xl mx-4 sm:max-w-sm md:max-w-sm lg:max-w-sm xl:max-w-sm sm:mx-auto md:mx-auto lg:mx-auto xl:mx-auto bg-white shadow-xl rounded-lg text-gray-900 border border-purple-600 cursor-pointer"
                        wire:click.prevent="selectService({{ $service }})"
                        > --}}

                        <div class="text-center mt-2">
                            <h1 class="font-semibold">{{ $service->name }}</h1>
                            <p class="text-gray-500">${{ $service->price }}</p>
                            <p class="text-gray-500">{{ $service->note ? Str::limit($service->note, 22, '...') : '--' }}</p>
                        </div>
                        <ul class="py-4 mt-2 text-gray-700 flex items-center justify-around">
                            @php
                                $type = $service->service_type->getBadgeType();
                            @endphp
                            <x-badge :type="$type">{{ __($service->service_type->getName()) }}</x-badge>
                        </ul>

                    </div>
                  </div>
                @empty
                    {{ __('No entries found.') }}
                @endforelse
                </div>
              </div>
            </section>

            <div class="p-4">
                <div class="pt-3">
                    {{ $services->links() }}
                </div>
            </div>

            <x-modal wire:model="showCustomerAssociate" maxWidth="4xl">
                <x-slot name="title">
                    <div class="flex items-center justify-between rounded-t-3xl p-3 w-full">
                        <div class="text-lg font-bold text-navy-700 dark:text-white">
                            {{ __('Select Customer') }} <i class="fa-solid fa-right-long"></i> <strong>{{ $serviceAssociate?->name }}</strong>
                        </div>
                        <button class="linear rounded-[20px] bg-lightPrimary px-4 py-2 text-base font-medium text-brand-500 transition duration-200 hover:bg-red-200 border border-red-300 active:bg-gray-200 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 dark:active:bg-white/20" wire:click.prevent="cancel">
                            @lang('Cancel')
                        </button>
                    </div>

                     {{-- <button class="inline-block left-0 rounded-md bg-red-500 px-10 py-2 font-semibold text-red-100 shadow-md duration-75 hover:bg-red-400" wire:click.prevent="cancel">@lang('Cancel')</button> --}}

                </x-slot>

                <x-slot name="content">
                    <div class="px-1 mx-auto">
                        <div class="flex flex-row">
                            <div class="w-full">
                                <div class="p-4">

                                  <div class="flex h-full flex-col items-center justify-center space-y-6 bg-white px-2 sm:flex-row sm:space-x-3 py-4 sm:space-y-0">
                                    <div class="w-1/2 mr-4 max-w-sm overflow-hidden rounded-lg bg-white shadow-md duration-300 hover:scale-105 hover:shadow-xl">
                                        <div class="flex items-center justify-center text-4xl my-6">
                                            🤽
                                        </div>
                                      <h1 class="mt-2 text-center text-2xl font-bold text-gray-500">Mismo cliente </h1>
                                      <p class="my-4 text-center text-sm text-gray-500">Mismo comprador se le asigna el servicio</p>
                                      <div class="space-x-4 bg-gray-100 pb-4 text-center">

                                        @if($serviceAssociate?->with_input)
                                            <div class="md:w-full sm:w-full px-14 py-4">
                                                <x-input id="quantity" class="block mt-1 w-full text-center" type="text" name="quantity"
                                                    wire:model.lazy="quantity" />
                                                <x-input-error :messages="$errors->get('quantity')" for="quantity" class="mt-2" />
                                            </div>
                                        @endif

                                        @if($serviceAssociate?->with_days)

                                        {{-- @json($selectedDays)<br> --}}
                                        {{-- @json($quantitySelectDays) --}}
                                            <div class="flex items-center justify-center bg-[url('images/gradient.png')] rounded-md mb-4 py-4">
                                                <div class="flex items-center max-w-md mx-auto bg-white rounded-lg " >
                                                    <div class="w-full">
                                                      <div class="flex">
                                                        <select name="hourFirst" wire:model="hourFirst" class="bg-transparent text-xl appearance-none outline-none">
                                                            <option value="" class="text-center">{{ __('Select Hour') }}</option>
                                                            @foreach ($hours as $hour)
                                                                <option class="{{ $hour->is_am ? 'text-blue-600' : 'text-red-400' }}"
                                                                    value="{{ $hour->full_label }}">
                                                                        {{ $hour->hour.' '.$hour->label_is_am }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @if($hourFirst !== '')
                                                            <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow ml-1" wire:click.prevent="setHourFirst">
                                                              <i class="fa-solid fa-hand-point-left"></i>
                                                            </button>
                                                        @endif
                                                      </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="inline-flex items-center">
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

                                        <div class="mt-9 mb-9">
                                            <label class="border-dashed border-4 border-red-300 py-2 px-9">
                                              <input type="checkbox" class="accent-pink-500" wire:model="mix"> &nbsp; Mixto
                                            </label>
                                        </div>

                                        @endif

                                        <div class="mt-3 pt-3">
                                            <button wire:click.prevent="selectService({{ $serviceAssociate }})" class="inline-block rounded-md bg-green-500 px-6 py-2 font-semibold text-green-100 shadow-md duration-75 hover:bg-green-400">@lang('Select')</button>
                                        </div>

                                      </div>
                                    </div>

                                    <div class="w-1/2 ml-4 max-w-sm overflow-hidden rounded-lg bg-white shadow-md duration-300 hover:scale-105 hover:shadow-xl">
                                        <div class="flex items-center justify-center text-4xl my-6">
                                            🏊🏾‍♀️🏊
                                        </div>
                                        <div class="mx-4 text-center pb-4 ">

                                            <livewire:components.select-customer-second
                                                name="customerAssociate"
                                                placeholder="{{ __('Choose a Customer') }}"
                                                :value="request('customerAssociate')"
                                                :searchable="true"
                                            />

                                            {{-- <select required id="customerAssociate" name="customerAssociate" wire:model="customerAssociate"
                                                class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1 ">
                                                <option value="" class="text-center">{{ __('Select Customer') }}</option>
                                                @foreach ($this->customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select> --}}

                                            {{-- <x-input-error :messages="$errors->get('customerAssociate')" for="customerAssociate" class="mt-2" /> --}}

                                            @if($customerAssociate)
                                                @livewire('customers.alert-inscription-second', ['customer_id' => $customerAssociate])
                                            @else
                                                <p class="my-4 text-center text-sm text-gray-500">Servicio asignado a otro cliente</p>
                                            @endif

                                            @if($serviceAssociate?->with_days)

                                                <div class="flex items-center justify-center bg-[url('images/gradient.png')] rounded-md mb-4 py-4">
                                                    <div class="flex items-center max-w-md mx-auto bg-white rounded-lg " >
                                                        <div class="w-full">
                                                          <div class="flex">
                                                            <select name="hourSelected" wire:model="hourSelected" class="bg-transparent text-xl appearance-none outline-none">
                                                                <option value="" class="text-center">{{ __('Select Hour') }}</option>
                                                                @foreach ($hours as $hour)
                                                                    <option class="{{ $hour->is_am ? 'text-blue-600' : 'text-red-400' }}"
                                                                        value="{{ $hour->full_label }}">
                                                                            {{ $hour->hour.' '.$hour->label_is_am }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @if($hourSelected !== '')
                                                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow ml-1" wire:click.prevent="setHourSecond">
                                                                  <i class="fa-solid fa-hand-point-left"></i>
                                                                </button>
                                                            @endif
                                                          </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="inline-flex items-center">
                                                    <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg text-center dark:bg-gray-700 dark:border-gray-600 dark:text-white pt-3">
                                                        @foreach ($this->days as $day)

                                                            <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                                                               <li class="pb-2 sm:pb-3">
                                                                  <div class="flex items-center rtl:space-x-reverse">
                                                                     <div class="flex-1 min-w-0">
                                                                        <div class="flex items-center ps-3">
                                                                            <input id="vue-checkbox-second-{{ $day->value }}" type="checkbox" value="{{ $day->name }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" wire:model="selectedDaysSecond.{{ $loop->index + 1 }}">
                                                                            <label for="vue-checkbox-second-{{ $day->value }}" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __($day->name) }}</label>
                                                                        </div>
                                                                     </div>
                                                                     <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                                                        <div class="flex items-center ">
                                                                            <select name="quantitySelectDaysSecond" wire:model="quantitySelectDaysSecond.{{ $loop->index +1 }}" class="rounded-lg mx-4 text-xl ">
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

                                                <div class="mt-9 mb-9">
                                                    <label class="border-dashed border-4 border-red-300 py-2 px-9">
                                                      <input type="checkbox" class="accent-pink-500" wire:model="mixSecond"> &nbsp; Mixto
                                                    </label>
                                                </div>

                                            @endif
                                            @if($serviceAssociate?->with_input)
                                                <div class="md:w-full sm:w-full px-14 pb-4">
                                                    <x-input id="quantity_" class="block mt-1 w-full text-center" type="text" name="quantity_"
                                                        wire:model.lazy="quantity_" />
                                                    <x-input-error :messages="$errors->get('quantity_')" for="quantity_" class="mt-2" />
                                                </div>
                                            @endif
                                        </div>
                                      <div class="space-x-4 bg-gray-100 py-4 text-center">
                                       
                                        <button wire:click.prevent="selectServiceWithCustomer({{ $serviceAssociate }})" class="inline-block rounded-md bg-green-500 px-6 py-2 font-semibold text-green-100 shadow-md duration-75 hover:bg-green-400">@lang('Select')</button>
                                      </div>
                                    </div>
                                  </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-modal>



        </x-slot>
    </x-modal>
</div>
