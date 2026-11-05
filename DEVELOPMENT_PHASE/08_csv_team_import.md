# Phase 8 – CSV Team Import

## Goal
Allow administrators to import multiple team records from a CSV file while preserving validation and review rules.

## Scope
- CSV template and download.
- Upload and parsing.
- Preview before import.
- Validation and error report.
- Import batch tracking.

## Proposed CSV format
Use one row per person and group rows by `team_name`:

```csv
team_name,person_name,email,role
Code Warriors,Ahmad,ahmad@example.com,coach
Code Warriors,Ali,ali@example.com,member
Code Warriors,Sara,sara@example.com,member
Debuggers,Fatima,fatima@example.com,coach
Debuggers,Omar,omar@example.com,member
```

This is a proposed format and should be confirmed before implementation. The current public registration process also requires contestant ID-card documents, so decide how imported contestants will provide those documents.

## Implementation tasks
1. Provide a downloadable CSV template.
2. Upload and parse the CSV safely.
3. Group rows by team name.
4. Validate required columns and supported role values.
5. Validate exactly one coach and the required number of contestants per team.
6. Check duplicate emails, duplicate team names, and contest team-size limits.
7. Show a preview and row/team-level validation errors.
8. Import only after administrator confirmation.
9. Track the import batch and its result.

## Important decision
Imported teams should normally enter the same verification and approval workflow as public applications unless the institution explicitly defines a controlled administrator-only bypass. Do not silently mark imported contestants as ID-verified when no proof has been reviewed.

## Suggested records
- `contest_import_batches`
- Reuse registration/application and team records where practical rather than creating a second unrelated team workflow.

## Completion checklist
- [ ] CSV template is available.
- [ ] File type and size are validated.
- [ ] Rows are grouped into teams correctly.
- [ ] Coach/member counts are validated.
- [ ] Duplicate and invalid data is reported clearly.
- [ ] Preview is shown before import.
- [ ] Failed validation does not create partial teams.
- [ ] Import batch is recorded.
- [ ] ID verification and approval rules are preserved.
