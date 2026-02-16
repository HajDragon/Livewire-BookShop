<div>
    <livewire:header />
    <livewire:mobile-navbar>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Page Header -->
            <div class="mb-8">
                <flux:heading size="xl" class="mb-2">Admin Dashboard</flux:heading>
                <flux:text>Manage orders, monitor sales, and view analytics.</flux:text>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <flux:card>
                    <div class="text-center py-4">
                        <flux:text class="text-sm text-gray-500 mb-2">Total Orders</flux:text>
                        <flux:heading size="2xl">{{ $this->totalOrders }}</flux:heading>
                    </div>
                </flux:card>

                <flux:card>
                    <div class="text-center py-4">
                        <flux:text class="text-sm text-gray-500 mb-2">Total Revenue</flux:text>
                        <flux:heading size="2xl">${{ number_format($this->totalRevenue, 2) }}</flux:heading>
                    </div>
                </flux:card>

                <flux:card>
                    <div class="text-center py-4">
                        <flux:text class="text-sm text-gray-500 mb-2">Total Users</flux:text>
                        <flux:heading size="2xl">{{ $this->totalUsers }}</flux:heading>
                    </div>
                </flux:card>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Revenue Chart -->
                <flux:card>
                    <div class="p-6">
                        <flux:heading size="lg" class="mb-4">Revenue (Last 30 Days)</flux:heading>
                        <div style="height: 300px; position: relative;">
                            <canvas id="revenueChart" wire:ignore></canvas>
                        </div>
                    </div>
                </flux:card>

                <!-- Status Chart -->
                <flux:card>
                    <div class="p-6">
                        <flux:heading size="lg" class="mb-4">Orders by Status</flux:heading>
                        <div style="height: 300px; position: relative;">
                            <canvas id="statusChart" wire:ignore></canvas>
                        </div>
                    </div>
                </flux:card>
            </div>

            <!-- Orders Table -->
            <flux:card>
                <div class="p-6">
                    <flux:heading size="lg" class="mb-6">All Orders</flux:heading>

                    @if($this->orders->isEmpty())
                        <div class="text-center py-8">
                            <flux:text class="text-gray-500">No orders yet.</flux:text>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($this->orders as $order)
                                        <tr wire:key="order-{{ $order->id }}">
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                #{{ $order->id }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $order->user->name }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ${{ number_format($order->total, 2) }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <select
                                                    wire:change="updateStatus({{ $order->id }}, $event.target.value)"
                                                    class="text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                >
                                                    <option value="pending" {{ $order->status->value === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="processing" {{ $order->status->value === 'processing' ? 'selected' : '' }}>Processing</option>
                                                    <option value="completed" {{ $order->status->value === 'completed' ? 'selected' : '' }}>Completed</option>
                                                    <option value="cancelled" {{ $order->status->value === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <select
                                                    wire:change="updatePriority({{ $order->id }}, $event.target.value)"
                                                    class="text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                >
                                                    <option value="low" {{ $order->priority->value === 'low' ? 'selected' : '' }}>Low</option>
                                                    <option value="normal" {{ $order->priority->value === 'normal' ? 'selected' : '' }}>Normal</option>
                                                    <option value="high" {{ $order->priority->value === 'high' ? 'selected' : '' }}>High</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                                <flux:button
                                                    wire:click="deleteOrder({{ $order->id }})"
                                                    wire:confirm="Are you sure you want to delete this order?"
                                                    variant="danger"
                                                    size="sm"
                                                >
                                                    Delete
                                                </flux:button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </flux:card>
        </div>
    </livewire:mobile-navbar>

    <!-- Chart.js Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

    @script
    <script>
        // Revenue Line Chart
        const revenueCtx = document.getElementById('revenueChart');
        const revenueData = @json($this->revenueChartData);
        if (revenueCtx && revenueData) {
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: revenueData.labels || [],
                    datasets: [{
                        label: 'Revenue ($)',
                        data: revenueData.values || [],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });
        }

        // Status Pie Chart
        const statusCtx = document.getElementById('statusChart');
        const statusData = @json($this->statusChartData);

        if (statusCtx && statusData) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusData.labels || [],
                    datasets: [{
                        data: statusData.values || [],
                        backgroundColor: statusData.colors || [],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Listen for order updates to refresh charts
        Livewire.on('order-updated', () => {
            window.location.reload();
        });

        Livewire.on('order-deleted', () => {
            window.location.reload();
        });
    </script>
    @endscript
</div>
