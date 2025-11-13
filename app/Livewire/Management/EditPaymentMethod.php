<?php

namespace App\Livewire\Management;

use App\Models\PaymentMethod;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditPaymentMethod extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public PaymentMethod $record;

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
                    TextInput::make('name')->label('Metode Pembayaran'),
                    TextInput::make('description')->label('Deskripsi')->unique()
                ])
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);

        Notification::make()->title('Metode Pembayaran telah diupdate!')->body("Metode Pembayaran {$this->record->name} telah diupdate!")->success()->send();
    }

    public function render(): View
    {
        return view('livewire.management.edit-payment-method');
    }
}
