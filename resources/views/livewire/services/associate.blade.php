<div>
    <x-modal wire:model="createAssociate">
        <x-slot name="title">
            {{ __('Services') }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-wrap justify-center">
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
                <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2">
                    <div class="my-2">
                        <x-input wire:model.debounce.500ms="search" placeholder="{{ __('Search') }}" autofocus />
                    </div>
                </div>
            </div>

            <section class="text-gray-700 body-font">
              <div class="container px-5 py-16 mx-auto">
                <div class="flex flex-wrap -m-4 text-center">
                @forelse($services as $service)
                  <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
                    <div
                        class="max-w-2xl mx-4 sm:max-w-sm md:max-w-sm lg:max-w-sm xl:max-w-sm sm:mx-auto md:mx-auto lg:mx-auto xl:mx-auto bg-white shadow-xl rounded-lg text-gray-900 border border-purple-600 cursor-pointer"
                        wire:click.prevent="selectService({{ $service }})"
                        >

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

        </x-slot>
    </x-modal>
</div>
