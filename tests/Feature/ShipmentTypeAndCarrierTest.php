<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\ShipmentStatus;
use App\Enums\ShippingType;
use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\User;
use App\Services\ShipmentCodeGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipmentTypeAndCarrierTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customerUserA;
    private User $customerUserB;
    private Customer $customerA;
    private Customer $customerB;
    private Order $orderA;
    private Order $orderB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'role' => UserRole::ADMIN,
            'customer_id' => null,
        ]);

        $this->customerA = Customer::create([
            'name' => 'Customer A',
            'company_name' => 'PT Nusantara Cargo',
            'phone' => '081234567890',
            'email' => 'customera@example.com',
            'address' => 'Jl. Merdeka No. 1',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'postal_code' => '10110',
        ]);

        $this->customerB = Customer::create([
            'name' => 'Customer B',
            'company_name' => 'PT Swadaya Transport',
            'phone' => '089876543210',
            'email' => 'customerb@example.com',
            'address' => 'Jl. Pemuda No. 2',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'postal_code' => '60111',
        ]);

        $this->customerUserA = User::factory()->create([
            'role' => UserRole::CUSTOMER,
            'customer_id' => $this->customerA->id,
        ]);

        $this->customerUserB = User::factory()->create([
            'role' => UserRole::CUSTOMER,
            'customer_id' => $this->customerB->id,
        ]);

        $this->orderA = Order::create([
            'order_number' => 'ORD-TEST-001',
            'customer_id' => $this->customerA->id,
            'order_date' => now(),
            'status' => OrderStatus::PENDING,
        ]);

        $this->orderB = Order::create([
            'order_number' => 'ORD-TEST-002',
            'customer_id' => $this->customerB->id,
            'order_date' => now(),
            'status' => OrderStatus::PENDING,
        ]);
    }

    /** 1. Membuat pengiriman internal menghasilkan kode PKM-YYYY-000001 (bukan SHP) */
    public function test_create_internal_shipment_generates_custom_prefix_code(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderA->id,
            'shipping_type' => 'INTERNAL',
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => 'READY',
            'invoice_payment_status' => 'Belum Dibayar',
        ]);

        $response->assertRedirect(route('admin.shipments.index'));
        $response->assertSessionHas('success');

        $shipment = Shipment::firstWhere('order_id', $this->orderA->id);
        $this->assertNotNull($shipment);
        $this->assertStringStartsWith('PKM-', $shipment->shipment_number);
        $this->assertFalse(str_starts_with($shipment->shipment_number, 'SHP-'));
        $this->assertEquals(ShippingType::INTERNAL, $shipment->shipping_type);
        $this->assertNull($shipment->carrier);
        $this->assertNull($shipment->tracking_number);
        $this->assertEquals($shipment->shipment_number, $shipment->display_code);
    }

    /** 2. Membuat pengiriman internal kedua menghasilkan nomor berbeda secara berurutan */
    public function test_create_second_internal_shipment_generates_consecutive_number(): void
    {
        $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderA->id,
            'shipping_type' => 'INTERNAL',
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => 'READY',
        ]);

        $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderB->id,
            'shipping_type' => 'INTERNAL',
            'origin' => 'Surabaya',
            'destination' => 'Malang',
            'status' => 'READY',
        ]);

        $shipments = Shipment::orderBy('id', 'asc')->get();
        $this->assertCount(2, $shipments);

        $num1 = $shipments[0]->shipment_number;
        $num2 = $shipments[1]->shipment_number;

        $this->assertNotEquals($num1, $num2);

        $parts1 = explode('-', $num1);
        $parts2 = explode('-', $num2);
        $seq1 = (int) end($parts1);
        $seq2 = (int) end($parts2);
        $this->assertEquals($seq1 + 1, $seq2);
    }

    /** 3. Membuat pengiriman JNE dengan resi asli menyimpan resi tanpa diubah */
    public function test_create_external_shipment_preserves_original_resi(): void
    {
        $originalResi = 'JNE123456789999';

        $response = $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderA->id,
            'shipping_type' => 'EXTERNAL',
            'carrier' => 'JNE',
            'tracking_number' => $originalResi,
            'origin' => 'Jakarta',
            'destination' => 'Medan',
            'status' => 'READY',
        ]);

        $response->assertRedirect(route('admin.shipments.index'));

        $shipment = Shipment::firstWhere('order_id', $this->orderA->id);
        $this->assertNotNull($shipment);
        $this->assertEquals('EXTERNAL', $shipment->shipping_type->value);
        $this->assertEquals('JNE', $shipment->carrier);
        $this->assertEquals($originalResi, $shipment->tracking_number);
        $this->assertEquals($originalResi, $shipment->display_code);
    }

    /** 4. Memilih operator eksternal tanpa nomor resi harus gagal validasi */
    public function test_external_shipment_requires_carrier_and_tracking_number(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderA->id,
            'shipping_type' => 'EXTERNAL',
            'carrier' => 'JNE',
            'tracking_number' => '', // Kosong -> harus gagal
            'origin' => 'Jakarta',
            'destination' => 'Medan',
            'status' => 'READY',
        ]);

        $response->assertSessionHasErrors(['tracking_number']);
    }

    /** 5. Pengiriman internal tidak memerlukan operator eksternal */
    public function test_internal_shipment_does_not_require_external_tracking(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderA->id,
            'shipping_type' => 'INTERNAL',
            'carrier' => null,
            'tracking_number' => null,
            'origin' => 'Semarang',
            'destination' => 'Solo',
            'status' => 'READY',
        ]);

        $response->assertRedirect(route('admin.shipments.index'));
        $this->assertDatabaseHas('shipments', [
            'order_id' => $this->orderA->id,
            'shipping_type' => 'INTERNAL',
            'carrier' => null,
            'tracking_number' => null,
        ]);
    }

    /** 6. Pengiriman lama berformat SHP-xxx tetap dapat dibuka & tracking tetap berjalan */
    public function test_historical_shp_shipments_remain_functional(): void
    {
        $historical = Shipment::create([
            'shipment_number' => 'SHP-20251201-099',
            'shipping_type' => 'INTERNAL',
            'order_id' => $this->orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Denpasar',
            'destination' => 'Mataram',
            'status' => ShipmentStatus::IN_TRANSIT,
        ]);

        // Buka detail admin
        $adminRes = $this->actingAs($this->admin)->get(route('admin.shipments.show', $historical));
        $adminRes->assertOk();
        $adminRes->assertSee('SHP-20251201-099');

        // Buka portal customer
        $custRes = $this->actingAs($this->customerUserA)->get(route('customer.shipments.show', $historical));
        $custRes->assertOk();
        $custRes->assertSee('SHP-20251201-099');
    }

    /** 7. Detail pengiriman & portal pelanggan menampilkan pengenal yang benar */
    public function test_customer_portal_displays_correct_display_code(): void
    {
        $externalShipment = Shipment::create([
            'shipment_number' => ShipmentCodeGenerator::generate(),
            'shipping_type' => 'EXTERNAL',
            'carrier' => 'SiCepat',
            'tracking_number' => 'SICEPAT889911',
            'order_id' => $this->orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Bogor',
            'destination' => 'Depok',
            'status' => ShipmentStatus::IN_TRANSIT,
        ]);

        $response = $this->actingAs($this->customerUserA)->get(route('customer.shipments.show', $externalShipment));
        $response->assertOk();
        $response->assertSee('SICEPAT889911');
        $response->assertSee('SiCepat');
    }

    /** 8. Pencarian dapat menemukan pengiriman berdasarkan kode internal maupun resi eksternal */
    public function test_search_finds_shipments_by_internal_code_or_external_resi(): void
    {
        $shipmentInternal = Shipment::create([
            'shipment_number' => 'PKM-2026-990001',
            'shipping_type' => 'INTERNAL',
            'order_id' => $this->orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => ShipmentStatus::READY,
        ]);

        $shipmentExternal = Shipment::create([
            'shipment_number' => 'PKM-2026-990002',
            'shipping_type' => 'EXTERNAL',
            'carrier' => 'Pos Indonesia',
            'tracking_number' => 'POS9988776655',
            'order_id' => $this->orderB->id,
            'customer_id' => $this->customerB->id,
            'origin' => 'Surabaya',
            'destination' => 'Yogyakarta',
            'status' => ShipmentStatus::READY,
        ]);

        // Cari berdasarkan resi eksternal
        $resExt = $this->actingAs($this->admin)->get(route('admin.shipments.index', ['search' => 'POS9988776655']));
        $resExt->assertOk();
        $resExt->assertSee('POS9988776655');
        $resExt->assertDontSee('PKM-2026-990001');

        // Cari berdasarkan kode internal
        $resInt = $this->actingAs($this->admin)->get(route('admin.shipments.index', ['search' => 'PKM-2026-990001']));
        $resInt->assertOk();
        $resInt->assertSee('PKM-2026-990001');
        $resInt->assertDontSee('POS9988776655');
    }

    /** 9. Otorisasi tetap ketat: Customer B tidak dapat mengakses pengiriman Customer A */
    public function test_customer_authorization_remains_strict(): void
    {
        $shipmentA = Shipment::create([
            'shipment_number' => 'PKM-2026-111111',
            'shipping_type' => 'INTERNAL',
            'order_id' => $this->orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Jakarta',
            'destination' => 'Bogor',
            'status' => ShipmentStatus::READY,
        ]);

        $response = $this->actingAs($this->customerUserB)->get(route('customer.shipments.show', $shipmentA));
        $response->assertStatus(404);
    }
}
