<x-layouts.app :title="__('Mẫu tin nhắn Lark')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="mb-2">Mẫu tin nhắn Lark</flux:heading>
                <flux:subheading>Tùy chỉnh nội dung tin nhắn gửi lên Lark bằng placeholder</flux:subheading>
            </div>
            <flux:button :href="route('lark-settings.index')" wire:navigate>
                Quay lại cấu hình Lark
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

        <!-- Form template -->
        <div class="rounded-md border p-6">
            <form action="{{ route('lark-settings.template.update') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="message_template" class="block text-sm font-medium mb-2">
                        Nội dung mẫu tin nhắn (Text)
                    </label>
                    <textarea
                        id="message_template"
                        name="message_template"
                        rows="10"
                        class="w-full rounded-md border p-3 bg-white dark:bg-zinc-900"
                    >{{ old('message_template', $settings->message_template) }}</textarea>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        Placeholder hỗ trợ:
                        <code>{{ '{' }}{{ '{' }} title {{ '}' }}{{ '}' }}</code>,
                        <code>{{ '{' }}{{ '{' }} project_name {{ '}' }}{{ '}' }}</code>,
                        <code>{{ '{' }}{{ '{' }} period {{ '}' }}{{ '}' }}</code>,
                        <code>{{ '{' }}{{ '{' }} revenue {{ '}' }}{{ '}' }}</code>,
                        <code>{{ '{' }}{{ '{' }} spend {{ '}' }}{{ '}' }}</code>,
                        <code>{{ '{' }}{{ '{' }} catse_cost {{ '}' }}{{ '}' }}</code>,
                        <code>{{ '{' }}{{ '{' }} expected_profit {{ '}' }}{{ '}' }}</code>,
                        <code>{{ '{' }}{{ '{' }} profit {{ '}' }}{{ '}' }}</code>
                    </p>
                </div>

                <div class="flex gap-3">
                    <flux:button type="submit" variant="primary">
                        Lưu mẫu tin nhắn
                    </flux:button>

                    @if(!empty($settings->message_template))
                        <flux:button type="button" variant="outline" onclick="testTemplate()">
                            Gửi tin test theo mẫu
                        </flux:button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Gợi ý -->
        <div class="rounded-md border p-6 bg-gray-50 dark:bg-zinc-800">
            <h3 class="font-semibold mb-3">Gợi ý thiết kế mẫu</h3>
            <ul class="list-disc list-inside space-y-2 text-sm">
                <li>Dùng emoji để tăng tính trực quan: 📊 📅 💰 💸 💼 📈 🔮</li>
                <li>Giữ nội dung ngắn gọn, súc tích và có thứ tự rõ ràng.</li>
                <li>Đảm bảo placeholder viết đúng chính tả như danh sách hỗ trợ ở trên.</li>
            </ul>
        </div>
    </div>

    @push('scripts')
        <script>
            function testTemplate() {
                if (confirm('Gửi tin nhắn test theo mẫu?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("lark-settings.template.test") }}';

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