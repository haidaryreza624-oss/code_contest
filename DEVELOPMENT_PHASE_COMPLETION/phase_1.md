# CodeArena --- Phase 1: Project Foundation

## Development Notes: Artisan Commands, Tinker Tests, Configuration, and Important Code

**Phase:** 1 --- Project Foundation\
**Status:** Completed\
**Purpose:** This document records the commands, configuration changes,
test commands, and important implementation decisions from Phase 1. It
is not a copy of the entire Laravel source code.

------------------------------------------------------------------------

## 1. Artisan Commands Used

Run these commands from the root directory of the Laravel project.

  ------------------------------------------------------------------------------------------------------------
  Command                                                                  Purpose
  ------------------------------------------------------------------------ -----------------------------------
  `php artisan --version`                                                  Check the installed Laravel version

  `php artisan serve`                                                      Start Laravel's local development
                                                                           server

  `php artisan optimize:clear`                                             Clear cached configuration and
                                                                           optimization files

  `php artisan migrate`                                                    Run pending database migrations

  `php artisan migrate:status`                                             Check which migrations have run

  `php artisan make:migration add_is_admin_to_users_table --table=users`   Create a migration to add the
                                                                           administrator flag

  `php artisan make:controller Admin/AuthController`                       Create the administrator
                                                                           authentication controller

  `php artisan make:middleware AdminMiddleware`                            Create middleware to restrict
                                                                           administrator pages

  `php artisan make:seeder AdminSeeder`                                    Create the initial administrator
                                                                           seeder

  `php artisan db:seed --class=AdminSeeder`                                Run the administrator seeder

  `php artisan make:job TestQueueJob`                                      Create a queue test job

  `php artisan make:queue-table`                                           Generate the database jobs-table
                                                                           migration, if missing

  `php artisan make:queue-batches-table`                                   Generate the job batches migration,
                                                                           if missing

  `php artisan make:queue-failed-table`                                    Generate the failed jobs migration,
                                                                           if missing

  `php artisan queue:work database --tries=3 --timeout=60`                 Start a database queue worker

  `php artisan route:list`                                                 Display registered application
                                                                           routes

  `php artisan tinker`                                                     Open Laravel's interactive PHP
                                                                           shell
  ------------------------------------------------------------------------------------------------------------

**Migration note:** Before generating queue migrations, check
`database/migrations/` to avoid creating duplicate migrations for tables
that already exist.

------------------------------------------------------------------------

## 2. Tinker Commands Used for Testing

Open Tinker:

``` bash
php artisan tinker
```

### 2.1 Test email delivery

The following sends a real email using the configured SMTP settings:

``` php
\Mail::raw(
    'This is a test email from CodeArena.',
    function ($message) {
        $message->to('your-recipient@gmail.com')
                ->subject('CodeArena Email Test');
    }
);
```

Replace the recipient with an inbox you can access.

### 2.2 Dispatch the queue test job

``` php
App\Jobs\TestQueueJob::dispatch();
```

Then start the queue worker in a separate terminal:

``` bash
php artisan queue:work database --tries=3 --timeout=60
```

Expected log message in `storage/logs/laravel.log`:

``` text
CodeArena queue is working!
```

### 2.3 Test private file storage

``` php
use Illuminate\Support\Facades\Storage;

Storage::disk('local')->put(
    'test/private-test.txt',
    'CodeArena private storage is working.'
);
```

Check whether the file exists:

``` php
Storage::disk('local')->exists('test/private-test.txt');
```

Expected result:

``` text
true
```

Expected file location:

``` text
storage/app/private/test/private-test.txt
```

### 2.4 Test the FastAPI Judge Engine connection

``` php
$judge = app(\App\Services\JudgeService::class);

$judge->getLanguages();
```

Expected: a response containing the languages currently configured and
available in the FastAPI engine.

Exit Tinker:

``` php
exit
```

------------------------------------------------------------------------

## 3. `.env` Configuration

The following are the important environment settings introduced or
configured during Phase 1. Merge them with the existing `.env`; do not
replace unrelated settings.

### 3.1 Application and database

``` env
APP_NAME=CodeArena
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=codearena
DB_USERNAME=root
DB_PASSWORD=
```

The database credentials are examples for local development. Use the
credentials configured on your machine.

### 3.2 Real Gmail SMTP

