<div>
    <div class="flex flex-row">
        <div class="lg:w-1/2 sm:w-full px-3">
            <form wire:submit.prevent="update">
                <div class="flex flex-wrap ">
                    <div class="w-1/2 sm:w-full px-2">
                        <x-label for="name" :value="__('Name')" required />
                        <input
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            type="text" name="name" wire:model.lazy="name" required>
                        @error('name')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-1/2 sm:w-full px-2">
                        <x-label for="email" :value="__('Email')" required />
                        <input
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            type="email" name="email" wire:model="email" required>
                        @error('email')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-1/2 sm:w-full px-2">
                        <x-label for="phone" :value="__('Phone')" required />
                        <input
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            type="text" name="phone" wire:model.lazy="phone" required>

                        @error('phone')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- <div class="w-full px-2">
                        <x-label for="Role" :value="__('Role')" required />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="role" id="role" wire:model="role" required>
                        </select>
                    </div> --}}

                    {{-- <div class="w-full px-2">
                        <x-label for="is_active" :value="__('Status')" required />
                        <select
                            class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                            name="is_active" id="is_active" wire:model="is_active" required>
                            <option value="1" {{ $user->is_active == 1 ? 'selected' : '' }}>
                                {{ __('Active') }}
                            </option>
                            <option value="2" {{ $user->is_active == 2 ? 'selected' : '' }}>
                                {{ __('Deactive') }}</option>
                        </select>
                    </div> --}}
                    <div class="w-full px-2">
                        <x-button type="submit" primary class="mt-4">
                            {{ __('Update') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </div>

        <div class="lg:w-1/2 sm:w-full px-3">
            <form wire:submit.prevent="updatePassword">
                <div class="mb-4">
                    <label for="current_password">{{ __('Current Password') }} <span
                            class="text-danger">*</span></label>
                    <x-input type="password" name="current_password" id="current_password" wire:model.lazy="current_password" required/>
                    @error('current_password')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password">{{ __('New Password') }} <span class="text-danger">*</span></label>
                    <x-input type="password" name="password" wire:model="password" required />
                    @error('password')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password_confirmation">{{ __('Confirm Password') }} <span
                            class="text-danger">*</span></label>
                    <x-input type="password" name="password_confirmation" wire:model="password_confirmation" required />
                    @error('password_confirmation')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <x-button type="submit" primary>
                        {{ __('Update Password') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-50" aria-hidden="true">
        <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>

        <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
          <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-50 sm:left-[calc(60%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div>
    </div>

</div>
