# CodeArena — Phase 3 Development Notes
## Phase 3: Problem Management

**Project:** CodeArena  
**Framework:** Laravel  
**Frontend:** Blade + Bootstrap CDN  
**Database:** MySQL / MariaDB  
**Status:** Implementation completed; final verification recommended before starting Phase 4.

---

## 1. Phase Objective

Phase 3 implements the problem-management foundation of CodeArena. Administrators can create and manage programming problems, maintain sample and hidden test cases, and publish problems. Public visitors can browse published problems and view their statements and sample test cases.

An important security boundary is established: hidden test cases must remain server-side and must never be included in public problem pages or public responses.

## 2. Main Features Implemented

| Feature | Description |
|---|---|
| Problem management | Administrators can create, list, and edit draft problems. |
| Problem details | Stores title, slug, statement, input/output formats, constraints, difficulty, time limit, and memory limit. |
| Test-case management | Administrators can add, edit, list, and delete test cases for draft problems. |
| Sample test cases | Sample cases are visible on the public problem page. |
| Hidden test cases | Hidden cases are stored separately and are not loaded by the public problem controller. |
| Publishing | A draft can be published only when it has at least one sample and one hidden test case. |
| Published problem restrictions | The implemented admin controller blocks editing and test-case changes for published problems. |
| Public problem list | Visitors can browse published problems. |
| Public problem details | Visitors can view a published problem statement, formats, constraints, limits, and sample cases. |
| Seed data | Seeders provide 20 programming problems and generate 20 test cases per problem (400 generated cases in total). |

## 3. Database Structure

### 3.1 `problems` table

Created with:

```bash
php artisan make:model Problem -m
```

Important columns:

| Column | Purpose |
|---|---|
| `user_id` | Administrator who created the problem; references `users`. |
| `title` | Problem title. |
| `slug` | Unique URL-friendly identifier. |
| `description` | Problem statement. |
| `input_format` | Required input format. |
| `output_format` | Expected output format. |
| `constraints` | Problem constraints. |
| `difficulty` | `easy`, `medium`, or `hard`. |
| `time_limit_ms` | Time limit in milliseconds. |
| `memory_limit_mb` | Memory limit in megabytes. |
| `status` | Draft or published. |

The `user_id` foreign key uses restricted deletion so a creator cannot be deleted while their problems still reference that account.

### 3.2 `problem_test_cases` table

Created with:

```bash
php artisan make:model ProblemTestCase -m
```

Important columns:

| Column | Purpose |
|---|---|
| `problem_id` | Parent problem. |
| `input` | Input supplied to the submitted program. |
| `expected_output` | Expected program output. |
| `is_sample` | Indicates whether the case is publicly visible. |
| `position` | Controls display and execution order. |

The `problem_id` foreign key cascades on deletion, so deleting a problem also deletes its associated test cases.

### 3.3 Run migrations

```bash
php artisan migrate
php artisan migrate:status
```

Do not use destructive migration options on a database containing data unless you intentionally want to reset it.

## 4. Models and Relationships

### `app/Models/Problem.php`

The `Problem` model contains the mass-assignable problem fields and relationships:

- `user()` — belongs to the administrator who created the problem.
- `testCases()` — has many `ProblemTestCase` records.

### `app/Models/ProblemTestCase.php`

The `ProblemTestCase` model contains the test-case fields and casts:

- `is_sample` to boolean.
- `position` to integer.
- `problem()` — belongs to a problem.

## 5. Seeders and Development Data

### Create the seeders

```bash
php artisan make:seeder ProblemSeeder
php artisan make:seeder ProblemTestCaseSeeder
```

### `database/seeders/ProblemSeeder.php`

Seeds these 20 programming problems:

1. Two Sum
2. Valid Parentheses
3. Merge Two Sorted Lists
4. Best Time to Buy and Sell Stock
5. Valid Palindrome
6. Invert Binary Tree
7. Valid Anagram
8. Binary Search
9. Flood Fill
10. Lowest Common Ancestor of a Binary Search Tree
11. Balanced Binary Tree
12. Linked List Cycle
13. Min Stack
14. Maximum Subarray
15. Climbing Stairs
16. Product of Array Except Self
17. 3Sum
18. Container With Most Water
19. Number of Islands
20. Course Schedule

The seeder assigns each problem to an existing administrator account. Create an administrator before running it.

```bash
php artisan db:seed --class=ProblemSeeder
```

### `database/seeders/ProblemTestCaseSeeder.php`

Generates 20 test cases for each of the 20 seeded problems:

