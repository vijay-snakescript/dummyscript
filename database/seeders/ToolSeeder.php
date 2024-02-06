<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Filesystem\Filesystem;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedToolCategories();
        $this->seedTools();
    }

    protected function seedToolCategories()
    {
        $categories = [
            [
                'type' => 'tool',
                'status' => true,
                'order' => 1,
                'name' => 'Text Analysis Tools',
                'slug' => 'text-analysis-tools',
                'description' => 'A complete set of text tools is now at your fingertips. Check plagiarism, rewrite an article, run a spell checker, count words or change text case.',
                'title' => 'Text Analysis Tools',
            ],
            [
                'type' => 'tool',
                'status' => true,
                'order' => 2,
                'name' => 'Password Managment Tools',
                'slug' => 'password-management-tools',
                'description' => 'A complete set of password tools is now at your fingertips. Generate passwords, check strengths and much more.',
                'title' => 'Password Managment Tools',
            ],
            [
                'type' => 'tool',
                'status' => true,
                'order' => 3,
                'name' => 'Online Calculators',
                'slug' => 'online-calculators',
                'description' => 'A complete set of calculation tools is now at your fingertips. Check calculations, find out results and much more.',
                'title' => 'Online Calculators',
            ],
            [
                'type' => 'tool',
                'status' => true,
                'order' => 4,
                'name' => 'Unit Converters',
                'slug' => 'unit-calculators',
                'description' => 'A complete set of unit tools is now at your fingertips. Check different unit conversions, find out different results and much more.',
                'title' => 'Unit Calculators',
            ],
            [
                'type' => 'tool',
                'status' => true,
                'order' => 5,
                'name' => 'Binary Converters',
                'slug' => 'binary-converter',
                'description' => 'A complete set of binary tools is now at your fingertips. Check different binary conversions, find out different results and much more.',
                'title' => 'Binary Converters',
            ],
            [
                'type' => 'tool',
                'status' => true,
                'order' => 5,
                'name' => 'Image Tools',
                'slug' => 'image-tools',
                'description' => 'A complete set of image tools is now at your fingertips. Check different binary conversions, find out different results and much more.',
                'title' => 'Image Tools',
            ],
            [
                'type' => 'tool',
                'status' => true,
                'order' => 5,
                'name' => 'Domains Tools',
                'slug' => 'domain-tools',
                'description' => 'A complete set of domain tools is now at your fingertips. Check different binary conversions, find out different results and much more.',
                'title' => 'Domain Tools',
            ],
            [
                'type' => 'tool',
                'status' => true,
                'order' => 5,
                'name' => 'Tags Tools',
                'slug' => 'tag-tools',
                'description' => 'A complete set of tag tools is now at your fingertips. Check different binary conversions, find out different results and much more.',
                'title' => 'Tags Tools',
            ],
            [
                'type' => 'tool',
                'status' => true,
                'order' => 5,
                'name' => 'Development Tools',
                'slug' => 'development-tools',
                'description' => 'A complete set of tag tools is now at your fingertips. Check different binary conversions, find out different results and much more.',
                'title' => 'Development Tools',
            ],
            [
                'type' => 'tool',
                'status' => true,
                'order' => 5,
                'name' => 'Website Management Tools',
                'slug' => 'website-management-tools',
                'description' => 'A complete set of Website tools is now at your fingertips. Find out different results and much more.',
                'title' => 'Website Management Tools',
            ],

        ];

        foreach ($categories as $item) {
            $category = Category::tool()->slug($item['slug'])->firstOr(function () {
                return Category::make();
            });
            $category->fill($item);
            $category->save();
        }
    }

    protected function seedTools()
    {
        $wc_tool_value_auth = 100;
        $wc_tool_value_guest = 50;
        $du_tool_value_auth = 10;
        $du_tool_value_guest = 5;
        $fs_tool_value_auth = 5;
        $fs_tool_value_guest = 1;
        $no_file_tool_value_auth = 2;
        $no_file_tool_value_guest = 1;
        $no_domain_tool_value_auth = 2;
        $no_domain_tool_value_guest = 1;
        $tools = [
            [
                'category' => 'password-management-tools',
                'tools' => [
                    [
                        'display' => 0,
                        'slug' => "md5-generator",
                        'icon' => resource_path('themes/default/assets/images/icons/md5_generator.svg'),
                        'class_name' => 'App\Tools\Md5Generator',
                        'icon_type' => 'class',
                        'icon_class' => 'md5-generator',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'MD5 Generator', 'description' => 'This is an easy to use tool that enables you to generate the MD5 hash of a string. In order to use the tool, enter the text you want to convert to MD5 below and click on ‘Generate’ button.', 'content' => 'This is an easy to use tool that enables you to generate the MD5 hash of a string. In order to use the tool, enter the text you want to convert to MD5 below and click on ‘Generate’ button. Edit me from admin panel...']
                    ],
                    [
                        'display' => 1,
                        'slug' => "wordpress-password-generator",
                        'icon' => resource_path('themes/default/assets/images/icons/wp_generator.svg'),
                        'class_name' => 'App\Tools\WordpressPasswordGenerator',
                        'icon_type' => 'class',
                        'icon_class' => 'wp-generator',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Wordpress Password Generator', 'description' => 'This is an easy to use tool that enables you to generate the WordPress hash of a string. In order to use the tool, enter the text you want to convert to WP hash below and click on ‘Generate’ button.', 'content' => 'This is an easy to use tool that enables you to generate the MD5 hash of a string. In order to use the tool, enter the text you want to convert to MD5 below and click on ‘Generate’ button. Edit me from admin panel...']
                    ],
                    [
                        'display' => 11,
                        'slug' => "password-strength-checker",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\PasswordStrengthChecker',
                        'icon_type' => 'class',
                        'icon_class' => 'strength-checker',
                        'en' => ['name' => 'Password Strength Checker', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 16,
                        'slug' => "password-generator",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\PasswordGenerator',
                        'icon_type' => 'class',
                        'icon_class' => 'online-text-editor',
                        'en' => ['name' => 'Password Generator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                ]

            ],
            [
                'category' => 'online-calculators',
                'tools' => [
                    [
                        'display' => 19,
                        'slug' => "age-calculator",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\AgeCalculator',
                        'icon_type' => 'class',
                        'icon_class' => 'age-calculator',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Age Calculator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 20,
                        'slug' => "percentage-calculator",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\PercentageCalculator',
                        'icon_type' => 'class',
                        'icon_class' => 'percentage-calculator',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Percentage Calculator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 21,
                        'slug' => "average-calculator",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\AverageCalculator',
                        'icon_type' => 'class',
                        'icon_class' => 'average-calculator',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Average Calculator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 22,
                        'slug' => "sales-tax-calculator",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\SalesTaxCalculator',
                        'icon_type' => 'class',
                        'icon_class' => 'sales-tax-calculator',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Sales Tax Calculator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 23,
                        'slug' => "discount-calculator",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\DiscountCalculator',
                        'icon_type' => 'class',
                        'icon_class' => 'discount-calculator',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Discount Calculator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 24,
                        'slug' => "probability-calculator",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\ProbabilityCalculator',
                        'icon_type' => 'class',
                        'icon_class' => 'probability-calculator',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Probability  Calculator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    // [
                    //     'display' => 25,
                    //     'slug' => "simple-interest-calcultator",
                    //     'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                    //     'class_name' => 'App\Tools\SimpleInterestCalculator',
                    //     'icon_type' => 'class',
                    //     'icon_class' => 'simple-interest-calculator',
                    //     'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                    //     'en' => ['name' => 'Simple Interest Calculator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    // ],

                ]
            ],
            [
                'category' => 'text-analysis-tools',
                'tools' => [
                    [
                        'display' => 2,
                        'slug' => "rewrite-article",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\RewriteArticle',
                        'icon_type' => 'class',
                        'icon_class' => 'article-rewriter',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Rewrite Article', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 3,
                        'slug' => "case-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/uppercase_to_lowercase.svg'),
                        'class_name' => 'App\Tools\CaseConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'uppercase-to-lowercase',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Case Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 4,
                        'slug' => "reverse-text-generator",
                        'icon' => resource_path('themes/default/assets/images/icons/reverse_text_generator.svg'),
                        'class_name' => 'App\Tools\ReverseTextGenerator',
                        'icon_type' => 'class',
                        'icon_class' => 'reverse-text-generator',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Reverse Text Generator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 5,
                        'slug' => "jpg-to-word",
                        'icon' => resource_path('themes/default/assets/images/icons/img_word.svg'),
                        'class_name' => 'App\Tools\JPGToWord',
                        'icon_type' => 'class',
                        'icon_class' => 'img-word',
                        'properties' => ["properties" => ["fs-tool", "du-tool"], "auth" => ["fs-tool" => $fs_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["fs-tool" => $fs_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'JPG To Word', 'description' => 'Online JPG to Word converter allows you to turn jpg image into word documents in a few seconds.', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 5,
                        'slug' => "image-to-text-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/img_text.svg'),
                        'class_name' => 'App\Tools\ImageToText',
                        'icon_type' => 'class',
                        'icon_class' => 'img-text',
                        'properties' => ["properties" => ["fs-tool", "du-tool"], "auth" => ["fs-tool" => $fs_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["fs-tool" => $fs_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Image to Text Converter', 'description' => 'To extract text from image, we introduce a free online OCR (Optical Character Recognition) service. Upload a photo to the image to text converter online, click on convert and get your text file instantly.', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 6,
                        'slug' => "online-text-editor",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\OnlineTextEditor',
                        'icon_type' => 'class',
                        'icon_class' => 'online-text-editor',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Online Text Editor', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 7,
                        'slug' => "rgb-to-hex",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\RgbToHexConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'rgb-hex',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'RGB to Hex Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 8,
                        'slug' => "small-text-generator",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\SmallTextGenerator',
                        'icon_type' => 'class',
                        'icon_class' => 'small-text-generator',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Small Text Generator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 10,
                        'slug' => "word-combiner",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\WordCombiner',
                        'icon_type' => 'class',
                        'icon_class' => 'word-combiner',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Word Combiner', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                ],
            ],
            [
                'category' => 'unit-calculators',
                'tools' => [
                    [
                        'display' => 26,
                        'slug' => "power-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\PowerConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'power-converter',
                        'en' => ['name' => 'Power Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 27,
                        'slug' => "weight-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\WeightConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'weight-converter',
                        'en' => ['name' => 'Weight Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 28,
                        'slug' => "temperature-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\TemperatureConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'temperature-converter',
                        'en' => ['name' => 'Temperature Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 29,
                        'slug' => "voltage-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\VoltageConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'electric-voltage-converter',
                        'en' => ['name' => 'Electric / Voltage Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 30,
                        'slug' => "area-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\AreaConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'area-converter',
                        'en' => ['name' => 'Area Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 31,
                        'slug' => "length-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\LengthConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'length-converter',
                        'en' => ['name' => 'Length Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 32,
                        'slug' => "byte-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\ByteConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'byte-bit-converter',
                        'en' => ['name' => 'Byte/Bit Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 33,
                        'slug' => "time-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\TimeConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'time-converter',
                        'en' => ['name' => 'Time Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 34,
                        'slug' => "pressure-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\PressureConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'pressure-converter',
                        'en' => ['name' => 'Pressure Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 35,
                        'slug' => "speed-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\SpeedConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'speed-converter',
                        'en' => ['name' => 'Speed Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 36,
                        'slug' => "volume-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\VolumeConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'volume-converter',
                        'en' => ['name' => 'Volume Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 37,
                        'slug' => "torque-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\TorqueConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'torque-converter',
                        'en' => ['name' => 'Torque Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                ]
            ],
            [
                'category' => 'binary-converter',
                'tools' => [
                    [
                        'display' => 38,
                        'slug' => "text-to-binary",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\TextToBinary',
                        'icon_type' => 'class',
                        'icon_class' => 'text-to-binary',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Text To Binary', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 39,
                        'slug' => "binary-to-text",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\BinaryToText',
                        'icon_type' => 'class',
                        'icon_class' => 'binary-to-text',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Binary To Text', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 40,
                        'slug' => "binary-to-hex",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\BinaryToHex',
                        'icon_type' => 'class',
                        'icon_class' => 'binary-to-hex',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Binary To HEx', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 40,
                        'slug' => "hex-to-binary",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\HexToBinary',
                        'icon_type' => 'class',
                        'icon_class' => 'hex-to-binary',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Hex To Binary', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 41,
                        'slug' => "binary-to-ascii",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\BinaryToAscii',
                        'icon_type' => 'class',
                        'icon_class' => 'binary-to-ascii',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Binary To ASCII', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 42,
                        'slug' => "ascii-to-binary",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\AsciiToBinary',
                        'icon_type' => 'class',
                        'icon_class' => 'ascii-to-binary',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'ASCII To Binary', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 43,
                        'slug' => "binary-to-decimal",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\BinaryToDecimal',
                        'icon_type' => 'class',
                        'icon_class' => 'binary-to-decimal',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Binary To Decimal', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 44,
                        'slug' => "decimal-to-binary",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\DecimalToBinary',
                        'icon_type' => 'class',
                        'icon_class' => 'decimal-to-binary',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Decimal To Binary', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 45,
                        'slug' => "text-to-ascii",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\TextToAscii',
                        'icon_type' => 'class',
                        'icon_class' => 'text-to-ascii',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Text To ASCII', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 45,
                        'slug' => "decimal-to-hex",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\DecimalToHex',
                        'icon_type' => 'class',
                        'icon_class' => 'decimal-to-hex',
                        'properties' => ["properties" => ["wc-tool", "du-tool"], "auth" => ["wc-tool" => $wc_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["wc-tool" => $wc_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Decimal To Hex', 'description' => 'Age calculator online helps you find your age from date of birth or interval between two dates. Calculate age in years, months, weeks, days, hours, minutes, and seconds.', 'content' => 'Edit me from admin panel...']
                    ],
                ]
            ],
            [
                'category' => 'image-tools',
                'tools' => [
                    [
                        'display' => 9,
                        'slug' => "png-to-jpg",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\PngToJpgConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'online-png',
                        'properties' => ["properties" => ["fs-tool", "du-tool", "no-file-tool"], "auth" => ["fs-tool" => $fs_tool_value_auth, "du-tool" => $du_tool_value_auth, "no-file-tool" => $no_file_tool_value_auth], "guest" => ["fs-tool" => $fs_tool_value_guest, "du-tool" => $du_tool_value_guest, "no-file-tool" => $no_file_tool_value_guest]],
                        'en' => ['name' => 'Png to JPG Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 9,
                        'slug' => "jpg-to-png",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\JpgToPngConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'convert-jpg',
                        'properties' => ["properties" => ["fs-tool", "du-tool", "no-file-tool"], "auth" => ["fs-tool" => $fs_tool_value_auth, "du-tool" => $du_tool_value_auth, "no-file-tool" => $no_file_tool_value_auth], "guest" => ["fs-tool" => $fs_tool_value_guest, "du-tool" => $du_tool_value_guest, "no-file-tool" => $no_file_tool_value_guest]],
                        'en' => ['name' => 'JPG to PNG Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 46,
                        'slug' => "reverse-image-search",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\ReverseImageSearch',
                        'icon_type' => 'class',
                        'icon_class' => 'reverse-image-search',
                        'properties' => ["properties" => ["fs-tool", "du-tool"], "auth" => ["fs-tool" => $fs_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["fs-tool" => $fs_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Reverse Image Search', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 47,
                        'slug' => "text-image",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\TextToImage',
                        'icon_type' => 'class',
                        'icon_class' => 'text-to-image',
                        'en' => ['name' => 'Text To Image', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 63,
                        'slug' => "jpg-converter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\JpgConverter',
                        'icon_type' => 'class',
                        'icon_class' => 'convert-jpg',
                        'properties' => ["properties" => ["fs-tool", "du-tool", "no-file-tool"], "auth" => ["fs-tool" => $fs_tool_value_auth, "du-tool" => $du_tool_value_auth, "no-file-tool" => $no_file_tool_value_auth], "guest" => ["fs-tool" => $fs_tool_value_guest, "du-tool" => $du_tool_value_guest, "no-file-tool" => $no_file_tool_value_guest]],
                        'en' => ['name' => 'Jpg Converter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 64,
                        'slug' => "favicon-generator",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\FaviconGenerator',
                        'icon_type' => 'class',
                        'icon_class' => 'reverse-image-search',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Favicon Generator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 65,
                        'slug' => "image-compressor",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\ImageCompressor',
                        'icon_type' => 'class',
                        'icon_class' => 'image-editor',
                        'properties' => ["properties" => ["fs-tool", "du-tool", "no-file-tool"], "auth" => ["fs-tool" => $fs_tool_value_auth, "du-tool" => $du_tool_value_auth, "no-file-tool" => $no_file_tool_value_auth], "guest" => ["fs-tool" => $fs_tool_value_guest, "du-tool" => $du_tool_value_guest, "no-file-tool" => $no_file_tool_value_guest]],
                        'en' => ['name' => 'Image Compressor', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 66,
                        'slug' => "image-resizer",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\ImageResizer',
                        'icon_type' => 'class',
                        'icon_class' => 'image-resizer',
                        'properties' => ["properties" => ["fs-tool", "du-tool"], "auth" => ["fs-tool" => $fs_tool_value_auth, "du-tool" => $du_tool_value_auth], "guest" => ["fs-tool" => $fs_tool_value_guest, "du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Image Resizer', 'description' => 'Image Resizer online allows you to resize image in seconds. Simply Upload your photo, set your required dimensions, click on the \'Resize Image\' button, and download.', 'content' => 'Image Resizer online allows you to resize image in seconds. Simply Upload your photo, set your required dimensions, click on the \'Resize Image\' button, and download.']
                    ],
                    // [
                    //     'display' => 66,
                    //     'slug' => "image-editor",
                    //     'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                    //     'class_name' => 'App\Tools\ImageEditor',
                    //     'icon_type' => 'class',
                    //     'icon_class' => 'image-editor',
                    //     'en' => ['name' => 'Image Editor', 'description' => 'Cropping an image has never been easier! Our free online Image editor Tool enables you to crop and modify an image with ease to the size of your choice. Upload your image and click on \'Crop Image\' button.', 'content' => 'Cropping an image has never been easier! Our free online Image editor Tool enables you to crop and modify an image with ease to the size of your choice. Upload your image and click on \'Crop Image\' button.']
                    // ],
                    [
                        'display' => 67,
                        'slug' => "meme-generator",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\MemeGenerator',
                        'icon_type' => 'class',
                        'icon_class' => 'meme-generator',
                        'properties' => ["properties" => ["fs-tool"], "auth" => ["fs-tool" => $fs_tool_value_auth], "guest" => ["fs-tool" => $fs_tool_value_guest]],
                        'en' => ['name' => 'Meme Generator', 'description' => 'Create awesome looking memes insanely fast. Our online meme generator lets you create your own memes, add your own images to create custom memes.', 'content' => 'Create awesome looking memes insanely fast. Our online meme generator lets you create your own memes, add your own images to create custom memes.']
                    ],
                ]
            ],
            [
                'category' => 'domain-tools',
                'tools' => [
                    [
                        'display' => 47,
                        'slug' => "domain-age-checker",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\DomainAgeChecker',
                        'icon_type' => 'class',
                        'icon_class' => 'domain-age-checker',
                        'properties' => ["properties" => ["du-tool", "no-domain-tool"], "auth" => ["du-tool" => $du_tool_value_auth, "no-domain-tool" => $no_domain_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest, "no-domain-tool" => $no_domain_tool_value_guest]],
                        'en' => ['name' => 'Domain Age Checker', 'description' => 'Find out the age and history of any domain with our Domain Age Checker. Simply enter the domain name to see its registration and expiration dates, as well as its age in years, months, and days.', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 48,
                        'slug' => "domain-name-search",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\DomainNameSearch',
                        'icon_type' => 'class',
                        'icon_class' => 'domain-name-search',
                        'properties' => ["properties" => ["du-tool", "no-domain-tool"], "auth" => ["du-tool" => $du_tool_value_auth, "no-domain-tool" => $no_domain_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest, "no-domain-tool" => $no_domain_tool_value_guest]],
                        'en' => ['name' => 'Domain Name Search', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 49,
                        'slug' => "domain-hosting-checker",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\DomainHostingChecker',
                        'icon_type' => 'class',
                        'icon_class' => 'domain-hosting-checker',
                        'properties' => ["properties" => ["du-tool", "no-domain-tool"], "auth" => ["du-tool" => $du_tool_value_auth, "no-domain-tool" => $no_domain_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest, "no-domain-tool" => $no_domain_tool_value_guest]],
                        'en' => ['name' => 'Domain Hosting Checker', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 50,
                        'slug' => "domain-authority-checker",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\DomainAuthorityChecker',
                        'icon_type' => 'class',
                        'icon_class' => 'domain-authority-checker',
                        'properties' => ["properties" => ["du-tool", "no-domain-tool"], "auth" => ["du-tool" => $du_tool_value_auth, "no-domain-tool" => $no_domain_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest, "no-domain-tool" => $no_domain_tool_value_guest]],
                        'en' => ['name' => 'Domain Authority Checker', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 51,
                        'slug' => "domain-to-api",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\DomainToIp',
                        'icon_type' => 'class',
                        'icon_class' => 'domain-to-ip',
                        'properties' => ["properties" => ["du-tool", "no-domain-tool"], "auth" => ["du-tool" => $du_tool_value_auth, "no-domain-tool" => $no_domain_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest, "no-domain-tool" => $no_domain_tool_value_guest]],
                        'en' => ['name' => 'Domain To IP', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 52,
                        'slug' => "find-dns-record",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\FindDnsRecord',
                        'icon_type' => 'class',
                        'icon_class' => 'find-dns-record',
                        'properties' => ["properties" => ["du-tool", "no-domain-tool"], "auth" => ["du-tool" => $du_tool_value_auth, "no-domain-tool" => $no_domain_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest, "no-domain-tool" => $no_domain_tool_value_guest]],
                        'en' => ['name' => 'Find DNS Record', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 52,
                        'slug' => "blacklist-check",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\BlacklistCheck',
                        'icon_type' => 'class',
                        'icon_class' => 'black-list-check',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Blacklist Checker', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 12,
                        'slug' => "what-is-my-ip",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\WhatIsMyIp',
                        'icon_type' => 'class',
                        'icon_class' => 'my-ip',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'What Is My IP', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 13,
                        'slug' => "ip-location",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\IpLocation',
                        'icon_type' => 'class',
                        'icon_class' => 'ip-loaction',
                        'properties' => ["properties" => ["du-tool", "no-domain-tool"], "auth" => ["du-tool" => $du_tool_value_auth, "no-domain-tool" => 3], "guest" => ["du-tool" => $du_tool_value_guest, "no-domain-tool" => 5]],
                        'en' => ['name' => 'IP Address Location', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                ]
            ],
            [
                'category' => 'tag-tools',
                'tools' => [
                    [
                        'display' => 53,
                        'slug' => "meta-tag-analyzer",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\MetaTagAnalyzer',
                        'icon_type' => 'class',
                        'icon_class' => 'meta-tag-analyzer',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Meta Tag Analyzer', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 15,
                        'slug' => "tag-generator",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\TagGenerator',
                        'icon_type' => 'class',
                        'icon_class' => 'tag-generator',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Meta Tag Generator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                ]
            ],
            [
                'category' => 'development-tools',
                'tools' => [
                    [
                        'display' => 54,
                        'slug' => "json-to-xml",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\JsonToXml',
                        'icon_type' => 'class',
                        'icon_class' => 'json-to-xml',
                        'en' => ['name' => 'JSON to XML', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 55,
                        'slug' => "json-viewer",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\JsonViewer',
                        'icon_type' => 'class',
                        'icon_class' => 'json-viewer',
                        'en' => ['name' => 'JSON Viewer', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 56,
                        'slug' => "json-formatter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\JsonFormatter',
                        'icon_type' => 'class',
                        'icon_class' => 'json-formatter',
                        'en' => ['name' => 'JSON Formatter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 57,
                        'slug' => "json-validator",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\JsonValidator',
                        'icon_type' => 'class',
                        'icon_class' => 'json-validator',
                        'en' => ['name' => 'JSON Validator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 58,
                        'slug' => "json-beautifier",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\JsonBeautifier',
                        'icon_type' => 'class',
                        'icon_class' => 'json-beautifier',
                        'en' => ['name' => 'JSON Beautifier', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 59,
                        'slug' => "json-editor",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\JsonEditor',
                        'icon_type' => 'class',
                        'icon_class' => 'json-editor',
                        'en' => ['name' => 'JSON Editor', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 60,
                        'slug' => "xml-json",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\XmlToJson',
                        'icon_type' => 'class',
                        'icon_class' => 'xml-to-json',
                        'en' => ['name' => 'Xml to JSON', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                ]
            ],
            [
                'category' => 'website-management-tools',
                'tools' => [
                    [
                        'display' => 14,
                        'slug' => "wordpress-theme-detector",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\WordPressThemeDetector',
                        'icon_type' => 'class',
                        'icon_class' => 'wp-theme-detector',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'WordPress Theme Detector', 'description' => 'Want to know what WordPress theme a website is using? Did you see a nice website and want to know how it was constructed? Just enter the website URL and our WordPress theme detector will do the rest. ', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 17,
                        'slug' => "twitter-card-generator",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\TwitterCardGenerator',
                        'icon_type' => 'class',
                        'icon_class' => 'twitter-card-generator',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Twitter Card Generator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 18,
                        'slug' => "open-graph-generator",
                        'icon' => resource_path('themes/default/assets/images/icons/online_text_editor.svg'),
                        'class_name' => 'App\Tools\OpenGraphGenerator',
                        'icon_type' => 'class',
                        'icon_class' => 'open-graph-generator',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Open Graph Generator', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 5,
                        'slug' => "html-viewer",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\HtmlViewer',
                        'icon_type' => 'class',
                        'icon_class' => 'online-html-viewer',
                        'en' => ['name' => 'Online HTML Viewer', 'description' => 'A web-based HTML viewer that allows users to enter and view HTML code in real time. Perfect for testing and debugging HTML code, or for learning how different HTML elements and attributes are displayed in the browser.', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 61,
                        'slug' => "xml-formatter",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\XmlFormatter',
                        'icon_type' => 'class',
                        'icon_class' => 'xml-formatter',
                        'en' => ['name' => 'Xml Formatter', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 1,
                        'slug' => "seo-report",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\SEOReport',
                        'icon_type' => 'class',
                        'icon_class' => 'seo-report',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Website SEO Score Checker', 'description' => "Want to boost the SEO performance of your webpages? Our report can help! These comprehensive, user-friendly reports provide key metrics and are designed to help you optimize and improve your website's performance. So why wait? Start improving your website's SEO today with the help of our powerful tool.", 'content' => "Want to boost the SEO performance of your webpages? Our report can help! These comprehensive, user-friendly reports provide key metrics and are designed to help you optimize and improve your website's performance. So why wait? Start improving your website's SEO today with the help of our powerful tool."]
                    ],
                    [
                        'display' => 1,
                        'slug' => "website-screenshot-generator",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\WebsiteScreenshotGenerator',
                        'icon_type' => 'class',
                        'icon_class' => 'website-screenshot',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Website Screenshot Generator', 'description' => "Take stunning website screenshots with just a few clicks! Our website screenshot generator allows you to capture the perfect image of any webpage in high resolution. Whether you're a designer, developer, or marketer, our tool makes it easy to showcase your work or showcase your products online. So why wait? Try our website screenshot generator today and start creating beautiful website images!", 'content' => "Take stunning website screenshots with just a few clicks! Our website screenshot generator allows you to capture the perfect image of any webpage in high resolution. Whether you're a designer, developer, or marketer, our tool makes it easy to showcase your work or showcase your products online. So why wait? Try our website screenshot generator today and start creating beautiful website images!"]
                    ],
                    [
                        'display' => 63,
                        'slug' => "ping-tool",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\PingTool',
                        'icon_type' => 'class',
                        'icon_class' => 'ping-tool',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Online Ping Website Tool', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 64,
                        'slug' => "url-encode-decode",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\UrlEncodeDecode',
                        'icon_type' => 'class',
                        'icon_class' => 'url-encode-decode',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'URL Encoder Decoder', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 64,
                        'slug' => "base64-encode-decode",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\Base64EncodeDecode',
                        'icon_type' => 'class',
                        'icon_class' => 'base64-encode-decode',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Base64 Encode Decode', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 65,
                        'slug' => "qr-code-generator",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\QrCodeGenerator',
                        'icon_type' => 'class',
                        'icon_class' => 'qr-code-generator',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'QR Code Generator', 'description' => 'Create custom QR codes with the QR Code Generator. Simply enter your data and choose your design to generate professional QR codes in seconds. Perfect for marketing and sharing information.', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 66,
                        'slug' => "html-editor",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\HtmlEditor',
                        'icon_type' => 'class',
                        'icon_class' => 'html-editor',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'HTML Editor', 'description' => 'HTML Minifier is a tool that optimizes your website\'s HTML code, resulting in faster loading times and improved performance. Simply click to remove excess characters and improve your website\'s efficiency. Try it out now!', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 62,
                        'slug' => "html-minifier",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\HtmlMinify',
                        'icon_type' => 'class',
                        'icon_class' => 'html-minifier',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'HTML Minifier', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 62,
                        'slug' => "js-minifier",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\JavascriptMinify',
                        'icon_type' => 'class',
                        'icon_class' => 'javascript-minifier',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'Javascript Minifier', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 62,
                        'slug' => "css-minifier",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\CssMinify',
                        'icon_type' => 'class',
                        'icon_class' => 'css-minifier',
                        'properties' => ["properties" => ["du-tool"], "auth" => ["du-tool" => $du_tool_value_auth], "guest" => ["du-tool" => $du_tool_value_guest]],
                        'en' => ['name' => 'CSS Minifier', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 63,
                        'slug' => "url-opener",
                        'icon' => resource_path('themes/default/assets/images/icons/article_rewriter.svg'),
                        'class_name' => 'App\Tools\UrlOpener',
                        'icon_type' => 'class',
                        'icon_class' => 'url-opener',
                        'en' => ['name' => 'URL Opener', 'description' => 'Edit me from admin panel...', 'content' => 'Edit me from admin panel...']
                    ],
                    [
                        'display' => 65,
                        'slug' => "xml-sitemap-generator",
                        'icon' => null,
                        'class_name' => 'App\Tools\SitemapGenerator',
                        'icon_type' => 'class',
                        'icon_class' => 'json-to-xml',
                        'en' => ['name' => 'XML Sitemap Generator', 'description' => 'Effortlessly create an XML sitemap for your website with our free generator. Notify search engines about your web pages and changes, and ensure accurate indexing. Get started now!', 'content' => 'Effortlessly create an XML sitemap for your website with our free generator. Notify search engines about your web pages and changes, and ensure accurate indexing. Get started now!']
                    ],
                ]
            ],
        ];

        foreach ($tools as $data) {
            $category = Category::slug($data['category'])->first();
            if (!$category) {
                continue;
            }

            foreach ($data['tools'] as $item) {
                $icon = $item['icon'];
                unset($item['icon']);
                $tool = $category->tools()->slug($item['slug'])->firstOr(function () use ($category, $item) {
                    return $category->tools()->make([]);
                });
                $tool->fill($item);
                $tool->save();
                $tool->category()->sync($category);
                // $tool->addMedia($icon)->preservingOriginal()->toMediaCollection('tool-icon');
            }
        }
    }
}
