<?php

use Illuminate\Database\Seeder;
use App\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Admin::truncate();
        Schema::enableForeignKeyConstraints();
        $admins = [
            'full_name' => 'Admin',
            'email' => "admin@admin.com",
            'contact_no' => '1234567890',
            'password' => Hash::make('12345678'),
            'permissions' => serialize(getPermissions('admin')),
            'is_active' => 'y',
            'type' => 'admin',
            'profile' => NULL,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ];

        Admin::create($admins);
    }
}
