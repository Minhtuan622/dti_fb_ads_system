<?php

namespace App\Services;

use App\Models\LarkSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LarkNotificationService
{
    protected string $webhookUrl;
    protected ?string $secret;

    public function __construct()
    {
        $settings = LarkSetting::getActiveSettings();
        if ($settings) {
            $this->webhookUrl = $settings->webhook_url;
            $this->secret = $settings->webhook_secret;
        } else {
            // Fallback to config
            $this->webhookUrl = config('services.lark.webhook_url', '');
            $this->secret = config('services.lark.webhook_secret');
        }
    }

    /**
     * Render nội dung theo template đơn giản với placeholder {{key}}
     */
    public function renderMessageTemplate(string $template, array $data): string
    {
        // Bổ sung các giá trị đã format nếu có
        $profit = ($data['revenue'] ?? 0) - ($data['spend'] ?? 0) - ($data['catse_cost'] ?? 0);

        $formatted = [
            'title' => $data['title'] ?? '',
            'project_name' => $data['project_name'] ?? '',
            'period' => $data['period'] ?? '',
            'revenue' => number_format($data['revenue'] ?? 0, 2),
            'spend' => number_format($data['spend'] ?? 0, 2),
            'catse_cost' => number_format($data['catse_cost'] ?? 0, 2),
            'expected_profit' => number_format($data['expected_profit'] ?? 0, 2),
            'profit' => number_format($profit, 2),
        ];

        // Thay thế các placeholder {{ key }}
        $rendered = preg_replace_callback('/\{\{\s*(.*?)\s*\}\}/', function ($matches) use ($formatted) {
            $key = $matches[1];
            return $formatted[$key] ?? '';
        }, $template);

        return $rendered;
    }

    /**
     * Gửi báo cáo dạng text đơn giản
     */
    public function sendTextMessage(string $message): bool
    {
        if (empty($this->webhookUrl)) {
            Log::warning('Lark webhook URL chưa được cấu hình');
            return false;
        }

        try {
            $payload = [
                'msg_type' => 'text',
                'content' => [
                    'text' => $message
                ]
            ];

            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->webhookUrl, $payload);

            if ($response->successful()) {
                Log::info('Đã gửi tin nhắn Lark thành công');
                return true;
            } else {
                Log::error('Gửi tin nhắn Lark thất bại', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi gửi tin nhắn Lark', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Gửi báo cáo dạng rich text với nhiều thông tin
     */
    public function sendRichTextMessage(array $content): bool
    {
        if (empty($this->webhookUrl)) {
            Log::warning('Lark webhook URL chưa được cấu hình');
            return false;
        }

        try {
            $payload = [
                'msg_type' => 'post',
                'content' => [
                    'post' => $content
                ]
            ];

            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->webhookUrl, $payload);

            if ($response->successful()) {
                Log::info('Đã gửi rich text message Lark thành công');
                return true;
            } else {
                Log::error('Gửi rich text message Lark thất bại', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi gửi rich text message Lark', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Gửi báo cáo tài chính
     */
    public function sendFinancialReport(array $reportData): bool
    {
        $title = $reportData['title'] ?? 'Báo cáo tài chính';
        $projectName = $reportData['project_name'] ?? 'Không xác định';
        $period = $reportData['period'] ?? '';
        
        $revenue = number_format($reportData['revenue'] ?? 0, 2);
        $spend = number_format($reportData['spend'] ?? 0, 2);
        $catseCost = number_format($reportData['catse_cost'] ?? 0, 2);
        $expectedProfit = number_format($reportData['expected_profit'] ?? 0, 2);
        $profit = ($reportData['revenue'] ?? 0) - ($reportData['spend'] ?? 0) - ($reportData['catse_cost'] ?? 0);
        $profitFormatted = number_format($profit, 2);

        $content = [
            'zh_cn' => [
                'title' => $title,
                'content' => [
                    [
                        ['tag' => 'text', 'text' => '📊 '],
                        ['tag' => 'text', 'text' => 'Dự án: ', 'style' => ['bold']],
                        ['tag' => 'text', 'text' => $projectName]
                    ],
                    [
                        ['tag' => 'text', 'text' => '📅 '],
                        ['tag' => 'text', 'text' => 'Thời gian: ', 'style' => ['bold']],
                        ['tag' => 'text', 'text' => $period]
                    ],
                    [
                        ['tag' => 'hr']
                    ],
                    [
                        ['tag' => 'text', 'text' => '💰 Doanh thu: ', 'style' => ['bold']],
                        ['tag' => 'text', 'text' => "{$revenue} VND", 'style' => ['bold', 'green']]
                    ],
                    [
                        ['tag' => 'text', 'text' => '💸 Chi phí Ads: ', 'style' => ['bold']],
                        ['tag' => 'text', 'text' => "{$spend} VND", 'style' => ['bold', 'red']]
                    ],
                    [
                        ['tag' => 'text', 'text' => '💼 Chi phí Catse: ', 'style' => ['bold']],
                        ['tag' => 'text', 'text' => "{$catseCost} VND", 'style' => ['bold', 'orange']]
                    ],
                    [
                        ['tag' => 'text', 'text' => '📈 Lợi nhuận: ', 'style' => ['bold']],
                        ['tag' => 'text', 'text' => "{$profitFormatted} VND", 'style' => $profit >= 0 ? ['bold', 'green'] : ['bold', 'red']]
                    ],
                    [
                        ['tag' => 'text', 'text' => '🔮 Lợi nhuận dự kiến: ', 'style' => ['bold']],
                        ['tag' => 'text', 'text' => "{$expectedProfit} VND", 'style' => ['bold', 'blue']]
                    ]
                ]
            ]
        ];

        return $this->sendRichTextMessage($content);
    }

    /**
     * Format và gửi báo cáo từ model Report
     */
    public function sendReportFromModel(\App\Models\Report $report): bool
    {
        $reportData = [
            'title' => "Báo cáo: {$report->name}",
            'project_name' => $report->project->name ?? 'Không xác định',
            'period' => sprintf(
                '%s - %s',
                $report->start_at ? $report->start_at->format('d/m/Y H:i') : 'Không xác định',
                $report->end_at ? $report->end_at->format('d/m/Y H:i') : 'Không xác định'
            ),
            'revenue' => $report->revenue,
            'spend' => $report->spend,
            'catse_cost' => $report->catse_cost,
            'expected_profit' => $report->expected_profit
        ];

        // Nếu có template cấu hình, ưu tiên gửi dạng text theo template
        $settings = LarkSetting::getActiveSettings();
        if ($settings && !empty($settings->message_template)) {
            $text = $this->renderMessageTemplate($settings->message_template, $reportData);
            return $this->sendTextMessage($text);
        }

        // Mặc định gửi rich text tài chính
        return $this->sendFinancialReport($reportData);
    }
}