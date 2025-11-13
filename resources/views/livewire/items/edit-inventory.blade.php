<div>
    <form wire:submit="save">
        {{ $this->form }}

        <x-filament::button class="ml-2 mt-3" type="submit">
            Edit
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>
