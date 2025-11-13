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

class CreateItem extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Section::make("Buat Item baru!!")->description('Update data item sesukamu')->columns(2)->schema([
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
            ->model(Item::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Item::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()->title('Item telah dibuat!')->body("Item {$record->name} telah berhasil dibuat!")->success()->send();
    }



    public function render(): View
    {
        return view('livewire.items.create-item');
    }
}
