<div class="relative mt-1" x-data="{ showScan: false }">
    <div class="mb-3 px-2">
        <div class="mb-2 w-full relative text-gray-600 focus-within:text-gray-400">
            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                <a href="#" class="p-1 focus:outline-none focus:shadow-outline" @click="showScan = true">
                    <i class="fas fa-camera"></i>
                </a>
            </span>
            <x-input wire:keydown.escape="resetQuery" wire:model.debounce.500ms="query" type="search" class="pl-10"
                minlength="4" placeholder="{{ __('Search for products with code, reference or name') }}" autofocus />
            <div class="absolute right-0 top-0 mt-2 mr-4 text-purple-lighter">
                <button wire:click="resetQuery" type="button">X</button>
            </div>
        </div>
        <div class="flex flex-wrap -mx-2 mb-3">
            <div class="lg:w-1/3 md:w-1/3 sm:w-1/2 px-2 flex items-center">
                <div class="flex items-center space-x-2">
                    <span>{{ __('All') }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="featured"  class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    </label>

                    <span>{{ __('Favorite') }}</span>
                </div>
            </div>
            <div class="lg:w-1/3 md:w-1/3 sm:w-1/2 px-2">
                <x-label for="category" :value="__('Category')" />
                <x-select-list :options="$this->categories" wire:model="category_id" name="category_id" id="category_id" />
            </div>

            <div class="lg:w-1/3 md:w-1/3 sm:w-1/2 px-2">
                <x-label for="showCount" :value="__('Product per page')" />
                <select wire:model="showCount"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                    <option value="9">9</option>
                    <option value="15">15</option>
                    <option value="21">21</option>
                    <option value="30">30</option>
                    <option value="">{{ __('All') }}</option>
                </select>
            </div>

        </div>
    </div>
    
    <div class="w-full px-2 mb-4 bg-transparent">
        <div class="flex flex-wrap w-full">
            <div
                class="w-full grid gap-3 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 px-2 mt-5 overflow-y-auto">
                @forelse($products as $product)
                    <div 
                        wire:click.prevent="selectProduct({{ $product }})"
                        class="px-3 py-3 flex flex-col border border-red-400 rounded-md h-32 justify-between cursor-pointer">
                      <div>
                        @php
                            $warehouse = $product->warehouses->where('id', $warehouse_id)->first();
                            $qty = $warehouse ? $warehouse->pivot->qty : 0;
                        @endphp
                        <div class="font-bold text-sm text-gray-800">{{ $product->name }} - {{ '['.$qty.' '.$product->unit.']' }}</div>
                        <span class="font-light text-xs text-gray-600">{{ $product->code }}</span>
                      </div>
                      <div class="flex flex-row justify-between items-center">
                        @php
                            $price = $warehouse ? $warehouse->pivot->price : 0;
                        @endphp
                        <span class="self-end font-bold text-sm text-blue-500">${{ $price }}</span>
                        @if($product->image)
                            <img src="{{ asset('/storage/' . $product->image) }}" class=" h-14 w-14 object-cover rounded-md" alt="">
                        @endif
                      </div>
                    </div>
                @empty
                    <div class="col-span-full w-full px-2 py-3 mb-4 border rounded">
                        <span class="inline-block align-middle mr-8">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11a1 1 0 11-2 0 1 1 0 012 0zm-1-3a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="inline-block align-middle mr-8">
                            {{ __('No product found') }}
                        </span>
                    </div>
                @endforelse
            </div>
            <div class="my-3 mx-auto">
                @if ($products->count() >= $showCount)
                    <x-button wire:click.prevent="loadMore" primary type="button">
                        {{ __('Load More') }} <i class="bi bi-arrow-down-circle"></i>
                    </x-button>
                @endif
            </div>
        </div>
    </div>

    <div x-show="showScan" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="fixed top-0 left-0 h-screen w-screen flex items-center justify-center z-50"
        @click.away="showScan = false; Quagga.stop();">
        <div class="bg-white rounded-lg p-4 overflow-hidden shadow-xl transform transition-all">
            <div class="px-4 py-3">
                <h2 class="text-lg leading-6 font-medium text-gray-900">{{ __('Scan barcode') }}</h2>
                <div class="mt-2">
                    <x-button type="button" primary @click="initQuaggaJS()">{{ __('Start Scanning') }}
                    </x-button>
                </div>
                <div class="px-4 py-3 text-right">
                    <x-button @click="showScan = false; Quagga.stop();" danger>
                        {{ __('Close') }}
                    </x-button>
                </div>
                <div style="height:200px">
                    <div class="hidden" id="scanner-container"></div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"
        integrity="sha512-bCsBoYoW6zE0aja5xcIyoCDPfT27+cGr7AOCqelttLVRGay6EKGQbR6wm6SUcUGOMGXJpj+jrIpMS6i80+kZPw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        function initQuaggaJS() {
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#scanner-container')
                },
                decoder: {
                    readers: ["code_128_reader"]
                }
            }, function(err) {
                if (err) {
                    console.log(err);
                    return
                }
                console.log("Initialization finished. Ready to start");
                Quagga.start();
            });
            document.querySelector("#scanner-container").classList.remove("hidden");
        }

        Quagga.onDetected(function(result) {
            document.querySelector("#productSearch").value = result.codeResult.code;
            document.querySelector("#scanner-container").classList.add("hidden");
            Quagga.stop();
            showScan = false;
        });
    </script>
@endpush
