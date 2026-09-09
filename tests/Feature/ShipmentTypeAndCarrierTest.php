<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\ShipmentStatus;
use App\Enums\ShippingType;
use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\ExpeditionProvider;
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
    private ExpeditionProvider $providerAEI;

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

        $this->providerAEI = ExpeditionProvider::firstOrCreate(
            ['code' => 'AEI'],
            ['name' => 'AEI — PT. Antar Exprindo Indah', 'description' => 'Mitra Ekspedisi Utam', 'is_active' => true]
        );
    }

    /** 1. Membuat pengiriman ekspedisi eksternal dengan provider AEI */
    public function test_create_external_shipment_with_aei_provider(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderA->id,
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-99887766',
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => 'READY',
            'invoice_payment_status' => 'Belum Dibayar',
        ]);

        $response->assertRedirect(route('admin.shipments.index'));
        $response->assertSessionHas('success');

        $shipment = Shipment::firstWhere('order_id', $this->orderA->id);
        $this->assertNotNull($shipment);
        $this->assertEquals(ShippingType::EXTERNAL, $shipment->shipping_type);
        $this->assertEquals($this->providerAEI->id, $shipment->expedition_provider_id);
        $this->assertEquals('AEI-99887766', $shipment->tracking_number);
        $this->assertEquals('AEI-99887766', $shipment->display_code);
    }

    /** 2. Membuat pengiriman kedua menyimpan nomor resi secara independen */
    public function test_create_second_shipment_saves_independent_resi(): void
    {
        $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderA->id,
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-001',
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => 'READY',
        ]);

        $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderB->id,
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-002',
            'origin' => 'Surabaya',
            'destination' => 'Malang',
            'status' => 'READY',
        ]);

        $shipments = Shipment::orderBy('id', 'asc')->get();
        $this->assertCount(2, $shipments);
        $this->assertEquals('AEI-001', $shipments[0]->tracking_number);
        $this->assertEquals('AEI-002', $shipments[1]->tracking_number);
    }

    /** 3. Membuat pengiriman ekspedisi dengan resi asli menyimpan resi tanpa diubah */
    public function test_create_external_shipment_preserves_original_resi(): void
    {
        $originalResi = 'AEI123456789999';

        $response = $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderA->id,
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'carrier' => 'AEI',
            'tracking_number' => $originalResi,
            'origin' => 'Jakarta',
            'destination' => 'Medan',
            'status' => 'READY',
        ]);

        $response->assertRedirect(route('admin.shipments.index'));

        $shipment = Shipment::firstWhere('order_id', $this->orderA->id);
        $this->assertNotNull($shipment);
        $this->assertEquals('EXTERNAL', $shipment->shipping_type->value);
        $this->assertEquals($originalResi, $shipment->tracking_number);
        $this->assertEquals($originalResi, $shipment->display_code);
    }

    /** 4. Memilih operator eksternal tanpa provider atau nomor resi harus gagal validasi */
    public function test_external_shipment_requires_provider_and_tracking_number(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderA->id,
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => null,
            'tracking_number' => '', // Kosong -> harus gagal
            'origin' => 'Jakarta',
            'destination' => 'Medan',
            'status' => 'READY',
        ]);

        $response->assertSessionHasErrors(['expedition_provider_id', 'tracking_number']);
    }

    /** 5. Pengiriman lama berformat SHP-xxx tetap dapat dibuka & tracking tetap berjalan */
    public function test_historical_shp_shipments_remain_functional(): void
    {
        $historical = Shipment::create([
            'shipment_number' => 'SHP-20251201-099',
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-HIST-099',
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
        $custRes->assertSee('AEI-HIST-099');
    }

    /** 6. Detail pengiriman & portal pelanggan menampilkan pengenal yang benar */
    public function test_customer_portal_displays_correct_display_code(): void
    {
        $externalShipment = Shipment::create([
            'shipment_number' => ShipmentCodeGenerator::generate(),
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'carrier' => 'AEI',
            'tracking_number' => 'AEI889911',
            'order_id' => $this->orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Bogor',
            'destination' => 'Depok',
            'status' => ShipmentStatus::IN_TRANSIT,
        ]);

        $response = $this->actingAs($this->customerUserA)->get(route('customer.shipments.show', $externalShipment));
        $response->assertOk();
        $response->assertSee('AEI889911');
        $response->assertSee('AEI');
    }

    /** 7. Pencarian dapat menemukan pengiriman berdasarkan kode internal maupun resi eksternal */
    public function test_search_finds_shipments_by_internal_code_or_external_resi(): void
    {
        $shipment1 = Shipment::create([
            'shipment_number' => 'SHP-2026-990001',
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-TRACK-001',
            'order_id' => $this->orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => ShipmentStatus::READY,
        ]);

        $shipment2 = Shipment::create([
            'shipment_number' => 'SHP-2026-990002',
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-TRACK-002',
            'order_id' => $this->orderB->id,
            'customer_id' => $this->customerB->id,
            'origin' => 'Surabaya',
            'destination' => 'Yogyakarta',
            'status' => ShipmentStatus::READY,
        ]);

        // Cari berdasarkan resi eksternal
        $resExt = $this->actingAs($this->admin)->get(route('admin.shipments.index', ['search' => 'AEI-TRACK-002']));
        $resExt->assertOk();
        $resExt->assertSee('AEI-TRACK-002');
        $resExt->assertDontSee('AEI-TRACK-001');
    }

    /** 8. Otorisasi tetap ketat: Customer B tidak dapat mengakses pengiriman Customer A */
    public function test_customer_authorization_remains_strict(): void
    {
        $shipmentA = Shipment::create([
            'shipment_number' => 'SHP-2026-111111',
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-111111',
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

