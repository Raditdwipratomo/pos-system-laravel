<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditItem extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Item $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Section::make("Edit the Item")->description('Update data item sesukamu')->columns(2)->schema([
                    TextInput::make('name')->label('Nama Item'),
                    TextInput::make('sku')->unique(),
                    TextInput::make('price')->prefix('$')->numeric(),
                    ToggleButtons::make('status')
                        ->label('Apakah Item ini Aktif?')
                        ->options([
                            'active' => 'Aktif',
                            'inactive' => 'Tidak Aktif'
                        ])->grouped()
                ])


            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);

        Notification::make()->title('Item telah diupdate!')->body("Item {$this->record->name} telah diupdate!")->success()->send();
    }

    public function render(): View
    {
        return view('livewire.items.edit-item');
    }
}
