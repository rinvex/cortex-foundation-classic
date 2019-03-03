<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

class CortexFoundationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $abilities = [
            ['name' => 'access-adminarea', 'title' => 'Access adminarea'],
            ['name' => 'access-managerarea', 'title' => 'Access managerarea'],

            ['name' => 'list', 'title' => 'List media', 'entity_type' => 'media'],
            ['name' => 'create', 'title' => 'Create media', 'entity_type' => 'media'],
            ['name' => 'update', 'title' => 'Update media', 'entity_type' => 'media'],
            ['name' => 'delete', 'title' => 'Delete media', 'entity_type' => 'media'],
        ];

        collect($abilities)->each(function (array $ability) {
            app('cortex.auth.ability')->create($ability);
        });
    }
}
