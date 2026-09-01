<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KedaiSystemTest extends TestCase
{
    use DatabaseTransactions;

    public function test_active_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'active_test_' . uniqid() . '@kedai.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_deactivated_user_cannot_login()
    {
        $user = User::factory()->create([
            'email' => 'inactive_test_' . uniqid() . '@kedai.com',
            'password' => bcrypt('password123'),
            'role' => 'kasir',
            'is_active' => false,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_kasir_dashboard_calculates_hourly_data_without_errors()
    {
        $kasir = User::factory()->create(['role' => 'kasir', 'is_active' => true]);
        $table = Table::first() ?? Table::create(['kode_meja' => 'TEST-' . uniqid(), 'nama_meja' => 'Meja Test', 'kapasitas' => 4]);

        $order = Order::create([
            'table_id' => $table->id,
            'nama_pelanggan' => 'Budi',
            'status' => 'confirmed',
            'kasir_id' => $kasir->id,
            'total_harga' => 50000,
        ]);

        $response = $this->actingAs($kasir)->get('/kasir');

        $response->assertStatus(200);
        $response->assertViewHas('hourly_data');
        $response->assertViewHas('orders');
    }

    public function test_profile_update_works()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'password' => bcrypt('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'John Updated',
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHas('success');
        $user->refresh();
        $this->assertEquals('John Updated', $user->name);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword123', $user->password));
    }

    public function test_customer_menu_loads_with_empty_categories()
    {
        $table = Table::first() ?? Table::create(['kode_meja' => 'TEST-' . uniqid(), 'nama_meja' => 'Meja Test', 'kapasitas' => 2]);

        $response = $this->get('/order/' . $table->qr_token);

        $response->assertStatus(200);
    }

    public function test_customer_can_create_order_successfully()
    {
        $table = Table::create(['kode_meja' => 'T-' . uniqid(), 'nama_meja' => 'Meja Test', 'kapasitas' => 2, 'is_active' => true]);
        $category = Category::first() ?? Category::create(['nama' => 'Minuman', 'urutan' => 1]);
        $menu = Menu::first() ?? Menu::create([
            'category_id' => $category->id,
            'nama' => 'Kopi Tubruk',
            'harga' => 15000,
            'is_available' => true,
            'is_active' => true,
        ]);

        $response = $this->post('/order/' . $table->qr_token, [
            'nama_pelanggan' => 'Andi',
            'items' => [
                [
                    'menu_id' => $menu->id,
                    'jumlah' => 2,
                    'catatan' => 'Sedikit gula',
                ]
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'table_id' => $table->id,
            'nama_pelanggan' => 'Andi',
            'status' => 'pending',
            'total_harga' => $menu->harga * 2,
        ]);
    }

    public function test_customer_invoice_download()
    {
        $table = Table::first() ?? Table::create(['kode_meja' => 'TEST-' . uniqid(), 'nama_meja' => 'Meja Test', 'kapasitas' => 2]);
        $menu = Menu::first();

        $order = Order::create([
            'table_id' => $table->id,
            'nama_pelanggan' => 'Siti',
            'status' => 'completed',
            'total_harga' => 25000,
        ]);

        if ($menu) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menu->id,
                'nama_menu' => $menu->nama,
                'harga' => $menu->harga,
                'jumlah' => 1,
            ]);
        }

        $response = $this->get('/order/' . $table->qr_token . '/status/' . $order->id . '/invoice');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_reports_and_pdf_export()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $response = $this->actingAs($admin)->get('/admin/reports');
        $response->assertStatus(200);

        $pdfResponse = $this->actingAs($admin)->get('/admin/reports/export-pdf');
        $pdfResponse->assertStatus(200);
        $pdfResponse->assertHeader('content-type', 'application/pdf');
    }

    public function test_kasir_cannot_pay_for_cancelled_order()
    {
        $kasir = User::factory()->create(['role' => 'kasir', 'is_active' => true]);
        $table = Table::first() ?? Table::create(['kode_meja' => 'T-' . uniqid(), 'nama_meja' => 'Meja Test', 'kapasitas' => 2]);

        $order = Order::create([
            'table_id' => $table->id,
            'nama_pelanggan' => 'Cancelled Guest',
            'status' => 'cancelled',
            'total_harga' => 30000,
        ]);

        $response = $this->actingAs($kasir)->post('/kasir/orders/' . $order->id . '/pay', [
            'metode' => 'tunai',
            'jumlah_bayar' => 50000,
        ]);

        $response->assertSessionHas('error');
        $order->refresh();
        $this->assertEquals('cancelled', $order->status);
        $this->assertNull($order->payment);
    }

    public function test_admin_category_active_and_inactive_display()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $activeCat = Category::create(['nama' => 'Cat Active ' . uniqid(), 'urutan' => 1, 'is_active' => true]);
        $inactiveCat = Category::create(['nama' => 'Cat Inactive ' . uniqid(), 'urutan' => 2, 'is_active' => false]);

        $response = $this->actingAs($admin)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee($activeCat->nama);
        $response->assertSee($inactiveCat->nama);
        $response->assertSee('Non-Aktif');
    }

    public function test_admin_can_create_user_with_password_confirmation()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'New Staff Kasir',
            'email' => 'newkasir_' . uniqid() . '@kedai.com',
            'role' => 'kasir',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'name' => 'New Staff Kasir',
            'role' => 'kasir',
            'is_active' => 1,
        ]);
    }

    public function test_admin_can_update_user_and_preserve_active_status()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $targetUser = User::factory()->create(['role' => 'kasir', 'is_active' => true]);

        $response = $this->actingAs($admin)->put('/admin/users/' . $targetUser->id, [
            'name' => 'Updated Name',
            'email' => $targetUser->email,
            'role' => 'kasir',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/users');
        $targetUser->refresh();
        $this->assertEquals('Updated Name', $targetUser->name);
        $this->assertTrue((bool) $targetUser->is_active);
    }
}


