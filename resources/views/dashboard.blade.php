<x-layouts.app :title="__('Bảng điều khiển')">
    @php
        $user = auth()->user();

        $projectsCount = $user->projects()->count();
        $accountsCount = $user->facebookAccounts()->count();
        $pagesCount = $user->facebookPages()->count();
        $adsCount = $user->ads()->count();
        $reportsCount = $user->reports()->count();

        $projectIds = $user->projects()->pluck('id');
        $adsSpendTotal = \App\Models\Ad::whereIn('project_id', $projectIds)->sum('spend');
        $reportsRevenueTotal = \App\Models\Report::whereIn('project_id', $projectIds)->sum('revenue');
        $reportsSpendTotal = \App\Models\Report::whereIn('project_id', $projectIds)->sum('spend');
        $reportsProfitTotal = \App\Models\Report::whereIn('project_id', $projectIds)->sum('expected_profit');

        $latestAds = \App\Models\Ad::whereIn('project_id', $projectIds)->latest()->limit(5)->get();
        $latestReports = \App\Models\Report::whereIn('project_id', $projectIds)->latest()->limit(5)->get();
        $accountIds = $user->facebookAccounts()->pluck('id');
        $latestPages = \App\Models\FacebookPage::whereIn('facebook_account_id', $accountIds)->latest()->limit(5)->get();
    @endphp

    <div class="space-y-8">
        <div class="text-center">
            <flux:heading size="xl" class="mb-4">Bảng điều khiển</flux:heading>
            <flux:subheading>Tổng quan hệ thống quảng cáo Facebook</flux:subheading>
        </div>

        <!-- Thẻ số liệu tổng quan -->
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-md border p-4">
                <div class="text-sm text-zinc-500 mb-1">Dự án</div>
                <div class="text-2xl font-semibold">{{ $projectsCount }}</div>
                <flux:link :href="route('projects.index')" wire:navigate class="text-blue-600">Quản lý</flux:link>
            </div>
            <div class="rounded-md border p-4">
                <div class="text-sm text-zinc-500 mb-1">Tài khoản FB</div>
                <div class="text-2xl font-semibold">{{ $accountsCount }}</div>
                <flux:link :href="route('facebook-accounts.index')" wire:navigate class="text-blue-600">Quản lý</flux:link>
            </div>
            <div class="rounded-md border p-4">
                <div class="text-sm text-zinc-500 mb-1">Trang FB</div>
                <div class="text-2xl font-semibold">{{ $pagesCount }}</div>
                <flux:link :href="route('facebook-pages.index')" wire:navigate class="text-blue-600">Quản lý</flux:link>
            </div>
            <div class="rounded-md border p-4">
                <div class="text-sm text-zinc-500 mb-1">Quảng cáo</div>
                <div class="text-2xl font-semibold">{{ $adsCount }}</div>
                <flux:link :href="route('ads.index')" wire:navigate class="text-blue-600">Quản lý</flux:link>
            </div>
            <div class="rounded-md border p-4">
                <div class="text-sm text-zinc-500 mb-1">Báo cáo</div>
                <div class="text-2xl font-semibold">{{ $reportsCount }}</div>
                <flux:link :href="route('reports.index')" wire:navigate class="text-blue-600">Quản lý</flux:link>
            </div>
            <div class="rounded-md border p-4">
                <div class="text-sm text-zinc-500 mb-1">Tổng quan tài chính</div>
                <div class="space-y-1 text-sm">
                    <div>Doanh thu: <span class="font-semibold">{{ number_format($reportsRevenueTotal, 2) }}</span></div>
                    <div>Chi phí (report): <span class="font-semibold">{{ number_format($reportsSpendTotal, 2) }}</span></div>
                    <div>Chi phí (ads): <span class="font-semibold">{{ number_format($adsSpendTotal, 2) }}</span></div>
                    <div>Lợi nhuận kỳ vọng: <span class="font-semibold">{{ number_format($reportsProfitTotal, 2) }}</span></div>
                </div>
            </div>
        </div>

        <!-- Hoạt động gần đây -->
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-md border p-4 overflow-x-auto">
                <div class="text-sm text-zinc-500 mb-2">Báo cáo gần đây</div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left">
                            <th class="py-2">Tên</th>
                            <th class="py-2">Doanh thu</th>
                            <th class="py-2">Chi phí</th>
                            <th class="py-2">Lợi nhuận</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestReports as $r)
                            <tr class="border-t">
                                <td class="py-2">{{ $r->name }}</td>
                                <td class="py-2">{{ number_format($r->revenue, 2) }}</td>
                                <td class="py-2">{{ number_format($r->spend, 2) }}</td>
                                <td class="py-2">{{ number_format($r->expected_profit, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2">
                    <flux:link :href="route('reports.index')" wire:navigate class="text-blue-600">Xem tất cả</flux:link>
                </div>
            </div>

            <div class="rounded-md border p-4 overflow-x-auto">
                <div class="text-sm text-zinc-500 mb-2">Quảng cáo gần đây</div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left">
                            <th class="py-2">Ads ID</th>
                            <th class="py-2">Dự án</th>
                            <th class="py-2">Chi phí</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestAds as $a)
                            <tr class="border-t">
                                <td class="py-2">{{ $a->ad_id }}</td>
                                <td class="py-2">{{ optional($a->project)->name }}</td>
                                <td class="py-2">{{ number_format($a->spend, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2">
                    <flux:link :href="route('ads.index')" wire:navigate class="text-blue-600">Xem tất cả</flux:link>
                </div>
            </div>

            <div class="rounded-md border p-4 overflow-x-auto">
                <div class="text-sm text-zinc-500 mb-2">Trang gần đây</div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left">
                            <th class="py-2">Page ID</th>
                            <th class="py-2">Tên</th>
                            <th class="py-2">Tài khoản</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestPages as $p)
                            <tr class="border-t">
                                <td class="py-2">{{ $p->page_id }}</td>
                                <td class="py-2">{{ $p->name }}</td>
                                <td class="py-2">{{ optional($p->facebookAccount)->name ?? optional($p->facebookAccount)->account_id }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2">
                    <flux:link :href="route('facebook-pages.index')" wire:navigate class="text-blue-600">Xem tất cả</flux:link>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
