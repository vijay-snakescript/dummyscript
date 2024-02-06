<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Post;

class PostRepository
{
    public function list(array $args = [], $query = false)
    {
        $post_id = $args['post_id'] ?? false;
        $order = $args['order'] ?? '';
        $featured = $args['featured'] ?? '';
        $limit = $args['limit'] ?? false;
        $categories = $args['categories'] ?? false;
        $tags = $args['tags'] ?? false;
        $views = $args['views'] ?? false;
        $views_count = $args['views_count'] ?? false;
        $author = $args['author'] ?? true;
        $active = $args['active'] ?? true;
        $translation = $args['translation'] ?? true;
        $media = $args['media'] ?? true;
        $search = $args['search'] ?? false;
        $make = $args['make'] ?? 'get';
        $by_author = $args['by_author'] ?? '';
        $recent_days = $args['recent_days'] ?? false;
        $author_exists = $args['author_exists'] ?? true;
        $translated = $args['translated'] ?? false;

        if (!$query) {
            $query = Post::query();
        }

        $query->when($author_exists, function ($q) {
            return $q->has('author');
        });
        $query->when($translated, function ($q) use ($translated) {
            return $q->translatedIn($translated !== true ? $translated : null);
        });
        $query->when(!empty($search), function ($q) use ($search) {
            return $q->search($search);
        });
        $query->when($by_author, function ($q) use ($by_author) {
            return $q->author($by_author);
        });
        $query->when($active, function ($q) {
            return $q->published();
        });
        $query->when($translation, function ($q) {
            return $q->with('translations');
        });
        $query->when($media, function ($q) {
            return $q->with('media');
        });
        $query->when($order == 'latest', function ($q) {
            return $q->latest();
        });
        $query->when($order == 'oldest', function ($q) {
            return $q->oldest();
        });
        $query->when($order == 'random', function ($q) {
            return $q->inRandomOrder();
        });
        $query->when($order == 'popular', function ($q) {
            return $q->orderByViews('desc');
        });
        $query->when($featured == 'featured', function ($q) {
            return $q->featured();
        });
        $query->when($featured == 'editor', function ($q) {
            return $q->editorchoice();
        });
        $query->when($views, function ($q) {
            return $q->with('views');
        });
        $query->when($views_count, function ($q) {
            return $q->withCount('views');
        });
        $query->when($categories, function ($q) {
            return $q->with('categories');
        });
        $query->when($tags, function ($q) {
            return $q->with('tags');
        });
        $query->when($author, function ($q) {
            return $q->with('author');
        });
        $query->when($recent_days, function ($q) use ($recent_days) {
            return $q->where('created_at', '>=', Carbon::now()->subDays(intval($recent_days))->toDateTimeString());
        });

        $list = null;
        if ($make == 'limit') {
            $list = $query->limit($limit)->get();
        } elseif ($make == 'paginate') {
            $list = $query->paginate($limit);
        } elseif ($make == 'get') {
            $list = $query->get();
        } elseif ($make == 'first') {
            $list = $query->first();
        } elseif ($make == 'findOrFail') {
            $list = $query->findOrFail($post_id);
        } elseif ($make == 'firstOrFail') {
            $list = $query->firstOrFail();
        } elseif ($make == 'find') {
            $list = $query->find($post_id);
        }

        return $list;
    }
}
