<?php

namespace Tests\Feature;

use App\Enums\DocumentType;
use App\Enums\ShipmentStatus;
use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Driver;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityCriticalHardeningTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customerUserA;
    private User $customerUserB;
    private Customer $customerA;
    private Customer $customerB;
    private \App\Models\Order $orderA;
    private \App\Models\Order $orderB;

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

        $this->orderA = \App\Models\Order::create([
            'order_number' => 'ORD-CRIT-A',
            'customer_id' => $this->customerA->id,
            'order_date' => now(),
            'status' => \App\Enums\OrderStatus::PROCESSING,
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

        $this->orderB = \App\Models\Order::create([
            'order_number' => 'ORD-CRIT-B',
            'customer_id' => $this->customerB->id,
            'order_date' => now(),
            'status' => \App\Enums\OrderStatus::PROCESSING,
        ]);

        $this->customerUserB = User::factory()->create([
            'role' => UserRole::CUSTOMER,
            'customer_id' => $this->customerB->id,
        ]);
    }

    public function test_guest_cannot_access_private_document(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('test_resi.pdf', 100, 'application/pdf');
        $path = $file->store('documents', 'local');

        $shipment = Shipment::create([
            'shipment_number' => 'SHP-TEST-001',
            'order_id' => $this->orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => ShipmentStatus::READY,
        ]);

        $document = Document::create([
            'shipment_id' => $shipment->id,
            'user_id' => $this->admin->id,
            'type' => DocumentType::RESI,
            'file_path' => $path,
            'file_name' => 'test_resi.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 102400,
        ]);

        $response = $this->get(route('documents.show', $document));
        $response->assertRedirect(route('login'));
    }

    public function test_customer_can_access_their_own_document(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('resi_customer_a.pdf', 100, 'application/pdf');
        $path = $file->store('documents', 'local');

        $shipment = Shipment::create([
            'shipment_number' => 'SHP-TEST-002',
            'order_id' => $this->orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'status' => ShipmentStatus::READY,
        ]);

        $document = Document::create([
            'shipment_id' => $shipment->id,
            'user_id' => $this->admin->id,
            'type' => DocumentType::RESI,
            'file_path' => $path,
            'file_name' => 'resi_customer_a.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 102400,
        ]);

        $response = $this->actingAs($this->customerUserA)->get(route('documents.show', $document));
        $response->assertOk();
    }

    public function test_customer_cannot_access_other_customer_document(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('resi_customer_b.pdf', 100, 'application/pdf');
        $path = $file->store('documents', 'local');

        $shipmentB = Shipment::create([
            'shipment_number' => 'SHP-TEST-003',
            'order_id' => $this->orderB->id,
            'customer_id' => $this->customerB->id,
            'origin' => 'Surabaya',
            'destination' => 'Malang',
            'status' => ShipmentStatus::READY,
        ]);

        $documentB = Document::create([
            'shipment_id' => $shipmentB->id,
            'user_id' => $this->admin->id,
            'type' => DocumentType::SURAT_JALAN,
            'file_path' => $path,
            'file_name' => 'resi_customer_b.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 102400,
        ]);

        $response = $this->actingAs($this->customerUserA)->get(route('documents.show', $documentB));
        $response->assertForbidden();
    }

    public function test_admin_can_access_any_valid_document(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('admin_view_doc.pdf', 100, 'application/pdf');
        $path = $file->store('documents', 'local');

        $shipment = Shipment::create([
            'shipment_number' => 'SHP-TEST-004',
            'order_id' => $this->orderB->id,
            'customer_id' => $this->customerB->id,
            'origin' => 'Surabaya',
            'destination' => 'Malang',
            'status' => ShipmentStatus::READY,
        ]);

        $document = Document::create([
            'shipment_id' => $shipment->id,
            'user_id' => $this->admin->id,
            'type' => DocumentType::FOTO_BARANG,
            'file_path' => $path,
            'file_name' => 'admin_view_doc.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 102400,
        ]);

        $response = $this->actingAs($this->admin)->get(route('documents.show', $document));
        $response->assertOk();
    }

    public function test_admin_upload_document_stores_to_private_storage(): void
    {
        Storage::fake('local');

        $shipment = Shipment::create([
            'shipment_number' => 'SHP-TEST-005',
            'order_id' => $this->orderA->id,
            'customer_id' => $this->customerA->id,
            'origin' => 'Jakarta',
            'destination' => 'Semarang',
            'status' => ShipmentStatus::READY,
        ]);

        $file = UploadedFile::fake()->create('surat_jalan.pdf', 200, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.shipments.documents.store', $shipment), [
            'file' => $file,
            'title' => 'Surat Jalan No 123',
            'type' => 'SURAT_JALAN',
        ]);

        $response->assertRedirect(route('admin.shipments.show', $shipment));
        $this->assertDatabaseHas('documents', [
            'shipment_id' => $shipment->id,
            'type' => 'SURAT_JALAN',
            'file_name' => 'surat_jalan.pdf',
        ]);
    }

    public function test_admin_can_update_customer(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.customers.update', $this->customerA), [
            'name' => 'John Doe Updated',
            'company_name' => 'PT Maju Jaya Abadi',
            'phone' => '08111222333',
            'email' => 'updated@majujaya.com',
            'address' => 'Jl. Baru No. 10',
            'city' => 'Jakarta Pusat',
            'province' => 'DKI Jakarta',
            'postal_code' => '10120',
            'notes' => 'Customer prioritas',
        ]);

        $response->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseHas('customers', [
            'id' => $this->customerA->id,
            'company_name' => 'PT Maju Jaya Abadi',
            'name' => 'John Doe Updated',
        ]);
    }

    public function test_admin_can_create_and_update_vehicle(): void
    {
        $createResponse = $this->actingAs($this->admin)->post(route('admin.vehicles.store'), [
            'plate_number' => 'B 1234 CD',
            'vehicle_type' => 'Truck',
            'brand' => 'Hino',
            'capacity' => 5000,
            'status' => 'AVAILABLE',
            'notes' => 'Kondisi prima',
        ]);

        $createResponse->assertRedirect(route('admin.vehicles.index'));
        $this->assertDatabaseHas('vehicles', [
            'plate_number' => 'B 1234 CD',
            'brand' => 'Hino',
        ]);

        $vehicle = Vehicle::where('plate_number', 'B 1234 CD')->first();

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.vehicles.update', $vehicle), [
            'plate_number' => 'B 1234 CD',
            'vehicle_type' => 'Truck Wingbox',
            'brand' => 'Hino Dutro',
            'capacity' => 6000,
            'status' => 'IN_USE',
            'notes' => 'Baru diservis',
        ]);

        $updateResponse->assertRedirect(route('admin.vehicles.index'));
        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'vehicle_type' => 'Truck Wingbox',
            'brand' => 'Hino Dutro',
            'capacity' => 6000,
        ]);
    }

    public function test_admin_can_create_driver(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.drivers.store'), [
            'name' => 'Budi Santoso',
            'phone' => '081299887766',
            'license_number' => 'SIM-B2-998877',
            'status' => 'ACTIVE',
        ]);

        $response->assertRedirect(route('admin.drivers.index'));
        $this->assertDatabaseHas('drivers', [
            'name' => 'Budi Santoso',
            'license_number' => 'SIM-B2-998877',
        ]);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::create([
            'sku' => 'PRD-001',
            'name' => 'Kardus Box Ukuran L',
            'description' => 'Box packing standar',
            'unit' => 'Pcs',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'sku' => 'PRD-001',
            'name' => 'Kardus Box Ukuran L Tebal',
            'description' => 'Box packing premium tebal',
            'unit' => 'Pcs',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Kardus Box Ukuran L Tebal',
            'description' => 'Box packing premium tebal',
        ]);
    }

    public function test_customer_cannot_update_product(): void
    {
        $product = Product::create([
            'sku' => 'PRD-002',
            'name' => 'Pallet Kayu',
            'description' => 'Pallet kayu standar',
            'unit' => 'Unit',
        ]);

        $response = $this->actingAs($this->customerUserA)->put(route('admin.products.update', $product), [
            'sku' => 'PRD-002',
            'name' => 'Pallet Kayu Hacked',
            'description' => 'Pallet kayu hacked',
            'unit' => 'Unit',
        ]);

        $response->assertRedirect(route('customer.dashboard'));
        $this->assertDatabaseMissing('products', [
            'name' => 'Pallet Kayu Hacked',
        ]);
    }
}