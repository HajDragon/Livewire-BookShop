<?php

use App\Enums\OrderPriority;
use App\Enums\OrderStatus;
use App\Livewire\Pages\AdminDashboard;
use App\Models\Order;
use App\Models\User;
use Livewire\Livewire;

test('admin can access dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin);

    $response = $this->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSeeLivewire(AdminDashboard::class);
});

test('non-admin users cannot access dashboard', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user);

    $response = $this->get(route('admin.dashboard'));

    $response->assertForbidden();
});

test('guests cannot access dashboard', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertRedirect(route('login'));
});

test('admin can view all orders', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();

    $order1 = Order::factory()->for($admin)->create();
    $order2 = Order::factory()->for($user)->create();

    $this->actingAs($admin);

    Livewire::test(AdminDashboard::class)
        ->assertSee($order1->id)
        ->assertSee($order2->id);
});

test('admin can update order status', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $order = Order::factory()->pending()->create();

    $this->actingAs($admin);

    Livewire::test(AdminDashboard::class)
        ->call('updateStatus', $order->id, 'completed')
        ->assertDispatched('order-updated');

    expect($order->fresh()->status)->toBe(OrderStatus::Completed);
});

test('admin can update order priority', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $order = Order::factory()->create(['priority' => OrderPriority::Normal]);

    $this->actingAs($admin);

    Livewire::test(AdminDashboard::class)
        ->call('updatePriority', $order->id, 'high')
        ->assertDispatched('order-updated');

    expect($order->fresh()->priority)->toBe(OrderPriority::High);
});

test('admin can delete order', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $order = Order::factory()->create();

    $this->actingAs($admin);

    Livewire::test(AdminDashboard::class)
        ->call('deleteOrder', $order->id)
        ->assertDispatched('order-deleted');

    expect(Order::find($order->id))->toBeNull();
});

test('non-admin cannot update order status', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $order = Order::factory()->for($user)->pending()->create();

    $this->actingAs($user);

    Livewire::test(AdminDashboard::class)
        ->call('updateStatus', $order->id, 'completed')
        ->assertForbidden();

    expect($order->fresh()->status)->toBe(OrderStatus::Pending);
});

test('non-admin cannot delete order', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $order = Order::factory()->for($user)->create();

    $this->actingAs($user);

    Livewire::test(AdminDashboard::class)
        ->call('deleteOrder', $order->id)
        ->assertForbidden();

    expect(Order::find($order->id))->not->toBeNull();
});

test('dashboard shows correct stats', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    Order::factory()->count(5)->completed()->create(['total' => 100]);
    Order::factory()->count(2)->cancelled()->create(['total' => 50]);

    $this->actingAs($admin);

    Livewire::test(AdminDashboard::class)
        ->assertSee('7') // Total orders
        ->assertSee('500.00'); // Total revenue (5 completed * 100, cancelled excluded)
});

test('admin panel link is visible to admins in header', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin);

    $response = $this->get(route('home'));

    $response->assertSee('Admin Panel');
});

test('admin panel link is not visible to regular users', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user);

    $response = $this->get(route('home'));

    $response->assertDontSee('Admin Panel');
});
