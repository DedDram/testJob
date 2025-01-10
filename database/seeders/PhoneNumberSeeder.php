<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PhoneNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        // Получаем все ID организаций
        $organizationIds = DB::table('organizations')->pluck('id');

        foreach ($organizationIds as $organizationId) {
            // Для каждой организации генерируем от 1 до 3 номеров телефонов
            $phoneNumbersCount = rand(1, 3);

            for ($i = 0; $i < $phoneNumbersCount; $i++) {
                // Генерируем номер телефона в формате +19294866439
                $phoneNumber = '+1' . $faker->numerify('##########');

                DB::table('phone_numbers')->insert([
                    'organization_id' => $organizationId,
                    'phone_number' => $phoneNumber,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
