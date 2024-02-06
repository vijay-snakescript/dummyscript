<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Tool;
use App\Models\Property;
use App\Models\PlanProperty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('plans')->count() == 0) {
            $plans = [
                [
                    'name' => 'Basic I',
                    'description' => 'Basic plan for students.',
                    'status' => true,
                    'recommended' => false,
                    'is_ads' => true,
                    'is_api_allowed' => false,
                    'monthly_price' => 9.99,
                    'yearly_price' => 99.99,
                    'properties' => ['du-tool' => 10, 'wc-tool' => 1000, 'fs-tool' => 1, 'no-file-tool' => 5, 'no-domain-tool' => 5],
                ],
                [
                    'name' => 'Classic',
                    'description' => 'Best plan for individuals and small teams.',
                    'status' => true,
                    'recommended' => false,
                    'is_ads' => true,
                    'is_api_allowed' => false,
                    'monthly_price' => 24.99,
                    'yearly_price' => 249.99,
                    'properties' => ['du-tool' => 50, 'wc-tool' => 2000, 'fs-tool' => 2, 'no-file-tool' => 10, 'no-domain-tool' => 10],
                ],
                [
                    'name' => 'Professional',
                    'description' => 'Best plan for large teams.',
                    'status' => true,
                    'recommended' => true,
                    'is_ads' => true,
                    'is_api_allowed' => false,
                    'monthly_price' => 49.99,
                    'yearly_price' => 499.99,
                    'properties' => ['du-tool' => 100, 'wc-tool' => 3000, 'fs-tool' => 5, 'no-file-tool' => 20, 'no-domain-tool' => 10],
                ],
                [
                    'name' => 'Enterprise',
                    'description' => 'Best for teams and organizations.',
                    'status' => true,
                    'recommended' => false,
                    'is_ads' => true,
                    'is_api_allowed' => true,
                    'monthly_price' => 99.99,
                    'yearly_price' => 999.99,
                    'properties' => ['du-tool' => 250, 'wc-tool' => 4000, 'fs-tool' => 20, 'no-file-tool' => 30, 'no-domain-tool' => 15],
                ],
            ];

            foreach ($plans as $plan) {
                $plan_properties = $plan['properties'];
                unset($plan['properties']);
                $plan = Plan::create($plan);
                // TODO: implement plan properties

                $tools = Tool::all();
                $properties = Property::active()->get();

                foreach ($tools as $tool) {
                    if (isset($tool->properties['properties'])) {
                        foreach ($properties->whereIn('prop_key', $tool->properties['properties']) as $property) {
                            $value = $plan_properties[$property->prop_key] ? $plan_properties[$property->prop_key] : 0;
                            $proArray = [
                                'property_id' => $property->id,
                                'plan_id' => $plan->id,
                                'tool_id' => $tool->id,
                                'value' => $value
                            ];

                            PlanProperty::create($proArray);
                        }
                    }
                }
            }
        }
    }
}
