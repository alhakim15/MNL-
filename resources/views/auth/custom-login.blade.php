<x-filament::layouts.app>
    <div class="flex items-center justify-center min-h-screen">
        <form method="POST" action="{{ route('login') }}" class="space-y-4 w-full max-w-sm bg-white p-6 rounded shadow">
            @csrf

            <x-filament::input.wrapper>
                <x-filament::input.label for="email" value="Email" />
                <x-filament::input.text name="email" type="email" required autofocus />
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input.label for="password" value="Password" />
                <x-filament::input.text name="password" type="password" required />
            </x-filament::input.wrapper>

            @if($errors->any())
            <div class="text-red-500 text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <x-filament::button type="submit" class="w-full">
                Login
            </x-filament::button>
        </form>
    </div>
</x-filament::layouts.app>