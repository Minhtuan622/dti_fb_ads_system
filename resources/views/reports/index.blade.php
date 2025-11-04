<x-layouts.app :title="__('Báo cáo')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-2">Báo cáo</flux:heading>
                <flux:subheading>Xem và quản lý báo cáo đã tạo</flux:subheading>
            </div>
            <flux:button :href="route('reports.create')" wire:navigate>
                Tạo báo cáo mới
            </flux:button>
        </div>

        <!-- Bộ lọc -->
        <div class="grid gap-3 md:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1">Lọc theo dự án</label>
                <select id="filterProject" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" onchange="filterReports()">
                    <option value="">-- Tất cả --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Tìm kiếm (Tên báo cáo)</label>
                <input type="text" id="searchInput" value="{{ request('search') }}" 
                       class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" 
                       placeholder="VD: Báo cáo tuần 42" onkeyup="searchReports(event)">
            </div>
        </div>

        <!-- Danh sách báo cáo đã lưu -->
        <div class="rounded-md border p-4 overflow-x-auto">
            <h3 class="font-semibold mb-3">Báo cáo đã lưu</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="py-2">Tên</th>
                        <th class="py-2">Dự án</th>
                        <th class="py-2">Doanh số</th>
                        <th class="py-2">Chi phí Ads</th>
                        <th class="py-2">Catse</th>
                        <th class="py-2">Lợi nhuận dự kiến</th>
                        <th class="py-2">Thời gian</th>
                        <th class="py-2">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr class="border-t">
                            <td class="py-2">{{ $report->name }}</td>
                            <td class="py-2">{{ $report->project->name }}</td>
                            <td class="py-2">{{ number_format($report->revenue, 2) }}</td>
                            <td class="py-2">{{ number_format($report->spend, 2) }}</td>
                            <td class="py-2">{{ number_format($report->catse_cost, 2) }}</td>
                            <td class="py-2">{{ number_format($report->expected_profit, 2) }}</td>
                            <td class="py-2">{{ optional($report->end_at)->format('Y-m-d H:i') }}</td>
                            <td class="py-2">
                                <div class="flex space-x-2">
                                    <flux:button size="sm" variant="filled" :href="route('reports.edit', $report)" wire:navigate>
                                        Sửa
                                    </flux:button>
                                    <flux:button size="sm" variant="danger" onclick="confirmDeleteReport({{ $report->id }})">
                                        Xóa
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if($reports->isEmpty())
                        <tr class="border-t">
                            <td colspan="8" class="py-4 text-center text-gray-500">Không có báo cáo nào</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="mt-3">
                {{ $reports->links() }}
            </div>
        </div>
    </div>

    <!-- Modal xác nhận xóa -->
    <div id="deleteReportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 max-w-sm mx-4">
            <h3 class="text-lg font-semibold mb-4">Xác nhận xóa</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-6">Bạn có chắc chắn muốn xóa báo cáo này không?</p>
            <div class="flex gap-3 justify-end">
                <flux:button variant="ghost" onclick="closeDeleteReportModal()">
                    Hủy
                </flux:button>
                <flux:button variant="danger" onclick="deleteReport()">
                    Xóa
                </flux:button>
            </div>
        </div>
    </div>

    <script>
        let deleteReportId = null;

        function confirmDeleteReport(id) {
            deleteReportId = id;
            document.getElementById('deleteReportModal').classList.remove('hidden');
            document.getElementById('deleteReportModal').classList.add('flex');
        }

        function closeDeleteReportModal() {
            document.getElementById('deleteReportModal').classList.add('hidden');
            document.getElementById('deleteReportModal').classList.remove('flex');
            deleteReportId = null;
        }

        function deleteReport() {
            if (deleteReportId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/reports/${deleteReportId}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function filterReports() {
            const projectId = document.getElementById('filterProject').value;
            const search = document.getElementById('searchInput').value;
            const url = new URL(window.location);
            
            if (projectId) {
                url.searchParams.set('project_id', projectId);
            } else {
                url.searchParams.delete('project_id');
            }
            
            if (search) {
                url.searchParams.set('search', search);
            } else {
                url.searchParams.delete('search');
            }
            
            window.location.href = url.toString();
        }

        function searchReports(event) {
            if (event.key === 'Enter') {
                filterReports();
            }
        }
    </script>
</x-layouts.app>