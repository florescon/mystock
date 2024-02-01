<div>
   {{--  <x-wizard-heading>Select Customer</x-wizard-heading>

    <div>
        <x-label for="customer_id" value="Customer" class="mt-4" />

        <select class="block mt-1 w-1/4" wire:model.defer="customer_id">
            <option value="">--Select Customer--</option>
            @foreach ($customers as $key => $value)
                <option value="{{$key}}">{{$value}}</option>
            @endforeach
        </select>

        <x-wizard-validation-error for="customer_id" />
    </div> --}}

    <div class="flex flex-wrap justify-center">

        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2">
            <div class="my-2">
                <x-input wire:model.debounce.500ms="search" placeholder="{{ __('Search') }}" autofocus />
            </div>
        </div>

    </div>

      <div class="container mt-9 mx-auto">
            <table class="table">
                <thead>
                    <th>
                    </th>
                    <th>
                        {{ _('ID') }}
                    </th>
                    <th>
                        {{ __('Name') }}
                    </th>
                    <th>
                        {{ __('Description') }}
                    </th>
                    <th>
                        {{ __('Price') }}
                    </th>
                </thead>

                <tbody>
                    @forelse($services as $service)
                        <tr  wire:key="row-{{ $service->id }}">
                            <td class="pr-0">
                                <input wire:model="selected" type="checkbox" value="{{ $service->id }}" />
                            </td>
                            <td>
                                {{ $service->id }}
                            </td>
                            <td>
                                {{ $service->name }}
                            </td>
                            <td>
                                {{ $service->note }}
                            </td>
                            <td>
                                {{ $service->price }}
                            </td>
                        </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>

      </div>

</div>