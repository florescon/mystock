<div>

    <div class="grid grid-cols-2 gap-2">
      <div>

        <div class="px-2 md:px-5 py-2 md:py-4">
            <div class="flex items-center justify-between">
                <p tabindex="0" class="focus:outline-none text-base sm:text-lg md:text-xl lg:text-2xl font-bold leading-normal text-gray-800">@lang('Sales')</p>

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
            @foreach($sales as $sale)
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
            {{ $sales->links() }}
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
                  <td>{{ format_currency($expense->amount) }}</td>
                </tr>
            @endforeach
          </tbody>
        </table>    

        <div class="px-6 py-3">
            {{ $expenses->links() }}
        </div>

      </div>
    </div>

</div>
