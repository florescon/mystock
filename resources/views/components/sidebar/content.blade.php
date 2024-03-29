<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-3 px-3">

    <x-sidebar.link title="{{ __('Dashboard') }}" href="{{ route('home') }}" :isActive="request()->routeIs('home')">
        <x-slot name="icon">
            <span class="inline-block mx-4">
                <x-icons.dashboard class="w-5 h-5" aria-hidden="true" />
            </span>
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.dropdown title="{{ __('Services') }}" :active="request()->routeIs('services.index') || request()->routeIs('services-inscriptions.index') || request()->routeIs('services-monthly.index') || request()->routeIs('services-free-swim.index') || request()->routeIs('services-free.index') || request()->routeIs('services-other.index') || request()->routeIs('service-format.index')">

        <x-slot name="icon">
            <span class="inline-block mx-4">
                <i class="fas fa-solid fa-tarp-droplet w-5 h-5"></i>
            </span>
        </x-slot>

        @can('category_access')
        <x-sidebar.sublink title="{{ __('Services') }}" href="{{ route('services.index') }}"
            :active="request()->routeIs('services.index')" />
        @endcan
        <x-sidebar.sublink title="{{ __('Inscriptions') }}" href="{{ route('services-inscriptions.index') }}"
            :active="request()->routeIs('services-inscriptions.index')" />
        <x-sidebar.sublink title="{{ __('Monthly payments') }}" href="{{ route('services-monthly.index') }}"
            :active="request()->routeIs('services-monthly.index')" />
        <x-sidebar.sublink title="{{ __('Free swim') }}" href="{{ route('services-free.index') }}"
            :active="request()->routeIs('services-free.index')" />

        <x-sidebar.sublink title="{{ __('Free swim') .' - '. __('Passes') }}" href="{{ route('services-free-swim.index') }}"
            :active="request()->routeIs('services-free-swim.index')" />

        <x-sidebar.sublink title="{{ __('Other Services') }}" href="{{ route('services-other.index') }}"
            :active="request()->routeIs('services-other.index')" />

        <x-sidebar.sublink title="{{ __('Create Format') }}" href="{{ route('service-format.index') }}"
            :active="request()->routeIs('service-format.index')" />

    </x-sidebar.dropdown>

    @canany(['product_access', 'brand_access'])
    
    <x-sidebar.dropdown title="{{ __('Products') }}" :active="request()->routeIs([
        'products',
        'product-categories.index',
        'barcode.print',
        'brands.index',
        'warehouses.index',
        'adjustments.*',
    ])">

        <x-slot name="icon">
            <span class="inline-block mx-4">
                <i class="fas fa-boxes w-5 h-5"></i>
            </span>
        </x-slot>

        @can('category_access')
            <x-sidebar.sublink title="{{ __('Categories') }}" href="{{ route('product-categories.index') }}"
                :active="request()->routeIs('product-categories.index')" />
        @endcan
            <x-sidebar.sublink title="{{ __('All Products') }}" href="{{ route('products') }}" :active="request()->routeIs('products')" />
        @can('print_barcodes')
            <x-sidebar.sublink title="{{ __('Print Barcode') }}" href="{{ route('barcode.print') }}" :active="request()->routeIs('barcode.print')" />
        @endcan
        @can('brand_access')
            <x-sidebar.sublink title="{{ __('Brands') }}" href="{{ route('brands.index') }}" :active="request()->routeIs('brands.index')" />
        @endcan
        @can('warehouse_access')
            <x-sidebar.sublink title="{{ __('Warehouses') }}" href="{{ route('warehouses.index') }}" :active="request()->routeIs('warehouses.index')" />
        @endcan
        @can('adjustment_access')
            <x-sidebar.sublink title="{{ __('Stock adjustments') }}" href="{{ route('adjustments.index') }}"
                :active="request()->routeIs('adjustments.index')" />
        @endcan

    </x-sidebar.dropdown>
    @endcanany

    {{-- @can('quotation_access')
        <x-sidebar.dropdown title="{{ __('Quotations') }}" :active="request()->routeIs('quotations.index')">

            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-file-invoice-dollar w-5 h-5"></i>
                </span>
            </x-slot>
            <x-sidebar.sublink title="{{ __('All Quotations') }}" href="{{ route('quotations.index') }}"
                :active="request()->routeIs('quotations.index')" />
        </x-sidebar.dropdown>
    @endcan --}}

    {{-- @can('purchase_access')
        <x-sidebar.dropdown title="{{ __('Purchases') }}" :active="request()->routeIs('purchases.index') || request()->routeIs('purchase-returns.index')">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-shopping-cart w-5 h-5"></i>
                </span>
            </x-slot>
            <x-sidebar.sublink title="{{ __('All Purchases') }}" href="{{ route('purchases.index') }}"
                :active="request()->routeIs('purchases.index')" />
            @can('purchase_return_access')
                <x-sidebar.sublink title="{{ __('All Purchase Returns') }}" href="{{ route('purchase-returns.index') }}"
                    :active="request()->routeIs('purchase-returns.index')" />
            @endcan
        </x-sidebar.dropdown>
    @endcan --}}
    @can('sale_access')
        <x-sidebar.dropdown title="{{ __('Sales') }}" :active="request()->routeIs(['sales.index', 'sale-returns.index'])">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-shopping-bag w-5 h-5"></i>
                </span>
            </x-slot>

            <x-sidebar.sublink title="{{ __('All Sales') }}" href="{{ route('sales.index') }}" :active="request()->routeIs('sales.index')" />

            {{-- @can('sale_return_access')
                <x-sidebar.sublink title="{{ __('All Sale Returns') }}" href="{{ route('sale-returns.index') }}"
                    :active="request()->routeIs('sale-returns.index')" />
            @endcan --}}
        </x-sidebar.dropdown>
    @endcan


    @can('expense_access')
        <x-sidebar.dropdown title="{{ __('Finances') }}" :active="request()->routeIs(['expenses.index', 'incomes.index', 'expense-categories.index'])">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-money-bill-alt w-5 h-5"></i>
                </span>
            </x-slot>

            @can('expense_categories_access')
                <x-sidebar.sublink title="{{ __('Categories') }}" href="{{ route('expense-categories.index') }}"
                    :active="request()->routeIs('expense-categories.index')" />
            @endcan
            <x-sidebar.sublink title="{{ __('All Expenses').' (-)' }}" href="{{ route('expenses.index') }}" :active="request()->routeIs('expenses.index')" />
            <x-sidebar.sublink title="{{ __('All Incomes').' (+)' }}" href="{{ route('incomes.index') }}" :active="request()->routeIs('incomes.index')" />
        </x-sidebar.dropdown>
    @endcan

    @can('expense_access')
        <x-sidebar.dropdown title="{{ __('Cash Out') }}" :active="request()->routeIs(['cash.index', 'cash-history.index'])">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-money-bill-alt w-5 h-5"></i>
                </span>
            </x-slot>

            <x-sidebar.sublink title="{{ __('Cash Out') }}" href="{{ route('cash.index') }}" :active="request()->routeIs('cash.index')" />
            <x-sidebar.sublink title="{{ __('All Cash Out') }}" href="{{ route('cash-history.index') }}" :active="request()->routeIs('cash-history.index')" />
        </x-sidebar.dropdown>
    @endcan

    @can('report_access')
        <x-sidebar.dropdown title="{{ __('Reports') }}" :active="request()->routeIs([
            'purchases-report.index',
            'sales-report.index',
            'sales-return-report.index',
            'payments-report.index',
            'purchases-return-report.index',
            'profit-loss-report.index',
        ])">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-chart-line w-5 h-5"></i>
                </span>
            </x-slot>

            <x-sidebar.sublink title="{{ __('Purchases Report') }}" href="{{ route('purchases-report.index') }}"
                :active="request()->routeIs('purchases-report.index')" />
            <x-sidebar.sublink title="{{ __('Sale Report') }}" href="{{ route('sales-report.index') }}"
                :active="request()->routeIs('sales-report.index')" />
            <x-sidebar.sublink title="{{ __('Sale Return Report') }}" href="{{ route('sales-return-report.index') }}"
                :active="request()->routeIs('sales-return-report.index')" />
            <x-sidebar.sublink title="{{ __('Payment Report') }}" href="{{ route('payments-report.index') }}"
                :active="request()->routeIs('payments-report.index')" />
            <x-sidebar.sublink title="{{ __('Purchases Return Report') }}"
                href="{{ route('purchases-return-report.index') }}" :active="request()->routeIs('purchases-return-report.index')" />
            <x-sidebar.sublink title="{{ __('Profit Report') }}" href="{{ route('profit-loss-report.index') }}"
                :active="request()->routeIs('profit-loss-report.index')" />

        </x-sidebar.dropdown>
    @endcan

    @canany(['user_access', 'customer_access', 'suppliers_access', 'access_roles', 'access_permissions'])
        <x-sidebar.dropdown title="{{ __('People') }}" :active="request()->routeIs('customers.*') ||
            request()->routeIs('customer-group.*') ||
            request()->routeIs('suppliers.*') ||
            request()->routeIs('users.*') ||
            request()->routeIs('roles.*') ||
            request()->routeIs('permissions.*')">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-users w-5 h-5"></i>
                </span>
            </x-slot>

            @can('user_access')
                <x-sidebar.sublink title="{{ __('Users') }}" href="{{ route('users.index') }}" :active="request()->routeIs('users.index')" />
            @endcan
            @can('customer_access')
                <x-sidebar.sublink title="{{ __('Customers') }}" href="{{ route('customers.index') }}" :active="request()->routeIs('customers.index')" />
            @endcan
            @can('customer_group_access')
                <x-sidebar.sublink title="{{ __('Customer Groups') }}" href="{{ route('customer-group.index') }}" :active="request()->routeIs('customer-group.index')" />
            @endcan
            @can('suppliers_access')
                <x-sidebar.sublink title="{{ __('Suppliers') }}" href="{{ route('suppliers.index') }}" :active="request()->routeIs('suppliers.index')" />
            @endcan
            @can('access_roles')
                <x-sidebar.sublink title="{{ __('Roles') }}" href="{{ route('roles.index') }}" :active="request()->routeIs('roles.index')" />
            @endcan
            @can('access_permissions')
                <x-sidebar.sublink title="{{ __('Permissions') }}" href="{{ route('permissions.index') }}"
                    :active="request()->routeIs('permissions.index')" />
            @endcan
        </x-sidebar.dropdown>
    @endcanany
    @can('access_settings')
        <x-sidebar.dropdown title="{{ __('Settings') }}" :active="request()->routeIs([
            'settings.index',
            'setting-hour.index',
            'logs.index',
            'currencies.index',
            'languages.index',
            'backup.index',
        ])">
            <x-slot name="icon">
                <span class="inline-block mx-4">
                    <i class="fas fa-cog w-5 h-5"></i>
                </span>
            </x-slot>
            <x-sidebar.sublink title="{{ __('Settings') }}" href="{{ route('settings.index') }}" :active="request()->routeIs('settings.index')" />
            {{-- <x-sidebar.sublink title="{{ __('Monthly payment schedules') }}" href="{{ route('setting-hour.index') }}" :active="request()->routeIs('setting-hour.index')" /> --}}
            {{-- @can('log_access')
                <x-sidebar.sublink title="{{ __('Logs') }}" href="{{ route('logs.index') }}" :active="request()->routeIs('logs.index')" />
            @endcan --}}
            @can('currency_access')
                <x-sidebar.sublink title="{{ __('Currencies') }}" href="{{ route('currencies.index') }}" :active="request()->routeIs('currencies.index')" />
            @endcan
            @can('language_access')
                <x-sidebar.sublink title="{{ __('Languages') }}" href="{{ route('languages.index') }}" :active="request()->routeIs('languages.index')" />
            @endcan
            @can('backup_access')
                {{-- <x-sidebar.sublink title="{{ __('Backup') }}" href="{{ route('backup.index') }}" :active="request()->routeIs('backup.index')" /> --}}
            @endcan

        </x-sidebar.dropdown>
    @endcan

    <x-sidebar.link title="{{ __('Logout') }}"
        onclick="event.preventDefault();
                        document.getElementById('logoutform').submit();"
        href="#">
        <x-slot name="icon">
            <span class="inline-block mx-4">
                <i class="fas fa-sign-out-alt w-5 h-5" aria-hidden="true"></i>
            </span>
        </x-slot>
    </x-sidebar.link>

</x-perfect-scrollbar>
