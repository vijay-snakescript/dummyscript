<x-app-layout>
    <x-posts-form :locales="$locales" :post="$post" :route="route('admin.posts.edit', $post)" :title="__('admin.editPost')" :button_text="__('common.update')"
        :users="$users" :categories="$categories" :tags="$tags" />
</x-app-layout>
