<?php

declare(strict_types=1);

use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\ExpenseCategoriesController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\CashHistoryController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchasePaymentsController;
use App\Http\Controllers\PurchaseReturnPaymentsController;
use App\Http\Controllers\PurchasesReturnController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\QuotationSalesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\SendQuotationEmailController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SettingHourController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\DocsController;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Language\EditTranslation;
use Illuminate\Support\Facades\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::get('/', [AuthenticatedSessionController::class, 'create']);

// Route::get('/docs/{file?}', [DocsController::class, 'index'])->name('docs.index');

// Route::get('/docs', function() {
//     View::addExtension('html', 'php'); // allows .html
//     return view('docs.index'); // loads /public/docs/index.html
// });

Route::get('/docs', function () {
    if ($file != 'index') {
        $file = $file.'/index';
    }

    return File::get(public_path().'/docs/'.$file.'.html');
});

Route::group(['middleware' => 'auth'], function () {
    // change lang
    Route::get('/lang/{lang}', [HomeController::class, 'changeLanguage'])->name('changelanguage');

    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

    // Charts
    Route::get('/sales-purchases/chart-data', [HomeController::class, 'salesPurchasesChart'])->name('sales-purchases.chart');
    Route::get('/current-month/chart-data', [HomeController::class, 'currentMonthChart'])->name('current-month.chart');
    Route::get('/payment-flow/chart-data', [HomeController::class, 'paymentChart'])->name('payment-flow.chart');

    //Product Adjustment
    Route::resource('adjustments', AdjustmentController::class);

    //Currencies
    Route::get('currencies', CurrencyController::class)->name('currencies.index');

    //Expense Category
    Route::get('expense-categories', ExpenseCategoriesController::class)->name('expense-categories.index');

    //Expense
    Route::get('expenses', ExpenseController::class)->name('expenses.index');

    //Expense
    Route::get('incomes', IncomeController::class)->name('incomes.index');

    Route::get('cash', CashController::class)->name('cash.index');
    Route::get('cash-history', [CashHistoryController::class, 'history'])->name('cash-history.index');

    Route::get('/cash-history-print-short/{cash}', [CashHistoryController::class, 'printShort'])->name('cash-history-print-short.index');
    Route::get('/cash-history-print/{cash}', [CashHistoryController::class, 'print'])->name('cash-history-print.index');

    //Customers
    Route::get('customers', [CustomersController::class, 'index'])->name('customers.index');
    Route::get('customer/details/{id}', [CustomersController::class, 'show'])->name('customer.details');

    Route::get('customergroup', [CustomerGroupController::class, 'index'])->name('customer-group.index');

    //Suppliers
    Route::get('suppliers', [SuppliersController::class, 'index'])->name('suppliers.index');
    Route::get('supplier/details/{id}', [SuppliersController::class, 'show'])->name('supplier.details');

    //Warehouses
    Route::get('warehouses', WarehouseController::class)->name('warehouses.index');

    //Brands
    Route::get('brands', BrandsController::class)->name('brands.index');

    //Print Barcode
    Route::get('/products/print-barcode', [BarcodeController::class, 'index'])->name('barcode.print');

    //Product Category
    Route::get('product-categories', CategoriesController::class)->name('product-categories.index');

    Route::get('/products', ProductController::class)->name('products');

    //Generate Quotation PDF
    Route::get('/quotations/pdf/{id}', [ExportController::class, 'quotation'])->name('quotations.pdf');

    //Send Quotation Mail
    Route::get('/quotation/mail/{quotation}', SendQuotationEmailController::class)->name('quotation.email');

    //Sales Form Quotation
    Route::get('quotation-sales/{quotation}', QuotationSalesController::class)->name('quotation-sales.create');

    //Quotations
    Route::resource('quotations', QuotationController::class);

    //Generate Purchase PDF
    Route::get('/purchases/pdf/{id}', [ExportController::class, 'purchase'])->name('purchases.pdf');

    //Purchases
    Route::resource('purchases', PurchaseController::class);

    //Purchase Payments
    Route::get('/purchase-payments/{purchase_id}', [PurchasePaymentsController::class, 'index'])->name('purchase-payments.index');
    Route::get('/purchase-payments/{purchase_id}/create', [PurchasePaymentsController::class, 'create'])->name('purchase-payments.create');
    Route::post('/purchase-payments/{purchase_id}', [PurchasePaymentsController::class, 'store'])->name('purchase-payments.store');
    Route::get('/purchase-payments/{purchase_id}/edit/{purchasePayment}', [PurchasePaymentsController::class, 'edit'])->name('purchase-payments.edit');
    Route::patch('/purchase-payments/update/{purchasePayment}', [PurchasePaymentsController::class, 'update'])->name('purchase-payments.update');
    Route::delete('/purchase-payments/destroy/{purchasePayment}', [PurchasePaymentsController::class, 'destroy'])->name('purchase-payments.destroy');

    //Generate Purchase Return PDF
    Route::get('/purchase-returns/pdf/{id}', [ExportController::class, 'purchaseReturns'])->name('purchase-returns.pdf');

    //Purchase Returns
    Route::resource('purchase-returns', PurchasesReturnController::class);

    //Purchase Returns Payments
    Route::get('/purchase-return-payments/{purchaseReturn_id}', [PurchaseReturnPaymentsController::class, 'index'])->name('purchase-return-payments.index');

    Route::get('/purchase-return-payments/{purchase_return_id}/create', [PurchaseReturnPaymentsController::class, 'create'])
        ->name('purchase-return-payments.create');
    Route::post('/purchase-return-payments/store', [PurchaseReturnPaymentsController::class, 'store'])
        ->name('purchase-return-payments.store');
    Route::get('/purchase-return-payments/{purchase_return_id}/edit/{purchaseReturnPayment}', [PurchaseReturnPaymentsController::class, 'edit'])
        ->name('purchase-return-payments.edit');
    Route::patch('/purchase-return-payments/update/{purchaseReturnPayment}', [PurchaseReturnPaymentsController::class, 'update'])
        ->name('purchase-return-payments.update');
    Route::delete('/purchase-return-payments/destroy/{purchaseReturnPayment}', [PurchaseReturnPaymentsController::class, 'destroy'])
        ->name('purchase-return-payments.destroy');

    //Profit Loss Report
    Route::get('/profit-loss-report', [ReportsController::class, 'profitLossReport'])->name('profit-loss-report.index');
    //Payments Report
    Route::get('/payments-report', [ReportsController::class, 'paymentsReport'])->name('payments-report.index');
    //Sales Report
    Route::get('/sales-report', [ReportsController::class, 'salesReport'])->name('sales-report.index');
    //Purchases Report
    Route::get('/purchases-report', [ReportsController::class, 'purchasesReport'])->name('purchases-report.index');
    //Sales Return Report
    Route::get('/sales-return-report', [ReportsController::class, 'salesReturnReport'])->name('sales-return-report.index');
    //Purchases Return Report
    Route::get('/purchases-return-report', [ReportsController::class, 'purchasesReturnReport'])->name('purchases-return-report.index');

    //POS
    Route::get('/pos', [PosController::class, 'index'])->name('app.pos.index');
    Route::post('/app/pos', [PosController::class, 'store'])->name('app.pos.store');

    //Generate Sale PDF
    Route::get('/sales/pdf/{id}', [ExportController::class, 'sale'])->name('sales.pdf');
    Route::get('/sales/pos/pdf/{id}', [ExportController::class, 'salePos'])->name('sales.pos.pdf');

    Route::get('/inscription/print/{id}', [ExportController::class, 'inscriptionPrint'])->name('inscription.print');

    Route::get('/sales/ddd/{products?}', [ExportController::class, 'ddd'])->name('sales.ddd');

    //Sales
    Route::resource('sales', SaleController::class);

    //Generate Sale Returns PDF
    Route::get('/sale-returns/pdf/{id}', [ExportController::class, 'saleReturns'])->name('sale-returns.pdf');

    //Sale Returns
    Route::resource('sale-returns', SalesReturnController::class);

    //User Profile
    Route::get('/user/profile', [ProfileController::class, 'index'])->name('profile.index');

    //Users
    Route::get('users', UsersController::class)->name('users.index');

    //Roles
    Route::get('roles', RoleController::class)->name('roles.index');

    // Permissions
    Route::get('permissions', PermissionController::class)->name('permissions.index');

    //Logs
    Route::get('logs', LogController::class)->name('logs.index');

    //Language Settings
    Route::get('languages', LanguageController::class)->name('languages.index');
    Route::get('/translation/{code}', EditTranslation::class)->name('translation.index');

    //Backup
    Route::get('backup', BackupController::class)->name('backup.index');

    //General Settings
    Route::get('/settings', SettingController::class)->name('settings.index');

    Route::get('/setting-hour', SettingHourController::class)->name('setting-hour.index');

    // Integrations
    Route::get('/integrations', IntegrationController::class)->name('integrations.index');

    //Services
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/inscriptions', [ServiceController::class, 'inscriptions'])->name('services-inscriptions.index');
    Route::get('/monthly', [ServiceController::class, 'monthly'])->name('services-monthly.index');
    Route::get('/free', [ServiceController::class, 'free'])->name('services-free.index');

    Route::get('/free-swim', [ServiceController::class, 'freeSwim'])->name('services-free-swim.index');

    Route::get('/service-format', [ExportController::class, 'serviceFormat'])->name('service-format.index');

    Route::get('/format-one/{services?}', [ExportController::class, 'formatOne'])->name('format-one.index');
    Route::get('/format-two/{services?}', [ExportController::class, 'formatTwo'])->name('format-two.index');
    Route::get('/format-three/{services?}', [ExportController::class, 'formatThree'])->name('format-three.index');


});
