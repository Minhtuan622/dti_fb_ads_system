<x-layouts.app :title="__('Tạo báo cáo mới')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-2">Tạo báo cáo mới</flux:heading>
                <flux:subheading>Tạo báo cáo mới từ dữ liệu quảng cáo</flux:subheading>
            </div>
            <flux:button variant="ghost" :href="route('reports.index')" wire:navigate>
                ← Quay lại
            </flux:button>
        </div>

        <!-- Form nhập tham số -->
        <div class="rounded-md border p-6">
            <form action="{{ route('reports.store') }}" method="POST" class="grid gap-4 md:grid-cols-3">
                @csrf
                <div>
                    <label for="project_id" class="block text-sm font-medium mb-1">Dự án</label>
                    <select name="project_id" id="project_id" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                        <option value="">-- Chọn dự án --</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ old('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

            <div>
                <label for="name" class="block text-sm font-medium mb-1">Tên báo cáo</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="ads_id" class="block text-sm font-medium mb-1">Ads ID</label>
                <input type="text" name="ads_id" id="ads_id" value="{{ old('ads_id') }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('ads_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="page_id" class="block text-sm font-medium mb-1">Page ID (tuỳ chọn)</label>
                <input type="text" name="page_id" id="page_id" value="{{ old('page_id') }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('page_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="post_id" class="block text-sm font-medium mb-1">Post ID (tuỳ chọn)</label>
                <input type="text" name="post_id" id="post_id" value="{{ old('post_id') }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('post_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="start_at" class="block text-sm font-medium mb-1">Thời gian bắt đầu</label>
                <input type="datetime-local" name="start_at" id="start_at" value="{{ old('start_at') }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('start_at') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="end_at" class="block text-sm font-medium mb-1">Thời gian kết thúc</label>
                <input type="datetime-local" name="end_at" id="end_at" value="{{ old('end_at') }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('end_at') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="delivery_rate" class="block text-sm font-medium mb-1">Tỷ lệ giao (mặc định 0.94)</label>
                <input type="number" step="0.01" min="0" max="1" name="delivery_rate" id="delivery_rate" value="{{ old('delivery_rate', 0.94) }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('delivery_rate') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="catse_cost" class="block text-sm font-medium mb-1">Chi phí catse</label>
                <input type="number" step="0.01" min="0" name="catse_cost" id="catse_cost" value="{{ old('catse_cost', 0) }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('catse_cost') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="revenue" class="block text-sm font-medium mb-1">Doanh số</label>
                <input type="number" step="0.01" min="0" name="revenue" id="revenue" value="{{ old('revenue', 0) }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('revenue') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="spend" class="block text-sm font-medium mb-1">Chi phí Ads</label>
                <input type="number" step="0.01" min="0" name="spend" id="spend" value="{{ old('spend', 0) }}" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                @error('spend') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-3 flex gap-3 pt-2">
                <flux:button type="submit" variant="primary">Tạo báo cáo</flux:button>
                <flux:button type="button" variant="ghost" onclick="window.history.back()">Hủy</flux:button>
            </div>
            </form>
        </div>
    </div>
</x-layouts.app>