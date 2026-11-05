# Phase 10 – Contest-Day Check-In and Team Login

## Goal
Prepare teams to enter the contest environment using printed credentials on assigned connected machines.

## Scope
- Contest-day administrative checklist.
- A5 credential sheets.
- Team login.
- Waiting dashboard.
- Access restrictions before the official start.

## Credential sheet
Prepare one A5 sheet per team containing:
- Contest name.
- Team name.
- Team username.
- Team password.
- Simple login instructions.

The administrator prints and hands the sheet to the team on contest day. Credentials must not be sent to the coach or contestants before the event.

## Team login
1. Team opens the CodeArena login page on its assigned machine.
2. Team enters the shared username and password.
3. Server authenticates the team account.
4. Server checks that the account belongs to the correct scheduled contest.
5. Team is directed to its contest waiting dashboard.

## Waiting dashboard before start
Before the official start time, show only approved waiting information:
- Countdown to contest start.
- Contest information.
- Team information.

Do not show:
- Problem statements or descriptions.
- Problem test cases.
- Submission controls.
- Leaderboard.

## Security and operations
- Do not reveal credentials in public pages or URLs.
- Prevent one team account from accessing another team's private data.
- Keep the login and session flow secure.
- Ensure all contest machines can reach the Laravel application.
- Confirm server time and contest time zone before the event.

## Completion checklist
- [ ] Credential sheets are prepared and access-controlled.
- [ ] Admin can print one sheet per team.
- [ ] Team can log in with its shared account.
- [ ] Team is restricted to its own contest context.
- [ ] Waiting dashboard displays the countdown.
- [ ] Problems, submissions, and leaderboard remain hidden before start.
- [ ] Contest machines and server clock are checked.
