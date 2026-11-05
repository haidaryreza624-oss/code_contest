# Phase 15 – Archiving, Reporting, and Administration

## Goal
Preserve completed contest records, provide administrative reports, and prepare the installation for future contests.

## Scope
- Contest archive.
- Historical standings.
- Administrative reports.
- Document retention.
- Audit and operational review.
- Backup and recovery procedures.

## Implementation tasks
1. Allow administrators to archive a finished contest.
2. Keep historical contest, team, submission, verdict, and score records available for authorized review.
3. Provide reports for participation, solved problems, team rankings, and submission outcomes.
4. Provide filters by contest, team, problem, and date where appropriate.
5. Define retention and deletion rules for identity documents and correction files.
6. Define retention rules for source code, judge results, generated PDFs, and audit logs.
7. Configure database and private-file backups.
8. Test restoration procedures.
9. Review failed jobs, email delivery, queue health, and judge-service availability.
10. Document the process for preparing the next contest.

## Archive rules
- Archiving must not rewrite historical scores or submissions.
- Archived contests should be read-only through normal application workflows.
- Any exceptional correction to historical data must be permission-protected and audited.
- Do not retain sensitive identity documents longer than the institution's approved retention policy requires.

## Operational monitoring
Track:
- Queue worker health.
- Failed Laravel jobs.
- Email delivery failures.
- Judge Engine availability and job failures.
- Storage capacity.
- Database backup status.
- Contest transition and audit events.

## Completion checklist
- [ ] Finished contests can be archived.
- [ ] Historical results remain available to authorized administrators.
- [ ] Reports can be generated.
- [ ] Retention rules are documented and implemented.
- [ ] Backups include database and required private files.
- [ ] Restore procedure has been tested.
- [ ] Queue, email, and judge-service failures can be reviewed.
- [ ] Next-contest preparation process is documented.
