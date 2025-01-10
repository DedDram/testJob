<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 100; $i++) {
            $organizationId = DB::table('organizations')->insertGetId([
                'name' => $faker->company,
                'building_id' => rand(1, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $phoneNumbers = $faker->numberBetween(1, 3);
            for ($j = 0; $j < $phoneNumbers; $j++) {
                DB::table('phone_numbers')->insert([
                    'organization_id' => $organizationId,
                    'phone_number' => $faker->phoneNumber,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $activityIds = DB::table('activities')->inRandomOrder()->limit(rand(1, 5))->pluck('id');
            foreach ($activityIds as $activityId) {
                DB::table('organization_activity')->insert([
                    'organization_id' => $organizationId,
                    'activity_id' => $activityId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
