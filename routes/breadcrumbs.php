<?php

use Illuminate\Support\Facades\Auth;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Dashboard ---------------------------------------------------------------------------------------------------------------------------------------------------
Breadcrumbs::register('dashboard', function ($breadcrumbs) {
    $breadcrumbs->push('Dashboard', route(Auth::getDefaultDriver() . '.dashboard.index'));
});

// Users -------------------------------------------------------------------------------------------------------------------------------------------------------
Breadcrumbs::register('users_list', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Registered Users', route(Auth::getDefaultDriver() . '.users.index'));
});

// Quick Links
Breadcrumbs::register('quick_link', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__('Mange Quick Link'), route('admin.quickLink'));
});
// Profile
Breadcrumbs::register('my_profile', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__('Manage Account'), route('admin.profile-view'));
});

Breadcrumbs::register('users_create', function ($breadcrumbs) {
    $breadcrumbs->parent('users_list');
    $breadcrumbs->push('Add New Registered User', route(Auth::getDefaultDriver() . '.users.create'));
});

Breadcrumbs::register('users_update', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('users_list');
    $breadcrumbs->push('Edit Registered User', route(Auth::getDefaultDriver() . '.users.edit', $id));
});

// Role Management -------------------------------------------------------------------------------------------------------------------------------------------------------
Breadcrumbs::register('roles_list', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Roles', route(Auth::getDefaultDriver() . '.roles.index'));
});
Breadcrumbs::register('roles_create', function ($breadcrumbs) {
    $breadcrumbs->parent('roles_list');
    $breadcrumbs->push('Add New Role', route(Auth::getDefaultDriver() . '.roles.create'));
});
Breadcrumbs::register('roles_update', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('roles_list');
    $breadcrumbs->push(__('Edit Role'), route('admin.roles.edit', $id));
});

// CMS Pages ---------------------------------------------------------------------------------------------------------------------------------------------------
Breadcrumbs::register('cms_list', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__('CMS Pages'), route('admin.pages.index'));
});
Breadcrumbs::register('cms_update', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('cms_list');
    $breadcrumbs->push(__('Edit CMS Page'), route('admin.pages.edit', $id));
});
//site configuartion
Breadcrumbs::register('site_setting', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(__('Site Configuration'), route('admin.settings.index'));
});

// User Plans -------------------------------------------------------------------------------------------------------------------------------------------------------
Breadcrumbs::register('user_plans_list', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Subscribed Users', route(Auth::getDefaultDriver() . '.user_plans.index'));
});
Breadcrumbs::register('user_plans_create', function ($breadcrumbs) {
    $breadcrumbs->parent('user_plans_list');
    $breadcrumbs->push('Add New Subscribed Users', route(Auth::getDefaultDriver() . '.user_plans.create'));
});

Breadcrumbs::register('user_plans_update', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('user_plans_list');
    $breadcrumbs->push('Edit Subscribed Users', route(Auth::getDefaultDriver() . '.user_plans.edit', $id));
});

// project
Breadcrumbs::register('projects_list', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Project', route(Auth::getDefaultDriver() . '.projects.index'));
});

Breadcrumbs::register('projects_update', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('projects_list');
    $breadcrumbs->push('Edit Project', route(Auth::getDefaultDriver() . '.projects.edit', $id));
});

// Plans -------------------------------------------------------------------------------------------------------------------------------------------------------
Breadcrumbs::register('plans_list', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Plans', route(Auth::getDefaultDriver() . '.plans.index'));
});
Breadcrumbs::register('plans_create', function ($breadcrumbs) {
    $breadcrumbs->parent('plans_list');
    $breadcrumbs->push('Add New Plan', route(Auth::getDefaultDriver() . '.plans.create'));
});

Breadcrumbs::register('plans_update', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('plans_list');
    $breadcrumbs->push('Edit Plan', route(Auth::getDefaultDriver() . '.plans.edit', $id));
});


//  ---------Faqs----------------------------------------------------------------------------------------------------------------------------------------------
Breadcrumbs::register('faqs_list', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Faqs', route(Auth::getDefaultDriver() . '.faqs.index'));
});
Breadcrumbs::register('faqs_create', function ($breadcrumbs) {
    $breadcrumbs->parent('faqs_list');
    $breadcrumbs->push('Add New Faqs', route(Auth::getDefaultDriver() . '.faqs.create'));
});

Breadcrumbs::register('faqs_update', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('faqs_list');
    $breadcrumbs->push('Edit Faqs', route(Auth::getDefaultDriver() . '.faqs.edit', $id));
});
