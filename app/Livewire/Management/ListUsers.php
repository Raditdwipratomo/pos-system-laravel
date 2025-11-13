<?php

namespace App\Livewire\Management;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;


class ListUsers extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => User::query())
            ->columns([
                //
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('email')->label('Email')->searchable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
                Action::make('create')->url(fn() => route('user.create'))->openUrlInNewTab()->label('+ Tambah User')
            ])
            ->recordActions([
                //
                Action::make('delete')->requiresConfirmation()->modalHeading('Hapus User')->modalDescription('Apakah anda yakin ingin menghapus data user ini?')->modalSubmitActionLabel('Ya, Hapus')->modalCancelActionLabel('Batal')->color('danger')->action(fn(User $record) => $record->delete())->successNotification(Notification::make()->make('User berhasil dihapus')->success())->modalIcon('heroicon-o-trash'),
                Action::make('edit')->url(fn(User $record): string => route('user.update', $record))->openUrlInNewTab()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.management.list-users');
    }
}
