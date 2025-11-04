<x-layouts.app :title="__('Dự án')">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-2">Dự án</flux:heading>
                <flux:subheading>Quản lý dự án để kiểm thử tính năng báo cáo</flux:subheading>
            </div>
            <div>
                <flux:button href="{{ route('projects.create') }}" variant="primary">
                    Tạo dự án mới
                </flux:button>
            </div>
        </div>

        <div class="mb-4 flex items-center space-x-4">
            <div class="w-64">
                <label for="searchInput" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Tìm kiếm</label>
                <input type="text" id="searchInput" placeholder="Tìm theo tên dự án..." class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
            </div>
            <div class="w-64">
                <label for="statusFilter" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Trạng thái</label>
                <select id="statusFilter" class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <div class="rounded-md border p-4 overflow-x-auto bg-white dark:bg-zinc-900">
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
                            <td class="py-2">
                                @if($project->status === 'active')
                                    <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                        {{ $project->status }}
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                        {{ $project->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-2">{{ $project->facebook_pages_count }}</td>
                            <td class="py-2">{{ $project->ads_count }}</td>
                            <td class="py-2 space-x-2">
                                <flux:button href="{{ route('projects.edit', $project) }}" variant="filled" size="xs">
                                    Sửa
                                </flux:button>
                                <flux:button onclick="confirmDelete('{{ $project->id }}')" variant="danger" size="xs">
                                    Xóa
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $projects->links() }}
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                </div>
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
                <div class="inline-block transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left align-bottom shadow-xl transition-all dark:bg-zinc-800 sm:my-8 sm:w-full sm:max-w-lg sm:p-6 sm:align-middle">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Xóa dự án</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-300">
                                    Bạn có chắc chắn muốn xóa dự án này? Hành động này không thể hoàn tác.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <flux:button type="submit" variant="danger" class="ml-3">
                                Xóa
                            </flux:button>
                        </form>
                        <flux:button onclick="closeDeleteModal()" variant="ghost">
                            Hủy
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            document.getElementById('deleteForm').action = `/projects/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Filter and search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const tableRows = document.querySelectorAll('tbody tr');

            function filterProjects() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value.toLowerCase();

                tableRows.forEach(row => {
                    const name = row.cells[0].textContent.toLowerCase();
                    const status = row.cells[1].textContent.trim().toLowerCase();
                    
                    const matchesSearch = name.includes(searchTerm);
                    const matchesStatus = statusValue === '' || status.includes(statusValue);
                    
                    row.style.display = matchesSearch && matchesStatus ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterProjects);
            statusFilter.addEventListener('change', filterProjects);
        });
    </script>
</x-layouts.app>
