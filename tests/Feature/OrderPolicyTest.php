<?php

use App\Models\Order;
use App\Models\User;
use App\Policies\OrderPolicy;

test('admin can view any orders', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $policy = new OrderPolicy;

    expect($policy->viewAny($admin))->toBeTrue();
});

test('non-admin cannot view any orders', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $policy = new OrderPolicy;

    expect($policy->viewAny($user))->toBeFalse();
});

test('admin can view any order', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $order = Order::factory()->create();
    $policy = new OrderPolicy;

    expect($policy->view($admin, $order))->toBeTrue();
});

test('user can view their own order', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $order = Order::factory()->for($user)->create();
    $policy = new OrderPolicy;

    expect($policy->view($user, $order))->toBeTrue();
});

test('user cannot view another user order', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $otherUser = User::factory()->create();
    $order = Order::factory()->for($otherUser)->create();
    $policy = new OrderPolicy;

    expect($policy->view($user, $order))->toBeFalse();
});

test('admin can update any order', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $order = Order::factory()->create();
    $policy = new OrderPolicy;

    expect($policy->update($admin, $order))->toBeTrue();
});

test('non-admin cannot update orders', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $order = Order::factory()->for($user)->create();
    $policy = new OrderPolicy;

    expect($policy->update($user, $order))->toBeFalse();
});

test('admin can delete any order', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $order = Order::factory()->create();
    $policy = new OrderPolicy;

    expect($policy->delete($admin, $order))->toBeTrue();
});

test('non-admin cannot delete orders', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $order = Order::factory()->for($user)->create();
    $policy = new OrderPolicy;

    expect($policy->delete($user, $order))->toBeFalse();
});
