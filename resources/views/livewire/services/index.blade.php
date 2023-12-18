<div>
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
            <div class="bg-white rounded-lg overflow-hidden shadow-lg ring-4 ring-indigo-500 ring-opacity-40 max-w-sm">
                <div class="relative">
                    {{-- @php
                        $banner = $service->service_type->getBannerType();
                    @endphp

                    @if(!$service->image)
                        <img class="w-full" src='{{ asset('images/'.$banner) }}' alt="{{ $service->name }}">
                    @else
                        <img class="w-full" src='{{ asset('/storage/'.$service->image) }}' alt="{{ $service->name }}">
                    @endif --}}

                    <div class="absolute top-0 right-0 text-white px-2 py-1 m-2 rounded-md text-sm font-medium">
                        @php
                            $type = $service->service_type->getBadgeType();
                        @endphp
                        <x-badge :type="$type">{{ __($service->service_type->getName()) }}</x-badge>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-medium mt-9">{{ $service->name }}</h3>
                    <p class="text-gray-600 text-sm mt-2 mb-12">{{ $service->note ? Str::limit($service->note, 28, '...') : '--' }}</p>
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-lg">${{ $service->price }}</span>
                    @can('service_update')
                    <li class="flex flex-col items-center justify-around">
                        <x-button primary wire:click="$emit('editModal', {{ $service->id }})" type="button"
                            wire:loading.attr="disabled">
                            <i class="fas fa-edit"></i>
                        </x-button>
                    </li>
                    @endcan
                    @can('service_delete')

                    @if($service->id > 3)
                    <li class="flex flex-col items-center justify-around">
                        <x-button danger wire:click="$emit('deleteModal', {{ $service->id }})" type="button"
                            wire:loading.attr="disabled">
                            <i class="fas fa-trash"></i>
                        </x-button>
                    </li>
                    @endif
                    @endcan
                    </div>
                </div>
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

    <!-- Edit Modal -->
    @livewire('services.edit', ['service' => $service])
    <!-- End Edit modal -->

    <!-- Create modal -->
    <livewire:services.create />
    <!-- End Create modal -->

    <!-- Import modal -->
    <x-modal wire:model="importModal">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                {{ __('Import Excel') }}
                <x-button primary wire:click="downloadSample" type="button">
                    {{ __('Download Sample') }}
                </x-button>
            </div>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="import">
                <div class="mb-4">
                    <div class="my-4">
                        <x-label for="file" :value="__('Import')" />
                        <x-input id="file" class="block mt-1 w-full" type="file" name="file"
                            wire:model.defer="file" />
                        <x-input-error :messages="$errors->get('file')" for="file" class="mt-2" />
                    </div>

                    <div class="w-full flex justify-start">
                        <x-button primary wire:click="import" type="button" wire:loading.attr="disabled">
                            {{ __('Import') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Import modal -->

    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function() {
                window.livewire.on('deleteModal', serviceId => {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '¡Si, eliminar!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.livewire.emit('delete', serviceId)
                        }
                    })
                })
            })
        </script>
    @endpush


</div>
