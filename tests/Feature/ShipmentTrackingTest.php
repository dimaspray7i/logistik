<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\ShipmentStatus;
use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\TrackingUpdate;
use App\Models\User;
use App\Services\GeocodingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipmentTrackingTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customerUserA;
    private User $customerUserB;
    private Customer $customerA;
    private Customer $customerB;
    private Shipment $shipmentA;
    private Shipment $shipmentB;

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
            'company_name' => 'PT Andalas Logistik',
            'phone' => '0811111111',
            'email' => 'customera@example.com',
            'address' => 'Jl. Asal No. 10',
            'city' => 'Medan',
            'province' => 'Sumatera Utara',
            'postal_code' => '20111',
        ]);

        $this->customerB = Customer::create([
            'name' => 'Customer B',
            'company_name' => 'PT Borneo Sentosa',
            'phone' => '0822222222',
            'email' => 'customerb@example.com',
            'address' => 'Jl. Tujuan No. 20',
            'city' => 'Balikpapan',
            'province' => 'Kalimantan Timur',
            'postal_code' => '76111',
        ]);

        $this->customerUserA = User::factory()->create([
            'role' => UserRole::CUSTOMER,
            'customer_id' => $this->customerA->id,
        ]);

        $this->customerUserB = User::factory()->create([
            'role' => UserRole::CUSTOMER,
            'customer_id' => $this->customerB->id,
        ]);

        $orderA = Order::create([
            'order_number' => 'ORD-TRACK-001',
            'customer_id' => $this->customerA->id,
            'order_date' => now(),
            'status' => OrderStatus::PROCESSING,
        ]);

        $orderB = Order::create([
            'order_number' => 'ORD-TRACK-002',
            'customer_id' => $this->customerB->id,
            'order_date' => now(),
            'status' => OrderStatus::PROCESSING,
        ]);

        $this->shipmentA = Shipment::create([
            'shipment_number' => 'SHP-20260901-004',
            'order_id' => $orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Medan',
            'destination' => 'Palembang',
            'departure_date' => now()->subDays(2),
            'estimated_arrival' => now()->addDays(2),
            'total_weight' => 500,
            'status' => ShipmentStatus::READY,
        ]);

        $this->shipmentB = Shipment::create([
            'shipment_number' => 'SHP-20260901-005',
            'order_id' => $orderB->id,
            'customer_id' => $this->customerB->id,
            'origin' => 'Jakarta',
            'destination' => 'Balikpapan',
            'departure_date' => now()->subDay(),
            'estimated_arrival' => now()->addDays(3),
            'total_weight' => 300,
            'status' => ShipmentStatus::READY,
        ]);
    }

    public function test_shipment_has_tracking_code(): void
    {
        $this->assertNotEmpty($this->shipmentA->shipment_number);
        $this->assertEquals('SHP-20260901-004', $this->shipmentA->shipment_number);
    }

    public function test_shipment_can_have_multiple_tracking_updates(): void
    {
        $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::READY,
            'location' => 'Gudang Medan',
            'description' => 'Barang siap dikirim',
            'latitude' => 3.5952,
            'longitude' => 98.6722,
            'tracked_at' => now()->subHours(5),
        ]);

        $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Pekanbaru',
            'description' => 'Dalam perjalanan ke Palembang',
            'latitude' => 0.5071,
            'longitude' => 101.4478,
            'tracked_at' => now()->subHours(2),
        ]);

        $this->assertEquals(2, $this->shipmentA->trackingUpdates()->count());
    }

    public function test_tracking_update_belongs_to_correct_shipment(): void
    {
        $updateA = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Transit Pekanbaru',
            'description' => 'Barang berada di Pekanbaru',
            'latitude' => 0.5071,
            'longitude' => 101.4478,
            'tracked_at' => now(),
        ]);

        $this->assertEquals($this->shipmentA->id, $updateA->shipment_id);
        $this->assertEquals(0, $this->shipmentB->trackingUpdates()->count());
    }

    public function test_admin_can_add_tracking_update_with_coordinates(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.shipments.tracking.store', $this->shipmentA), [
                'status' => 'IN_TRANSIT',
                'location' => 'Hub Pekanbaru',
                'address' => 'Jl. Soekarno Hatta No. 10',
                'city' => 'Pekanbaru',
                'province' => 'Riau',
                'country' => 'Indonesia',
                'latitude' => 0.5071,
                'longitude' => 101.4478,
                'tracked_at' => now()->format('Y-m-d H:i:s'),
                'description' => 'Tiba di pusat distribusi Pekanbaru',
            ]);

        $response->assertRedirect(route('admin.shipments.show', $this->shipmentA));
        $this->assertDatabaseHas('tracking_updates', [
            'shipment_id' => $this->shipmentA->id,
            'location' => 'Hub Pekanbaru',
            'city' => 'Pekanbaru',
            'status' => ShipmentStatus::IN_TRANSIT->value,
        ]);

        $this->assertEquals(ShipmentStatus::IN_TRANSIT, $this->shipmentA->fresh()->status);
    }

    public function test_admin_can_add_tracking_update_with_auto_geocoding(): void
    {
        // Tanpa latitude dan longitude, sistem otomatis mendeteksi dari "Pekanbaru, Riau"
        $response = $this->actingAs($this->admin)
            ->post(route('admin.shipments.tracking.store', $this->shipmentA), [
                'status' => 'IN_TRANSIT',
                'location' => 'Pekanbaru, Riau',
                'description' => 'Transit di Pekanbaru',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tracking_updates', [
            'shipment_id' => $this->shipmentA->id,
            'location' => 'Pekanbaru, Riau',
            'city' => 'Pekanbaru',
            'country' => 'Indonesia',
        ]);

        $created = $this->shipmentA->trackingUpdates()->first();
        $this->assertNotNull($created->latitude);
        $this->assertNotNull($created->longitude);
        $this->assertEquals(0.5071, round((float) $created->latitude, 4));
    }

    public function test_admin_can_delete_tracking_update_and_sync_status(): void
    {
        $update1 = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::READY,
            'location' => 'Medan',
            'description' => 'Pesanan siap',
            'tracked_at' => now()->subHours(3),
        ]);

        $update2 = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Pekanbaru',
            'description' => 'Dalam perjalanan',
            'tracked_at' => now()->subHours(1),
        ]);

        $this->shipmentA->update(['status' => ShipmentStatus::IN_TRANSIT]);

        // Hapus update2
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.shipments.tracking.destroy', [$this->shipmentA, $update2]));

        $response->assertRedirect(route('admin.shipments.show', $this->shipmentA));
        $this->assertDatabaseMissing('tracking_updates', ['id' => $update2->id]);

        // Status shipment otomatis kembali sinkron ke status update1 (READY)
        $this->assertEquals(ShipmentStatus::READY, $this->shipmentA->fresh()->status);
    }

    public function test_admin_can_view_shipment_tracking_page(): void
    {
        $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Pekanbaru',
            'description' => 'Barang transit di Pekanbaru',
            'latitude' => 0.5071,
            'longitude' => 101.4478,
            'tracked_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.shipments.show', $this->shipmentA));

        $response->assertStatus(200);
        $response->assertSee('SHP-20260901-004');
        $response->assertSee('Pelacakan Pengiriman');
        $response->assertSee('Pekanbaru');
        $response->assertSee('Tambah Update Tracking Pengiriman');
    }

    public function test_customer_can_view_tracking_of_own_shipment(): void
    {
        $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Pekanbaru, Riau',
            'description' => 'Barang menuju tujuan',
            'latitude' => 0.5071,
            'longitude' => 101.4478,
            'tracked_at' => now(),
        ]);

        $response = $this->actingAs($this->customerUserA)
            ->get(route('customer.shipments.show', $this->shipmentA));

        $response->assertStatus(200);
        $response->assertSee('SHP-20260901-004');
        $response->assertSee('Pelacakan Pengiriman');
        $response->assertSee('Pekanbaru, Riau');
        // Customer tidak boleh melihat form tambah update tracking
        $response->assertDontSee('Tambah Update Tracking Pengiriman');
    }

    public function test_customer_cannot_view_tracking_of_other_customer_shipment(): void
    {
        // Customer A mencoba mengakses Shipment milik Customer B
        $response = $this->actingAs($this->customerUserA)
            ->get(route('customer.shipments.show', $this->shipmentB));

        // Harus 404 (karena scoped query di controller)
        $response->assertStatus(404);
    }

    public function test_customer_cannot_create_or_delete_tracking_update(): void
    {
        // 1. Policy melarang Customer membuat TrackingUpdate
        $this->assertFalse($this->customerUserA->can('create', TrackingUpdate::class));

        // 2. Route dilindungi middleware role.admin -> redirect ke customer.dashboard
        $postResponse = $this->actingAs($this->customerUserA)
            ->post(route('admin.shipments.tracking.store', $this->shipmentA), [
                'status' => 'DELIVERED',
                'location' => 'Palembang',
                'description' => 'Selesai diantar',
            ]);

        $postResponse->assertRedirect(route('customer.dashboard'));
        $this->assertEquals(0, $this->shipmentA->trackingUpdates()->count());
    }

    public function test_coordinates_validation_rules(): void
    {
        // 1. Latitude di luar range
        $res1 = $this->actingAs($this->admin)
            ->post(route('admin.shipments.tracking.store', $this->shipmentA), [
                'status' => 'IN_TRANSIT',
                'location' => 'Medan',
                'latitude' => 150,
                'longitude' => 100,
            ]);
        $res1->assertSessionHasErrors('latitude');

        // 2. Longitude di luar range
        $res2 = $this->actingAs($this->admin)
            ->post(route('admin.shipments.tracking.store', $this->shipmentA), [
                'status' => 'IN_TRANSIT',
                'location' => 'Medan',
                'latitude' => 3.5,
                'longitude' => 200,
            ]);
        $res2->assertSessionHasErrors('longitude');

        // 3. Latitude ada tapi longitude kosong (required_with)
        $res3 = $this->actingAs($this->admin)
            ->post(route('admin.shipments.tracking.store', $this->shipmentA), [
                'status' => 'IN_TRANSIT',
                'location' => 'Medan',
                'latitude' => 3.5,
                'longitude' => null,
            ]);
        $res3->assertSessionHasErrors('longitude');

        // 4. Longitude ada tapi latitude kosong (required_with)
        $res4 = $this->actingAs($this->admin)
            ->post(route('admin.shipments.tracking.store', $this->shipmentA), [
                'status' => 'IN_TRANSIT',
                'location' => 'Medan',
                'latitude' => null,
                'longitude' => 98.6,
            ]);
        $res4->assertSessionHasErrors('latitude');
    }

    public function test_tracking_updates_sorted_by_tracked_at_timestamp(): void
    {
        $time1 = now()->subDays(3);
        $time2 = now()->subDays(2);
        $time3 = now()->subDays(1);

        // Buat secara acak
        $u2 = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::READY,
            'location' => 'Titik 2',
            'description' => 'Update 2',
            'tracked_at' => $time2,
        ]);

        $u1 = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::DRAFT,
            'location' => 'Titik 1',
            'description' => 'Update 1',
            'tracked_at' => $time1,
        ]);

        $u3 = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Titik 3',
            'description' => 'Update 3',
            'tracked_at' => $time3,
        ]);

        $ordered = $this->shipmentA->trackingUpdates;
        $this->assertEquals('Titik 1', $ordered[0]->location);
        $this->assertEquals('Titik 2', $ordered[1]->location);
        $this->assertEquals('Titik 3', $ordered[2]->location);
    }

    public function test_current_location_determined_by_latest_timestamp_not_id(): void
    {
        // Masukkan update dengan ID lebih kecil tapi timestamp lebih baru
        $laterTime = now();
        $earlierTime = now()->subDays(2);

        $update1 = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Palembang (Terbaru)',
            'description' => 'Posisi terkini barang',
            'tracked_at' => $laterTime,
        ]);

        $update2 = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::READY,
            'location' => 'Medan (Lama)',
            'description' => 'Posisi awal barang',
            'tracked_at' => $earlierTime,
        ]);

        $latest = $this->shipmentA->trackingUpdates()->reorder('tracked_at', 'desc')->first();
        $this->assertEquals('Palembang (Terbaru)', $latest->location);
        $this->assertEquals($update1->id, $latest->id);
    }

    public function test_international_and_non_indonesia_coordinates_supported(): void
    {
        // Rute Internasional: Medan -> Kuala Lumpur -> Singapore -> Bangkok
        $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::READY,
            'location' => 'Medan',
            'country' => 'Indonesia',
            'description' => 'Dari Medan',
            'latitude' => 3.5952,
            'longitude' => 98.6722,
            'tracked_at' => now()->subHours(10),
        ]);

        $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Kuala Lumpur',
            'country' => 'Malaysia',
            'description' => 'Tiba di Kuala Lumpur Hub',
            'latitude' => 3.1390,
            'longitude' => 101.6869,
            'tracked_at' => now()->subHours(6),
        ]);

        $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Singapore',
            'country' => 'Singapore',
            'description' => 'Transit di Singapore',
            'latitude' => 1.3521,
            'longitude' => 103.8198,
            'tracked_at' => now()->subHours(2),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.shipments.show', $this->shipmentA));

        $response->assertStatus(200);
        $response->assertSee('Kuala Lumpur');
        $response->assertSee('Singapore');
        $response->assertSee('101.6869');
        $response->assertSee('103.8198');
    }

    public function test_shipment_without_coordinates_renders_gracefully(): void
    {
        // Buat tracking update tanpa koordinat sama sekali
        $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::READY,
            'location' => 'Gudang Pusat',
            'description' => 'Belum ada GPS',
            'latitude' => null,
            'longitude' => null,
            'tracked_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.shipments.show', $this->shipmentA));

        $response->assertStatus(200);
        $response->assertSee('Gudang Pusat');
        $response->assertSee('Koordinat Belum Lengkap');
    }

    public function test_route_coordinates_displayed_on_map_when_no_tracking_updates(): void
    {
        // Buat Route dengan koordinat Asal dan Tujuan
        $route = $this->shipmentA->route()->create([
            'distance' => 350,
            'duration' => 8,
        ]);

        $route->points()->create([
            'sequence' => 1,
            'location_name' => 'Medan Hub',
            'latitude' => 3.5952,
            'longitude' => 98.6722,
        ]);

        $route->points()->create([
            'sequence' => 2,
            'location_name' => 'Palembang Central',
            'latitude' => -2.9761,
            'longitude' => 104.7754,
            'estimated_arrival' => now()->addDays(2),
        ]);

        // Halaman admin harus menampilkan 2 titik terkoordinat pada peta
        $adminResponse = $this->actingAs($this->admin)
            ->get(route('admin.shipments.show', $this->shipmentA));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('2 Titik Terkoordinat');
        $adminResponse->assertSee('Medan Hub');
        $adminResponse->assertSee('Palembang Central');
        $adminResponse->assertSee('3.5952');
        $adminResponse->assertSee('104.7754');

        // Halaman customer juga harus menampilkan titik koordinat yang sama untuk shipment miliknya
        $customerResponse = $this->actingAs($this->customerUserA)
            ->get(route('customer.shipments.show', $this->shipmentA));
        $customerResponse->assertStatus(200);
        $customerResponse->assertSee('2 Titik Terkoordinat');
        $customerResponse->assertSee('Medan Hub');
        $customerResponse->assertSee('Palembang Central');
    }

    public function test_route_points_with_transit_displays_all_points_in_order(): void
    {
        // Buat Route dengan Asal, 2 Transit, dan Tujuan
        $route = $this->shipmentA->route()->create([
            'distance' => 900,
            'duration' => 24,
        ]);

        $route->points()->create([
            'sequence' => 1,
            'location_name' => 'Medan (Origin)',
            'latitude' => 3.5952,
            'longitude' => 98.6722,
        ]);

        $route->points()->create([
            'sequence' => 2,
            'location_name' => 'Pekanbaru (Transit 1)',
            'latitude' => 0.5071,
            'longitude' => 101.4478,
        ]);

        $route->points()->create([
            'sequence' => 3,
            'location_name' => 'Jambi (Transit 2)',
            'latitude' => -1.6101,
            'longitude' => 103.6131,
        ]);

        $route->points()->create([
            'sequence' => 4,
            'location_name' => 'Palembang (Destination)',
            'latitude' => -2.9761,
            'longitude' => 104.7754,
            'estimated_arrival' => now()->addDays(3),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.shipments.show', $this->shipmentA));

        $response->assertStatus(200);
        $response->assertSee('4 Titik Terkoordinat');
        $response->assertSee('Pekanbaru (Transit 1)');
        $response->assertSee('Jambi (Transit 2)');
    }

    public function test_admin_can_save_route_with_destination_eta_and_auto_geocoding(): void
    {
        $etaDate = now()->addDays(3)->format('Y-m-d\TH:i');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.shipments.route.store', $this->shipmentA), [
                'distance' => 600,
                'duration' => 14,
                'points' => [
                    [
                        'location_name' => 'Jakarta Hub',
                        'address' => 'Jl. Asal No. 1',
                        'latitude' => null,
                        'longitude' => null,
                    ],
                    [
                        'location_name' => 'Surabaya Hub',
                        'address' => 'Jl. Tujuan No. 2',
                        'latitude' => null,
                        'longitude' => null,
                        'estimated_arrival' => $etaDate,
                    ],
                ],
            ]);

        $response->assertRedirect(route('admin.shipments.show', $this->shipmentA));

        $this->shipmentA->refresh();
        $this->assertNotNull($this->shipmentA->route);
        $this->assertEquals(2, $this->shipmentA->route->points()->count());

        $firstPoint = $this->shipmentA->route->points()->where('sequence', 1)->first();
        $this->assertEquals('Jakarta Hub', $firstPoint->location_name);
        $this->assertNotNull($firstPoint->latitude); // Auto-geocoded to Jakarta (-6.2088)

        $secondPoint = $this->shipmentA->route->points()->where('sequence', 2)->first();
        $this->assertEquals('Surabaya Hub', $secondPoint->location_name);
        $this->assertNotNull($secondPoint->latitude); // Auto-geocoded to Surabaya (-7.2575)
    }

    public function test_admin_can_add_tracking_update_with_indonesian_field_aliases(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.shipments.tracking.store', $this->shipmentA), [
                'status' => 'IN_TRANSIT',
                'lokasi' => 'Gudang Transit Palembang',
                'alamat' => 'Jl. Kolonel Burlian No. 88',
                'kota' => 'Palembang',
                'provinsi' => 'Sumatera Selatan',
                'negara' => 'Indonesia',
                'lintang' => -2.9761,
                'bujur' => 104.7754,
                'dilacak_at' => now()->format('Y-m-d\TH:i'),
                'deskripsi' => 'Barang transit dan sedang diproses muat.',
            ]);

        $response->assertRedirect(route('admin.shipments.show', $this->shipmentA));

        $this->assertDatabaseHas('tracking_updates', [
            'shipment_id' => $this->shipmentA->id,
            'status' => 'IN_TRANSIT',
            'location' => 'Gudang Transit Palembang',
            'latitude' => -2.9761,
            'longitude' => 104.7754,
        ]);

        $this->shipmentA->refresh();
        $this->assertEquals(ShipmentStatus::IN_TRANSIT, $this->shipmentA->status);
    }

    public function test_tracking_update_foreign_key_and_data_integrity(): void
    {
        $update = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::READY,
            'location' => 'Medan Hub',
            'description' => 'Siap dikirim',
            'latitude' => 3.5952,
            'longitude' => 98.6722,
            'tracked_at' => now(),
        ]);

        $this->assertEquals($this->shipmentA->id, $update->shipment->id);
        $this->assertEquals($this->admin->id, $update->user->id);
        $this->assertTrue($update->hasCoordinates());
        $this->assertStringContainsString('Medan Hub', $update->full_location);
    }

    public function test_geocoding_service_resolves_known_locations(): void
    {
        $medan = GeocodingService::geocode('Gudang Transit Medan');
        $this->assertNotNull($medan);
        $this->assertEquals(3.5952, $medan['latitude']);
        $this->assertEquals('Indonesia', $medan['country']);

        $kl = GeocodingService::geocode('Hub Kuala Lumpur');
        $this->assertNotNull($kl);
        $this->assertEquals(3.1390, $kl['latitude']);
        $this->assertEquals('Malaysia', $kl['country']);

        $sg = GeocodingService::geocode('Singapore Port');
        $this->assertNotNull($sg);
        $this->assertEquals(1.3521, $sg['latitude']);
        $this->assertEquals('Singapore', $sg['country']);
    }

    public function test_tracking_map_renders_entire_route_with_origin_trackings_and_destination(): void
    {
        // 1. Buat Planned Route (Medan -> Palembang)
        $route = $this->shipmentA->route()->create([
            'distance' => 1200,
            'duration' => 30,
        ]);
        $route->points()->create([
            'sequence' => 1,
            'location_name' => 'Medan Origin Hub',
            'latitude' => 3.5952,
            'longitude' => 98.6722,
        ]);
        $route->points()->create([
            'sequence' => 2,
            'location_name' => 'Palembang Destination Hub',
            'latitude' => -2.9761,
            'longitude' => 104.7754,
            'estimated_arrival' => now()->addDays(3),
        ]);

        // 2. Tambahkan 2 Tracking Checkpoints
        $t1 = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Hub Pekanbaru',
            'latitude' => 0.5071,
            'longitude' => 101.4478,
            'tracked_at' => now()->subHours(10),
            'description' => 'Tiba di Hub Pekanbaru untuk sortir',
        ]);

        $t2 = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Hub Jambi',
            'latitude' => -1.6101,
            'longitude' => 103.6131,
            'tracked_at' => now()->subHours(2),
            'description' => 'Berangkat dari Hub Jambi menuju Palembang',
        ]);

        // 3. Akses halaman Admin
        $response = $this->actingAs($this->admin)
            ->get(route('admin.shipments.show', $this->shipmentA));

        $response->assertStatus(200);
        $response->assertSee('4 Titik Terkoordinat');
        $response->assertSee('Medan Origin Hub');
        $response->assertSee('Hub Pekanbaru');
        $response->assertSee('Hub Jambi');
        $response->assertSee('Palembang Destination Hub');
        $response->assertSee('Posisi Terkini');

        // Pastikan JSON data peta memuat semua titik asal, tracking, dan tujuan
        $response->assertSee('origin-point');
        $response->assertSee('dest-point');
        $response->assertSee('track-' . $t1->id);
        $response->assertSee('track-' . $t2->id);
        $response->assertSee('tracking_latest');
        $response->assertSee('traveledPath');
        $response->assertSee('plannedPath');

        // Customer juga dapat melihat rute utuh yang sama untuk shipment miliknya
        $customerResponse = $this->actingAs($this->customerUserA)
            ->get(route('customer.shipments.show', $this->shipmentA));
        $customerResponse->assertStatus(200);
        $customerResponse->assertSee('4 Titik Terkoordinat');
        $customerResponse->assertSee('Hub Jambi');
        $customerResponse->assertSee('Posisi Terkini');
    }

    public function test_timeline_is_ordered_chronologically_with_newest_on_top(): void
    {
        // Tambahkan tracking update dengan waktu acak
        $oldTrack = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::READY,
            'location' => 'Gudang Penjemputan Medan',
            'latitude' => 3.5952,
            'longitude' => 98.6722,
            'description' => 'Penjemputan paket dari pengirim',
            'tracked_at' => now()->subDays(2),
        ]);

        $newTrack = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Gudang Transit Bandar Lampung',
            'latitude' => -5.4292,
            'longitude' => 105.2625,
            'description' => 'Tiba di transit Lampung',
            'tracked_at' => now(),
        ]);

        $midTrack = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Gudang Transit Palembang',
            'latitude' => -2.9761,
            'longitude' => 104.7754,
            'description' => 'Paket sedang disortir di Palembang',
            'tracked_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.shipments.show', $this->shipmentA));

        $response->assertStatus(200);
        // Posisi Terkini harus ditandai pada update terbaru
        $response->assertSee('Gudang Transit Bandar Lampung');
        $content = $response->getContent();
        
        // Pastikan card timeline terurut dari yang terbaru ke terlama
        $posNew = strpos($content, 'timeline-card-' . $newTrack->id);
        $posMid = strpos($content, 'timeline-card-' . $midTrack->id);
        $posOld = strpos($content, 'timeline-card-' . $oldTrack->id);

        $this->assertNotFalse($posNew);
        $this->assertNotFalse($posMid);
        $this->assertNotFalse($posOld);
        $this->assertTrue($posNew < $posMid, 'Card tracking terbaru harus muncul sebelum tracking pertengahan');
        $this->assertTrue($posMid < $posOld, 'Card tracking pertengahan harus muncul sebelum tracking terlama');
    }

    public function test_clean_description_accessor_and_no_duplication(): void
    {
        $updateWithDash = new TrackingUpdate(['description' => '-']);
        $this->assertNull($updateWithDash->clean_description);

        $updateWithLegacyAddress = new TrackingUpdate(['description' => 'Barang tiba di hub | Alamat: Jl. Sudirman No. 1']);
        $this->assertEquals('Barang tiba di hub', $updateWithLegacyAddress->clean_description);

        $updateWithCleanText = new TrackingUpdate(['description' => 'Paket sedang dalam perjalanan menuju kota tujuan']);
        $this->assertEquals('Paket sedang dalam perjalanan menuju kota tujuan', $updateWithCleanText->clean_description);
    }

    public function test_origin_checkpoint_is_always_displayed_in_timeline(): void
    {
        // ShipmentA memiliki origin 'Medan' dan destination 'Palembang'
        // Tambahkan tracking update di kota transit (Pekanbaru)
        $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Hub Transit Pekanbaru',
            'latitude' => 0.5071,
            'longitude' => 101.4478,
            'description' => 'Tiba di hub transit',
            'tracked_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.shipments.show', $this->shipmentA));

        $response->assertStatus(200);
        // Pastikan node origin 'timeline-card-origin' muncul di timeline
        $response->assertSee('timeline-card-origin');
        $response->assertSee('Asal Pengiriman');
        $response->assertSee('Medan');
    }

    public function test_delivered_status_syncs_order_status_to_completed(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.shipments.tracking.store', $this->shipmentA), [
                'status' => 'DELIVERED',
                'location' => 'Palembang Destination',
                'address' => 'Jl. Merdeka No. 10',
                'city' => 'Palembang',
                'latitude' => -2.9761,
                'longitude' => 104.7754,
                'tracked_at' => now()->format('Y-m-d\TH:i'),
                'description' => 'Barang telah diterima oleh customer',
            ]);

        $response->assertRedirect(route('admin.shipments.show', $this->shipmentA));

        $this->shipmentA->refresh();
        $this->assertEquals(ShipmentStatus::DELIVERED, $this->shipmentA->status);
        $this->assertNotNull($this->shipmentA->actual_arrival);

        // Order terkait harus otomatis tersinkronisasi menjadi COMPLETED
        $order = $this->shipmentA->order;
        $order->refresh();
        $this->assertEquals(OrderStatus::COMPLETED, $order->status);
    }

    public function test_delivered_cannot_be_assigned_to_transit_route_point(): void
    {
        $route = $this->shipmentA->route()->create([
            'distance' => 800,
            'duration' => 20,
        ]);
        $originPoint = $route->points()->create([
            'sequence' => 1,
            'location_name' => 'Medan Origin',
            'latitude' => 3.5952,
            'longitude' => 98.6722,
        ]);
        $transitPoint = $route->points()->create([
            'sequence' => 2,
            'location_name' => 'Pekanbaru Transit',
            'latitude' => 0.5071,
            'longitude' => 101.4478,
        ]);
        $destPoint = $route->points()->create([
            'sequence' => 3,
            'location_name' => 'Palembang Destination',
            'latitude' => -2.9761,
            'longitude' => 104.7754,
        ]);

        // Coba assign DELIVERED pada transit point (bukan destination point)
        $response = $this->actingAs($this->admin)
            ->post(route('admin.shipments.tracking.store', $this->shipmentA), [
                'status' => 'DELIVERED',
                'route_point_id' => $transitPoint->id,
                'location' => 'Pekanbaru Transit',
                'latitude' => 0.5071,
                'longitude' => 101.4478,
                'tracked_at' => now()->format('Y-m-d\TH:i'),
                'description' => 'Salah input status terkirim di transit',
            ]);

        $response->assertSessionHasErrors(['status']);
        $this->shipmentA->refresh();
        $this->assertNotEquals(ShipmentStatus::DELIVERED, $this->shipmentA->status);
    }

    public function test_retroactive_older_tracking_does_not_downgrade_delivered_shipment(): void
    {
        // 1. Kiriman sudah DELIVERED pada jam sekarang
        $deliveryTime = now();
        $this->shipmentA->update([
            'status' => ShipmentStatus::DELIVERED,
            'actual_arrival' => $deliveryTime,
        ]);

        $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::DELIVERED,
            'location' => 'Palembang Destination',
            'latitude' => -2.9761,
            'longitude' => 104.7754,
            'description' => 'Terkirim',
            'tracked_at' => $deliveryTime,
        ]);

        // 2. Admin menginput log transit lama yang terjadi 1 hari yang lalu
        $response = $this->actingAs($this->admin)
            ->post(route('admin.shipments.tracking.store', $this->shipmentA), [
                'status' => 'IN_TRANSIT',
                'location' => 'Hub Jambi',
                'latitude' => -1.6101,
                'longitude' => 103.6131,
                'tracked_at' => $deliveryTime->copy()->subDay()->format('Y-m-d\TH:i'),
                'description' => 'Log susulan transit Jambi kemarin',
            ]);

        $response->assertRedirect(route('admin.shipments.show', $this->shipmentA));

        // 3. Status pengiriman harus TETAP DELIVERED, tidak boleh turun menjadi IN_TRANSIT
        $this->shipmentA->refresh();
        $this->assertEquals(ShipmentStatus::DELIVERED, $this->shipmentA->status);
        $this->assertNotNull($this->shipmentA->actual_arrival);
    }

    public function test_timeline_exact_item_count_formula_0_1_2_3_updates(): void
    {
        // 0 Tracking Updates -> Tepat 1 card di timeline (Origin Checkpoint)
        $res0 = $this->actingAs($this->admin)->get(route('admin.shipments.show', $this->shipmentA));
        $res0->assertStatus(200);
        $content0 = $res0->getContent();
        preg_match_all('/id="timeline-card-([^"]+)"/', $content0, $matches0);
        $this->assertCount(1, $matches0[0], 'Timeline dengan 0 updates harus memiliki tepat 1 item (Asal)');
        $this->assertEquals(['origin'], $matches0[1]);

        // 1 Tracking Update -> Tepat 2 cards di timeline (Update 1 + Origin)
        $t1 = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Hub Pekanbaru',
            'latitude' => 0.5071,
            'longitude' => 101.4478,
            'description' => 'Paket sedang transit di Pekanbaru',
            'tracked_at' => now()->subHours(5),
        ]);

        $res1 = $this->actingAs($this->admin)->get(route('admin.shipments.show', $this->shipmentA));
        $res1->assertStatus(200);
        $content1 = $res1->getContent();
        preg_match_all('/id="timeline-card-([^"]+)"/', $content1, $matches1);
        $this->assertCount(2, $matches1[0], 'Timeline dengan 1 update harus memiliki tepat 2 items (Update 1 + Asal)');
        $this->assertEquals([(string)$t1->id, 'origin'], $matches1[1]);

        // 2 Tracking Updates -> Tepat 3 cards di timeline (Update 2 + Update 1 + Origin)
        $t2 = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::IN_TRANSIT,
            'location' => 'Hub Jambi',
            'latitude' => -1.6101,
            'longitude' => 103.6131,
            'description' => 'Paket tiba di Jambi',
            'tracked_at' => now()->subHours(2),
        ]);

        $res2 = $this->actingAs($this->admin)->get(route('admin.shipments.show', $this->shipmentA));
        $res2->assertStatus(200);
        $content2 = $res2->getContent();
        preg_match_all('/id="timeline-card-([^"]+)"/', $content2, $matches2);
        $this->assertCount(3, $matches2[0], 'Timeline dengan 2 updates harus memiliki tepat 3 items (Update 2 + Update 1 + Asal)');
        $this->assertEquals([(string)$t2->id, (string)$t1->id, 'origin'], $matches2[1]);

        // 3 Tracking Updates -> Tepat 4 cards di timeline (Update 3 + Update 2 + Update 1 + Origin)
        $t3 = $this->shipmentA->trackingUpdates()->create([
            'user_id' => $this->admin->id,
            'status' => ShipmentStatus::ARRIVED,
            'location' => 'Hub Palembang',
            'latitude' => -2.9761,
            'longitude' => 104.7754,
            'description' => 'Paket tiba di Hub Palembang',
            'tracked_at' => now(),
        ]);

        $res3 = $this->actingAs($this->admin)->get(route('admin.shipments.show', $this->shipmentA));
        $res3->assertStatus(200);
        $content3 = $res3->getContent();
        preg_match_all('/id="timeline-card-([^"]+)"/', $content3, $matches3);
        $this->assertCount(4, $matches3[0], 'Timeline dengan 3 updates harus memiliki tepat 4 items (Update 3 + Update 2 + Update 1 + Asal)');
        $this->assertEquals([(string)$t3->id, (string)$t2->id, (string)$t1->id, 'origin'], $matches3[1]);
    }
}
