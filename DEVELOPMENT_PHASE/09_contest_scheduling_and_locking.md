# Phase 9 – Contest Scheduling and Locking

## Goal
Finalize an approved contest and lock all data that must remain stable during the event.

## Preconditions
Before scheduling, verify:
- Registration is closed.
- All applications have been reviewed.
- Every approved contestant ID is verified.
- Team sizes satisfy contest rules.
- Every official team has exactly one coach and the required contestants.
- Every team has a shared account.
- Usernames are unique and credentials are ready for controlled printing.
- Contest problems and hidden test cases are valid.
- Start and end times are valid.
- Required print documents can be generated.

## Implementation tasks
1. Add an explicit Schedule Contest action for administrators.
2. Run all pre-scheduling validations on the server.
3. Show a validation report with blocking errors.
4. Generate or prepare contest documents.
5. Change lifecycle status from `DRAFT` to `SCHEDULED`.
6. Lock teams, members, coaches, team usernames/passwords, contest problem assignments, and relevant contest settings.
7. Record who scheduled the contest and when.
8. Keep an audit trail of the transition.

## Rules after scheduling
- Admin cannot regenerate team passwords.
- Team composition and contest problem assignments are locked.
- Contest configuration that affects scoring or participation is locked.
- Document regeneration must not silently alter credentials or contest content.
- Any exceptional administrative override should be explicit, permission-protected, and audited.

## Completion checklist
- [ ] Registration is closed before scheduling.
- [ ] All approval and ID checks pass.
- [ ] Team and account validation passes.
- [ ] Problems and hidden tests pass validation.
- [ ] Required documents are generated or prepared.
- [ ] Scheduling is an explicit action.
- [ ] Locked records cannot be edited through normal admin screens.
- [ ] Scheduling action is audited.
