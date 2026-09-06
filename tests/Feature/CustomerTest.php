<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Customer $customer1;
    private Customer $customer2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'role' => UserRole::ADMIN,
        ]);

        $this->customer1 = Customer::create([
            'name' => 'Budi Santos',
            'company_name' => 'PT Maju Bersama',
            'shipment_code_prefix' => 'MJB',
            'phone' => '081234567890',
            'email' => 'contact@majubersama.com',
            'address' => 'Jl. Merdeka No. 10',
            'city' => 'Jakarta Pusat',
            'province' => 'DKI Jakarta',
            'postal_code' => '10110',
            'notes' => 'Catatan awal',
        ]);

        $this->customer2 = Customer::create([
            'name' => 'Siti Aminah',
            'company_name' => 'PT Adijaya Sentosa',
            'shipment_code_prefix' => 'ADJ',
            'phone' => '089876543210',
            'email' => 'contact@adijaya.com',
            'address' => 'Jl. Sudirman No. 5',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'postal_code' => '60111',
        ]);
    }

    /** Admin dapat membuka halaman edit customer */
    public function test_admin_can_view_edit_customer_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.customers.edit', $this->customer1));

        $response->assertOk();
        $response->assertSee('PT Maju Bersama');
        $response->assertSee('MJB');
    }

    /** Admin dapat memperbarui detail customer */
    public function test_admin_can_update_customer_details(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.customers.update', $this->customer1), [
                'name' => 'Budi Santoso Updated',
                'company_name' => 'PT Maju Bersama Sukses',
                'shipment_code_prefix' => 'MBS',
                'phone' => '081299998888',
                'email' => 'info@majubersama.com',
                'address' => 'Jl. Merdeka Barat No. 12',
                'city' => 'Jakarta Barat',
                'province' => 'DKI Jakarta',
                'postal_code' => '11220',
                'notes' => 'Catatan diperbarui',
            ]);

        $response->assertRedirect(route('admin.customers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('customers', [
            'id' => $this->customer1->id,
            'name' => 'Budi Santoso Updated',
            'company_name' => 'PT Maju Bersama Sukses',
            'shipment_code_prefix' => 'MBS',
            'phone' => '081299998888',
            'email' => 'info@majubersama.com',
            'address' => 'Jl. Merdeka Barat No. 12',
            'city' => 'Jakarta Barat',
            'province' => 'DKI Jakarta',
            'postal_code' => '11220',
            'notes' => 'Catatan diperbarui',
        ]);
    }

    /** Memperbarui customer dengan mempertahankan prefix yang sama (Rule::unique ignore) */
    public function test_admin_can_update_customer_keeping_same_prefix(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.customers.update', $this->customer1), [
                'name' => 'Budi Santoso',
                'company_name' => 'PT Maju Bersama',
                'shipment_code_prefix' => 'MJB', // Tetap MJB
                'phone' => '081234567890',
                'email' => 'contact@majubersama.com',
                'address' => 'Jl. Merdeka No. 10',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'postal_code' => '10110',
            ]);

        $response->assertRedirect(route('admin.customers.index'));
        $response->assertSessionHas('success');
        $this->assertEquals('MJB', $this->customer1->fresh()->shipment_code_prefix);
    }

    /** Memperbarui customer dengan mengosongkan prefix (nullable) */
    public function test_admin_can_update_customer_clearing_prefix(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.customers.update', $this->customer1), [
                'name' => 'Budi Santoso',
                'company_name' => 'PT Maju Bersama',
                'shipment_code_prefix' => '', // Dikosongkan
                'phone' => '081234567890',
                'email' => 'contact@majubersama.com',
                'address' => 'Jl. Merdeka No. 10',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'postal_code' => '10110',
            ]);

        $response->assertRedirect(route('admin.customers.index'));
        $response->assertSessionHas('success');
        $this->assertNull($this->customer1->fresh()->shipment_code_prefix);
    }

    /** Input prefix huruf kecil otomatis disanitasi menjadi huruf besar (UPPERCASE) */
    public function test_admin_can_update_customer_with_lowercase_prefix_sanitized(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.customers.update', $this->customer1), [
                'name' => 'Budi Santoso',
                'company_name' => 'PT Maju Bersama',
                'shipment_code_prefix' => 'mjb2',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 10',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
            ]);

        $response->assertRedirect(route('admin.customers.index'));
        $this->assertEquals('MJB2', $this->customer1->fresh()->shipment_code_prefix);
    }

    /** Memperbarui customer dengan prefix yang sudah dipakai customer lain ditolak (422) */
    public function test_cannot_update_customer_with_duplicate_prefix_belonging_to_another_customer(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.customers.update', $this->customer1), [
                'name' => 'Budi Santoso',
                'company_name' => 'PT Maju Bersama',
                'shipment_code_prefix' => 'ADJ', // Prefix milik customer2
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 10',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
            ]);

        $response->assertSessionHasErrors(['shipment_code_prefix']);
    }

    /** Menyinkronkan nama User milik customer ketika nama customer diperbarui */
    public function test_syncs_user_name_when_customer_updated(): void
    {
        $user = User::factory()->create([
            'name' => 'Old User Name',
            'role' => UserRole::CUSTOMER,
            'customer_id' => $this->customer1->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.customers.update', $this->customer1), [
                'name' => 'New Customer Name',
                'company_name' => 'PT Maju Bersama',
                'shipment_code_prefix' => 'MJB',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 10',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
            ]);

        $response->assertRedirect(route('admin.customers.index'));
        $this->assertEquals('New Customer Name', $user->fresh()->name);
    }

    /** Non-admin tidak dapat memperbarui customer */
    public function test_non_admin_cannot_update_customer(): void
    {
        $regularUser = User::factory()->create([
            'role' => UserRole::CUSTOMER,
        ]);

        $response = $this->actingAs($regularUser)
            ->put(route('admin.customers.update', $this->customer1), [
                'name' => 'Hacker Name',
                'company_name' => 'PT Hack',
                'phone' => '081234567890',
                'address' => 'Jl. Hack',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
            ]);

        $response->assertStatus(302);
    }
}
