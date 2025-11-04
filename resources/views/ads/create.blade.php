<x-layouts.app :title="__('Thêm quảng cáo mới')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-2">Thêm quảng cáo mới</flux:heading>
                <flux:subheading>Tạo quảng cáo Facebook mới</flux:subheading>
            </div>
            <flux:button variant="ghost" :href="route('ads.index')" wire:navigate>
                ← Quay lại
            </flux:button>
        </div>

        <!-- Form tạo quảng cáo -->
        <div class="rounded-md border p-6">
            <form action="{{ route('ads.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="project_id" class="block text-sm font-medium mb-1">Dự án *</label>
                        <select name="project_id" id="project_id" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" required>
                            <option value="">-- Chọn dự án --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ad_id" class="block text-sm font-medium mb-1">Ads ID *</label>
                        <input type="text" name="ad_id" id="ad_id" value="{{ old('ad_id') }}" 
                               class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" 
                               placeholder="VD: 1234567890" required>
                        @error('ad_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="post_id" class="block text-sm font-medium mb-1">Post ID (tuỳ chọn)</label>
                        <input type="text" name="post_id" id="post_id" value="{{ old('post_id') }}" 
                               class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" 
                               placeholder="VD: 9876543210">
                        @error('post_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium mb-1">Trạng thái *</label>
                        <select name="status" id="status" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label for="spend" class="block text-sm font-medium mb-1">Chi phí (spend)</label>
                        <input type="number" step="0.01" min="0" name="spend" id="spend" value="{{ old('spend', 0) }}" 
                               class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" 
                               placeholder="0.00">
                        @error('spend')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="impressions" class="block text-sm font-medium mb-1">Impressions</label>
                        <input type="number" min="0" name="impressions" id="impressions" value="{{ old('impressions', 0) }}" 
                               class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" 
                               placeholder="0">
                        @error('impressions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="clicks" class="block text-sm font-medium mb-1">Clicks</label>
                        <input type="number" min="0" name="clicks" id="clicks" value="{{ old('clicks', 0) }}" 
                               class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" 
                               placeholder="0">
                        @error('clicks')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <flux:button type="submit" variant="primary">
                        Thêm quảng cáo
                    </flux:button>
                    <flux:button type="button" variant="ghost" onclick="window.history.back()">
                        Hủy
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>