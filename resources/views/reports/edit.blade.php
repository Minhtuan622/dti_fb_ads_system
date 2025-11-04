<x-layouts.app :title="__('Chỉnh sửa báo cáo')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-2">Chỉnh sửa báo cáo</flux:heading>
                <flux:subheading>Cập nhật thông tin báo cáo</flux:subheading>
            </div>
            <flux:button variant="ghost" :href="route('reports.index')" wire:navigate>
                ← Quay lại
            </flux:button>
        </div>

        <!-- Form chỉnh sửa -->
        <div class="rounded-md border p-6">
            <form action="{{ route('reports.update', $report) }}" method="POST" class="grid gap-4 md:grid-cols-3">
                @csrf
                @method('PUT')
            <div>
                <label for="project_id" class="block text-sm font-medium mb-1">Dự án</label>
                <select name="project_id" id="project_id" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                    <option value="">-- Chọn dự án --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ old('project_id', $report->project_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
                @error('project_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-medium mb-1">Tên báo cáo</label>
                <input type="text" name="name" id="name" value="{{ old('name', $report->name) }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="start_at" class="block text-sm font-medium mb-1">Thời gian bắt đầu</label>
                <input type="datetime-local" name="start_at" id="start_at" value="{{ old('start_at', $report->start_at ? $report->start_at->format('Y-m-d\TH:i') : '') }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('start_at') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="end_at" class="block text-sm font-medium mb-1">Thời gian kết thúc</label>
                <input type="datetime-local" name="end_at" id="end_at" value="{{ old('end_at', $report->end_at ? $report->end_at->format('Y-m-d\TH:i') : '') }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('end_at') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="revenue" class="block text-sm font-medium mb-1">Doanh số</label>
                <input type="number" step="0.01" min="0" name="revenue" id="revenue" value="{{ old('revenue', $report->revenue) }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('revenue') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="spend" class="block text-sm font-medium mb-1">Chi phí Ads</label>
                <input type="number" step="0.01" min="0" name="spend" id="spend" value="{{ old('spend', $report->spend) }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('spend') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="catse_cost" class="block text-sm font-medium mb-1">Chi phí catse</label>
                <input type="number" step="0.01" min="0" name="catse_cost" id="catse_cost" value="{{ old('catse_cost', $report->catse_cost) }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('catse_cost') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="expected_revenue" class="block text-sm font-medium mb-1">Doanh thu dự kiến</label>
                <input type="number" step="0.01" min="0" name="expected_revenue" id="expected_revenue" value="{{ old('expected_revenue', $report->expected_revenue) }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('expected_revenue') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="expected_profit" class="block text-sm font-medium mb-1">Lợi nhuận dự kiến</label>
                <input type="number" step="0.01" name="expected_profit" id="expected_profit" value="{{ old('expected_profit', $report->expected_profit) }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('expected_profit') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3 flex gap-3 pt-2">
                <flux:button type="submit" variant="primary">Cập nhật</flux:button>
                <flux:button type="button" variant="ghost" onclick="window.history.back()">Hủy</flux:button>
            </div>
            </form>
        </div>
    </div>
</x-layouts.app>