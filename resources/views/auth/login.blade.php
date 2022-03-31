<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            {{-- <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a> --}}
        </x-slot>

        <div
            class="py-10 bg-gray-100 rounded-lg mb-10 bg-opacity-50 border-solid border-opacity-5 border-2 border-gray-300 shadow-md">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" class="mx-auto h-20 w-auto" />
                {{-- <img src="{{ asset('assets/img/AlturushDeliveryTextColored.png') }}" alt="ALTURUSH DELIVERY"
                    class="mx-auto h-20 w-auto"> --}}
            </a>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Username -->
            <div>
                {{--
                <x-label for="username" :value="__('Username')" />

                <x-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')"
                    autocomplete="off" required autofocus /> --}}
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                    <x-input type="text" name="username" id="username"
                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-11 pr-12 border-gray-300 rounded-md"
                        autocomplete="off" placeholder="Username" required autofocus />
                </div>
            </div>

            <!-- Password -->
            <div class="mt-4">
                {{--
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" /> --}}
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z" />
                            </svg>
                        </span>
                    </div>
                    <x-input type="password" name="password" id="password"
                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-11 pr-12 border-gray-300 rounded-md"
                        autocomplete="off" placeholder="Password" required />
                </div>
            </div>

            <!-- Remember Me -->
            {{-- <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div> --}}

            <div class="flex items-center justify-end mt-4">


                <x-button class="bg-red-600 shadow">
                    {{ __('Login') }}
                </x-button>

                {{-- @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                @endif --}}
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>