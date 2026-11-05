# Phase 12 – Code Submission and Judging

## Goal
Accept team code submissions in Laravel and execute them asynchronously through the existing FastAPI Judge Engine.

## Architecture rule
**Laravel owns the data. The Judge Engine only executes submissions.**

Laravel validates and stores the submission, retrieves hidden tests, sends a judging job to FastAPI, polls for the result through background jobs, and stores the final result. The browser never sends hidden test cases and never talks directly to FastAPI.

## Submission request
The browser sends only:
- Contest problem assignment identifier (`contest_problem_id`).
- Programming language.
- Source code.

## Validation
Laravel must verify:
1. The user is authenticated as a team account.
2. The team account belongs to the active contest.
3. The contest is `RUNNING`.
4. The current server time is within the allowed submission period.
5. The `contest_problem_id` belongs to that contest.
6. The selected language is supported by the configured judge.
7. Source code meets configured size and validation rules.
8. The team has not violated any configured submission limits.

## Asynchronous judging workflow
1. Create an immutable submission record with execution status `PENDING`.
2. Retrieve hidden test cases from Laravel's database.
3. Dispatch a queued job such as `SubmitToJudge`.
4. `JudgeService` sends language, source code, test cases, time limit, and memory limit to FastAPI.
5. FastAPI returns HTTP 202 and a `job_id`.
6. Laravel stores the external `judge_job_id` against its own submission record.
7. A queued polling job checks `GET /api/v1/submissions/{job_id}`.
8. Poll until the judge reports a terminal state or the configured timeout/retry policy is reached.
9. Store the final verdict and execution statistics.
10. Dispatch score processing after a valid final result is stored.

## Existing Judge Engine API
The existing service documents:
- `POST /api/v1/submissions/`
- `GET /api/v1/submissions/{job_id}`
- `GET /api/v1/languages/`
- Swagger documentation at `/docs`

The documented language list currently includes Python and C++. C and Java must be verified or added before they are offered in CodeArena. The API reports execution states such as Queued, Running, Completed, and Failed, and verdicts such as Accepted, Wrong Answer, Time Limit Exceeded, Memory Limit Exceeded, Runtime Error, Compilation Error, and Internal Error.

## Data model guidance
- `submissions` stores the immutable attempt and its `judge_job_id`.
- Keep execution status separate from verdict.
- Store detailed results in related result records where appropriate.
- Do not use the external job ID as the primary identity of a CodeArena submission.

## Important reliability rules
- A judge infrastructure failure is not a contestant Wrong Answer.
- Retry temporary connection failures with a bounded retry/backoff policy.
- Handle duplicate queue execution safely.
- Validate that a returned judge job belongs to the expected submission.
- Do not trust client-supplied test cases or judge result values.
- Do not expose hidden test inputs or expected outputs in team-facing responses.
- Enforce time and memory limits in the execution environment.

## Critical security blocker
The current judge documentation indicates that API authentication and strong execution isolation are not yet present, and memory monitoring is approximate rather than OS-enforced. Do not run untrusted public submissions until sandboxing, enforced resource limits, and API authentication are implemented and tested.

## Completion checklist
- [ ] Submission validation is server-side.
- [ ] Submission records are immutable attempts.
- [ ] Hidden tests are retrieved only by Laravel.
- [ ] Browser cannot call FastAPI directly.
- [ ] `JudgeService` handles API communication.
- [ ] Queue jobs submit and poll asynchronously.
- [ ] Judge job IDs are stored against local submission IDs.
- [ ] Execution status and verdict are separate.
- [ ] Infrastructure failures are not recorded as Wrong Answer.
- [ ] Hidden test details are never exposed.
- [ ] Sandbox, resource enforcement, and API authentication are complete before public use.
