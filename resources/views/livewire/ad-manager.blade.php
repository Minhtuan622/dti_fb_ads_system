<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="mb-2">Quảng cáo</flux:heading>
            <flux:subheading>Quản lý các quảng cáo Facebook</flux:subheading>
        </div>
    </div>
    <!-- Bộ lọc -->
    <div class="grid gap-3 md:grid-cols-3">
        <div>
            <label class="block text-sm font-medium mb-1">Lọc theo dự án</label>
            <select wire:model="filterProjectId" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                <option value="">-- Tất cả --</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Tìm kiếm (Ads ID / Post ID)</label>
            <input type="text" wire:model.debounce.300ms="search" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" placeholder="VD: 1234567890">
        </div>
    </div>

    <!-- Form tạo/sửa -->
    <form wire:submit.prevent="{{ $editingId ? 'updateAd' : 'createAd' }}" class="grid gap-3 md:grid-cols-3">
        <div>
            <label class="block text-sm font-medium mb-1">Dự án</label>
            <select wire:model.defer="projectId" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                <option value="">-- Chọn dự án --</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
            @error('projectId') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Ads ID</label>
            <input type="text" wire:model.defer="adId" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
            @error('adId') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Post ID (tuỳ chọn)</label>
            <input type="text" wire:model.defer="postId" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Trạng thái</label>
            <select wire:model.defer="status" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                <option value="active">Hoạt động</option>
                <option value="inactive">Tạm dừng</option>
            </select>
            @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Chi phí (spend)</label>
            <input type="number" step="0.01" min="0" wire:model.defer="spend" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Impressions</label>
            <input type="number" min="0" wire:model.defer="impressions" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Clicks</label>
            <input type="number" min="0" wire:model.defer="clicks" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
        </div>

        <div class="md:col-span-3 flex gap-2">
            <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white">
                {{ $editingId ? 'Cập nhật' : 'Thêm quảng cáo' }}
            </button>
            @if($editingId)
                <button type="button" wire:click="cancelEdit" class="rounded-md bg-zinc-600 px-4 py-2 text-white">Hủy</button>
            @endif
        </div>
    </form>

    <!-- Danh sách quảng cáo -->
    <div class="rounded-md border p-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="py-2">Ads ID</th>
                    <th class="py-2">Dự án</th>
                    <th class="py-2">Chi phí</th>
                    <th class="py-2">Impressions</th>
                    <th class="py-2">Clicks</th>
                    <th class="py-2">Trạng thái</th>
                    <th class="py-2">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ads as $ad)
                    <tr class="border-t">
                        <td class="py-2">{{ $ad->ad_id }}</td>
                        <td class="py-2">{{ optional($ad->project)->name }}</td>
                        <td class="py-2">{{ number_format($ad->spend, 2) }}</td>
                        <td class="py-2">{{ $ad->impressions }}</td>
                        <td class="py-2">{{ $ad->clicks }}</td>
                        <td class="py-2">{{ $ad->status }}</td>
                        <td class="py-2 space-x-2">
                            <button type="button" wire:click="editAd({{ $ad->id }})" class="rounded-md bg-amber-600 px-3 py-1 text-white">Sửa</button>
                            <button type="button" wire:click="refreshFromFacebook({{ $ad->id }})" class="rounded-md bg-emerald-600 px-3 py-1 text-white">Làm mới</button>
                            <button type="button" wire:click="deleteAd({{ $ad->id }})" class="rounded-md bg-red-600 px-3 py-1 text-white">Xóa</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $ads->links() }}
        </div>
    </div>
</div>