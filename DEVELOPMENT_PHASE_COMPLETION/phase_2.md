# CodeArena --- Phase 2 Development Notes

## Administrator and Institution Management

**Phase:** 2\
**Project:** CodeArena

## 1. Overview

Phase 2 establishes the initial administration layer for a
single-institution CodeArena installation.

Implemented areas: - Institution settings management. - Administrator
account listing and creation. - Authorization Gates for administrative
operations. - Administrative audit log storage and viewing. -
Administrator dashboard navigation.

This phase does not implement multi-tenancy.

## 2. Database changes

### Institution settings

Created with:

``` bash
php artisan make:model InstitutionSetting -m
php artisan migrate
```

  Field                Purpose
  -------------------- ----------------------------------
  `institution_name`   Institution name
  `contact_email`      Optional contact email
  `contact_phone`      Optional contact phone
  `address`            Optional address
  `website`            Optional website
  Timestamps           Record creation and update times

  : `institution_settings`

The model uses `$fillable` for these fields. The controller uses
`updateOrCreate()` with `id = 1`, reflecting the single-institution
installation design.

### Administrative audit logs

Created with:

``` bash
php artisan make:model AdminAuditLog -m
php artisan migrate
```

  Field           Purpose
  --------------- ----------------------------------------
  `admin_id`      Administrator who performed the action
  `action`        Action identifier
  `target_type`   Type of affected record
  `target_id`     ID of affected record
  `description`   Human-readable description
  Timestamps      Record creation and update times

  : `admin_audit_logs`

The `admin_id` foreign key references `users.id` and uses
`restrictOnDelete()` to preserve audit-log references.

## 3. Institution settings

Created: - `app/Models/InstitutionSetting.php` -
`app/Http/Controllers/Admin/InstitutionSettingController.php` -
`resources/views/admin/settings/edit.blade.php`

Controller methods:

  Method       Purpose
  ------------ ----------------------------------------
  `edit()`     Displays current institution settings
  `update()`   Validates and saves submitted settings

Validation covers the required institution name and optional contact
email, phone, address, and website. Email and website use Laravel's
`email` and `url` rules.

Routes inside the existing protected administrator route group:

  Method   URI                 Route name
  -------- ------------------- -------------------------
  GET      `/admin/settings`   `admin.settings.edit`
  PUT      `/admin/settings`   `admin.settings.update`

## 4. Administrator account management

Created: - `app/Http/Controllers/Admin/AdminUserController.php` -
`resources/views/admin/admin-users/index.blade.php` -
`resources/views/admin/admin-users/create.blade.php`

Controller methods:

  Method       Purpose
  ------------ ------------------------------------------
  `index()`    Lists users with `is_admin = true`
  `create()`   Displays the administrator creation form
  `store()`    Validates and creates an administrator

Important validation:

``` php
'email' => 'required|email|max:255|unique:users,email',
'password' => 'required|string|min:8|confirmed',
```

The password is hashed before storage:

``` php
$admin->password = Hash::make($validated['password']);
$admin->is_admin = true;
$admin->save();
```

Routes inside the protected administrator route group:

  Method   URI                              Route name
  -------- -------------------------------- ----------------------------
  GET      `/admin/administrators`          `admin.admin-users.index`
  GET      `/admin/administrators/create`   `admin.admin-users.create`
  POST     `/admin/administrators`          `admin.admin-users.store`

The interface supports listing and creating administrators. Editing,
deactivation, and deletion are not included.

## 5. Authorization Gates

Configured in `app/Providers/AppServiceProvider.php`.

``` php
Gate::define('manage-administrators', function ($user) {
    return $user->is_admin === true;
});

Gate::define('manage-institution-settings', function ($user) {
    return $user->is_admin === true;
});
```

Routes use Laravel's `can` middleware, for example:

``` php
->middleware('can:manage-administrators')
```

The routes also remain inside the existing Phase 1 `auth` and `admin`
middleware group.

**Current limitation:** All administrators receive the same permissions.
Separate roles and fine-grained permissions have not been implemented.

## 6. Administrative audit logging

The `AdminAuditLog` model defines the administrator relationship:

``` php
public function admin()
{
    return $this->belongsTo(User::class, 'admin_id');
}
```

It also provides a reusable `record()` method. Example:

``` php
AdminAuditLog::record(
    'administrator_created',
    'User',
    $admin->id,
    'Created administrator account: ' . $admin->email
);
```

