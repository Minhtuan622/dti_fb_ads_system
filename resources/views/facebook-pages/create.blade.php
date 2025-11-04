<x-layouts.app :title="__('Thêm trang Facebook')">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-2">Thêm trang Facebook</flux:heading>
                <flux:subheading>Nhập thông tin trang Facebook</flux:subheading>
            </div>
            <div>
                <flux:button href="{{ route('facebook-pages.index') }}" variant="ghost">
                    Quay lại
                </flux:button>
            </div>
        </div>

        <div class="rounded-md border p-6 bg-white dark:bg-zinc-900">
            <form action="{{ route('facebook-pages.store') }}" method="POST" class="grid gap-3 md:grid-cols-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Tài khoản Facebook</label>
                    <select name="facebook_account_id" class="w-full rounded-md border border-gray-300 p-2 bg-white shadow-sm focus:border-blue-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                        <option value="">-- Chọn tài khoản --</option>
                        @foreach($facebookAccounts as $account)
                            <option value="{{ $account->id }}" {{ old('facebook_account_id') == $account->id ? 'selected' : '' }}>{{ $account->name ?? $account->account_id }}</option>
                        @endforeach
                    </select>
                    @error('facebook_account_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Page ID</label>
                    <input type="text" name="page_id" value="{{ old('page_id') }}" class="w-full rounded-md border border-gray-300 p-2 bg-white shadow-sm focus:border-blue-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    @error('page_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Tên trang</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-md border border-gray-300 p-2 bg-white shadow-sm focus:border-blue-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-3 flex gap-2">
                    <flux:button type="submit" variant="primary">Lưu</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>