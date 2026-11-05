# Phase 5 – Public Team Registration

## Goal
Allow a coach to submit a team application through a simple public form without creating an account or signing in.

## Scope
- Public contest listing.
- Team application form.
- Coach details.
- Contestant details.
- Individual contestant ID-card uploads.
- Application receipt and confirmation email.

## Registration flow
1. Visitor opens the public contests page.
2. The page lists only contests whose registration is open.
3. Coach selects a contest and completes one form for the team.
4. Coach enters team information and coach contact details.
5. Coach enters each contestant's information.
6. Coach uploads an identity document for every contestant.
7. Server validates the submission and stores the application as pending review.
8. System sends a registration-received email to the coach and/or configured recipients.

## Important rules
- No coach account or login is required.
- No team account is created at registration time.
- A coach may be external to the institution.
- Contestants must be members of the institution and must provide the required ID-card proof.
- The application is not an official team until it has been approved.
- Enforce the contest's configured team-size limits.
- Apply rate limiting and suitable bot/spam protection to public forms.

## Suggested records
- `contest_registrations`
- `contest_registration_members`
- `contest_registration_documents`

Keep uploaded documents in private storage. Store metadata and private file paths in the database.

## Completion checklist
- [ ] Public listing shows only open registrations.
- [ ] Coach can submit without authentication.
- [ ] One application can include the coach and all contestants.
- [ ] Each contestant can provide an ID document.
- [ ] Team-size limits are enforced.
- [ ] Application is stored as pending review.
- [ ] No team account is created before approval.
- [ ] Private documents cannot be accessed publicly.
