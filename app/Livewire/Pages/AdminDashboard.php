<?php

namespace App\Livewire\Pages;

use App\Enums\OrderPriority;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin Dashboard')]
class AdminDashboard extends Component
{
    #[Computed]
    public function totalOrders(): int
    {
        return Order::count();
    }

    #[Computed]
    public function totalRevenue(): string
    {
        return Order::whereNot('status', OrderStatus::Cancelled)
            ->sum('total');
    }

    #[Computed]
    public function totalUsers(): int
    {
        return User::count();
    }

    #[Computed]
    public function revenueChartData(): array
    {
        $data = Order::whereNot('status', OrderStatus::Cancelled)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('M d'))->toArray(),
            'values' => $data->pluck('revenue')->toArray(),
        ];
    }

    #[Computed]
    public function statusChartData(): array
    {
        $data = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'labels' => $data->map(fn ($item) => $item->status->label())->toArray(),
            'values' => $data->pluck('count')->toArray(),
            'colors' => $data->map(fn ($item) => $this->getChartColor($item->status->color()))->toArray(),
        ];
    }

    #[Computed]
    public function orders(): \Illuminate\Database\Eloquent\Collection
    {
        return Order::with('user')
            ->latest()
            ->get();
    }

    public function updateStatus(int $orderId, string $status): void
    {
        $order = Order::findOrFail($orderId);

        $this->authorize('update', $order);

        $order->update([
            'status' => OrderStatus::from($status),
        ]);

        $this->dispatch('order-updated');
    }

    public function updatePriority(int $orderId, string $priority): void
    {
        $order = Order::findOrFail($orderId);

        $this->authorize('update', $order);

        $order->update([
            'priority' => OrderPriority::from($priority),
        ]);

        $this->dispatch('order-updated');
    }

    public function deleteOrder(int $orderId): void
    {
        $order = Order::findOrFail($orderId);

        $this->authorize('delete', $order);

        $order->delete();

        $this->dispatch('order-deleted');
    }

    public function render()
    {
        return view('pages.admin.dashboard');
    }

    private function getChartColor(string $color): string
    {
        return match ($color) {
            'yellow' => '#fbbf24',
            'blue' => '#3b82f6',
            'green' => '#10b981',
            'red' => '#ef4444',
            'gray' => '#6b7280',
            default => '#3b82f6',
        };
    }
}
