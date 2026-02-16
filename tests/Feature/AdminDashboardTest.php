<?php

use App\Enums\OrderPriority;
use App\Enums\OrderStatus;
use App\Livewire\Pages\AdminDashboard;
use App\Models\book;
use App\Models\Order;
use App\Models\OrderItem;
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

test('admin can view a specific order page', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create();

    $this->actingAs($admin);

    $response = $this->get(route('admin.orders.show', $order));

    $response->assertOk();
    $response->assertSee("Order #{$order->id}");
});

test('admin can view ordered items on the order page', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();
    $book = book::factory()->create([
        'name' => 'Test Book',
        'author' => 'Test Author',
    ]);

    $order = Order::factory()->for($user)->create();

    OrderItem::create([
        'order_id' => $order->id,
        'book_id' => $book->id,
        'book_name' => $book->name,
        'book_author' => $book->author,
        'quantity' => 2,
        'price' => 19.99,
    ]);

    $this->actingAs($admin);

    $response = $this->get(route('admin.orders.show', $order));

    $response->assertOk();
    $response->assertSee('Test Book');
    $response->assertSee('Test Author');
    $response->assertSee('Quantity: 2');
});

test('orders are sorted by priority then newest', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $highOld = Order::factory()->for($admin)->create([
        'priority' => OrderPriority::High,
        'created_at' => now()->subDays(3),
        'updated_at' => now()->subDays(3),
    ]);

    $highNew = Order::factory()->for($admin)->create([
        'priority' => OrderPriority::High,
        'created_at' => now()->subDays(1),
        'updated_at' => now()->subDays(1),
    ]);

    $normal = Order::factory()->for($admin)->create([
        'priority' => OrderPriority::Normal,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $low = Order::factory()->for($admin)->create([
        'priority' => OrderPriority::Low,
        'created_at' => now()->subDays(2),
        'updated_at' => now()->subDays(2),
    ]);

    $this->actingAs($admin);

    $orders = Livewire::test(AdminDashboard::class)->get('orders');

    expect($orders->pluck('id')->all())->toBe([
        $highNew->id,
        $highOld->id,
        $normal->id,
        $low->id,
    ]);
});

test('orders rows are tinted based on status', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    Order::factory()->for($admin)->pending()->create();
    Order::factory()->for($admin)->processing()->create();
    Order::factory()->for($admin)->completed()->create();
    Order::factory()->for($admin)->cancelled()->create();

    $this->actingAs($admin);

    Livewire::test(AdminDashboard::class)
        ->assertSeeHtml('bg-yellow-50')
        ->assertSeeHtml('bg-emerald-50')
        ->assertSeeHtml('bg-green-50')
        ->assertSeeHtml('bg-red-50');
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
