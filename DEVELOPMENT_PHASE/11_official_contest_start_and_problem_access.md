# Phase 11 – Official Contest Start and Problem Access

## Goal
Unlock the digital contest interface at the official start time while keeping problem statements available only on printed paper.

## Start workflow
1. System compares the current server time with the contest's official start time.
2. At the start time, the contest becomes `RUNNING`.
3. The team dashboard unlocks contest functionality.
4. Administrator distributes the printed problem papers.
5. Teams use paper statements and the digital platform for submissions and contest status.

## Digital dashboard
The digital interface may show:
- Problem labels such as A, B, C.
- Whether the team's own team has solved each problem.
- Submission form and submission history.
- Verdicts and permitted execution details.
- Leaderboard according to the final visibility policy.

The exact public solved-status display and leaderboard/freeze behavior remain decisions to confirm.

## Problem statement restriction
Problem statements and descriptions are printed only. They must not be sent to the browser, included in JavaScript, exposed through APIs, or retrievable through predictable URLs.

The client may receive the assigned problem label and identifiers needed to submit code, but not the statement content.

## Time enforcement
- Use the official contest start and end timestamps.
- Enforce start/end rules on the server, not only in JavaScript.
- Do not trust the browser clock.
- Define how submissions received near the deadline are treated.

## Completion checklist
- [ ] Contest transitions to Running at the official start.
- [ ] Team dashboard unlocks at the correct time.
- [ ] Admin can distribute printed papers.
- [ ] Digital pages do not expose problem statements.
- [ ] Submission controls become available only during the contest.
- [ ] Time checks are server-side.
