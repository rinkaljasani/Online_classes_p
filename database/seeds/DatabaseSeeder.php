<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SectionsTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(StatesSeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(CmsPageSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(ProjectSeeder::class);
    }
}
