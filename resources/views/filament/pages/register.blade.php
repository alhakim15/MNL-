<x-filament::page>
    <form wire:submit.prevent="register" class="space-y-6">
        {{ $this->form }}
        <x-filament::button type="submit">
            Register
        </x-filament::button>
    </form>
</x-filament::page>