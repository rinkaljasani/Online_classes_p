
<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Role::truncate();
        Schema::enableForeignKeyConstraints();
        $roles = [
                [
                    'section_id'                =>  1,
                    'title'                     =>  'Dashboard',
                    'route'                     =>  'admin.dashboard.index',
                    'params'                    =>  '',
                    'icon'                      =>  'icon-home',
                    'image'                     =>  '',
                    'icon_type'                 =>  'line-icons',
                    'allowed_permissions'       =>  'access',
                    'sequence'                  =>  1,
                    'is_display'                =>  'y',
                    'is_active'                 =>  'y',
                    'created_at'                => \Carbon\Carbon::now(),
                    'updated_at'                => \Carbon\Carbon::now(),
                ],
                [
                    'section_id'                =>  2,
                    'title'                     =>  'Users',
                    'route'                     =>  'admin.users.index',
                    'params'                    =>  '',
                    'icon'                      =>  'icon-users',
                    'image'                     =>  '',
                    'icon_type'                 =>  'line-icons',
                    'allowed_permissions'       =>  'access,add,edit,delete',
                    'sequence'                  =>  1,
                    'is_display'                =>  'y',
                    'is_active'                 =>  'y',
                    'created_at'                => \Carbon\Carbon::now(),
                    'updated_at'                => \Carbon\Carbon::now(),
                ],
                [
                    'section_id'                =>  8,
                    'title'                     =>  'Role Management',
                    'route'                     =>  'admin.roles.index',
                    'params'                    =>  '',
                    'icon'                      =>  'fab fa-black-tie',
                    'image'                     =>  '',
                    'icon_type'                 =>  'font-awesome',
                    'allowed_permissions'       =>  'access,add,edit,delete',
                    'sequence'                  =>  1,
                    'is_display'                =>  'y',
                    'is_active'                 =>  'y',
                    'created_at'                => \Carbon\Carbon::now(),
                    'updated_at'                => \Carbon\Carbon::now(),
                ],
                [
                    'section_id'                =>  9,
                    'title'                     =>  'CMS Pages',
                    'route'                     =>  'admin.pages.index',
                    'params'                    =>  '',
                    'icon'                      =>  'fas fa-book',
                    'image'                     =>  '',
                    'icon_type'                 =>  'font-awesome',
                    'allowed_permissions'       =>  'access,edit',
                    'sequence'                  =>  1,
                    'is_display'                =>  'y',
                    'is_active'                 =>  'y',
                    'created_at'                => \Carbon\Carbon::now(),
                    'updated_at'                => \Carbon\Carbon::now(),
                ],
                [
                    'section_id'                => 7,
                    'title'                     => 'Site Configuration',
                    'route'                     => 'admin.settings.index',
                    'params'                    => '',
                    'icon'                      =>  'icon-settings',
                    'image'                     => '',
                    'icon_type'                 =>  'font-awesome',
                    'sequence'                  => 1,
                    'is_display'                =>  'y',
                    'is_active'                 =>  'y',
                    'allowed_permissions'       => 'access',
                    'created_at'                => \Carbon\Carbon::now(),
                    'updated_at'                => \Carbon\Carbon::now(),
                ],
                [
                    'section_id'                => 4,
                    'title'                     => 'Project',
                    'route'                     => 'admin.projects.index',
                    'params'                    => '',
                    'icon'                      =>  'icon-settings',
                    'image'                     => '',
                    'icon_type'                 =>  'font-awesome',
                    'sequence'                  => 1,
                    'is_display'                =>  'y',
                    'is_active'                 =>  'y',
                    'allowed_permissions'       => 'access,edit',
                    'created_at'                => \Carbon\Carbon::now(),
                    'updated_at'                => \Carbon\Carbon::now(),
                ],
                [
                    'section_id'                => 5,
                    'title'                     => 'Plan',
                    'route'                     => 'admin.plans.index',
                    'params'                    => '',
                    'icon'                      =>  'icon-settings',
                    'image'                     => '',
                    'icon_type'                 =>  'font-awesome',
                    'sequence'                  => 1,
                    'is_display'                =>  'y',
                    'is_active'                 =>  'y',
                    'allowed_permissions'       => 'access,add,edit,delete',
                    'created_at'                => \Carbon\Carbon::now(),
                    'updated_at'                => \Carbon\Carbon::now(),
                ],
                [
                    'section_id'                => 6,
                    'title'                     => 'FAQs',
                    'route'                     => 'admin.faqs.index',
                    'params'                    => '',
                    'icon'                      =>  'icon-settings',
                    'image'                     => '',
                    'icon_type'                 =>  'font-awesome',
                    'sequence'                  => 1,
                    'is_display'                =>  'y',
                    'is_active'                 =>  'y',
                    'allowed_permissions'       => 'access,add,edit,delete',
                    'created_at'                => \Carbon\Carbon::now(),
                    'updated_at'                => \Carbon\Carbon::now(),
                ],
                [
                    'section_id'                => 3,
                    'title'                     => 'Subscribe Users',
                    'route'                     => 'admin.user_plans.index',
                    'params'                    => '',
                    'icon'                      =>  'icon-settings',
                    'image'                     => '',
                    'icon_type'                 =>  'font-awesome',
                    'sequence'                  => 1,
                    'is_display'                =>  'y',
                    'is_active'                 =>  'y',
                    'allowed_permissions'       => 'access,add,edit,delete',
                    'created_at'                => \Carbon\Carbon::now(),
                    'updated_at'                => \Carbon\Carbon::now(),
                ],
        ];
        Role::insert($roles);
        //updated permissions of admin for new added section and role
        if(!empty(getPermissions('admin'))){
            DB::table('admins')->where(['id' => 1])->update(['permissions' => serialize(getPermissions('admin'))]);
        }
    }
}
