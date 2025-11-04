<div class="space-y-6">
    @foreach ($projects as $project)
        <div class="flex items-center space-x-4">
            <flux:icon icon="user-group" class="mr-1 h-4 w-4" />
            {{ $project->facebookPages->count() }} Trang Facebook
        </div>
    @endforeach

    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="mb-2">Dự án</flux:heading>
            <flux:subheading>Quản lý dự án để kiểm thử tính năng báo cáo</flux:subheading>
        </div>
    </div>

    <form wire:submit.prevent="{{ $editingId ? 'updateProject' : 'createProject' }}" class="grid gap-3 md:grid-cols-3">
        <div class="md:col-span-1">
            <label class="block text-sm font-medium mb-1">Tên dự án</label>
            <input type="text" wire:model.defer="name" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-1">
            <label class="block text-sm font-medium mb-1">Trạng thái</label>
            <select wire:model.defer="status" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                <option value="active">Hoạt động</option>
                <option value="inactive">Tạm dừng</option>
            </select>
            @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-3">
            <label class="block text-sm font-medium mb-1">Mô tả</label>
            <textarea rows="3" wire:model.defer="description" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900"></textarea>
            @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-3 flex gap-2">
            <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white">
                {{ $editingId ? 'Cập nhật' : 'Tạo dự án' }}
            </button>
            @if($editingId)
                <button type="button" wire:click="cancelEdit" class="rounded-md bg-zinc-600 px-4 py-2 text-white">
                    Hủy
                </button>
            @endif
        </div>
    </form>

    <div class="rounded-md border p-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="py-2">Tên</th>
                    <th class="py-2">Trạng thái</th>
                    <th class="py-2">Trang FB</th>
                    <th class="py-2">Quảng cáo</th>
                    <th class="py-2">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr class="border-t">
                        <td class="py-2">{{ $project->name }}</td>
                        <td class="py-2">{{ $project->status }}</td>
                        <td class="py-2">{{ $project->facebook_pages_count }}</td>
                        <td class="py-2">{{ $project->ads_count }}</td>
                        <td class="py-2 space-x-2">
                            <button type="button" wire:click="editProject({{ $project->id }})" class="rounded-md bg-amber-600 px-3 py-1 text-white">Sửa</button>
                            <button type="button" wire:click="confirmDelete({{ $project->id }})" class="rounded-md bg-red-600 px-3 py-1 text-white">Xóa</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $projects->links() }}
        </div>
    </div>

    @if($confirmingDeleteId)
        <div class="rounded-md border p-4">
            <p class="mb-3">Xóa dự án này? Thao tác không thể hoàn tác.</p>
            <button type="button" wire:click="deleteProject" class="rounded-md bg-red-600 px-4 py-2 text-white">Xác nhận xóa</button>
            <button type="button" wire:click="$set('confirmingDeleteId', null)" class="rounded-md bg-zinc-600 px-4 py-2 text-white ml-2">Hủy</button>
        </div>
    @endif
</div>
