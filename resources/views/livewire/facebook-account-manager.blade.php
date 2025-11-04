<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="mb-2">Tài khoản Facebook</flux:heading>
            <flux:subheading>Quản lý tài khoản quảng cáo Facebook</flux:subheading>
        </div>
    </div>
    <!-- Tìm kiếm -->
    <div>
        <label class="block text-sm font-medium mb-1">Tìm kiếm (Tên / Account ID)</label>
        <input type="text" wire:model.debounce.300ms="search" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" placeholder="VD: 123456789">
    </div>

    <!-- Form tạo/sửa -->
    <form wire:submit.prevent="{{ $editingId ? 'updateAccount' : 'createAccount' }}" class="grid gap-3 md:grid-cols-3">
        <div>
            <label class="block text-sm font-medium mb-1">Account ID</label>
            <input type="text" wire:model.defer="accountId" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
            @error('accountId') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Tên tài khoản</label>
            <input type="text" wire:model.defer="name" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Access Token (tuỳ chọn)</label>
            <input type="text" wire:model.defer="accessToken" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
            @error('accessToken') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Trạng thái</label>
            <select wire:model.defer="status" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                <option value="active">Hoạt động</option>
                <option value="inactive">Tạm dừng</option>
            </select>
            @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-3 flex gap-2">
            <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white">
                {{ $editingId ? 'Cập nhật' : 'Thêm tài khoản' }}
            </button>
            @if($editingId)
                <button type="button" wire:click="cancelEdit" class="rounded-md bg-zinc-600 px-4 py-2 text-white">
                    Hủy
                </button>
            @endif
        </div>
    </form>

    <!-- Danh sách tài khoản -->
    <div class="rounded-md border p-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="py-2">Account ID</th>
                    <th class="py-2">Tên</th>
                    <th class="py-2">Trạng thái</th>
                    <th class="py-2">Trang FB</th>
                    <th class="py-2">Quảng cáo</th>
                    <th class="py-2">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($facebookAccounts as $acc)
                    <tr class="border-t">
                        <td class="py-2">{{ $acc->account_id }}</td>
                        <td class="py-2">{{ $acc->name }}</td>
                        <td class="py-2">{{ $acc->status }}</td>
                        <td class="py-2">{{ $acc->facebook_pages_count }}</td>
                        <td class="py-2">{{ $acc->ads_count }}</td>
                        <td class="py-2 space-x-2">
                            <button type="button" wire:click="editAccount({{ $acc->id }})" class="rounded-md bg-amber-600 px-3 py-1 text-white">Sửa</button>
                            <button type="button" wire:click="refreshFromFacebook({{ $acc->id }})" class="rounded-md bg-emerald-600 px-3 py-1 text-white">Làm mới</button>

                            @if($confirmingDeleteId === $acc->id)
                                <span class="text-sm">Xác nhận?</span>
                                <button type="button" wire:click="deleteAccount" class="rounded-md bg-red-600 px-3 py-1 text-white">Xóa</button>
                                <button type="button" wire:click="$set('confirmingDeleteId', null)" class="rounded-md bg-zinc-600 px-3 py-1 text-white">Hủy</button>
                            @else
                                <button type="button" wire:click="confirmDelete({{ $acc->id }})" class="rounded-md bg-red-600 px-3 py-1 text-white">Xóa</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $facebookAccounts->links() }}
        </div>
    </div>
</div>