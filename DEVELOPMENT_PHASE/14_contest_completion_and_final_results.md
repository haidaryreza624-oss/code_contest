# Phase 14 – Contest Completion and Final Results

## Goal
Stop contest activity at the official end time, finalize judging and scoring, and publish the approved final results.

## End-of-contest workflow
1. At the official end time, block new submissions on the server.
2. Apply the documented policy for submissions accepted by the server before the deadline but still being judged.
3. Allow already accepted judging jobs to reach a terminal state within a controlled processing window.
4. Ensure every completed result has been processed exactly once.
5. Recalculate and verify final team scores.
6. Change contest lifecycle status to `FINISHED`.
7. Generate final standings and result reports.
8. Make results visible according to the configured publication policy.

## Important policy decisions
Before implementation, define:
- Whether submissions received before the deadline may finish judging after the deadline.
- How long the system waits for outstanding judge jobs.
- What happens to jobs that fail because the judge service is unavailable.
- Whether final standings are published immediately or require administrator approval.
- Whether detailed submission results are public.

## Result information
Possible final report fields:
- Rank.
- Team name.
- Solved count.
- Total penalty.
- Solved problem labels.
- Relevant accepted times, if permitted.

Avoid exposing hidden tests, expected outputs, private contestant documents, or credentials in reports.

## Administrative verification
Provide an administrator review screen to inspect:
- Teams and final membership.
- Submission totals and terminal states.
- Accepted problems.
- Score calculations.
- Outstanding or failed judge jobs.
- Audit history.

## Completion checklist
- [ ] New submissions are blocked at contest end.
- [ ] Deadline handling for in-flight submissions is implemented.
- [ ] Outstanding judge jobs are handled by documented policy.
- [ ] Scores are finalized and verified.
- [ ] Contest status changes to Finished.
- [ ] Final results are generated.
- [ ] Publication follows the configured policy.
- [ ] Private and hidden data is excluded from reports.
