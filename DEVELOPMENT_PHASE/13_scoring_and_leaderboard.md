# Phase 13 – Scoring and Leaderboard

## Goal
Calculate ICPC-style team scores and update the leaderboard after judging is complete.

## Scoring rules
- A team can solve each contest problem only once for scoring.
- Rank teams by solved problem count, descending.
- Break ties by total penalty, ascending.
- For each solved problem, penalty is:
  - Minutes from the official contest start to the accepted submission time; plus
  - 20 minutes for each wrong attempt before the first Accepted submission for that problem.
- Attempts after the first Accepted submission do not add penalty.
- Use the official contest start time as the scoring reference.

## Processing workflow
1. Judge result is stored.
2. If the result is a valid final contestant verdict, update the team's problem result.
3. If it is the first Accepted result for that team/problem, mark the problem solved.
4. Calculate solve time from the official contest start to the accepted submission time.
5. Count eligible wrong attempts before that first Accepted result.
6. Calculate the problem penalty.
7. Recalculate or increment the team's contest score safely.
8. Refresh the leaderboard data.

## Concurrency and correctness
- Use database transactions.
- Lock the relevant team/problem result row when processing concurrent submissions.
- Ensure a submission result is processed idempotently; duplicate queue jobs must not double-count attempts or penalty.
- Store enough data to reproduce the score if a scoring bug is found.
- Define which verdicts count as wrong attempts. Infrastructure errors and internal judge failures must not count as contestant wrong answers.

## Suggested records
- `contest_team_problem_results`
- `contest_team_scores`
- Submission result records.

## Leaderboard decisions still to confirm
- Whether the leaderboard is visible throughout the contest.
- Whether the leaderboard freezes near the end.
- Whether public users can view it or only logged-in teams/admins.
- Whether solved problems show a count or only a yes/no indicator.
- Which submission details are visible to other teams.

Do not silently choose these policies; they affect contest strategy and information disclosure.

## Completion checklist
- [ ] Solved count is ranked descending.
- [ ] Penalty is ranked ascending for ties.
- [ ] Solve time uses official contest start.
- [ ] Wrong attempts before first AC add the configured penalty.
- [ ] Attempts after AC do not add penalty.
- [ ] A problem is counted as solved only once.
- [ ] Score updates are transactional and idempotent.
- [ ] Infrastructure errors are excluded from wrong-attempt counts.
- [ ] Leaderboard visibility and freeze policy are documented.
