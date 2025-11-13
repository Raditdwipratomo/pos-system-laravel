<?php

use App\Livewire\Customer\CreateCustomer;
use App\Livewire\Customer\EditCustomer;
use App\Livewire\Customer\ListCustomers;
use App\Livewire\Inventory\CreateInventory;
use App\Livewire\Items\CreateItem;
use App\Livewire\Items\EditInventory;
use App\Livewire\Items\EditItem;
use App\Livewire\Items\ListInventories;
use App\Livewire\Items\ListItems;
use App\Livewire\Management\CreatePaymentMethod;
use App\Livewire\Management\CreateUser;
use App\Livewire\Management\EditPaymentMethod;
use App\Livewire\Management\EditUser;
use App\Livewire\Management\ListPaymentMethods;
use App\Livewire\Management\ListUsers;
use App\Livewire\Sales\ListSales;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::middleware(['auth'])->group(function () {
    // Users
    Route::get('/manage-users', ListUsers::class)->name('users.index');
    Route::get('/edit-user/{record}', EditUser::class)->name('user.update');
    Route::get('/manage-users/create', CreateUser::class)->name('user.create');
    // Items
    Route::get('/manage-items', ListItems::class)->name('items.index');
    Route::get('/edit-item/{record}', EditItem::class)->name('item.update');
    Route::get('/manage-items/create', CreateItem::class)->name('item.create');
    // Inventory
    Route::get('/manage-inventories', ListInventories::class)->name('inventories.index');
    Route::get('/manage-inventories/create', CreateInventory::class)->name('inventory.create');
    Route::get('/edit-inventory/{record}', EditInventory::class)->name('inventory.update');
    // Sales
    Route::get('/manage-sales', ListSales::class)->name('sales.index');
    // Customers
    Route::get('/manage-customers', ListCustomers::class)->name('customers.index');
    Route::get('/edit-customer/{record}', EditCustomer::class)->name('customer.update');
    Route::get('/manage-customers/create', CreateCustomer::class)->name('customer.create');
    // Payment Methods
    Route::get('/manage-payment-methods', ListPaymentMethods::class)->name('payment.method.index');
    Route::get('/manage-payment-methods/create', CreatePaymentMethod::class)->name('payment.create');
    Route::get('/edit-payment-method/{record}', EditPaymentMethod::class)->name('payment.update');
});
