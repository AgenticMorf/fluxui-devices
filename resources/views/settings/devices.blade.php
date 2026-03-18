<x-layouts.app :title="__('Devices & Sessions')">
    <section class="w-full">
        @includeIf('partials.settings-heading')

        <x-settings.layout heading="Devices & Sessions" subheading="Manage your devices and active sessions">
            <livewire:fluxui-devices.device-manager />

            <flux:separator class="my-10" />

            <livewire:fluxui-devices.session-manager />
        </x-settings.layout>
    </section>
</x-layouts.app>
