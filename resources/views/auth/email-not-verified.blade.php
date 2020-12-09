<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo/>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Your email address could not be verified. Please check your link and try again.') }}
        </div>

    </x-jet-authentication-card>
</x-guest-layout>
