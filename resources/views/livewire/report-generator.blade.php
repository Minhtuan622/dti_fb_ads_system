<div class="space-y-6" wire:poll.120s.keep-alive="poll">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="mb-2">Báo cáo</flux:heading>
            <flux:subheading>Xem và quản lý báo cáo đã tạo</flux:subheading>
        </div>
        // ... existing code ...
    </div>

    <!-- Form nhập tham số -->
    <form wire:submit.prevent="start" class="grid gap-4 md:grid-cols-3">
        <div>
            <label class="block text-sm font-medium mb-1">Dự án</label>
            <select wire:model="projectId" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                <option value="">-- Chọn dự án --</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
            @error('projectId') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Ads ID</label>
            <input type="text" wire:model="adsId" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
            @error('adsId') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Page ID (tuỳ chọn)</label>
            <input type="text" wire:model="pageId" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Post ID (tuỳ chọn)</label>
            <input type="text" wire:model="postId" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Thời gian kết thúc</label>
            <input type="datetime-local" wire:model="endAt" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
            @error('endAt') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Tỷ lệ giao (mặc định 0.94)</label>
            <input type="number" step="0.01" min="0" max="1" wire:model="deliveryRate" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
            @error('deliveryRate') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Chi phí catse / giờ</label>
            <input type="number" step="0.01" min="0" wire:model="catseCostPerHour" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
            @error('catseCostPerHour') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-3 flex gap-2">
            <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white">Bắt đầu</button>
            <button type="button" wire:click="stop" class="rounded-md bg-zinc-600 px-4 py-2 text-white" @disabled(!$isRunning)>Dừng</button>
            <div class="ml-auto text-sm text-zinc-600 dark:text-zinc-300">
                Trạng thái: {{ $isRunning ? 'Đang chạy' : 'Dừng' }}
                @if($lastComputedAt)
                    • Cập nhật: {{ $lastComputedAt->diffForHumans() }}
                @endif
            </div>
        </div>
    </form>

    <!-- Kết quả hiện tại -->
    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-md border p-4">
            <h3 class="font-semibold mb-3">Kết quả hiện tại</h3>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div>Chi phí Ads</div><div class="text-right">{{ number_format($currentReport['spend'] ?? 0, 2) }}</div>
                <div>Doanh số</div><div class="text-right">{{ number_format($currentReport['revenue'] ?? 0, 2) }}</div>
                <div>Chi phí catse</div><div class="text-right">{{ number_format($currentReport['catse_cost'] ?? 0, 2) }}</div>
                <div>Doanh thu dự kiến</div><div class="text-right">{{ number_format($currentReport['expected_revenue'] ?? 0, 2) }}</div>
                <div>Lợi nhuận dự kiến</div><div class="text-right font-semibold">{{ number_format($currentReport['expected_profit'] ?? 0, 2) }}</div>
            </div>
        </div>

        <!-- Danh sách báo cáo đã lưu -->
        <div class="rounded-md border p-4 overflow-x-auto">
            <h3 class="font-semibold mb-3">Báo cáo đã lưu</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="py-2">Tên</th>
                        <th class="py-2">Dự án</th>
                        <th class="py-2">Doanh số</th>
                        <th class="py-2">Chi phí Ads</th>
                        <th class="py-2">Catse</th>
                        <th class="py-2">Lợi nhuận dự kiến</th>
                        <th class="py-2">Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $r)
                        <tr class="border-t">
                            <td class="py-2">{{ $r->name }}</td>
                            <td class="py-2">{{ $r->project_id }}</td>
                            <td class="py-2">{{ number_format($r->revenue, 2) }}</td>
                            <td class="py-2">{{ number_format($r->spend, 2) }}</td>
                            <td class="py-2">{{ number_format($r->catse_cost, 2) }}</td>
                            <td class="py-2">{{ number_format($r->expected_profit, 2) }}</td>
                            <td class="py-2">{{ optional($r->end_at)->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>