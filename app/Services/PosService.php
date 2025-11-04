<?php

namespace App\Services;

class PosService
{
    /**
     * Lấy dữ liệu doanh số realtime theo dự án và khoảng thời gian.
     */
    public function fetchRealtime(int $projectId, ?string $endTime = null): array
    {
        // TODO: Tích hợp hệ POS (API/DB), map dữ liệu về: revenue, orders, items...
        return [
            'project_id' => $projectId,
            'revenue' => 0.0,
            'orders' => 0,
            'raw' => [],
        ];
    }
}