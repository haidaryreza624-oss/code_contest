# Phase 2 – Administrator and Institution Management

## Goal
Provide secure administrator access and the basic institution-level management tools required to operate one CodeArena installation.

## Scope
- Administrator accounts and authentication.
- Authorization for administrative actions.
- Institution profile and configuration.
- Administrative audit logging.
- Basic dashboard and navigation.

## Implementation tasks
1. Define the administrator account model and authentication flow.
2. Restrict administrative routes with authentication middleware.
3. Add authorization policies or gates for sensitive actions.
4. Create an institution settings page for the institution name and relevant contact details.
5. Create the administrator dashboard with links to contests, problems, registrations, teams, and reports.
6. Record important actions such as creating contests, reviewing applications, changing statuses, and scheduling contests.
7. Add safe account-management procedures for administrator access.

## Suggested records
- `users` for administrator accounts.
- `institution_settings` for installation-specific configuration.
- `admin_audit_logs` for important administrative actions.

## Rules
- This installation belongs to one institution; it is not a multi-tenant SaaS application.
- Do not expose administrative data or routes to public visitors or team accounts.
- Team accounts are separate from administrator accounts.
- A coach does not receive a CodeArena login account under the current design.

## Completion checklist
- [ ] Administrator authentication works.
- [ ] Administrative routes are protected.
- [ ] Authorization rules are enforced server-side.
- [ ] Institution settings can be viewed and updated.
- [ ] Important administrative actions are logged.
- [ ] The dashboard links to the main management sections.
