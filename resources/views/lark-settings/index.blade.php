<x-layouts.app :title="__('Cấu hình Lark')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-2">Cấu hình Lark</flux:heading>
                <flux:subheading>Cấu hình webhook để gửi báo cáo lên Lark</flux:subheading>
            </div>
            <flux:button :href="route('reports.index')" wire:navigate>
                Quay lại báo cáo
            </flux:button>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form cấu hình -->
        <div class="rounded-md border p-6">
            <form action="{{ route('lark-settings.update') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="webhook_url" class="block text-sm font-medium mb-2">
                        Webhook URL <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="url"
                        id="webhook_url"
                        name="webhook_url"
                        value="{{ old('webhook_url', $settings->webhook_url) }}"
                        class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900"
                        placeholder="https://open.larksuite.com/open-apis/bot/v2/hook/xxxxxxxxxxxxxxxx"
                        required>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Nhập webhook URL từ Lark Bot của bạn
                    </p>
                </div>

                <div>
                    <label for="webhook_secret" class="block text-sm font-medium mb-2">
                        Webhook Secret (Tùy chọn)
                    </label>
                    <input
                        type="text"
                        id="webhook_secret"
                        name="webhook_secret"
                        value="{{ old('webhook_secret', $settings->webhook_secret) }}"
                        class="w-full rounded-md border p-2 bg-white dark:bg-zinc-900"
                        placeholder="Nhập secret nếu có">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Secret key cho webhook (nếu được cấu hình trong Lark Bot)
                    </p>
                </div>

                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            id="enabled"
                            name="enabled"
                            value="1"
                            {{ old('enabled', $settings->enabled) ? 'checked' : '' }}
                            class="mr-2">
                        <span class="text-sm font-medium">Kích hoạt gửi tin nhắn</span>
                    </label>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Bật/tắt chức năng gửi báo cáo lên Lark
                    </p>
                </div>

                <div class="flex gap-3">
                    <flux:button type="submit" variant="primary">
                        Lưu cấu hình
                    </flux:button>

                    @if(!empty($settings?->webhook_url))
                        <flux:button type="button" variant="outline" onclick="testWebhook()">
                            Test Webhook
                        </flux:button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Hướng dẫn -->
        <div class="rounded-md border p-6 bg-gray-50 dark:bg-zinc-800">
            <h3 class="font-semibold mb-3">Hướng dẫn cấu hình Lark Bot</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm">
                <li>Truy cập vào <a href="https://open.larksuite.com/" target="_blank"
                                    class="text-blue-600 hover:underline">Lark Open Platform</a></li>
                <li>Tạo một Bot mới hoặc sử dụng Bot có sẵn</li>
                <li>Vào phần cấu hình Bot và tìm mục "Webhook"</li>
                <li>Sao chép Webhook URL và dán vào form bên trên</li>
                <li>Nếu có webhook secret, nhập vào ô tương ứng</li>
                <li>Lưu cấu hình và test webhook để đảm bảo hoạt động</li>
            </ol>
        </div>
    </div>
    @push('scripts')
        <script>
            function testWebhook() {
                if (confirm('Bạn có muốn test webhook không? Một tin nhắn test sẽ được gửi đến Lark.')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("lark-settings.test") }}';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    @endpush
</x-layouts.app>
