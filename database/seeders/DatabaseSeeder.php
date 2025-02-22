<?php

namespace Database\Seeders;


use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\CategorySeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\PurchaseOrderItemSeeder;
use Database\Seeders\PurchaseOrderSeeder;

use Database\Seeders\ReceivedGoodDetailSeeder;
use Database\Seeders\ReceivedGoodSeeder;
use Database\Seeders\StockTransactionSeeder;
use Database\Seeders\VendorSeeder;
use Illuminate\Database\Seeder;
use OpenAfghanistan\Provinces\Database\Seeders\ProvincesSeeder;

use Database\Seeders\PricePackageSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        // Seed the User table (if necessary)
        // User::factory()->create([
        //     'name' => 'MTC Admin',
        //     'email' => 'mtc@example.com',
        // ]);

        // Seed department and vendor tables
        $this->call([
            DepartmentSeeder::class,
            VendorSeeder::class,
        ]);

        // Seed categories first (items depend on categories)
        $this->call(CategorySeeder::class);

        // Seed items
        $this->call(ItemSeeder::class);





        // Seed purchase orders after items and vendors are created
        $this->call(PurchaseOrderSeeder::class);

        // Finally, seed purchase order items (dependent on purchase orders and items)
        $this->call(PurchaseOrderItemSeeder::class);

        // Optional: Seed stock adjustments

        $this->call(ReceivedGoodSeeder::class);
        $this->call(ReceivedGoodDetailSeeder::class);
        $this->call(StockTransactionSeeder::class);
        $this->call(ProvincesSeeder::class);
        $this->call(CustomerSeeder::class);
        // $this->call(PricePackageSeeder::class); 
        $this->call(PricePackageSeeder::class);
       
    }
}
