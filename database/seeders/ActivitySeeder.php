<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $activities = [
            'Еда' => [
                'Мясная продукция' => [
                    'Свинина',
                    'Говядина',
                    'Птица' => [
                        'Курица',
                        'Индейка'
                    ]
                ],
                'Молочная продукция' => [
                    'Молоко',
                    'Сыр',
                    'Йогурт' => [
                        'Фруктовый',
                        'Натуральный'
                    ]
                ]
            ],
            'Автомобили' => [
                'Грузовые' => [
                    'Седельные тягачи',
                    'Фургоны',
                    'Самосвалы' => [
                        'Карьерные',
                        'Строительные'
                    ]
                ],
                'Легковые' => [
                    'Запчасти' => [
                        'Двигатели',
                        'Коробки передач',
                        'Подвеска' => [
                            'Амортизаторы',
                            'Пружины'
                        ]
                    ],
                    'Аксессуары' => [
                        'Чехлы',
                        'Навигаторы',
                        'Камеры заднего вида'
                    ]
                ]
            ],
            'Техника' => [
                'Бытовая техника' => [
                    'Кухонная техника' => [
                        'Микроволновки',
                        'Блендеры',
                        'Мясорубки'
                    ],
                    'Стиральные машины',
                    'Пылесосы'
                ],
                'Компьютерная техника' => [
                    'Ноутбуки',
                    'Мониторы',
                    'Принтеры' => [
                        'Лазерные',
                        'Струйные'
                    ]
                ]
            ]
        ];

        $this->createActivities($activities);
    }

    private function createActivities(array $activities, $parentId = null)
    {
        foreach ($activities as $name => $children) {
            if (is_array($children)) {
                $id = DB::table('activities')->insertGetId([
                    'name' => $name,
                    'parent_id' => $parentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->createActivities($children, $id);
            } else {
                DB::table('activities')->insert([
                    'name' => $children,
                    'parent_id' => $parentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
