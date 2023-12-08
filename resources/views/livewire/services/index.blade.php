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
            <div
                class="max-w-2xl mx-4 sm:max-w-sm md:max-w-sm lg:max-w-sm xl:max-w-sm sm:mx-auto md:mx-auto lg:mx-auto xl:mx-auto bg-white shadow-xl rounded-lg text-gray-900">
                <div class="rounded-t-lg h-32 overflow-hidden">
                    @php
                        $banner = $service->service_type->getBannerType();
                    @endphp

                    @if(!$service->image)
                        <img class="object-cover object-top w-full" src='{{ asset('images/'.$banner) }}' alt='  '>
                    @else
                        <img class="object-cover object-top w-full" src='{{ asset('/storage/'.$service->image) }}' alt='  '>
                    @endif
                </div>

                <div class="text-center mt-2">
                    <h1 class="font-semibold">{{ $service->name }}</h1>
                    <p class="text-gray-500">${{ $service->price }}</p>
                    <p class="text-gray-500">{{ $service->note ? Str::limit($service->note, 24, '...') : '--' }}</p>
                </div>
                <ul class="py-4 mt-2 text-gray-700 flex items-center justify-around">
                    @php
                        $type = $service->service_type->getBadgeType();
                    @endphp
                    <x-badge :type="$type">{{ __($service->service_type->getName()) }}</x-badge>
                </ul>

                <ul class="py-4 mt-2 text-gray-700 flex items-center justify-around">
                    @can('service_update')
                    <li class="flex flex-col items-center justify-around">
                        <x-button primary wire:click="$emit('editModal', {{ $service->id }})" type="button"
                            wire:loading.attr="disabled">
                            <i class="fas fa-edit"></i>
                        </x-button>
                    </li>
                    @endcan
                    @can('service_delete')
                    <li class="flex flex-col items-center justify-around">
                        <x-button danger wire:click="$emit('deleteModal', {{ $service->id }})" type="button"
                            wire:loading.attr="disabled">
                            <i class="fas fa-trash"></i>
                        </x-button>
                    </li>
                    @endcan
                </ul>
                <div class="p-4 border-t mx-4 mt-2">
                    <button class="w-full block mx-auto rounded-full bg-green-300 hover:shadow-lg font-semibold text-white px-6 py-2">Activo</button>
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
