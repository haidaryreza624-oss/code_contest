# Phase 4 – Contest Creation and Configuration

## Goal
Allow administrators to create a contest in Draft and configure all contest rules before registration and scheduling.

## Scope
- Contest details and dates.
- Registration opening and closing controls.
- Team-size rules.
- Contest lifecycle.
- Contest problem assignment and ordering.
- Contest configuration validation.

## Implementation tasks
1. Create a contest in `DRAFT` status.
2. Store title, description, official start time, official end time, and relevant time zone.
3. Configure minimum and maximum contestant count per team.
4. Configure registration opening and closing dates or explicit registration state.
5. Assign reusable problems through `contest_problems`.
6. Give each assigned problem a contest label such as A, B, or C and an ordering value.
7. Validate that the contest dates and team-size settings are consistent.
8. Separate registration status from contest lifecycle status.

## Suggested lifecycle
- `DRAFT`
- `SCHEDULED`
- `RUNNING`
- `FINISHED`
- `ARCHIVED`

Registration should be controlled separately, for example with explicit registration status and/or opening and closing timestamps. Do not use one status field to represent both registration and contest execution.

## Rules
- A Draft contest is not automatically public.
- A contest appears on the public listing only when an administrator explicitly opens registration.
- Scheduling is an explicit administrator action; reaching the registration deadline does not automatically schedule the contest.
- Contest problem assignments and relevant settings become locked after scheduling.

## Completion checklist
- [ ] Administrators can create and edit Draft contests.
- [ ] Registration can be opened and closed independently.
- [ ] Team-size limits are configurable.
- [ ] Problems can be assigned and ordered.
- [ ] Contest dates and configuration are validated.
- [ ] Public listing only shows contests with registration open.
