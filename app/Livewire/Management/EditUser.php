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


class EditUser extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public User $record;

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
                Section::make("Edit User")->description('Update data user sesukamu')->columns(2)->schema([
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
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);

        Notification::make()->title('User telah diupdate!')->body("User {$this->record->name} telah diupdate!")->success()->send();
    }

    public function render(): View
    {
        return view('livewire.management.edit-user');
    }
}
