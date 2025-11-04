<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="'Giao diện'" :subheading="'Thiết lập chế độ sáng/tối cho tài khoản'">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">Sáng</flux:radio>
            <flux:radio value="dark" icon="moon">Tối</flux:radio>
            <flux:radio value="system" icon="computer-desktop">Theo hệ thống</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>
