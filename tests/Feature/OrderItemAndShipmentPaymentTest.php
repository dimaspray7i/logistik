<?php

namespace Tests\Feature;

use App\Enums\InvoicePaymentStatus;
use App\Enums\OrderStatus;
use App\Enums\ShipmentStatus;
use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderItemAndShipmentPaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customerUser;
    private Customer $customer;
    private Product $productA;
    private Product $productB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'customer_id' => null,
        ]);

        $this->customer = Customer::create([
            'name' => 'Budi Santoso',
            'company_name' => 'PT Semen Perkasa',
            'phone' => '08123456789',
            'email' => 'semen@example.com',
            'address' => 'Jl. Industri No. 10',
            'city' => 'Gresik',
            'province' => 'Jawa Timur',
            'postal_code' => '61122',
        ]);

        $this->customerUser = User::factory()->create([
            'role' => UserRole::CUSTOMER,
            'customer_id' => $this->customer->id,
        ]);

        $this->productA = Product::create([
            'sku' => 'PRD-SMN-01',
            'name' => 'Semen Portland',
            'description' => 'Semen 50kg',
            'unit' => 'Sak',
        ]);

        $this->productB = Product::create([
            'sku' => 'PRD-KRM-01',
            'name' => 'Keramik Lantai 40x40',
            'description' => 'Keramik motif marmer',
            'unit' => 'Dus',
        ]);
    }

    // ==========================================
    // 1. ORDER ITEM TESTS
    // ==========================================

    public function test_admin_can_create_order_without_weight_and_with_unit_and_keterangan(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.orders.store'), [
            'customer_id' => $this->customer->id,
            'order_date' => '2026-09-02',
            'status' => 'PENDING',
            'notes' => 'Pesanan proyek konstruksi',
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'quantity' => 100,
                    'unit' => 'Sak',
                    'notes' => 'Semen Portland Gresik',
                ],
                [
                    'product_id' => $this->productB->id,
                    'quantity' => 50,
                    'unit' => 'Dus',
                    'notes' => 'Keramik lantai motif putih',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseHas('orders', [
            'customer_id' => $this->customer->id,
            'status' => OrderStatus::PENDING->value,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $this->productA->id,
            'quantity' => 100,
            'weight' => 0,
            'unit' => 'Sak',
            'notes' => 'Semen Portland Gresik',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $this->productB->id,
            'quantity' => 50,
            'weight' => 0,
            'unit' => 'Dus',
            'notes' => 'Keramik lantai motif putih',
        ]);
    }

    public function test_admin_can_update_order_items_unit_and_keterangan(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-TEST-UPD',
            'customer_id' => $this->customer->id,
            'order_date' => '2026-09-02',
            'status' => OrderStatus::PENDING,
        ]);

        $item = $order->items()->create([
            'product_id' => $this->productA->id,
            'quantity' => 100,
            'weight' => 0,
            'unit' => 'Sak',
            'notes' => 'Keterangan awal',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'order_date' => '2026-09-02',
            'status' => 'PROCESSING',
            'notes' => 'Catatan diperbarui',
            'items' => [
                [
                    'id' => $item->id,
                    'product_id' => $this->productA->id,
                    'quantity' => 150,
                    'unit' => 'Sak',
                    'notes' => 'Keterangan setelah update',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseHas('order_items', [
            'id' => $item->id,
            'quantity' => 150,
            'unit' => 'Sak',
            'notes' => 'Keterangan setelah update',
        ]);
    }

    public function test_order_creation_requires_unit_and_product(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.orders.store'), [
            'customer_id' => $this->customer->id,
            'order_date' => '2026-09-02',
            'status' => 'PENDING',
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'quantity' => 10,
                    'unit' => '', // Empty unit
                ],
            ],
        ]);

        $response->assertSessionHasErrors(['items.0.unit']);
    }

    // ==========================================
    // 2. SHIPMENT & INVOICE PAYMENT TESTS
    // ==========================================

    public function test_admin_can_create_shipment_with_inherited_items_and_default_unpaid_status(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-SHP-001',
            'customer_id' => $this->customer->id,
            'order_date' => '2026-09-02',
            'status' => OrderStatus::PROCESSING,
        ]);

        $order->items()->create([
            'product_id' => $this->productA->id,
            'quantity' => 100,
            'weight' => 0,
            'unit' => 'Sak',
            'notes' => 'Semen Portland Gresik',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $order->id,
            'origin' => 'Gresik',
            'destination' => 'Surabaya',
            'status' => 'READY',
            'notes' => 'Muatan semen siap kirim',
        ]);

        $response->assertRedirect(route('admin.shipments.index'));

        $this->assertDatabaseHas('shipments', [
            'order_id' => $order->id,
            'invoice_payment_status' => 'Belum Dibayar',
            'invoice_payment_date' => null,
        ]);

        $this->assertDatabaseHas('shipment_items', [
            'product_id' => $this->productA->id,
            'quantity' => 100,
            'unit' => 'Sak',
            'notes' => 'Semen Portland Gresik',
        ]);
    }

    public function test_admin_can_update_shipment_to_paid_with_valid_payment_date(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-SHP-002',
            'customer_id' => $this->customer->id,
            'order_date' => '2026-09-02',
            'status' => OrderStatus::PROCESSING,
        ]);

        $shipment = Shipment::create([
            'shipment_number' => 'SHP-PAY-001',
            'order_id' => $order->id,
            'customer_id' => $this->customer->id,
            'origin' => 'Gresik',
            'destination' => 'Surabaya',
            'status' => ShipmentStatus::READY,
            'invoice_payment_status' => InvoicePaymentStatus::UNPAID,
            'invoice_payment_date' => null,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.shipments.update', $shipment), [
            'origin' => 'Gresik',
            'destination' => 'Surabaya',
            'status' => 'DELIVERED',
            'invoice_payment_status' => 'Sudah Dibayar',
            'invoice_payment_date' => '2026-09-02',
        ]);

        $response->assertRedirect(route('admin.shipments.index'));

        $shipment->refresh();
        $this->assertEquals(InvoicePaymentStatus::PAID, $shipment->invoice_payment_status);
        $this->assertEquals('2026-09-02', $shipment->invoice_payment_date->format('Y-m-d'));
    }

    public function test_admin_cannot_update_shipment_to_paid_without_payment_date(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-SHP-003',
            'customer_id' => $this->customer->id,
            'order_date' => '2026-09-02',
            'status' => OrderStatus::PROCESSING,
        ]);

        $shipment = Shipment::create([
            'shipment_number' => 'SHP-PAY-002',
            'order_id' => $order->id,
            'customer_id' => $this->customer->id,
            'origin' => 'Gresik',
            'destination' => 'Surabaya',
            'status' => ShipmentStatus::READY,
            'invoice_payment_status' => InvoicePaymentStatus::UNPAID,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.shipments.update', $shipment), [
            'origin' => 'Gresik',
            'destination' => 'Surabaya',
            'status' => 'DELIVERED',
            'invoice_payment_status' => 'Sudah Dibayar',
            'invoice_payment_date' => '', // Empty date!
        ]);

        $response->assertSessionHasErrors(['invoice_payment_date']);
    }

    public function test_reverting_shipment_to_unpaid_resets_payment_date_to_null(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-SHP-004',
            'customer_id' => $this->customer->id,
            'order_date' => '2026-09-02',
            'status' => OrderStatus::PROCESSING,
        ]);

        $shipment = Shipment::create([
            'shipment_number' => 'SHP-PAY-003',
            'order_id' => $order->id,
            'customer_id' => $this->customer->id,
            'origin' => 'Gresik',
            'destination' => 'Surabaya',
            'status' => ShipmentStatus::DELIVERED,
            'invoice_payment_status' => InvoicePaymentStatus::PAID,
            'invoice_payment_date' => '2026-09-02',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.shipments.update', $shipment), [
            'origin' => 'Gresik',
            'destination' => 'Surabaya',
            'status' => 'DELIVERED',
            'invoice_payment_status' => 'Belum Dibayar',
            'invoice_payment_date' => '2026-09-02', // Passed date should be cleared
        ]);

        $response->assertRedirect(route('admin.shipments.index'));

        $shipment->refresh();
        $this->assertEquals(InvoicePaymentStatus::UNPAID, $shipment->invoice_payment_status);
        $this->assertNull($shipment->invoice_payment_date);
    }

    public function test_invalid_invoice_payment_status_is_rejected(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-SHP-005',
            'customer_id' => $this->customer->id,
            'order_date' => '2026-09-02',
            'status' => OrderStatus::PROCESSING,
        ]);

        $shipment = Shipment::create([
            'shipment_number' => 'SHP-PAY-004',
            'order_id' => $order->id,
            'customer_id' => $this->customer->id,
            'origin' => 'Gresik',
            'destination' => 'Surabaya',
            'status' => ShipmentStatus::READY,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.shipments.update', $shipment), [
            'origin' => 'Gresik',
            'destination' => 'Surabaya',
            'status' => 'READY',
            'invoice_payment_status' => 'ARBITRARY_INVALID_VALUE',
        ]);

        $response->assertSessionHasErrors(['invoice_payment_status']);
    }

    public function test_customer_cannot_update_shipment_or_payment_status(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-SHP-006',
            'customer_id' => $this->customer->id,
            'order_date' => '2026-09-02',
            'status' => OrderStatus::PROCESSING,
        ]);

        $shipment = Shipment::create([
            'shipment_number' => 'SHP-PAY-005',
            'order_id' => $order->id,
            'customer_id' => $this->customer->id,
            'origin' => 'Gresik',
            'destination' => 'Surabaya',
            'status' => ShipmentStatus::READY,
            'invoice_payment_status' => InvoicePaymentStatus::UNPAID,
        ]);

        $response = $this->actingAs($this->customerUser)->put(route('admin.shipments.update', $shipment), [
            'origin' => 'Gresik',
            'destination' => 'Surabaya',
            'status' => 'DELIVERED',
            'invoice_payment_status' => 'Sudah Dibayar',
            'invoice_payment_date' => '2026-09-02',
        ]);

        $response->assertRedirect(route('customer.dashboard'));

        $shipment->refresh();
        $this->assertEquals(InvoicePaymentStatus::UNPAID, $shipment->invoice_payment_status);
        $this->assertNull($shipment->invoice_payment_date);
    }
}