``` env
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-gmail-app-password
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

Important: - Use a Gmail App Password, not your normal Gmail password. -
Gmail App Passwords require 2-Step Verification. - Use the same Gmail
address for `MAIL_USERNAME` and `MAIL_FROM_ADDRESS`. - Do not commit the
App Password to GitHub.

### 3.3 Database queue

``` env
QUEUE_CONNECTION=database
```

This configures Laravel to store queued jobs in the database.

### 3.4 FastAPI Judge Engine

``` env
JUDGE_ENGINE_URL=http://127.0.0.1:8000
```

Use the actual reachable address if Laravel and FastAPI run in separate
containers or on different machines.

### 3.5 Initial administrator account

``` env
ADMIN_NAME="CodeArena Administrator"
ADMIN_EMAIL=admin@codearena.local
ADMIN_PASSWORD="ReplaceWithYourOwnStrongPassword"
```

These values are read by `AdminSeeder` to create or update the initial
administrator account. Use a strong, unique password.

After changing `.env`, run:

``` bash
php artisan optimize:clear
```

------------------------------------------------------------------------

## 4. Important Configuration Changes

### 4.1 `config/services.php` --- Judge Engine configuration

Added inside the returned configuration array:

``` php
'judge' => [
    'url' => env('JUDGE_ENGINE_URL', 'http://127.0.0.1:8000'),
],
```

**Why:** Keeps the FastAPI base URL in Laravel configuration instead of
hardcoding it throughout controllers and jobs.

### 4.2 `config/filesystems.php` --- Private local storage

The `local` disk was configured to use a private directory:

``` php
'local' => [
    'driver' => 'local',
    'root' => storage_path('app/private'),
    'serve' => false,
    'throw' => false,
    'report' => false,
],
```

**Why:** Contestants' identity documents must not be stored in a
publicly accessible directory.

Do not use `storage:link` to expose these private documents.

### 4.3 `bootstrap/app.php` --- Administrator middleware

Registered the administrator middleware alias and the guest redirect
inside the existing `withMiddleware` callback:

``` php
$middleware->alias([
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
]);

$middleware->redirectGuestsTo('/admin/login');
```

**Why:** The `admin` alias protects administrator routes. The redirect
sends unauthenticated visitors to the actual administrator login URL.

### 4.4 `routes/web.php` --- Protected administrator routes

The important route middleware and grouping pattern:

``` php
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Protected administrator routes
    });
```

**Why:** Both authentication and administrator authorization are
required to access routes inside this group.

------------------------------------------------------------------------

## 5. Important Code Decisions

These are selected code details worth remembering; the full controller,
views, middleware, and service source code are intentionally not
reproduced here.

### 5.1 Administrator-only login

In the administrator login method, the credentials are restricted using:

``` php
$credentials['is_admin'] = true;
```

**Why:** A normal authenticated user must not be able to log in through
the administrator login endpoint.

### 5.2 Administrator access check

The middleware checks:

``` php
if (!$request->user() || !$request->user()->is_admin) {
    abort(403, 'Unauthorized access.');
}
```

**Why:** Being logged in is not sufficient. The user must also have
administrator privileges.

### 5.3 Regenerate the session after login

``` php
$request->session()->regenerate();
```

**Why:** Regenerates the session ID after successful authentication.

### 5.4 Invalidate the session on logout

``` php
Auth::logout();

$request->session()->invalidate();
$request->session()->regenerateToken();
```

**Why:** Logs the user out, invalidates the previous session, and
regenerates the CSRF token.

### 5.5 Queue job implementation

The test job implements:

``` php
implements ShouldQueue
```

**Why:** Laravel treats it as a queued job rather than executing it
immediately in the normal request.

### 5.6 Judge Engine HTTP request

The important request pattern in `JudgeService` is:

``` php
$response = Http::acceptJson()
    ->timeout(15)
    ->post($this->baseUrl . '/api/v1/submissions/', $data);

$response->throw();