- Positions 1–2 are sample cases.
- Positions 3–20 are hidden cases.
- Total generated cases: 400.

Run:

```bash
php artisan db:seed --class=ProblemTestCaseSeeder
```

The seeder uses `updateOrCreate` by problem and position, so repeated runs update generated positions rather than blindly duplicating them. It does not remove additional manually created test cases at positions outside 1–20.

**Data-quality note:** These are initial development datasets. Before using them in real contests, independently validate expected outputs, add edge cases, and ensure every problem statement documents the exact stdin/stdout format expected by the judge. The original LeetCode-style problem names do not automatically mean their original function-based interfaces are supported; CodeArena uses its own input/output formats.

## 6. Administrator Controllers

### `app/Http/Controllers/Admin/ProblemController.php`

Implemented actions:

| Method | Responsibility |
|---|---|
| `index()` | Lists problems with creator information and pagination. |
| `create()` | Displays the create form. |
| `store()` | Validates and stores a new draft problem. |
| `edit()` | Displays the edit form for a draft problem. |
| `update()` | Validates and updates a draft problem. |
| `publish()` | Checks for sample and hidden test cases, then publishes the problem. |

Validation includes:

- Required title and statement fields.
- Difficulty restricted to `easy`, `medium`, or `hard`.
- Time limit between 100 and 60,000 milliseconds.
- Memory limit between 16 and 1,024 MB.

The create action generates a unique slug and sets the initial status to `draft`.

The publish action requires at least one sample case and at least one hidden case.

### `app/Http/Controllers/Admin/ProblemTestCaseController.php`

Implemented actions:

| Method | Responsibility |
|---|---|
| `index()` | Displays test cases belonging to the selected problem. |
| `store()` | Adds a test case to a draft problem. |
| `update()` | Updates a test case belonging to that problem. |
| `destroy()` | Deletes a test case belonging to that problem. |

Test-case operations are blocked when the problem is not a draft. The controller also checks that a test case belongs to the problem in the route before modifying or deleting it.

## 7. Public Controller

### `app/Http/Controllers/ProblemController.php`

Implemented actions:

| Method | Responsibility |
|---|---|
| `index()` | Lists published problems with pagination. |
| `show($slug)` | Displays one published problem and its sample cases. |

The public details action retrieves sample cases only:

```php
$sampleTestCases = $problem->testCases()
    ->where('is_sample', true)
    ->orderBy('position')
    ->get();
```

It also checks that the problem is published. A draft or nonexistent problem returns a 404 response.

**Security requirement:** Do not replace this sample-only query with an unrestricted `load('testCases')` or equivalent query. Hidden test cases must never be included in public Blade data, JSON responses, page source, or browser requests.

## 8. Routes

### Administrator routes

The problem and test-case routes are registered inside the existing administrator middleware group, using the `admin.` route-name prefix.

| HTTP method | Route | Name |
|---|---|---|
| GET | `/admin/problems` | `admin.problems.index` |
| GET | `/admin/problems/create` | `admin.problems.create` |
| POST | `/admin/problems` | `admin.problems.store` |
| GET | `/admin/problems/{problem}/edit` | `admin.problems.edit` |
| PUT | `/admin/problems/{problem}` | `admin.problems.update` |
| POST | `/admin/problems/{problem}/publish` | `admin.problems.publish` |
| GET | `/admin/problems/{problem}/test-cases` | `admin.problems.test-cases.index` |
| POST | `/admin/problems/{problem}/test-cases` | `admin.problems.test-cases.store` |
| PUT | `/admin/problems/{problem}/test-cases/{testCase}` | `admin.problems.test-cases.update` |
| DELETE | `/admin/problems/{problem}/test-cases/{testCase}` | `admin.problems.test-cases.destroy` |

### Public routes

These routes are outside the administrator middleware group:

| HTTP method | Route | Name |
|---|---|---|
| GET | `/problems` | `problems.index` |
| GET | `/problems/{slug}` | `problems.show` |

Check registered routes:

```bash
php artisan route:list
```

## 9. Blade Views

| File | Purpose |
|---|---|
| `resources/views/admin/problems/index.blade.php` | Administrator problem list, status, and actions. |
| `resources/views/admin/problems/create.blade.php` | Create-problem form. |
| `resources/views/admin/problems/edit.blade.php` | Edit-draft-problem form. |
| `resources/views/admin/problems/test-cases.blade.php` | Manage sample and hidden test cases. |
| `resources/views/problems/index.blade.php` | Public list of published problems. |
| `resources/views/problems/show.blade.php` | Public problem statement and sample cases. |
| `resources/views/layouts/app.blade.php` | Main navigation, including the Problems link. |

