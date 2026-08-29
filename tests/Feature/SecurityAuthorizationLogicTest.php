<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\ShipmentStatus;
use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\TrackingUpdate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAuthorizationLogicTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customerUserA;
    private User $customerUserB;
    private Customer $customerA;
    private Customer $customerB;
    private Order $orderA;
    private Order $orderB;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'customer_id' => null,
        ]);

        $this->customerA = Customer::create([
            'name' => 'John Doe',
            'company_name' => 'PT Maju Jaya',
            'phone' => '08123456789',
            'email' => 'majujaya@example.com',
            'address' => 'Jl. Merdeka No. 1',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'postal_code' => '10110',
        ]);

        $this->orderA = Order::create([
            'order_number' => 'ORD-SETUP-A',
            'customer_id' => $this->customerA->id,
            'order_date' => now(),
            'status' => OrderStatus::PROCESSING,
        ]);

        $this->customerUserA = User::factory()->create([
            'role' => UserRole::CUSTOMER,
            'customer_id' => $this->customerA->id,
        ]);

        $this->customerB = Customer::create([
            'name' => 'Jane Smith',
            'company_name' => 'PT Sukses Makmur',
            'phone' => '08987654321',
            'email' => 'sukses@example.com',
            'address' => 'Jl. Sudirman No. 2',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'postal_code' => '60111',
        ]);

        $this->orderB = Order::create([
            'order_number' => 'ORD-SETUP-B',
            'customer_id' => $this->customerB->id,
            'order_date' => now(),
            'status' => OrderStatus::PROCESSING,
        ]);

        $this->customerUserB = User::factory()->create([
            'role' => UserRole::CUSTOMER,
            'customer_id' => $this->customerB->id,
        ]);

        $this->product = Product::create([
            'sku' => 'PRD-LOGIC-01',
            'name' => 'Produk Tes Logistik',
            'description' => 'Produk uji coba',
            'unit' => 'Pcs',
        ]);
    }

    // ==========================================
    // 1. TRACKING SECURITY & AUTHORIZATION TESTS
    // ==========================================

    public function test_admin_can_create_tracking_update_and_synchronize_shipment_status(): void
    {
        $shipment = Shipment::create([
            'shipment_number' => 'SHP-TRK-001',
            'order_id' => $this->orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Jakarta',
            'destination' => 'Surabaya',
            'status' => ShipmentStatus::READY,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.shipments.tracking.store', $shipment), [
            'status' => 'IN_TRANSIT',
            'location' => 'Rest Area KM 57 Tol Cikampek',
            'description' => 'Truk melintas lancar',
            'tracked_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect(route('admin.shipments.show', $shipment));
        $this->assertDatabaseHas('tracking_updates', [
            'shipment_id' => $shipment->id,
            'user_id' => $this->admin->id,
            'status' => 'IN_TRANSIT',
            'location' => 'Rest Area KM 57 Tol Cikampek',
        ]);

        $shipment->refresh();
        $this->assertEquals(ShipmentStatus::IN_TRANSIT, $shipment->status);
    }

    public function test_admin_can_delete_tracking_update(): void
    {
        $shipment = Shipment::create([
            'shipment_number' => 'SHP-TRK-002',
            'order_id' => $this->orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => ShipmentStatus::IN_TRANSIT,
        ]);

        $tracking = TrackingUpdate::create([
            'shipment_id' => $shipment->id,
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Gerbang Tol Pasteur',
            'description' => 'Truk keluar tol',
            'tracked_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.shipments.tracking.destroy', [$shipment, $tracking]));

        $response->assertRedirect(route('admin.shipments.show', $shipment));
        $this->assertDatabaseMissing('tracking_updates', [
            'id' => $tracking->id,
        ]);
    }

    public function test_admin_cannot_delete_tracking_update_with_mismatched_shipment(): void
    {
        $shipment1 = Shipment::create([
            'shipment_number' => 'SHP-TRK-003',
            'order_id' => $this->orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Jakarta',
            'destination' => 'Semarang',
            'status' => ShipmentStatus::IN_TRANSIT,
        ]);

        $shipment2 = Shipment::create([
            'shipment_number' => 'SHP-TRK-004',
            'order_id' => $this->orderB->id,
            'customer_id' => $this->customerB->id,
            'origin' => 'Surabaya',
            'destination' => 'Malang',
            'status' => ShipmentStatus::IN_TRANSIT,
        ]);

        $trackingOfShipment2 = TrackingUpdate::create([
            'shipment_id' => $shipment2->id,
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Sidoarjo',
            'description' => 'Melintas di Sidoarjo',
            'tracked_at' => now(),
        ]);

        // Attempting to delete tracking belonging to shipment2 using shipment1 route
        $response = $this->actingAs($this->admin)->delete(route('admin.shipments.tracking.destroy', [$shipment1, $trackingOfShipment2]));
        $response->assertNotFound();
    }

    public function test_customer_cannot_create_or_delete_tracking_update(): void
    {
        $shipment = Shipment::create([
            'shipment_number' => 'SHP-TRK-005',
            'order_id' => $this->orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Jakarta',
            'destination' => 'Bekasi',
            'status' => ShipmentStatus::READY,
        ]);

        $tracking = TrackingUpdate::create([
            'shipment_id' => $shipment->id,
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::READY,
            'location' => 'Warehouse Cakung',
            'description' => 'Barang siap muat',
            'tracked_at' => now(),
        ]);

        // Customer attempts to create tracking
        $postResponse = $this->actingAs($this->customerUserA)->post(route('admin.shipments.tracking.store', $shipment), [
            'status' => 'IN_TRANSIT',
            'location' => 'Fake Location',
        ]);
        $postResponse->assertRedirect(route('customer.dashboard'));

        // Customer attempts to delete tracking
        $delResponse = $this->actingAs($this->customerUserA)->delete(route('admin.shipments.tracking.destroy', [$shipment, $tracking]));
        $delResponse->assertRedirect(route('customer.dashboard'));
    }

    // ==========================================
    // 2. ORDER ITEM SCOPING & IDOR DEFENSE TESTS
    // ==========================================

    public function test_admin_update_order_items_scoped_properly(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-TEST-001',
            'customer_id' => $this->customerA->id,
            'order_date' => '2026-08-20',
            'status' => OrderStatus::PENDING,
        ]);

        $item1 = $order->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 10,
            'weight' => 50,
            'unit' => 'Pcs',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'order_date' => '2026-08-20',
            'status' => 'PROCESSING',
            'notes' => 'Updated order note',
            'items' => [
                [
                    'id' => $item1->id,
                    'product_id' => $this->product->id,
                    'quantity' => 25,
                    'weight' => 125,
                    'unit' => 'Pcs',
                    'notes' => 'Item 1 updated',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseHas('order_items', [
            'id' => $item1->id,
            'order_id' => $order->id,
            'quantity' => 25,
            'weight' => 125,
        ]);
    }

    public function test_admin_cannot_manipulate_foreign_order_item_in_order_update(): void
    {
        $orderA = Order::create([
            'order_number' => 'ORD-TEST-A',
            'customer_id' => $this->customerA->id,
            'order_date' => '2026-08-20',
            'status' => OrderStatus::PENDING,
        ]);

        $itemA = $orderA->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 5,
            'weight' => 25,
            'unit' => 'Pcs',
        ]);

        $orderB = Order::create([
            'order_number' => 'ORD-TEST-B',
            'customer_id' => $this->customerB->id,
            'order_date' => '2026-08-20',
            'status' => OrderStatus::PENDING,
        ]);

        $itemB = $orderB->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 100,
            'weight' => 500,
            'unit' => 'Pcs',
        ]);

        // Attempting to update Order A while passing Item B's ID in request
        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $orderA), [
            'order_date' => '2026-08-20',
            'status' => 'PROCESSING',
            'items' => [
                [
                    'id' => $itemB->id, // Foreign item ID!
                    'product_id' => $this->product->id,
                    'quantity' => 1,
                    'weight' => 1,
                    'unit' => 'Pcs',
                ],
            ],
        ]);

        $response->assertForbidden();

        // Verify Order B's item is completely unchanged
        $itemB->refresh();
        $this->assertEquals(100, $itemB->quantity);
        $this->assertEquals(500, $itemB->weight);
    }

    // ==========================================
    // 3. ORDER & SHIPMENT OWNERSHIP TESTS
    // ==========================================

    public function test_customer_cannot_view_foreign_customer_order(): void
    {
        $orderB = Order::create([
            'order_number' => 'ORD-CUST-B',
            'customer_id' => $this->customerB->id,
            'order_date' => '2026-08-20',
            'status' => OrderStatus::PROCESSING,
        ]);

        // Customer A attempts to view Customer B's order
        $response = $this->actingAs($this->customerUserA)->get(route('customer.orders.show', $orderB));
        $response->assertNotFound();
    }

    public function test_customer_cannot_view_foreign_customer_shipment(): void
    {
        $shipmentB = Shipment::create([
            'shipment_number' => 'SHP-CUST-B',
            'order_id' => $this->orderB->id,
            'customer_id' => $this->customerB->id,
            'origin' => 'Surabaya',
            'destination' => 'Bali',
            'status' => ShipmentStatus::IN_TRANSIT,
        ]);

        // Customer A attempts to view Customer B's shipment
        $response = $this->actingAs($this->customerUserA)->get(route('customer.shipments.show', $shipmentB));
        $response->assertNotFound();
    }

    // ==========================================
    // 4. ROLE & PRIVILEGE ESCALATION DEFENSE
    // ==========================================

    public function test_customer_cannot_escalate_role_via_profile_update(): void
    {
        $response = $this->actingAs($this->customerUserA)->patch(route('profile.update'), [
            'name' => 'John Hacker',
            'email' => 'majujaya@example.com',
            'role' => 'ADMIN',
            'is_admin' => 1,
        ]);

        $this->customerUserA->refresh();
        $this->assertEquals(UserRole::CUSTOMER, $this->customerUserA->role);
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->customerUserA)->get(route('admin.dashboard'));
        $response->assertRedirect(route('customer.dashboard'));
    }
}