<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Contact;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User utama (Admin + Customer)
        $this->call([
            RoleAndCustomerSeeder::class,
        ]);

        // Cari PT Backburner 
        $demoCustomer = Customer::where('company_name', 'PT Backburner')->first();

        Customer::factory(4)->create();
        Product::factory(10)->create();
        Vehicle::factory(5)->create();
        Driver::factory(5)->create();

        // Order + Shipment random (konsisten)
        foreach (range(1, 15) as $i) {
            $order = Order::factory()->create();

            Shipment::factory()->create([
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
            ]);

            Contact::factory(2)->create([
                'customer_id' => $demoCustomer->id,
            ]);
        }

        // Order + Shipment khusus milik PT Backburner
        if ($demoCustomer) {
            foreach (range(1, 5) as $i) {
                $order = Order::factory()->create(['customer_id' => $demoCustomer->id]);

                Shipment::factory()->create([
                    'order_id' => $order->id,
                    'customer_id' => $demoCustomer->id,
                ]);
            }
        }
    }
}