<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function get(array $args = [])
    {
        $counts = isset($args['post_counts']) ? (bool) $args['post_counts'] : false;
        $tool_counts = isset($args['tool_counts']) ? (bool) $args['tool_counts'] : false;
        $hierarchy = isset($args['hierarchy']) ? (bool) $args['hierarchy'] : false;
        $empty_tools = isset($args['hide_empty_tools']) ? (bool) $args['hide_empty_tools'] : false;
        $empty_posts = isset($args['hide_empty_posts']) ? (bool) $args['hide_empty_posts'] : false;
        $order = isset($args['order_by']) ? $args['order_by'] : 'order';
        $translation = isset($args['translation']) ? (bool) $args['translation'] : true;
        $type = isset($args['type']) ? $args['type'] : null;

        $query = Category::active();

        $list = null;
        $query->when($empty_posts, function ($q) {
            return $q->has('posts');
        });
        $query->when($empty_tools, function ($q) {
            return $q->has('tools');
        });
        $query->when($type == 'tool', function ($q) {
            return $q->tool();
        });
        $query->when($type == 'post', function ($q) {
            return $q->post();
        });
        $query->when($translation, function ($q) {
            return $q->with('translations');
        });
        $query->when($hierarchy, function ($q) {
            return $q->with('children');
        });
        $query->when(!$hierarchy, function ($q) {
            return $q->parents();
        });
        $query->when($counts, function ($q) {
            return $q->withCount('posts');
        });
        $query->when($tool_counts, function ($q) {
            return $q->withCount('tools');
        });
        $query->when($order, function ($q) use ($order) {
            return $q->orderBy($order);
        });

        $list = $query->get();

        return $list;
    }

    /**
     *  find category by slug
     *
     * @PermissionAnnotation(name="ignore")
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function findBySlug($slug)
    {
        return Category::bySlug($slug)->first();
    }

    /**
     *  Get list of posts
     *
     * @PermissionAnnotation(name="ignore")
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function getBySlug($slug)
    {
        return Category::bySlug($slug)->firstOrFail();
    }

    /**
     *  Get list of posts
     *
     * @PermissionAnnotation(name="ignore")
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function getBySlugWithChildren($slug)
    {
        return Category::bySlug($slug)->with('children')->firstOrFail();
    }
}
