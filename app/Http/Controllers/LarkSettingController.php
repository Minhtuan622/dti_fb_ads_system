<?php

namespace App\Http\Controllers;

use App\Models\LarkSetting;
use App\Services\LarkNotificationService;
use Illuminate\Http\Request;

class LarkSettingController extends Controller
{
    public function index()
    {
        $settings = LarkSetting::firstOrCreate(
            [],
            [
                'webhook_url' => config('services.lark.webhook_url'),
                'webhook_secret' => config('services.lark.webhook_secret'),
                'enabled' => true
            ]
        );

        return view('lark-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'webhook_url' => 'required|url',
            'webhook_secret' => 'nullable|string',
            'enabled' => 'boolean'
        ]);

        $settings = LarkSetting::firstOrCreate([]);
        $settings->update($validated);

        return redirect()->route('lark-settings.index')
            ->with('success', 'Cấu hình Lark đã được cập nhật thành công!');
    }

    public function testWebhook()
    {
        $settings = LarkSetting::getActiveSettings();

        if (!$settings || !$settings->webhook_url) {
            return redirect()->route('lark-settings.index')
                ->with('error', 'Vui lòng cấu hình webhook URL trước khi test.');
        }

        try {
            $service = new LarkNotificationService();
            $result = $service->sendTextMessage('🔔 Test webhook từ hệ thống báo cáo DTI Ads!');

            if ($result) {
                return redirect()->route('lark-settings.index')
                    ->with('success', 'Test webhook thành công!');
            } else {
                return redirect()->route('lark-settings.index')
                    ->with('error', 'Test webhook thất bại. Vui lòng kiểm tra cấu hình.');
            }
        } catch (\Exception $e) {
            return redirect()->route('lark-settings.index')
                ->with('error', 'Lỗi khi test webhook: ' . $e->getMessage());
        }
    }

    /**
     * Trang chỉnh sửa mẫu tin nhắn gửi lên Lark
     */
    public function template()
    {
        $settings = LarkSetting::firstOrCreate(
            [],
            [
                'webhook_url' => config('services.lark.webhook_url'),
                'webhook_secret' => config('services.lark.webhook_secret'),
                'enabled' => true,
                'message_template' => null,
            ]
        );

        return view('lark-settings.template', compact('settings'));
    }

    /**
     * Lưu mẫu tin nhắn
     */
    public function updateTemplate(Request $request)
    {
        $validated = $request->validate([
            'message_template' => 'nullable|string',
        ]);

        $settings = LarkSetting::firstOrCreate([]);
        $settings->update($validated);

        return redirect()->route('lark-settings.template')
            ->with('success', 'Đã cập nhật mẫu tin nhắn Lark thành công!');
    }

    /**
     * Test gửi theo mẫu tin nhắn
     */
    public function testTemplate()
    {
        $settings = LarkSetting::getActiveSettings();

        if (!$settings || !$settings->webhook_url) {
            return redirect()->route('lark-settings.template')
                ->with('error', 'Vui lòng cấu hình webhook URL trước khi test.');
        }

        if (empty($settings->message_template)) {
            return redirect()->route('lark-settings.template')
                ->with('error', 'Vui lòng nhập mẫu tin nhắn trước khi test.');
        }

        try {
            $service = new LarkNotificationService();

            // Dữ liệu giả lập để thay vào template khi test
            $data = [
                'title' => 'Báo cáo: Demo',
                'project_name' => 'DTI Ads Demo',
                'period' => '01/11/2025 - 11/11/2025',
                'revenue' => 123456789.12,
                'spend' => 34567890.45,
                'catse_cost' => 12340000.00,
                'expected_profit' => 60000000.00,
            ];

            // Gửi tin theo template
            $rendered = $service->renderMessageTemplate($settings->message_template, $data);
            $result = $service->sendTextMessage($rendered);

            if ($result) {
                return redirect()->route('lark-settings.template')
                    ->with('success', 'Đã gửi tin nhắn test theo mẫu thành công!');
            } else {
                return redirect()->route('lark-settings.template')
                    ->with('error', 'Gửi tin nhắn test thất bại. Vui lòng kiểm tra webhook và nội dung mẫu.');
            }
        } catch (\Exception $e) {
            return redirect()->route('lark-settings.template')
                ->with('error', 'Lỗi khi test mẫu: ' . $e->getMessage());
        }
    }
}