return $response->json();
```

**Why:** - `acceptJson()` requests a JSON response. - `timeout(15)`
limits the HTTP request duration. - `post()` sends the execution
request. - `throw()` raises an exception for unsuccessful HTTP
responses. - `json()` converts the response into a PHP array.

A successful `202 Accepted` response means the Judge Engine has queued
the job. It does **not** mean the submitted solution was accepted.

------------------------------------------------------------------------

## 6. Important Files Created or Updated

This is a focused list of files introduced or modified during Phase 1.
Framework files and unrelated existing files are omitted.

  ----------------------------------------------------------------------------------------------------------
  File                                                       Action                  Reason
  ---------------------------------------------------------- ----------------------- -----------------------
  `database/migrations/...add_is_admin_to_users_table.php`   Created                 Adds `is_admin` to
                                                                                     users

  `app/Http/Controllers/Admin/AuthController.php`            Created                 Handles administrator
                                                                                     login and logout

  `app/Http/Middleware/AdminMiddleware.php`                  Created                 Restricts
                                                                                     administrator-only
                                                                                     pages

  `database/seeders/AdminSeeder.php`                         Created                 Creates the initial
                                                                                     administrator

  `database/seeders/DatabaseSeeder.php`                      Updated                 Calls `AdminSeeder`

  `app/Models/User.php`                                      Updated                 Casts `is_admin` as a
                                                                                     boolean

  `bootstrap/app.php`                                        Updated                 Registers `admin`
                                                                                     middleware and guest
                                                                                     redirect

  `routes/web.php`                                           Updated                 Defines homepage and
                                                                                     administrator routes

  `resources/views/layouts/app.blade.php`                    Created/updated         Shared Blade layout and
                                                                                     Bootstrap CDN

  `resources/views/layouts/partials/navbar.blade.php`        Created/updated         Shared navigation

  `resources/views/layouts/partials/footer.blade.php`        Created/updated         Shared footer

  `resources/views/home.blade.php`                           Created/updated         Homepage

  `resources/views/admin/auth/login.blade.php`               Created                 Administrator login
                                                                                     page

  `resources/views/admin/dashboard.blade.php`                Created                 Protected administrator
                                                                                     dashboard

  `app/Jobs/TestQueueJob.php`                                Created                 Tests database queue
                                                                                     processing

  `app/Services/JudgeService.php`                            Created                 Centralizes
                                                                                     communication with
                                                                                     FastAPI

  `config/services.php`                                      Updated                 Adds Judge Engine URL

  `config/filesystems.php`                                   Updated                 Configures private
                                                                                     local storage

  `.env`                                                     Updated                 Database, mail, queue,
                                                                                     admin and judge
                                                                                     settings
  ----------------------------------------------------------------------------------------------------------

The exact timestamped migration filename is generated by Artisan and
will differ between projects.

------------------------------------------------------------------------

## 7. Important Remarks and Design Decisions

### 7.1 `.env` must remain private

Never commit the real `.env` file to GitHub. It contains database
credentials, the Gmail App Password, and the administrator password.

Use `.env.example` for non-secret configuration examples only.

### 7.2 Email delivery is different from queue processing

SMTP configuration allows Laravel to send real email. Queue
configuration allows Laravel to process jobs in the background. They are
separate features.

### 7.3 Queue workers must remain running

For local development, run the queue worker in a separate terminal:

``` bash
php artisan queue:work database --tries=3 --timeout=60
```

The Laravel web server does not automatically start a queue worker.

### 7.4 Private documents must stay private

Contestant ID cards belong under:

``` text
storage/app/private/
```

Do not put them in `public/` or `storage/app/public/`. Later, downloads
must go through an authenticated and authorized controller.

### 7.5 Laravel and FastAPI may run in different environments

`127.0.0.1` means the current machine or container. If Laravel and
FastAPI are in separate containers, configure the correct service
hostname or reachable address.

### 7.6 Judge Engine security is not complete

The existing FastAPI documentation states that the engine currently has
no API authentication and does not isolate untrusted code. Its memory
measurement is approximate, not an enforced operating-system limit.

**Do not accept public contestant submissions until the execution
environment is properly sandboxed and resource-limited, and the API is
authenticated and restricted to trusted callers.**

### 7.7 Hidden test cases belong to Laravel

The browser must never send or receive hidden test cases or expected
outputs. Laravel retrieves them from its database and sends them
privately to the Judge Engine.

### 7.8 `Queued` does not mean `Accepted`

The Judge Engine's `202 Accepted` response only confirms that the
execution job was queued. Laravel must retrieve the final result later
and save the verdict.

### 7.9 Supported languages must be checked dynamically

The documented Judge Engine currently lists Python and C++. Do not
display C or Java as available until the engine supports them and its
language endpoint confirms availability.

### 7.10 Avoid duplicating migrations or middleware callbacks

Before generating queue migrations, inspect the existing migration
directory. Likewise, add middleware settings to the existing
`withMiddleware` callback in `bootstrap/app.php` instead of defining a
duplicate callback.

------------------------------------------------------------------------

## 8. Phase 1 Verification Checklist

Mark each item only after confirming it works in the actual project.

-   [ ] Laravel development server starts.
-   [ ] Database connection works and migrations complete.
-   [ ] Homepage and shared Blade layout render.
-   [ ] Bootstrap CSS and JavaScript load.
-   [ ] Administrator account is seeded.
-   [ ] Administrator login and logout work.
-   [ ] Non-administrator accounts cannot access administrator routes.
-   [ ] Guests are redirected to `/admin/login`.
-   [ ] Real SMTP test email is delivered.
-   [ ] Queue test job is processed by the worker.
-   [ ] Private storage test returns `true`.
-   [ ] Test file exists under `storage/app/private/`.
-   [ ] FastAPI language endpoint responds through `JudgeService`.
-   [ ] Judge Engine remains restricted to trusted local use.

------------------------------------------------------------------------

## 9. Phase 1 Completion

Phase 1 prepared the technical foundation for the next CodeArena
development phases:

-   Laravel project and database connection.
-   Blade and Bootstrap layout.
-   Initial administrator authentication.
-   Real SMTP email configuration.
-   Database-backed queues.
-   Private local file storage.
-   A dedicated `JudgeService` for the existing FastAPI Judge Engine.

**Next phase:** Phase 2 --- Administrator and Institution Management.

The Judge Engine still requires proper sandboxing, resource enforcement,
and API authentication before it can safely process untrusted public
contest submissions.
