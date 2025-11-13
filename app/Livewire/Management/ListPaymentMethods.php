<?php

namespace App\Livewire\Management;

use App\Models\PaymentMethod;
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


class ListPaymentMethods extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => PaymentMethod::query())
            ->columns([
                //
                TextColumn::make('name')->label('Metode Pembayaran')->searchable(),
                TextColumn::make('description')->limit(50)
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
                Action::make('create')->url(fn() => route('payment.create'))->openUrlInNewTab()->label('+ Buat Metode Pembayaran')
            ])
            ->recordActions([
                //
                Action::make('delete')->requiresConfirmation()->modalHeading('Hapus Payment Method')->modalDescription('Apakah anda yakin ingin menghapus data payment method ini?')->modalSubmitActionLabel('Ya, Hapus')->modalCancelActionLabel('Batal')->color('danger')->action(fn(PaymentMethod $record) => $record->delete())->successNotification(Notification::make()->make('Payment Method berhasil dihapus')->success())->modalIcon('heroicon-o-trash'),
                Action::make('edit')->url(fn(PaymentMethod $record): string => route('payment.update', $record))->openUrlInNewTab()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.management.list-payment-methods');
    }
}
