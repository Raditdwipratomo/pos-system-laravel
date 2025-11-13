<?php

namespace App\Livewire\Inventory;

use App\Models\Inventory;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateInventory extends Component implements HasActions, HasSchemas
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
                Section::make("Buat Inventory")->description('Buat data inventory sesukamu')->columns(2)->schema([
                    Select::make('item_id')->label('Nama Item')->relationship('item', 'name')->searchable()->preload()->native(false),
                    TextInput::make('quantity')->numeric()->label('Jumlah'),

                ])
            ])
            ->statePath('data')
            ->model(Inventory::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Inventory::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()->title('Inventory telah dibuat!')->success()->body("Inventory {$record->item->name} telah berhasil diupdate!!!")->send();
    }

    public function render(): View
    {
        return view('livewire.inventory.create-inventory');
    }
}