Logging has been added for:

  -----------------------------------------------------------------------
  Action identifier                   Trigger
  ----------------------------------- -----------------------------------
  `administrator_created`             An administrator creates another
                                      administrator

  `institution_settings_updated`      Institution settings are updated
  -----------------------------------------------------------------------

Created: - `app/Http/Controllers/Admin/AdminAuditLogController.php` -
`resources/views/admin/audit-logs/index.blade.php`

The controller loads the administrator relationship, sorts newest first,
and paginates 15 records per page.

Route:

  Method   URI                   Route name
  -------- --------------------- --------------------------
  GET      `/admin/audit-logs`   `admin.audit-logs.index`

Audit logging should be extended to contest, problem, registration,
team, and status-management actions as those modules are developed.
Treat audit records as historical records, not ordinary editable data.

## 7. Administrator dashboard

Updated: `resources/views/admin/dashboard.blade.php`

Dashboard sections: - Institution Settings - Administrators - Audit
Logs - Contests - Problems - Registrations - Teams - Reports

Sections for modules not yet developed are placeholders marked **Coming
Soon**. They are not functional modules.

## 8. Useful commands

Clear cached configuration and routes:

``` bash
php artisan optimize:clear
```

List routes:

``` bash
php artisan route:list
```

Check migration status:

``` bash
php artisan migrate:status
```

Inspect audit records in Tinker:

``` bash
php artisan tinker
```

``` php
App\\Models\\AdminAuditLog::latest()->get();
```

## 9. Manual testing checklist

-   [ ] Administrator can open `/admin/settings`.
-   [ ] Institution settings can be saved and remain after refresh.
-   [ ] Invalid email and website values are rejected.
-   [ ] Administrator can open `/admin/administrators`.
-   [ ] Administrator can create another administrator.
-   [ ] Duplicate email addresses are rejected.
-   [ ] The new administrator appears in the list.
-   [ ] Administrator can open `/admin/audit-logs`.
-   [ ] Creating an administrator creates an audit record.
-   [ ] Updating institution settings creates an audit record.
-   [ ] Guests cannot access protected administrator pages.
-   [ ] Dashboard links to settings, administrators, and audit logs
    work.

**Testing status:** This is a verification checklist. Mark items
complete only after testing them in the running application.

## 10. Files created or updated

  ---------------------------------------------------------------------------------------------------------------
  Part                    File                                                            Purpose
  ----------------------- --------------------------------------------------------------- -----------------------
  A                       `database/migrations/*_create_institution_settings_table.php`   Create institution
                                                                                          settings table

  B                       `app/Models/InstitutionSetting.php`                             Institution settings
                                                                                          model

  C                       `app/Http/Controllers/Admin/InstitutionSettingController.php`   Display and update
                                                                                          settings

  D                       `resources/views/admin/settings/edit.blade.php`                 Institution settings
                                                                                          form

  E                       `database/migrations/*_create_admin_audit_logs_table.php`       Create audit log table

  F                       `app/Models/AdminAuditLog.php`                                  Audit model,
                                                                                          relationship, and
                                                                                          recording method

  G                       `app/Http/Controllers/Admin/AdminUserController.php`            List and create
                                                                                          administrators

  H                       `resources/views/admin/admin-users/index.blade.php`             Administrator list

  I                       `resources/views/admin/admin-users/create.blade.php`            Administrator creation
                                                                                          form

  J                       `app/Providers/AppServiceProvider.php`                          Define authorization
                                                                                          Gates

  K                       `app/Http/Controllers/Admin/AdminAuditLogController.php`        Retrieve audit records

  L                       `resources/views/admin/audit-logs/index.blade.php`              Display audit records

  M                       `routes/web.php`                                                Register protected
                                                                                          Phase 2 routes

  N                       `resources/views/admin/dashboard.blade.php`                     Add administration
                                                                                          navigation
  ---------------------------------------------------------------------------------------------------------------

Migration filenames contain Laravel-generated timestamps.

## 11. Remaining limitations

Not included in Phase 2: 1. Administrator editing, deactivation, and
deletion. 2. Separate administrator roles and fine-grained permissions.
3. Audit logging for all future administrative modules. 4. Audit log
filtering and searching. 5. Functional contest, problem, registration,
team, and report modules.

## 12. Phase 2 summary

Phase 2 provides the initial administrator and institution management
layer for CodeArena. It adds institution settings, administrator account
creation and listing, server-side authorization Gates, and an audit log
system with a viewer.

The next planned phase is **Phase 3 --- Contest Management**.
