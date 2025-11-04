<x-layouts.app :title="__('Quảng cáo')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-2">Quảng cáo</flux:heading>
                <flux:subheading>Quản lý các quảng cáo Facebook</flux:subheading>
            </div>
            <flux:button :href="route('ads.create')" wire:navigate>
                Thêm quảng cáo
            </flux:button>
        </div>

        <!-- Bộ lọc -->
        <div class="grid gap-3 md:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1">Lọc theo dự án</label>
                <select id="filterProject" class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" onchange="filterByProject()">
                    <option value="">-- Tất cả --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Tìm kiếm (Ads ID / Post ID)</label>
                <input type="text" id="searchInput" value="{{ request('search') }}" 
                       class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900" 
                       placeholder="VD: 1234567890" onkeyup="searchAds(event)">
            </div>
        </div>

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
                    @forelse ($ads as $ad)
                        <tr class="border-t">
                            <td class="py-2">{{ $ad->ad_id }}</td>
                            <td class="py-2">{{ optional($ad->project)->name }}</td>
                            <td class="py-2">{{ number_format($ad->spend, 2) }}</td>
                            <td class="py-2">{{ number_format($ad->impressions) }}</td>
                            <td class="py-2">{{ number_format($ad->clicks) }}</td>
                            <td class="py-2">
                                <span class="px-2 py-1 rounded text-xs {{ $ad->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $ad->status === 'active' ? 'Hoạt động' : 'Tạm dừng' }}
                                </span>
                            </td>
                            <td class="py-2 space-x-2">
                                <flux:button size="sm" variant="filled" :href="route('ads.edit', $ad)" wire:navigate>
                                    Sửa
                                </flux:button>
                                <flux:button size="sm" variant="danger" onclick="confirmDelete({{ $ad->id }})">
                                    Xóa
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500">
                                Chưa có quảng cáo nào. <a href="{{ route('ads.create') }}" class="text-blue-600 hover:underline">Thêm quảng cáo đầu tiên</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($ads->hasPages())
                <div class="mt-4">
                    {{ $ads->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal xác nhận xóa -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 max-w-sm mx-4">
            <h3 class="text-lg font-semibold mb-4">Xác nhận xóa</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-6">Bạn có chắc chắn muốn xóa quảng cáo này không?</p>
            <div class="flex gap-3 justify-end">
                <flux:button variant="ghost" onclick="closeDeleteModal()">
                    Hủy
                </flux:button>
                <flux:button variant="danger" onclick="deleteAd()">
                    Xóa
                </flux:button>
            </div>
        </div>
    </div>

    <script>
        let deleteAdId = null;

        function confirmDelete(adId) {
            deleteAdId = adId;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
            deleteAdId = null;
        }

        function deleteAd() {
            if (deleteAdId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/ads/${deleteAdId}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function filterByProject() {
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

        function searchAds(event) {
            if (event.key === 'Enter') {
                filterByProject();
            }
        }
    </script>
</x-layouts.app>