The public details view escapes problem text before displaying it, for example:

```blade
{!! nl2br(e($problem->description)) !!}
```

This preserves line breaks while escaping HTML.

## 10. Verification Commands

### Check seeded problem count

```bash
php artisan tinker
```

```php
App\Models\Problem::count();
```

Expected for the initial seed dataset: `20`.

### Check generated test-case count

```php
App\Models\ProblemTestCase::count();
```

Expected for the initial seed dataset, if no additional cases exist: `400`.

### Check one problem's test cases

```php
$problem = App\Models\Problem::where('title', 'Two Sum')->first();

$problem->testCases()->count();

$problem->testCases()->where('is_sample', true)->count();

$problem->testCases()->where('is_sample', false)->count();
```

Expected for the generated dataset: 20 total, 2 sample, and 18 hidden.

### Check routes and clear cached application data

```bash
php artisan route:list
php artisan optimize:clear
```

## 11. Manual Acceptance Checklist

Complete these checks in the running application before treating the phase as fully verified:

- [ ] An administrator can open the problem list.
- [ ] An administrator can create a problem and it starts as a draft.
- [ ] An administrator can edit a draft problem.
- [ ] An administrator can add, edit, and delete test cases for a draft problem.
- [ ] A problem cannot be published without at least one sample and one hidden test case.
- [ ] A problem with both types of test case can be published.
- [ ] A published problem appears on the public problem list.
- [ ] A draft problem cannot be opened through the public URL.
- [ ] The public problem page displays sample cases only.
- [ ] Hidden test-case input and expected output do not appear in public HTML or public network responses.
- [ ] Published problem editing and test-case changes are rejected by the administrator controller.
- [ ] The 20 seeded problems and their generated expected outputs have been independently checked before real contest use.

## 12. Important Scope and Security Notes

1. This phase does not implement code submission or judging. Those belong to Phase 4.
2. Hidden test cases are protected from the public problem pages, but the judging service integration must later retrieve them through a trusted server-side workflow.
3. The FastAPI Judge Engine still requires its own security review. Do not expose untrusted code execution publicly until process isolation, operating-system-enforced CPU and memory limits, network restrictions, API authentication, and trusted service access have been properly implemented and tested.
4. Problem statement formats must match the judge's stdin/stdout contract. Review all seeded problem statements and expected outputs before using them in a real contest.
5. Published-problem restrictions are enforced in the described administrator controller actions. Future contest assignment and judging work should also protect the exact problem and test data used by an active contest, ideally with an immutable contest-specific snapshot.
6. Slug behavior: the current update action changes problem fields but does not regenerate the slug when the title changes. This keeps existing URLs stable, but should be an intentional decision.

## 13. Phase 3 File Summary

| Part | File | Purpose |
|---|---|---|
| A | `database/migrations/*_create_problems_table.php` | Creates the problems table. |
| B | `database/migrations/*_create_problem_test_cases_table.php` | Creates the test-cases table. |
| C | `app/Models/Problem.php` | Problem model and relationships. |
| D | `app/Models/ProblemTestCase.php` | Test-case model and relationships. |
| E | `database/seeders/ProblemSeeder.php` | Seeds 20 development problems. |
| F | `database/seeders/ProblemTestCaseSeeder.php` | Generates 400 development test cases. |
| G | `app/Http/Controllers/Admin/ProblemController.php` | Administrator problem management and publishing. |
| H | `app/Http/Controllers/Admin/ProblemTestCaseController.php` | Administrator test-case management. |
| I | `app/Http/Controllers/ProblemController.php` | Public problem listing and details. |
| J | `routes/web.php` | Admin and public problem routes. |
| K | `resources/views/admin/problems/` | Administrator problem and test-case pages. |
| L | `resources/views/problems/` | Public problem pages. |
| M | `resources/views/layouts/app.blade.php` | Problems navigation link. |

---

## Phase 3 Completion

**Phase 3 — Problem Management: implementation completed.**

The phase provides the database structure, administrator workflows, sample/hidden test-case management, publishing validation, and public problem browsing.

Before beginning Phase 4, run the verification commands and complete the manual acceptance checklist. Treat the seeded test data as development data until its correctness has been independently validated.

**Next phase:** Phase 4 — Submission & Judging System, connecting Laravel to the existing FastAPI Judge Engine, handling asynchronous judging, storing verdicts, and displaying submission history.
