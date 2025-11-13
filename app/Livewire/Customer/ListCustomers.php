<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
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

class ListCustomers extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => Customer::query())
            ->columns([
                //
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('phone')->label('Nomor Telepon')->searchable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
                Action::make('create')->url(fn() => route('customer.create'))->openUrlInNewTab()->label('+ Buat Customer')
            ])
            ->recordActions([
                //
                Action::make('delete')->requiresConfirmation()->modalHeading('Hapus Customer')->modalDescription('Apakah anda yakin ingin menghapus data Customer ini?')->modalSubmitActionLabel('Ya, Hapus')->modalCancelActionLabel('Batal')->color('danger')->action(fn(Customer $record) => $record->delete())->successNotification(Notification::make()->make('Customer berhasil dihapus')->success())->modalIcon('heroicon-o-trash'),
                Action::make('edit')->url(fn(Customer $record): string => route('customer.update', $record))->openUrlInNewTab()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.customer.list-customers');
    }
}
