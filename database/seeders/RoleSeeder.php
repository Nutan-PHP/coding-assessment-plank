<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Role;
        $admin->type = Role::ADMIN;
        $admin->title = 'Administrator';
        $admin->description = 'Universal site access';
        $admin->save();

        $editor = new Role;
        $editor->type = Role::EDITOR;
        $editor->title = ucwords(strtolower(Role::EDITOR));
        $editor->description = 'Manage the site content';
        $editor->save();

        $contributor = new Role;
        $contributor->type = Role::CONTRIBUTOR;
        $contributor->title = ucwords(strtolower(Role::CONTRIBUTOR));
        $contributor->description = 'Contribute certain content';
        $contributor->save();

    }
}
