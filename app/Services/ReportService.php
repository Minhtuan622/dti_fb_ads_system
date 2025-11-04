<?php

namespace App\Services;

class ReportService
{
    public function aggregateAndCompute(array $fb, array $pos, array $options = []): array
    {
        $spend = (float)($fb['spend'] ?? 0);
        $revenue = (float)($pos['revenue'] ?? 0);
        $catseCost = (float)($options['catse_cost'] ?? 0);
        $deliveryRate = isset($options['delivery_rate']) ? (float)$options['delivery_rate'] : 0.94;

        $expectedRevenue = $revenue * $deliveryRate;
        $expectedProfit = $expectedRevenue - $spend - $catseCost;

        return [
            'revenue' => $revenue,
            'spend' => $spend,
            'catse_cost' => $catseCost,
            'expected_revenue' => $expectedRevenue,
            'expected_profit' => $expectedProfit,
            'meta' => [
                'fb' => $fb,
                'pos' => $pos,
                'options' => $options,
            ],
        ];
    }
}