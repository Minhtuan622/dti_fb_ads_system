<x-layouts.app :title="__('Chỉnh sửa dự án')">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-2">Chỉnh sửa dự án</flux:heading>
                <flux:subheading>Cập nhật thông tin dự án</flux:subheading>
            </div>
            <div>
                <flux:button href="{{ route('projects.index') }}" variant="ghost">
                    Quay lại
                </flux:button>
            </div>
        </div>

        <div class="rounded-md border p-6 bg-white dark:bg-zinc-900">
            <form action="{{ route('projects.update', $project) }}" method="POST" class="grid gap-3 md:grid-cols-3">
                @csrf
                @method('PUT')
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Tên dự án</label>
                    <input type="text" name="name" value="{{ old('name', $project->name) }}" class="w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:outline-none bg-white dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Trạng thái</label>
                    <select name="status" class="w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:outline-none bg-white dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ old('status', $project->status) == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                    </select>
                    @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Mô tả</label>
                    <textarea rows="3" name="description" class="w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:outline-none bg-white dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">{{ old('description', $project->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-3 flex gap-2">
                    <flux:button type="submit" variant="primary">
                        Cập nhật
                    </flux:button>
                    <flux:button href="{{ route('projects.index') }}" variant="ghost">
                        Hủy
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>