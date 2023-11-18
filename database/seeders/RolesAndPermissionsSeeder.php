<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'user_access', 'description' => 'usuario - acceso']);
        Permission::create(['name' => 'user_create', 'description' => 'usuario - crear']);
        Permission::create(['name' => 'user_update', 'description' => 'usuario - actualizar']);
        Permission::create(['name' => 'user_delete', 'description' => 'usuario - eliminar']);
        Permission::create(['name' => 'role_access', 'description' => 'roles - acceso']);
        Permission::create(['name' => 'role_create', 'description' => 'roles - crear']);
        Permission::create(['name' => 'role_update', 'description' => 'roles - actualizar']);
        Permission::create(['name' => 'role_delete', 'description' => 'roles - eliminar']);
        Permission::create(['name' => 'permission_access', 'description' => 'permisos - acceso']);
        Permission::create(['name' => 'permission_create', 'description' => 'permisos - crear']);
        Permission::create(['name' => 'permission_update', 'description' => 'permisos - actualizar']);
        Permission::create(['name' => 'permission_delete', 'description' => 'permisos - eliminar']);
        Permission::create(['name' => 'customer_access', 'description' => 'clientes - acceso']);
        Permission::create(['name' => 'customer_show', 'description' => 'clientes - ver']);
        Permission::create(['name' => 'customer_create', 'description' => 'clientes - crear']);
        Permission::create(['name' => 'customer_update', 'description' => 'clientes - actualizar']);
        Permission::create(['name' => 'customer_delete', 'description' => 'clientes - eliminar']);
        Permission::create(['name' => 'product_access', 'description' => 'productos - acceso']);
        Permission::create(['name' => 'product_create', 'description' => 'productos - crear']);
        Permission::create(['name' => 'product_update', 'description' => 'productos - actualizar']);
        Permission::create(['name' => 'product_delete', 'description' => 'productos - eliminar']);
        Permission::create(['name' => 'product_show', 'description' => 'productos - ver']);
        Permission::create(['name' => 'product_import', 'description' => 'productos - importar']);
        Permission::create(['name' => 'sale_access', 'description' => 'ventas - acceso']);
        Permission::create(['name' => 'sale_create', 'description' => 'ventas - crear']);
        Permission::create(['name' => 'sale_show', 'description' => 'ventas - ver']);
        Permission::create(['name' => 'sale_update', 'description' => 'ventas - actualizar']);
        Permission::create(['name' => 'sale_delete', 'description' => 'ventas - eliminar']);
        Permission::create(['name' => 'purchase_access', 'description' => 'compras - acceso']);
        Permission::create(['name' => 'purchase_create', 'description' => 'compras - crear']);
        Permission::create(['name' => 'purchase_update', 'description' => 'compras - actualizar']);
        Permission::create(['name' => 'purchase_delete', 'description' => 'compras - eliminar']);
        Permission::create(['name' => 'report_access', 'description' => 'reportes - acceso']);
        Permission::create(['name' => 'log_access', 'description' => 'log - acceso']);
        Permission::create(['name' => 'backup_access', 'description' => 'respaldo - acceso']);
        Permission::create(['name' => 'setting_access', 'description' => 'configuraciones - acceso']);
        Permission::create(['name' => 'dashboard_access', 'description' => 'panel de inicio - acceso']);
        Permission::create(['name' => 'category_access', 'description' => 'categorías - acceso']);
        Permission::create(['name' => 'category_create', 'description' => 'categorías - crear']);
        Permission::create(['name' => 'category_update', 'description' => 'categorías - actualizar']);
        Permission::create(['name' => 'category_delete', 'description' => 'categorías - eliminar']);
        Permission::create(['name' => 'brand_access', 'description' => 'marcas - acceso']);
        Permission::create(['name' => 'brand_create', 'description' => 'marcas - crear']);
        Permission::create(['name' => 'brand_update', 'description' => 'marcas - actualizar']);
        Permission::create(['name' => 'brand_delete', 'description' => 'marcas - eliminar']);
        Permission::create(['name' => 'brand_show', 'description' => 'marcas - ver']);
        Permission::create(['name' => 'brand_import', 'description' => 'marcas - importar']);
        Permission::create(['name' => 'expense_access', 'description' => 'egresos - acceso']);
        Permission::create(['name' => 'expense_show', 'description' => 'egresos - ver']);
        Permission::create(['name' => 'expense_create', 'description' => 'egresos - crear']);
        Permission::create(['name' => 'expense_update', 'description' => 'egresos - actualizar']);
        Permission::create(['name' => 'expense_delete', 'description' => 'egresos - eliminar']);
        Permission::create(['name' => 'adjustment_access', 'description' => 'ajuste de existencias - acceso']);
        Permission::create(['name' => 'adjustment_create', 'description' => 'ajuste de existencias - crear']);
        Permission::create(['name' => 'adjustment_edit', 'description' => 'ajuste de existencias - editar']);
        Permission::create(['name' => 'adjustment_delete', 'description' => 'ajuste de existencias - eliminar']);
        Permission::create(['name' => 'printer_access', 'description' => 'impresoras - acceso']);
        Permission::create(['name' => 'printer_create', 'description' => 'impresoras - crear']);
        Permission::create(['name' => 'printer_show', 'description' => 'impresoras - ver']);
        Permission::create(['name' => 'printer_edit', 'description' => 'impresoras - editar']);
        Permission::create(['name' => 'printer_delete', 'description' => 'impresoras - eliminar']);
        Permission::create(['name' => 'quotation_access', 'description' => 'cotizaciones - acceso']);
        Permission::create(['name' => 'quotation_create', 'description' => 'cotizaciones - crear']);
        Permission::create(['name' => 'quotation_update', 'description' => 'cotizaciones - actualizar']);
        Permission::create(['name' => 'quotation_delete', 'description' => 'cotizaciones - eliminar']);
        Permission::create(['name' => 'quotation_sale', 'description' => 'cotizaciones']);
        Permission::create(['name' => 'print_barcodes', 'description' => 'imprimir códigos de barras']);
        Permission::create(['name' => 'purchase_return_access', 'description' => 'devoluciones de compras - acceso']);
        Permission::create(['name' => 'purchase_return_create', 'description' => 'devoluciones de compras - crear']);
        Permission::create(['name' => 'purchase_return_update', 'description' => 'devoluciones de compras - actualizar']);
        Permission::create(['name' => 'purchase_return_show', 'description' => 'devoluciones de compras - ver']);
        Permission::create(['name' => 'purchase_return_delete', 'description' => 'devoluciones de compras - eliminar']);
        Permission::create(['name' => 'sale_return_access', 'description' => 'devoluciones de ventas - acceso']);
        Permission::create(['name' => 'sale_return_create', 'description' => 'devoluciones de ventas - crear']);
        Permission::create(['name' => 'sale_return_update', 'description' => 'devoluciones de ventas - actualizar']);
        Permission::create(['name' => 'sale_return_show', 'description' => 'devoluciones de ventas - ver']);
        Permission::create(['name' => 'sale_return_delete', 'description' => 'devoluciones de ventas - eliminar']);
        Permission::create(['name' => 'currency_access', 'description' => 'monedas - acceso']);
        Permission::create(['name' => 'expense_categories_access', 'description' => 'categorías de egresos - acceso']);
        Permission::create(['name' => 'expense_categories_create', 'description' => 'categorías de egresos - crear']);
        Permission::create(['name' => 'expense_categories_show', 'description' => 'categorías de egresos - ver']);
        Permission::create(['name' => 'expense_categories_edit', 'description' => 'categorías de egresos - editar']);
        Permission::create(['name' => 'expense_categories_delete', 'description' => 'categorías de egresos - eliminar']);
        Permission::create(['name' => 'purchase_payment_access', 'description' => 'pagos de compras - acceso']);
        Permission::create(['name' => 'purchase_payment_create', 'description' => 'pagos de compras - crear']);
        Permission::create(['name' => 'purchase_payment_update', 'description' => 'pagos de compras - actualizar']);
        Permission::create(['name' => 'purchase_payment_delete', 'description' => 'pagos de compras - eliminar']);
        Permission::create(['name' => 'supplier_access', 'description' => 'proveedores - acceso']);
        Permission::create(['name' => 'supplier_create', 'description' => 'proveedores - crear']);
        Permission::create(['name' => 'supplier_show', 'description' => 'proveedores - ver']);
        Permission::create(['name' => 'supplier_update', 'description' => 'proveedores - actualizar']);
        Permission::create(['name' => 'supplier_import', 'description' => 'proveedores - importar']);
        Permission::create(['name' => 'supplier_delete', 'description' => 'proveedores - eliminar']);
        Permission::create(['name' => 'sale_payment_access', 'description' => 'pagos de ventas - acceso']);
        Permission::create(['name' => 'sale_payment_create', 'description' => 'pagos de ventas - crear']);
        Permission::create(['name' => 'sale_payment_update', 'description' => 'pagos de ventas - actualizar']);
        Permission::create(['name' => 'sale_payment_delete', 'description' => 'pagos de ventas - eliminar']);
        Permission::create(['name' => 'warehouse_access', 'description' => 'almacenes o tiendas - acceso']);
        Permission::create(['name' => 'warehouse_create', 'description' => 'almacenes o tiendas - crear']);
        Permission::create(['name' => 'warehouse_show', 'description' => 'almacenes o tiendas - ver']);
        Permission::create(['name' => 'warehouse_update', 'description' => 'almacenes o tiendas - actualizar']);
        Permission::create(['name' => 'warehouse_delete', 'description' => 'almacenes o tiendas - eliminar']);
        Permission::create(['name' => 'language_access', 'description' => 'idiomas - acceso']);
        Permission::create(['name' => 'language_create', 'description' => 'idiomas - crear']);
        Permission::create(['name' => 'language_update', 'description' => 'idiomas - actualizar']);
        Permission::create(['name' => 'language_delete', 'description' => 'idiomas - eliminar']);

        $role = Role::create(['name' => 'Super Admin']);
        $role->givePermissionTo(Permission::all());
    }
}
