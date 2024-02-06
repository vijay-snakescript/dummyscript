<?php

namespace Database\Seeders;

use App\Models\Faqs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FaqsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('faqs')->count() == 0) {
            $faqs = [
                ['question' => 'Illum aut sed quidem rem earum qui harum.', 'answer' => 'Corporis consequatur consequatur fugit error recusandae ad enim. Rerum exercitationem et mollitia eum ex. Quibusdam qui non commodi numquam. Voluptatem ipsum dolores tempora sit nisi nulla eum occaecati. Eius quia explicabo assumenda vel impedit earum aut.

Aut veniam aut est odit quia omnis. Quis voluptates quae mollitia non alias. Non eligendi ipsum similique commodi ducimus perferendis magni. Quo officia alias aut illum.', 'status' => '1', 'pricing' => '0'],
                ['question' => 'Dolorem qui officia nemo a qui numquam at nulla.', 'answer' => 'Sed ratione nihil beatae. Et numquam quaerat ut a eveniet architecto saepe dignissimos. Ut qui nisi ut optio nesciunt et veniam dolorem. Autem eveniet voluptates omnis nam est maxime sint sed.

Dolores eum et quidem qui. Reiciendis aperiam assumenda nemo dignissimos asperiores in. Dolor dolore inventore et et non dolor facilis. Et adipisci ut omnis ratione placeat illum vel.', 'status' => '1', 'pricing' => '0'],
                ['question' => 'Et qui omnis veritatis fuga ullam dignissimos aliquam.', 'answer' => 'Molestiae recusandae possimus odio qui neque aut. Porro neque sunt consequatur porro minus. Necessitatibus qui non mollitia quis nemo sed odio. A et sint dolorum sunt in.

Quia laudantium eligendi et distinctio est qui. Tempore ut eum ea ducimus error ratione cumque. A ratione qui et architecto quae voluptatem. Iste deserunt illum accusamus dignissimos assumenda aliquid et.', 'status' => '1', 'pricing' => '0'],
                ['question' => 'Consectetur eos veritatis est et nemo molestiae blanditiis.', 'answer' => 'Magnam animi aut autem et earum error aut. Corporis eos nisi tempora impedit. Ipsam aliquid nemo similique blanditiis commodi.

Et dolores minima quod. Vel possimus harum dolorum illum et. Quo sunt vel animi. Saepe perspiciatis tempora enim quia aliquam tempore.', 'status' => '1', 'pricing' => '0'],
                ['question' => 'Dignissimos dolore sunt quam ab velit libero.', 'answer' => 'Repudiandae sunt consequatur mollitia. Et dolorem similique omnis voluptatem consequatur. Quo repudiandae pariatur iste sed est. Et cumque fugiat quis adipisci molestias.

Illum eligendi dolorem magnam repudiandae sed fuga sequi. Eaque et molestiae hic officiis dolore. Veniam quod quas error voluptate. Nobis voluptatem maiores dicta porro omnis aut voluptatem unde.', 'status' => '1', 'pricing' => '0'],
                ['question' => 'Adipisci qui aliquam accusamus est eveniet sed laudantium.', 'answer' => 'Ex odit aliquid voluptatibus et laboriosam sit ad. Blanditiis nobis est culpa praesentium sed. Iure molestiae iste voluptatibus occaecati. Rerum ipsam voluptas temporibus et cumque nemo.

Tempora id quia eos dolor error rerum dolore. Nesciunt possimus est dolores in quidem nobis. Impedit repudiandae ut vero eum. Repellat aut atque natus.', 'status' => '1', 'pricing' => '1'],
                ['question' => 'Sunt nisi et voluptatem rerum suscipit iure nulla sint non.', 'answer' => 'Sunt hic itaque aut fugit itaque qui. Eligendi qui accusamus ratione tempore modi.

Quia mollitia suscipit eius dolores nemo sit ut iusto. Itaque voluptatem excepturi fugit quae aut. Non est quis illum earum. Accusantium et quae ut consequatur voluptas.', 'status' => '1', 'pricing' => '1'],
                ['question' => 'Provident commodi qui commodi iusto aliquam quia debitis.', 'answer' => 'Quas ex sed sed ea sed accusamus. Fugit maiores amet quae molestias. Aspernatur vero debitis aspernatur voluptas sit ut veniam.

Quia ut molestias aperiam ut quo. Qui quo ea quia sed dolorem aut. Quae quaerat dolorem adipisci. Molestiae adipisci qui a dolor distinctio voluptate non.', 'status' => '1', 'pricing' => '1'],
                ['question' => 'Explicabo ab aliquam tempore dolore architecto molestiae magnam explicabo.', 'answer' => 'Voluptatum et occaecati impedit aperiam aut sed est. Voluptas ex rerum eveniet dignissimos deleniti voluptate ut. Voluptatem excepturi voluptatibus ut quidem unde et. Velit ex harum officiis sit quam nostrum iure.

Non porro consequatur minima hic amet harum. Voluptates non facilis inventore et quibusdam reprehenderit. Ea dicta est perspiciatis iusto et quia ipsa. Omnis et voluptates consequuntur molestias quos sunt.', 'status' => '1', 'pricing' => '1']
            ];

            foreach ($faqs as $faq) {
                Faqs::create($faq);
            }
        }
    }
}
