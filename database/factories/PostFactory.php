<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            // $imageUrl = $this->faker->imageUrl(1920, 1080, null, true);
            $images = collect([
                'https://images.freeimages.com/images/large-previews/46e/red-beetle-1416148.jpg',
                'https://images.freeimages.com/images/large-previews/ab7/gerber-and-rose-2-1544099.jpg',
                'https://images.freeimages.com/images/large-previews/ebd/flamenco-1551386.jpg',
                'https://images.freeimages.com/images/large-previews/631/yellow-flowers-1463581.jpg',
                'https://images.freeimages.com/images/large-previews/3c9/boxcar-textures-3-1187581.jpg',
                'https://images.freeimages.com/images/large-previews/415/brotherhood-at-sunset-1-1244631.jpg',
                'https://images.freeimages.com/images/large-previews/af4/sparklers-2-1200038.jpg',
                'https://images.freeimages.com/images/large-previews/b65/bald-eagle-1635769.jpg',
            ]);
            $post->addMediaFromUrl($images->shuffle()->first())->toMediaCollection('featured-image');

            $tags = Tag::active()->limit($this->faker->biasedNumberBetween(1, 6))->pluck('id');
            $categories = Category::active()->post()->limit($this->faker->biasedNumberBetween(1, 2))->pluck('id');
            $post->tags()->sync($tags);
            $post->categories()->sync($categories);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = $this->faker->realText(60);
        $content = '<p>' . implode("</p>\n\n<p>", $this->faker->paragraphs($this->faker->biasedNumberBetween(8, 20))) . '</p>';
        return [
            'author_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'contents' => $content,
            'status' => 'published',
            'featured' => false,
            'comments_status' => true,
            'meta_title' => $this->faker->realText(60),
            'meta_description' => $this->faker->realText(160),
            'og_title' => $this->faker->realText(60),
            'og_description' => $this->faker->realText(160),
            'excerpt' => $this->faker->realText(200),
        ];
    }

    /**
     * Indicate that the post is featured
     *
     * @return static
     */
    public function featured()
    {
        return $this->state(fn (array $attributes) => [
            'featured' => 1,
        ]);
    }

    /**
     * Indicate that the post is featured
     *
     * @return static
     */
    public function editorchoice()
    {
        return $this->state(fn (array $attributes) => [
            'featured' => 2,
        ]);
    }
}
