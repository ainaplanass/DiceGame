<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{  public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'player']);
        Permission::create(['name' => 'editplayer']);
        Permission::create(['name' => 'throwdice']);
        Permission::create(['name' => 'deletegame']);
        Permission::create(['name' => 'playerswinrate']);
        Permission::create(['name' => 'playerslist']);
        Permission::create(['name' => 'playersranking']);

        $playerPermision = [
        'editplayer',
        'throwdice',
        'deletegame',
        'playerslist',];

        $role1 = Role::findByName('admin');
        $role2 = Role::findByName('player');

        $role1->syncPermissions(Permission::all());

        $role2->syncPermissions($playerPermision);
    }
}
