# Phase 7 – Team Approval and Account Generation

## Goal
Convert an approved registration application into an official contest team and create one shared login account for that team.

## Scope
- Official team creation.
- Team membership records.
- One shared team account.
- Username uniqueness.
- Password generation and secure credential handling.
- Administrator approval notification.

## Approval workflow
1. Administrator opens an application whose contestant IDs have all been verified.
2. Administrator approves the application.
3. System creates the official team for that contest.
4. System creates team-member records from the approved contestant information.
5. System creates exactly one shared account for the team.
6. System sends an approval notification email to the administrator containing the team and member details.
7. The coach and contestants do not receive login credentials by email.

## Account rules
- Each approved team receives one unique username and one password.
- Contestants use the shared team account.
- No individual contestant login accounts are created.
- Coach has no CodeArena login account.
- Admin may regenerate a team's password before the contest is scheduled.
- Once the contest is scheduled, the credentials are locked.
- Contestants cannot change the shared password.
- Passwords are handed to teams on contest day, not sent early by email.

## Secure password printing design
A password hash cannot be converted back into the original password. Therefore, the system needs a controlled way to produce the printed credential sheet without permanently storing plaintext passwords.

Choose and document a secure approach before implementation, such as:
- Generate a password and store it temporarily in encrypted form with strict access controls until contest-day printing; or
- Use a controlled credential-generation and delivery workflow that can replace the stored hash safely before scheduling.

Do not store plaintext passwords in ordinary database columns, logs, emails, or generated public files. Restrict credential viewing and printing to authorized administrators. Ensure password rotation invalidates any previous printable credential.

## Suggested records
- `contest_teams`
- `contest_team_members`
- `team_accounts`

## Completion checklist
- [ ] Only eligible applications can be approved.
- [ ] Approval creates an official contest team.
- [ ] Exactly one shared account is created per team.
- [ ] Username uniqueness is enforced.
- [ ] Admin can regenerate passwords before scheduling.
- [ ] Credentials become locked after scheduling.
- [ ] No password is emailed to coach or contestants.
- [ ] Admin approval notification includes team and member details.
- [ ] Credential storage and printing approach is security-reviewed.
