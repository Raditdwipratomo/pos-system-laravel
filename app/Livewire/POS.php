<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SalesItem;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class POS extends Component
{
    public $items;
    public $customers;
    public $paymentMethods;
    public $search = '';
    public $cart = [];

    // properties for checkout
    public $customer_id = null;
    public $payment_method_id = null;
    public $paid_amount = null;
    public $discount_amount = null;



    public function mount()
    {
        // Load all the items
        // $this->items = Item::with(['inventory' => function ($builder) {
        //     $builder->where('quantity', '>', 0);
        // }])->where('status', 'active')->get();

        $this->items = Item::whereHas('inventory', function ($builder) {
            $builder->where('quantity', '>', 0);
        })->with('inventory')->where('status', 'active')->get();
        // Load customers
        $this->customers = Customer::all();
        // Load payment methods
        $this->paymentMethods = PaymentMethod::all();
    }

    #[Computed]
    public function filteredItems()
    {
        if (empty($this->search)) {
            return $this->items;
        }

        return $this->items->filter(function ($items) {
            return str_contains(strtolower($items->name), strtolower($this->search)) || str_contains(strtolower($items->sku), strToLower($this->search));
        });
    }

    #[Computed]
    public function subtotal()
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    #[Computed]
    public function tax()
    {
        return $this->subtotal * 0.15;
    }

    #[Computed]
    public function totalBeforeDiscount()
    {
        return $this->subtotal + $this->tax;
    }

    #[Computed]
    public function total()
    {
        $discountedTotal = $this->totalBeforeDiscount - $this->discount_amount;
        return $discountedTotal;
    }

    #[Computed]
    public function change()
    {
        if ($this->paid_amount > $this->total) {
            return $this->paid_amount - $this->total;
        }
        return 0;
    }

    public function addToCart($itemId)
    {

        $item = Item::find($itemId);

        // Inventory
        $inventory = Inventory::where('item_id', $itemId)->first();
        if (!$inventory || $inventory->quantity <= 0) {
            Notification::make()->title('This item is out of stock!')->danger()->send();
            return;
        }

        if (isset($this->cart[$itemId])) {
            $currentQuantity = $this->cart[$itemId]['quantity'];
            if ($currentQuantity >= $inventory) {
                Notification::make()->title("Tidak bisa menambahkan lagi. Hanya tersedia {$inventory->quantity} di Stok!")->danger()->send();
                return;
            }
            $this->cart[$itemId]['quantity']++;
        } else {
            $this->cart[$itemId] = [
                'id' => $item->id,
                'name' => $item->name,
                'sku' => $item->sku,
                'price' => $item->price,
                'quantity' => 1,
            ];
        }
    }

    public function removeFromCart($itemId)
    {
        unset($this->cart[$itemId]);
    }

    public function updateQuantity($itemId, $quantity)
    {
        $quantity = max(1, (int) $quantity);
        $inventory = Inventory::where('item_id', $itemId)->first();
        if ($quantity > $inventory) {
            Notification::make()->title("Tidak bisa menambahkan lagi. Hanya tersedia {$inventory->quantity} di Stok!")->danger()->send();
            $this->cart[$itemId]['quantity'] = $inventory->quantity;
        } else {
            $this->cart[$itemId]['quantity'] = $quantity;
        }
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            Notification::make()->title('Gagal Checkout!')->body("Tidak bisa checkout. Pilih barang terlebih dahulu!")->danger()->send();
            return;
        }

        if ($this->paid_amount < $this->total) {
            Notification::make()->title('Gagal Checkout!')->body("Uang kurang!")->danger()->send();
            return;
        }

        try {
            //code...
            DB::beginTransaction();

            $sale = Sale::create([
                'total' => $this->total,
                'paid_amount' => $this->paid_amount,
                'customer_id' => $this->customer_id,
                'payment_method_id' => $this->payment_method_id,
                'discount' => $this->discount_amount
            ]);

            foreach ($this->cart as $item) {
                SalesItem::create([
                    'sale_id' => $sale->id,
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
                $inventory = Inventory::where('item_id', $item['id'])->first();
                if ($inventory) {
                    $inventory->quantity -= $item['quantity'];
                    $inventory->save();
                }
            }

            DB::commit();

            $this->cart = [];

            $this->search = '';
            $this->customer_id = null;

            $this->payment_method_id = null;
            $this->paid_amount = 0;
            $this->discount_amount = 0;

            Notification::make()->title('Penjualan Berhasil!')->body('Penjualan berhasil diselesaikan!')->success()->send();
        } catch (\Exception $th) {
            DB::rollBack();
            Notification::make()->title('Terjadi Kesalahan!')->body('Penjualan gagal diselesaikan, Mohon coba kembali!')->success()->send();
        }
    }
    public function render()
    {
        return view('livewire.p-o-s');
    }
}
