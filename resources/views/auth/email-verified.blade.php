<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo/>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('You have successfully verified your email address. You can now go ahead and use the app') }}
        </div>

    </x-jet-authentication-card>
</x-guest-layout>
