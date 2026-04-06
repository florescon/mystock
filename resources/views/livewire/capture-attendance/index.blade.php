<div class="flex flex-col items-center min-h-screen px-4 pt-4 bg-white">
    <div class="relative flex flex-col items-center rounded-[10px] border-[1px] border-gray-200 w-full max-w-[576px] mx-auto p-4 bg-white bg-clip-border shadow-md shadow-[#F3F3F3] dark:border-[#ffffff33] dark:!bg-navy-800 dark:text-white dark:shadow-none mb-6">
        <div class="flex items-center justify-between rounded-t-3xl p-3 w-full">
            <div class="text-lg font-bold text-navy-700 dark:text-white">
                Horarios <em class="">{{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd DD') }}</em>
            </div>
            <button class="linear rounded-[20px] bg-lightPrimary px-4 py-2 text-base font-medium text-brand-500 transition duration-200 hover:bg-gray-100 active:bg-gray-200 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 dark:active:bg-white/20" disabled>
                {{-- Historial hoy --}}
            </button>
        </div>
        @foreach($hours as $hour)
            <a href="{{ route('capture-attendance-hour.index', $hour->id) }}" class="w-full">
                <div class="flex h-full w-full items-start justify-between rounded-md border-[1px] border-[transparent] dark:hover:border-white/20 bg-white px-3 py-[20px] transition-all duration-150 hover:border-gray-200 dark:!bg-navy-800 dark:hover:!bg-navy-700">
                    <div class="flex items-center gap-3">
                        <div class="flex h-16 w-16 items-center justify-center">
                        @if($hour->is_am)
                        <img
                            class="h-full w-full rounded-xl"
                            src="https://horizon-tailwind-react-corporate-7s21b54hb-horizon-ui.vercel.app/static/media/Nft1.0fea34cca5aed6cad72b.png"
                            alt=""
                        />
                        @else
                        <img
                            class="h-full w-full rounded-xl"
                            src="https://horizon-tailwind-react-corporate-7s21b54hb-horizon-ui.vercel.app/static/media/Nft5.62dbaf7dd91b4180035c.png"
                            alt=""
                        />
                        @endif
                        </div>
                        <div class="flex flex-col">
                        <h5 class="text-base font-bold text-navy-700 dark:text-white">
                            {{ $hour->hour }}
                        </h5>
                        <p class="mt-1 text-sm font-normal text-gray-600">
                            {{ $hour->is_am ? 'AM' : 'PM' }}
                        </p>
                        </div>
                    </div> 
                    <div class="mt-1 flex items-center justify-center text-navy-700 dark:text-white">
                        <div>
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                          <path d="M96 224a96 96 0 1 0 0-192 96 96 0 1 0 0 192zm448 0a96 96 0 1 0 0-192 96 96 0 1 0 0 192zM320 256a128 128 0 1 0 0-256 128 128 0 1 0 0 256zM32 384c0-70.7 57.3-128 128-128 22.6 0 43.8 5.9 62.2 16.2C165.2 301.4 128 346.3 128 400v16c0 8.8 7.2 16 16 16H48c-26.5 0-48-21.5-48-48zm480 0c0-53.7-37.2-98.6-94.2-111.8C436.2 261.9 457.4 256 480 256c70.7 0 128 57.3 128 128 0 26.5-21.5 48-48 48h-96c8.8 0 16-7.2 16-16v-16zm-256 0c0-70.7 57.3-128 128-128s128 57.3 128 128v16c0 8.8-7.2 16-16 16H144c-8.8 0-16-7.2-16-16v-16c0-70.7 57.3-128 128-128z"></path>
                        </svg>
                        </div>
                        <div class="ml-1 flex items-center text-sm font-bold text-navy-700 dark:text-white">
                        <p>   </p>
                        {{-- <p class="ml-1">44</p> --}}
                        </div>
                        <div class="ml-2 flex items-center text-sm font-normal text-gray-600 dark:text-white">
                        <p>alumnos</p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>  
</div>
