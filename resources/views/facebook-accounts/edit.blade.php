<x-layouts.app :title="__('Sửa tài khoản Facebook')">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-2">Chỉnh sửa tài khoản Facebook</flux:heading>
                <flux:subheading>Cập nhật thông tin tài khoản quảng cáo Facebook</flux:subheading>
            </div>
            <div>
                <flux:button href="{{ route('facebook-accounts.index') }}" variant="ghost">
                    Quay lại
                </flux:button>
            </div>
        </div>

        <div class="rounded-md border p-6 bg-white dark:bg-zinc-900">
            <form action="{{ route('facebook-accounts.update', $facebookAccount) }}" method="POST" class="grid gap-3 md:grid-cols-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Account ID</label>
                    <input type="text" name="account_id" value="{{ old('account_id', $facebookAccount->account_id) }}" class="w-full rounded-md border border-gray-300 p-2 bg-white shadow-sm focus:border-blue-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    @error('account_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Tên tài khoản</label>
                    <input type="text" name="name" value="{{ old('name', $facebookAccount->name) }}" class="w-full rounded-md border border-gray-300 p-2 bg-white shadow-sm focus:border-blue-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Access Token (tuỳ chọn)</label>
                    <textarea rows="3" name="access_token" class="w-full rounded-md border border-gray-300 p-2 bg-white shadow-sm focus:border-blue-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">{{ old('access_token', $facebookAccount->access_token) }}</textarea>
                    @error('access_token') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Trạng thái</label>
                    <select name="status" class="w-full rounded-md border border-gray-300 p-2 bg-white shadow-sm focus:border-blue-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                        <option value="active" {{ old('status', $facebookAccount->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ old('status', $facebookAccount->status) == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                    </select>
                    @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-3 flex gap-2 mt-4">
                    <flux:button type="submit" variant="primary">
                        Cập nhật
                    </flux:button>
                    <flux:button href="{{ route('facebook-accounts.index') }}" variant="ghost">
                        Hủy
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>