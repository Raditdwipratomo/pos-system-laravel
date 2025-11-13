<?php

namespace App\Livewire\Management;

use App\Models\User;
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

class CreateUser extends Component implements HasActions, HasSchemas
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
                Section::make("Buat User baru")->description('Buat data user sesukamu')->columns(2)->schema([
                    TextInput::make('name')->label('Nama'),
                    TextInput::make('email')->label('Email')->unique(),
                    TextInput::make('password')->label('Password'),
                    ToggleButtons::make('role')
                        ->label('Role')
                        ->options([
                            'cashier' => 'Kasir',
                            'admin' => 'Admin'
                        ])->grouped()
                ])
            ])
            ->statePath('data')
            ->model(User::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = User::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()->title('User baru telah dibuat!')->body("User {$record->name} telah dibuat!")->success()->send();
    }

    public function render(): View
    {
        return view('livewire.management.create-user');
    }
}
