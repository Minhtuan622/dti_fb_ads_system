<x-layouts.app :title="__('Trang Facebook')">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-2">Trang Facebook</flux:heading>
                <flux:subheading>Quản lý các trang thuộc tài khoản Facebook</flux:subheading>
            </div>
            <div>
                <flux:button href="{{ route('facebook-pages.create') }}" variant="primary">
                    Thêm trang
                </flux:button>
            </div>
        </div>

        <!-- Filters -->
        <div class="grid gap-3 md:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1">Lọc theo tài khoản</label>
                <select class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                    <option value="">-- Tất cả --</option>
                    {{-- @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name ?? $acc->account_id }}</option>
                    @endforeach --}}
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Lọc theo dự án</label>
                <select class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900">
                    <option value="">-- Tất cả --</option>
                    {{-- @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach --}}
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Tìm kiếm (Tên / Page ID)</label>
                <input type="text" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" placeholder="VD: 1234567890">
            </div>
        </div>

        <!-- Pages list -->
        <div class="rounded-md border bg-white dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="border-b">
                        <tr class="text-left">
                            <th class="p-3">Page ID</th>
                            <th class="p-3">Tên</th>
                            <th class="p-3">Tài khoản</th>
                            <th class="p-3">Ads</th>
                            <th class="p-3">Dự án</th>
                            <th class="p-3">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                            <tr class="border-t">
                                <td class="p-3">{{ $page->page_id }}</td>
                                <td class="p-3">{{ $page->name }}</td>
                                <td class="p-3">{{ optional($page->facebookAccount)->name ?? optional($page->facebookAccount)->account_id }}</td>
                                <td class="p-3">{{ $page->ads_count }}</td>
                                <td class="p-3">{{ $page->projects_count }}</td>
                                <td class="p-3 space-x-2">
                                    <flux:button href="{{ route('facebook-pages.edit', $page->id) }}" variant="filled">Sửa</flux:button>
                                    <flux:button
                                        variant="danger"
                                        x-data
                                        x-on:click.prevent="$dispatch('open-modal', 'confirm-page-deletion-{{ $page->id }}')"
                                    >Xóa</flux:button>

                                    <x-modal name="confirm-page-deletion-{{ $page->id }}" :show="$errors->any()" focusable>
                                        <form method="post" action="{{ route('facebook-pages.destroy', $page) }}" class="p-6">
                                            @csrf
                                            @method('delete')

                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                {{ __('Bạn có chắc chắn muốn xóa trang này không?') }}
                                            </h2>

                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                {{ __('Khi trang đã bị xóa, tất cả tài nguyên và dữ liệu của nó sẽ bị xóa vĩnh viễn.') }}
                                            </p>

                                            <div class="mt-6 flex justify-end">
                                                <flux:button
                                                    type="button"
                                                    variant="ghost"
                                                    x-on:click="$dispatch('close')"
                                                >
                                                    {{ __('Hủy') }}
                                                </flux:button>

                                                <flux:button
                                                    type="submit"
                                                    variant="danger"
                                                    class="ms-3"
                                                >
                                                    {{ __('Xóa trang') }}
                                                </flux:button>
                                            </div>
                                        </form>
                                    </x-modal>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $pages->links() }}
        </div>
    </div>
</x-layouts.app>