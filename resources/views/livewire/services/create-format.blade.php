<div>
    <div class="h-16 grid grid-rows-1 grid-flow-col gap-2">
        @for ($i = 1; $i <= $totalSteps; $i++)
            <x-wizard-step :active="$i == $step">Paso {{$i }}</x-wizard-step>
        @endfor
    </div>

    <div class="h-96 mt-2 mb-12 border-b-2 border-gray-300">
        <div class="min-h-full">
            @include('livewire.services.format.step' . $step)
        </div>
    </div>

    <em class="text-red-400">Seleccionados:</em> @json($selected)

    <div class="flex justify-between mt-4">

        @if($step != 1)
            <x-wizard-button class="ml-4" wire:click="moveBack">Anterior</x-wizard-button>
        @else
            &nbsp;
        @endif

        <x-wizard-button class="mr-4" wire:click="moveAhead">{{$step != $totalSteps ? 'Siguiente' : '' }}</x-wizard-button>
    </div>
</div>