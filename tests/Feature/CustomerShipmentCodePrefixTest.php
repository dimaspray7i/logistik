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

class CustomerShipmentCodePrefixTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customerUserAdijaya;
    private User $customerUserMajuJaya;
    private Customer $adijaya;
    private Customer $majuJaya;
    private Order $orderAdijaya1;
    private Order $orderAdijaya2;
    private Order $orderMajuJaya1;
    private ExpeditionProvider $providerAEI;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'role' => UserRole::ADMIN,
            'customer_id' => null,
        ]);

        $this->providerAEI = ExpeditionProvider::firstOrCreate(
            ['code' => 'AEI'],
            ['name' => 'AEI — PT. Antar Exprindo Indah', 'is_active' => true]
        );

        // PT Adijaya dengan prefix ADJ
        $this->adijaya = Customer::create([
            'name' => 'Budi Adijaya',
            'company_name' => 'PT Adijaya',
            'shipment_code_prefix' => 'adj', // Akan otomatis dikonversi ke UPPERCASE (ADJ)
            'phone' => '0811111111',
            'email' => 'adijaya@example.com',
            'address' => 'Jl. Adijaya No. 1',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'postal_code' => '10110',
        ]);

        // PT Maju Jaya dengan prefix MJY
        $this->majuJaya = Customer::create([
            'name' => 'Siti Maju',
            'company_name' => 'PT Maju Jaya',
            'shipment_code_prefix' => 'MJY',
            'phone' => '0822222222',
            'email' => 'majujaya@example.com',
            'address' => 'Jl. Maju No. 2',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'postal_code' => '60111',
        ]);

        $this->customerUserAdijaya = User::factory()->create([
            'role' => UserRole::CUSTOMER,
            'customer_id' => $this->adijaya->id,
        ]);

        $this->customerUserMajuJaya = User::factory()->create([
            'role' => UserRole::CUSTOMER,
            'customer_id' => $this->majuJaya->id,
        ]);

        $this->orderAdijaya1 = Order::create([
            'order_number' => 'ORD-ADJ-001',
            'customer_id' => $this->adijaya->id,
            'order_date' => now(),
            'status' => OrderStatus::PENDING,
        ]);

        $this->orderAdijaya2 = Order::create([
            'order_number' => 'ORD-ADJ-002',
            'customer_id' => $this->adijaya->id,
            'order_date' => now(),
            'status' => OrderStatus::PENDING,
        ]);

        $this->orderMajuJaya1 = Order::create([
            'order_number' => 'ORD-MJY-001',
            'customer_id' => $this->majuJaya->id,
            'order_date' => now(),
            'status' => OrderStatus::PENDING,
        ]);
    }

    /** 1. Awalan otomatis dikonversi menjadi huruf besar (UPPERCASE) */
    public function test_customer_prefix_is_automatically_converted_to_uppercase(): void
    {
        $this->assertEquals('ADJ', $this->adijaya->fresh()->shipment_code_prefix);
    }

    /** 2. PT Adijaya dengan prefix ADJ menghasilkan kode ADJ-19001 */
    public function test_pt_adijaya_generates_adj_19001_on_first_shipment(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderAdijaya1->id,
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-TEST-001',
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => 'READY',
        ]);

        $response->assertRedirect(route('admin.shipments.index'));

        $shipment = Shipment::firstWhere('order_id', $this->orderAdijaya1->id);
        $this->assertNotNull($shipment);
        $this->assertEquals('ADJ-19001', $shipment->shipment_number);
    }

    /** 3. Pengiriman kedua untuk PT Adijaya menghasilkan ADJ-19002 */
    public function test_pt_adijaya_second_shipment_generates_adj_19002(): void
    {
        $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderAdijaya1->id,
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-TEST-001',
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => 'READY',
        ]);

        $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderAdijaya2->id,
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-TEST-002',
            'origin' => 'Jakarta',
            'destination' => 'Semarang',
            'status' => 'READY',
        ]);

        $shipment1 = Shipment::firstWhere('order_id', $this->orderAdijaya1->id);
        $shipment2 = Shipment::firstWhere('order_id', $this->orderAdijaya2->id);

        $this->assertEquals('ADJ-19001', $shipment1->shipment_number);
        $this->assertEquals('ADJ-19002', $shipment2->shipment_number);
    }

    /** 4. Pelanggan lain PT Maju Jaya dengan awalan MJY memiliki urutan sendiri (MJY-19001) */
    public function test_pt_maju_jaya_has_independent_sequence_mjy_19001(): void
    {
        // PT Adijaya buat 2 pengiriman -> ADJ-19001, ADJ-19002
        $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderAdijaya1->id,
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-TEST-001',
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => 'READY',
        ]);

        // PT Maju Jaya buat pengiriman -> Harus MJY-19001, bukan MJY-19003!
        $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderMajuJaya1->id,
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-TEST-003',
            'origin' => 'Surabaya',
            'destination' => 'Malang',
            'status' => 'READY',
        ]);

        $shipmentMajuJaya = Shipment::firstWhere('order_id', $this->orderMajuJaya1->id);
        $this->assertNotNull($shipmentMajuJaya);
        $this->assertEquals('MJY-19001', $shipmentMajuJaya->shipment_number);
    }

    /** 5. Customer tanpa prefix menggunakan fallback prefix aman tanpa SHP */
    public function test_customer_without_prefix_uses_safe_fallback_prefix(): void
    {
        $noPrefixCustomer = Customer::create([
            'name' => 'No Prefix Customer',
            'company_name' => 'PT Tanpa Awalan',
            'shipment_code_prefix' => null,
            'phone' => '0833333333',
            'address' => 'Jl. Polos No. 3',
            'city' => 'Medan',
            'province' => 'Sumatera Utara',
        ]);

        $orderNoPrefix = Order::create([
            'order_number' => 'ORD-NOPREFIX-001',
            'customer_id' => $noPrefixCustomer->id,
            'order_date' => now(),
            'status' => OrderStatus::PENDING,
        ]);

        $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $orderNoPrefix->id,
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-NOPREFIX-001',
            'origin' => 'Medan',
            'destination' => 'Padang',
            'status' => 'READY',
        ]);

        $shipment = Shipment::firstWhere('order_id', $orderNoPrefix->id);
        $this->assertNotNull($shipment);
        $this->assertFalse(str_starts_with($shipment->shipment_number, 'SHP-'));
        $this->assertStringStartsWith(config('shipment.prefix', 'PKM') . '-', $shipment->shipment_number);
    }

    /** 6. Validation menolak prefix duplikat */
    public function test_duplicate_prefix_is_rejected_by_customer_validation(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.customers.store'), [
            'name' => 'Budi Dua',
            'company_name' => 'PT Adijaya Tirta',
            'shipment_code_prefix' => 'ADJ', // Sudah dipakai oleh PT Adijaya
            'phone' => '0899999999',
            'address' => 'Jl. Alamat 2',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
        ]);

        $response->assertSessionHasErrors(['shipment_code_prefix']);
    }

    /** 7. Pengguna tidak dapat memalsukan shipment_number via HTTP request */
    public function test_user_cannot_spoof_fake_shipment_number_via_request(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderAdijaya1->id,
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-SPOOF-001',
            'shipment_number' => 'FAKE-CODE-999', // Payload jahat dipasang pengguna
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => 'READY',
        ]);

        $shipment = Shipment::firstWhere('order_id', $this->orderAdijaya1->id);
        $this->assertNotNull($shipment);
        // Tetap harus ADJ-19001 (prefix PT Adijaya dari database server), bukan FAKE-CODE-999
        $this->assertEquals('ADJ-19001', $shipment->shipment_number);
    }

    /** 8. Pengiriman eksternal menyimpan provider dan resi */
    public function test_external_shipment_saves_provider_and_resi(): void
    {
        $this->actingAs($this->admin)->post(route('admin.shipments.store'), [
            'order_id' => $this->orderAdijaya1->id,
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'carrier' => 'AEI',
            'tracking_number' => 'AEI88776655',
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => 'READY',
        ]);

        $shipment = Shipment::firstWhere('order_id', $this->orderAdijaya1->id);
        $this->assertNotNull($shipment);
        $this->assertEquals('AEI88776655', $shipment->display_code);
        $this->assertEquals('AEI88776655', $shipment->tracking_number);
    }

    /** 9. Historical SHP shipments remain safe and searchable */
    public function test_historical_shp_shipments_remain_safe_and_searchable(): void
    {
        $shp = Shipment::create([
            'shipment_number' => 'SHP-123456',
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-123456',
            'order_id' => $this->orderAdijaya1->id,
            'customer_id' => $this->adijaya->id,
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => ShipmentStatus::READY,
        ]);

        $res = $this->actingAs($this->admin)->get(route('admin.shipments.index', ['search' => 'SHP-123456']));
        $res->assertOk();
        $res->assertSee('SHP-123456');
    }

    /** 10. Pencarian menemukan ADJ-19001 dan MJY-19001 */
    public function test_search_finds_adj_and_mjy_shipments(): void
    {
        $shipmentAdj = Shipment::create([
            'shipment_number' => 'ADJ-19001',
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-ADJ-001',
            'order_id' => $this->orderAdijaya1->id,
            'customer_id' => $this->adijaya->id,
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => ShipmentStatus::READY,
        ]);

        $shipmentMjy = Shipment::create([
            'shipment_number' => 'MJY-19001',
            'shipping_type' => 'EXTERNAL',
            'expedition_provider_id' => $this->providerAEI->id,
            'tracking_number' => 'AEI-MJY-001',
            'order_id' => $this->orderMajuJaya1->id,
            'customer_id' => $this->majuJaya->id,
            'origin' => 'Surabaya',
            'destination' => 'Malang',
            'status' => ShipmentStatus::READY,
        ]);

        $res = $this->actingAs($this->admin)->get(route('admin.shipments.index', ['search' => 'ADJ-19001']));
        $res->assertOk();
        $res->assertSee('ADJ-19001');
        $res->assertDontSee('MJY-19001');
    }
}

