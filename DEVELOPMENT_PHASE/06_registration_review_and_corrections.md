# Phase 6 – Registration Review and Corrections

## Goal
Give administrators a controlled process to verify each contestant's identity document and request corrections from the coach.

## Scope
- Registration review dashboard.
- Individual ID verification.
- Team-level application decision.
- Correction requests by email.
- Secure correction links.
- Review and correction history.

## Individual document review
Each contestant document has its own status:
- `PENDING`
- `VERIFIED`
- `REJECTED`
- `RESUBMISSION_REQUIRED`

Administrators review every contestant's ID separately and record the decision, reviewer, date, and notes.

## Team application decision
The team application cannot be approved until every contestant's ID has been verified. After all individual checks pass, the administrator makes the final team-level approval decision.

Possible application states include:
- `PENDING_REVIEW`
- `CORRECTION_REQUESTED`
- `RESUBMITTED`
- `APPROVED`
- `REJECTED`

## Correction workflow
1. Administrator chooses Request Corrections.
2. Administrator enters a clear explanation of the required changes.
3. System creates a unique, random, unguessable, expiring correction token.
4. Store only a hash of the token, along with its expiry and status.
5. Email the correction URL to the registered coach.
6. Coach opens a prefilled correction form without logging in.
7. Coach edits the requested fields and/or replaces documents.
8. On resubmission, invalidate the old link and return the application to review.
9. Any replaced identity document returns to an unverified state.
10. Record the request and resubmission in the correction history.

## Security rules
- A correction link must access only its own registration.
- It must not provide access to the admin dashboard or other applications.
- Links expire and can be revoked.
- A link becomes invalid after approval.
- Never store the raw correction token.
- Keep uploaded files private and validate type and size.

## Suggested records
- `contest_registration_corrections`
- `contest_registration_logs`

## Completion checklist
- [ ] Admin can review each contestant ID separately.
- [ ] Each ID has an independent verification status.
- [ ] Team approval is blocked until all IDs are verified.
- [ ] Admin can request corrections with a reason.
- [ ] Coach receives a secure expiring correction link.
- [ ] Coach can update only the relevant application.
- [ ] Old links are invalidated after resubmission.
- [ ] Replaced IDs require verification again.
- [ ] Review and correction history is retained.
