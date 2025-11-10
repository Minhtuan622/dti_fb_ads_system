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
}
