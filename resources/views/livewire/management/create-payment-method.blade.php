<div>
    <form wire:submit="create">
        {{ $this->form }}

        <x-filament::button class="ml-2 mt-3" type="submit">
            Buat
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>
