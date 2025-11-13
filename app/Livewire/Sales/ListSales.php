<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ListSales extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => Sale::query())
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
                Action::make('delete')->requiresConfirmation()->modalHeading('Hapus Penjualan')->modalDescription('Apakah anda yakin ingin menghapus data Penjualan ini?')->modalSubmitActionLabel('Ya, Hapus')->modalCancelActionLabel('Batal')->color('danger')->action(fn(Sale $record) => $record->delete())->successNotification(Notification::make()->make('Penjualan berhasil dihapus')->success())->modalIcon('heroicon-o-trash')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.sales.list-sales');
    }
}
