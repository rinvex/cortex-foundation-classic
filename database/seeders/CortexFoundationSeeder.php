<?php

declare(strict_types=1);

namespace Cortex\Foundation\Database\Seeders;

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
        $accessAbilities = [
            ['name' => 'access-adminarea', 'title' => 'Access adminarea'],
            ['name' => 'access-managerarea', 'title' => 'Access managerarea'],
        ];

        $abilities = [
            ['name' => 'list', 'title' => 'List media', 'entity_type' => 'media'],
            ['name' => 'create', 'title' => 'Create media', 'entity_type' => 'media'],
            ['name' => 'update', 'title' => 'Update media', 'entity_type' => 'media'],
            ['name' => 'delete', 'title' => 'Delete media', 'entity_type' => 'media'],

            ['name' => 'list', 'title' => 'List Accessareas', 'entity_type' => 'accessarea'],
            ['name' => 'create', 'title' => 'Create Accessareas', 'entity_type' => 'accessarea'],
            ['name' => 'update', 'title' => 'Update Accessareas', 'entity_type' => 'accessarea'],
            ['name' => 'delete', 'title' => 'Delete Accessareas', 'entity_type' => 'accessarea'],
        ];

        $accessareas = [
            ['name' => 'absentarea', 'slug' => 'absentarea', 'is_protected' => true],
            ['name' => 'centralarea', 'slug' => 'centralarea', 'is_protected' => true],
            ['name' => 'frontarea', 'slug' => 'frontarea', 'is_protected' => true],
            ['name' => 'adminarea', 'slug' => 'adminarea', 'is_protected' => true, 'is_scoped' => false, 'is_indexable' => false, 'prefix' => 'adminarea'],
            ['name' => 'managerarea', 'slug' => 'managerarea', 'is_protected' => true, 'is_scoped' => false, 'is_indexable' => false, 'prefix' => 'managerarea'],
            ['name' => 'tenantarea', 'slug' => 'tenantarea', 'is_protected' => true],
        ];

        collect($accessAbilities)->each(function (array $ability) {
            app('cortex.auth.ability')->firstOrCreate([
                'name' => $ability['name'],
            ], $ability);
        });

        collect($abilities)->each(function (array $ability) {
            app('cortex.auth.ability')->firstOrCreate([
                'name' => $ability['name'],
                'entity_type' => $ability['entity_type'],
            ], $ability);
        });

        collect($accessareas)->each(function (array $accessarea) {
            app('cortex.foundation.accessarea')->firstOrCreate([
                'name' => $accessarea['name'],
            ], $accessarea);
        });
    }
}
