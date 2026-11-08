# Phase 4 – Submission & Judging Completion

## 1. Overview

Phase 4 focused on implementing the submission and judging system for CodeArena.

The main objective was to allow contestants to submit source-code files through Laravel, send submissions to the separate FastAPI Judge Engine, execute the code against problem test cases, and retrieve the results asynchronously.

The integration between Laravel and FastAPI was successfully implemented and tested.

## 2. Technologies Used

| Technology | Purpose |
|---|---|
| Laravel | Main application backend |
| PHP | Backend programming language |
| MySQL | Submission data storage |
| Blade | Submission interface |
| Bootstrap | Frontend styling |
| FastAPI | Separate judging service |
| Python | Judge Engine implementation |
| SQLite | Judge job persistence |
| Laravel Queues | Background result processing |

## 3. Implemented Features

### A. Submission Foundation

- Created the Submission model.
- Created the submissions database migration.
- Established relationships between submissions, users, and problems.
- Stored source code and submission information in the database.

### B. Source-Code Submission

- Implemented source-code file upload.
- Identified the programming language using the uploaded file extension.
- Supported Python and C++ according to the current Judge Engine capabilities.
- Stored the submitted source code in the database.

### C. Laravel–FastAPI Integration

Created a dedicated `JudgeService` to communicate with the FastAPI Judge Engine.

Implemented methods for:

- Submitting source code.
- Retrieving submission results.
- Retrieving supported programming languages.

The Laravel application sends the source code, programming language, execution limits, and test cases to FastAPI.

### D. Asynchronous Judging

Implemented asynchronous submission processing.

Workflow:

1. Laravel creates a submission record.
2. Laravel sends the submission to FastAPI.
3. FastAPI generates a job ID and adds the task to its queue.
4. Laravel stores the returned job ID.
5. Laravel dispatches a background job.
6. The background job periodically checks the FastAPI job status.
7. Once judging is completed, Laravel retrieves and stores the result.

### E. Verdict System

Implemented support for the following verdicts:

| Verdict | Description |
|---|---|
| Accepted | All test cases passed. |
| Wrong Answer | At least one test case produced an incorrect output. |
| Runtime Error | The submitted program encountered an execution error. |
| Time Limit Exceeded | The program exceeded its execution time limit. |
| Internal Error | An internal judging or processing error occurred. |

### F. Submission Results

The submission record stores:

- Execution status.
- Final verdict.
- Execution time.
- Memory usage.
- Passed test cases.
- Total test cases.
- Judge job ID.
- Error message, when applicable.

### G. Test Case Handling

- Public and hidden test cases are retrieved on the server side.
- Test cases are sent directly from Laravel to FastAPI.
- Hidden test cases are not exposed to the contestant's browser.
- The Judge Engine evaluates submitted code against the provided test cases.

## 4. Important Files and Their Responsibilities

| File | Responsibility |
|---|---|
| `app/Models/Submission.php` | Submission model and relationships |
| `database/migrations/` | Submission database structure |
| `app/Services/JudgeService.php` | Communication with FastAPI |
| `app/Jobs/ProcessSubmissionResult.php` | Background result polling and processing |
| `app/Http/Controllers/` | Submission request handling |
| `resources/views/problems/show.blade.php` | Problem statement and submission interface |
| `routes/web.php` | Submission-related routes |
| FastAPI `app/services/queue_manager.py` | Background judging queue and workers |
| FastAPI `app/services/judge_service.py` | Submission execution and evaluation |
| FastAPI `app/services/job_storage.py` | Judge job persistence |

## 5. Testing and Verification

The Judge Engine was tested using a Python test script.

All five test groups passed successfully.

| Test | Expected Result | Actual Result | Status |
|---|---|---|---|
| Basic execution | Accepted | Accepted | PASS |
| Wrong Answer detection | Wrong Answer | Wrong Answer | PASS |
| Runtime Error detection | Runtime Error | Runtime Error | PASS |
| Time Limit Exceeded detection | Time Limit Exceeded | Time Limit Exceeded | PASS |
| Two Sum – 20 test cases | Accepted | Accepted | PASS |

### Laravel Integration Verification

The Laravel-to-FastAPI integration was also verified using Laravel Tinker.

Command:

```bash
php artisan tinker
```

Query:

```php
App\Models\Submission::latest()->first();
```

Verified result:

| Field | Result |
|---|---|
| `execution_status` | completed |
| `verdict` | Accepted |
| `passed_test_cases` | 20 |
| `total_test_cases` | 20 |
| `execution_time_ms` | 1279 |
| `memory_used_kb` | 11124 |
| `error_message` | null |

This confirmed that Laravel successfully received the final judging result and updated the corresponding submission record.

## 6. Commands Used

### Start FastAPI Judge Engine

From the Judge Engine project directory:

```bash
uvicorn app.main:app
```

### Start Laravel Queue Worker

From the Laravel project directory:

```bash
php artisan queue:work
```

### Open Laravel Tinker

```bash
php artisan tinker
```

### Inspect the Latest Submission

```php
App\Models\Submission::latest()->first();
```

## 7. Known Limitations and Future Improvements

Although the functional judging workflow is working, the following areas still require attention before production deployment:

- Implement proper operating-system-level sandboxing for untrusted code.
- Verify and enforce memory limits.
- Test C++ compilation and execution through the complete integration.
- Perform concurrent submission and load testing.
- Improve queue recovery after Judge Engine restarts.
- Review execution-time and memory-measurement accuracy.

**Important:** Successful functional tests do not establish that the current execution environment is secure against malicious submissions.

## 8. Phase Completion Status

| Component | Status |
|---|---|
| Submission database and model | Completed |
| Source-code submission | Completed |
| Laravel–FastAPI communication | Completed |
| Asynchronous judging | Completed |
| Verdict processing | Completed |
| Submission result storage | Completed |
| Functional Judge Engine testing | Passed |
| Laravel integration testing | Passed |

**Overall Status: PHASE 4 FUNCTIONALLY COMPLETED**

The submission and judging workflow is operational. Security hardening and additional reliability testing remain future improvements.
