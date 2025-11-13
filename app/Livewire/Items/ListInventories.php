<?php

namespace App\Livewire\Items;

use App\Models\Inventory;
use App\Models\Item;
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

class ListInventories extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => Inventory::query())
            ->columns([
                //
                TextColumn::make('item.name')->label('Nama Item')->searchable()->sortable(),
                TextColumn::make('quantity')->label('Jumlah')->sortable()->badge()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
                Action::make('create')->url(fn() => route('inventory.create'))->openUrlInNewTab()->label('+ Tambah Inventory')
            ])
            ->recordActions([
                //
                Action::make('delete')->requiresConfirmation()->modalHeading('Hapus Inventory')->modalDescription('Apakah anda yakin ingin menghapus data inventory ini?')->modalSubmitActionLabel('Ya, Hapus')->modalCancelActionLabel('Batal')->color('danger')->action(fn(Inventory $record) => $record->delete())->successNotification(Notification::make()->make('Inventory berhasil dihapus')->success())->modalIcon('heroicon-o-trash'),
                Action::make('edit')->url(fn(Inventory $record): string => route('inventory.update', $record))->openUrlInNewTab()

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.items.list-inventories');
    }
}
