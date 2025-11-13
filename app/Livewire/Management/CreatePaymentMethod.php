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

class CreatePaymentMethod extends Component implements HasActions, HasSchemas
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
                Section::make("Buat Metode Pembayaran baru!!")->description('Buatlah data Metode pembayaran sesukamu')->columns(2)->schema([
                    TextInput::make('name')->label('Metode Pembayaran'),
                    TextInput::make('description')->label('Deskripsi'),
                ])
            ])
            ->statePath('data')
            ->model(PaymentMethod::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = PaymentMethod::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()->title('Metode Pembayaran telah dibuat!')->body("Metode Pembayaran {$record->name} telah dibuat!")->success()->send();
    }

    public function render(): View
    {
        return view('livewire.management.create-payment-method');
    }
}
