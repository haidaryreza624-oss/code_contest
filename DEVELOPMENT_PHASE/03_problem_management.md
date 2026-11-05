# Phase 3 – Problem Management

## Goal
Allow administrators to create and maintain reusable programming problems and their test cases.

## Scope
- Problem metadata and statements.
- Public sample test cases.
- Hidden judge test cases.
- Supported-language and resource-limit settings.
- Problem validation before contest assignment.

## Implementation tasks
1. Create the problem management interface.
2. Store reusable problem information such as title, internal code, statement, constraints, and status.
3. Support sample test cases that may be shown to contestants on printed problem papers.
4. Store hidden test cases privately for judging.
5. Configure time and memory limits for each problem.
6. Validate that a problem has the required test cases before it can be assigned to a contest.
7. Provide preview and editing tools for administrators.

## Suggested records
- `problems`
- `test_cases`

A test case should distinguish sample/public cases from hidden cases. Store hidden inputs and expected outputs so that they are never included in contestant-facing responses.

## Rules
- A problem is reusable across contests.
- A contest uses a specific assignment record (`contest_problems`) rather than relying only on the reusable problem ID.
- Hidden test cases must remain on the Laravel side until Laravel sends them to the judge engine.
- Do not expose hidden tests in Blade views, JavaScript, API responses, logs visible to teams, or predictable file URLs.

## Completion checklist
- [pending] Administrators can create, edit, view, and deactivate problems.
- [pending] Sample and hidden test cases are distinguished.
- [pending] Time and memory limits are configurable.
- [pending] Problem validation detects missing or invalid test data.
- [pending] Hidden test data is private.
