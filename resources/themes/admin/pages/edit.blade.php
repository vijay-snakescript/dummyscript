<x-app-layout>
    <x-pages-form :locales="$locales" :page="$page" :route="route('admin.pages.edit', $page)" :title="__('admin.editPage')" :button_text="__('common.update')" />
</x-app-layout>
