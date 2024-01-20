<div>
    <div class="grid grid-cols-2 gap-2">
        <div>
            <div class="px-2 md:px-5 py-2 md:py-4">
                <div class="flex items-center justify-between">
                    <p tabindex="0" class="focus:outline-none text-base sm:text-lg md:text-xl lg:text-2xl font-bold leading-normal text-gray-800">@lang('Sales') - @lang('Cash')</p>

                </div>
            </div>
            <table class="table-fixed">
              <thead>
                <tr>
                  <th>@lang('Reference')</th>
                  <th>@lang('Customer')</th>
                  <th>@lang('Total')</th>
                </tr>
              </thead>
              <tbody>
                @foreach($salesCash as $sale)
                <tr>
                  <td>{{ $sale->reference }}</td>
                  <td>
                    @if ($sale?->customer)
                        <a class="text-blue-400 hover:text-blue-600 focus:text-blue-600"
                            href="{{ route('customer.details', $sale->customer->uuid) }}">
                            {{ $sale?->customer?->name }}
                        </a>
                    @else
                        {{ $sale?->customer?->name }}
                    @endif                  
                  </td>
                  <td>{{ format_currency($sale->total_amount) }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>    
            <div class="px-6 py-3">
                {{ $salesCash->onEachSide(1)->links() }}
            </div>

            <div class="px-2 md:px-5 py-2 md:py-4">
                <div class="flex items-center justify-between">
                    <p tabindex="0" class="focus:outline-none text-base sm:text-lg md:text-xl lg:text-2xl font-bold leading-normal text-gray-800">@lang('Sales') - @lang('Another Payment Methods')</p>

                </div>
            </div>
            <table class="table-fixed">
              <thead>
                <tr>
                  <th>@lang('Reference')</th>
                  <th>@lang('Customer')</th>
                  <th>@lang('Total')</th>
                </tr>
              </thead>
              <tbody>
                @foreach($salesOutCash as $saleOut)
                <tr>
                  <td>{{ $saleOut->reference }}</td>
                  <td>
                    @if ($saleOut?->customer)
                        <a class="text-blue-400 hover:text-blue-600 focus:text-blue-600"
                            href="{{ route('customer.details', $saleOut->customer->uuid) }}">
                            {{ $saleOut?->customer?->name }}
                        </a>
                    @else
                        {{ $saleOut?->customer?->name }}
                    @endif                  
                  </td>
                  <td>{{ format_currency($saleOut->total_amount) }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>    
            <div class="px-6 py-3">
                {{ $salesOutCash->onEachSide(1)->links() }}
            </div>


        </div>
        <div>
            <div class="px-2 md:px-5 py-2 md:py-4">
                <div class="flex items-center justify-between">
                    <p tabindex="0" class="focus:outline-none text-base sm:text-lg md:text-xl lg:text-2xl font-bold leading-normal text-gray-800">@lang('Expenses')</p>

                </div>
            </div>
            <table class="table-fixed">
              <thead>
                <tr>
                  <th>@lang('Reference')</th>
                  <th>@lang('Category')</th>
                  <th>@lang('Amount')</th>
                </tr>
              </thead>
              <tbody>
                @foreach($expenses as $expense)
                    <tr>
                      <td>{{ $expense->reference }}</td>
                      <td>
                        @if($expense->category_id)
                            <x-badge type="info">
                                <small>{{ $expense?->category?->name ?? '' }}</small>
                            </x-badge>
                        @endif                  
                      </td>
                      <td class="text-red-400">{{ format_currency($expense->amount) }}</td>
                    </tr>
                @endforeach
              </tbody>
            </table>    

            <div class="px-6 py-3">
                {{ $expenses->onEachSide(1)->links() }}
            </div>


            <div class="px-2 md:px-5 py-2 md:py-4">
                <div class="flex items-center justify-between">
                    <p tabindex="0" class="focus:outline-none text-base sm:text-lg md:text-xl lg:text-2xl font-bold leading-normal text-gray-800">@lang('Incomes')</p>

                </div>
            </div>
            <table class="table-fixed">
              <thead>
                <tr>
                  <th>@lang('Reference')</th>
                  <th>@lang('Category')</th>
                  <th>@lang('Amount')</th>
                </tr>
              </thead>
              <tbody>
                @foreach($incomes as $income)
                    <tr>
                      <td>{{ $income->reference }}</td>
                      <td>
                        @if($income->category_id)
                            <x-badge type="info">
                                <small>{{ $income?->category?->name ?? '' }}</small>
                            </x-badge>
                        @endif                  
                      </td>
                      <td class="text-green-400">{{ format_currency($income->amount) }}</td>
                    </tr>
                @endforeach
              </tbody>
            </table>    

            <div class="px-6 py-3">
                {{ $incomes->onEachSide(1)->links() }}
            </div>

        </div>
    </div>

    <div class="grid grid-cols-1">
        <section class="relative isolate overflow-hidden bg-white px-6 py-24 sm:py-32 lg:px-8">
          <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-16 text-center lg:grid-cols-2">
              <div class="mx-auto flex max-w-xs flex-col gap-y-6">
                <dt class="text-base leading-7 text-gray-600">@lang('Cash')</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">${{ format_currency($totalCash) }}</dd>
              </div>
              <div class="mx-auto flex max-w-xs flex-col gap-y-6">
                <dt class="text-base leading-7 text-gray-600">@lang('Another Payment Methods')</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">${{ format_currency($currentSaleOutPaymentCash) }}</dd>
              </div>
            </dl>
          </div>

          <div class="absolute inset-0 -z-10 bg-[radial-gradient(45rem_50rem_at_top,theme(colors.indigo.100),white)] opacity-20"></div>
          <div class="absolute inset-y-0 right-1/2 -z-10 mr-16 w-[200%] origin-bottom-left skew-x-[-30deg] bg-white shadow-xl shadow-indigo-600/10 ring-1 ring-indigo-50 sm:mr-28 lg:mr-0 xl:mr-16 xl:origin-center"></div>
          <div class="mx-auto max-w-2xl lg:max-w-4xl">
            <figure class="mt-10">
              <blockquote class="text-center text-xl font-semibold leading-8 text-gray-900 sm:text-2xl sm:leading-9">
                <p>Verifique el corte de caja antes de procesar.</p>
              </blockquote>

                <div class="mt-10">
                  <a type="button" wire:click="$emit('processCash', {{ 1 }})" wire:click="CheckOutCash" class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Procesar</a>
                </div>
            </figure>
          </div>
        </section>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function() {
                window.livewire.on('processCash', cashId => {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡Verificar movimientos!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '¡Si, procesar!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.livewire.emit('CheckOutCash', cashId)
                        }
                    })
                })
            })
        </script>
    @endpush

</div>
