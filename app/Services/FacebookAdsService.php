<?php

namespace App\Services;

class FacebookAdsService
{
    /**
     * Lấy dữ liệu quảng cáo realtime theo Ads ID / Page ID / Post ID.
     * Trả về chuẩn hóa: spend, impressions, clicks, conversions..., v.v.
     */
    public function fetchRealtime(string $adsId, ?string $pageId = null, ?string $postId = null): array
    {
        // TODO: Gọi Facebook Marketing API, map dữ liệu về định dạng nội bộ.
        return [
            'ads_id' => $adsId,
            'page_id' => $pageId,
            'post_id' => $postId,
            'spend' => 0.0,
            'impressions' => 0,
            'clicks' => 0,
            'conversions' => 0,
            'raw' => [],
        ];
    }

    /**
     * Lấy thông tin tài khoản (mock).
     */
    public function fetchAccount(string $accountId): array
    {
        return [
            'account_id' => $accountId,
            'name' => 'FB Account '.$accountId,
            'status' => 'active',
            'raw' => [],
        ];
    }

    /**
     * Lấy danh sách trang thuộc tài khoản (mock).
     * Trả về mảng mỗi phần tử có ['id' => 'page_id', 'name' => 'Page Name'].
     */
    public function fetchPages(string $accountId): array
    {
        return [
            // ['id' => '1234567890', 'name' => 'My Page'],
        ];
    }

    /**
     * Lấy thông tin một trang theo Page ID (mock).
     */
    public function fetchPage(string $pageId): array
    {
        return [
            'page_id' => $pageId,
            'name' => 'FB Page '.$pageId,
            'raw' => [],
        ];
    }
}