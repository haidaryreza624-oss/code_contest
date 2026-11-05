# Phase 1 – Project Foundation

## Goal
Create the Laravel application and establish the technical foundation for CodeArena.

## Scope
- Create the Laravel project and configure the environment.
- Configure MySQL/MariaDB and database migrations.
- Configure Blade templates and Bootstrap.
- Establish the main application layout, navigation, and reusable UI components.
- Configure administrator authentication.
- Configure email delivery.
- Configure Laravel queues and background workers.
- Configure private file storage for documents.
- Configure the connection to the existing FastAPI Judge Engine through a dedicated `JudgeService`.

## Implementation tasks
1. Create the Laravel application and initialize Git.
2. Configure `.env` for the database, mail, queue, filesystem, and judge-engine URL.
3. Create the initial database and verify Laravel can connect.
4. Add Bootstrap to the Blade layout.
5. Create shared layouts and partials for navigation, alerts, forms, and page content.
6. Configure administrator login and logout.
7. Configure a queue driver and verify a background job can run.
8. Configure private storage for uploaded identity documents and generated contest files.
9. Create a configuration entry such as `JUDGE_ENGINE_URL`; do not hard-code the service URL in controllers.
10. Create a `JudgeService` class responsible for communicating with the existing FastAPI API.

## Important design decisions
- Use a modular Laravel monolith for the main application.
- Laravel is the source of truth for users, teams, contests, problems, submissions, and scores.
- The FastAPI service executes submitted code only; it does not own CodeArena records.
- Use queues for judging, email delivery, polling, and document generation.
- Keep identity documents and generated credentials in private storage.

## Security and reliability
- Do not expose private files through public URLs.
- Keep secrets and service URLs in environment configuration.
- Validate all environment settings before deployment.
- The current judge engine must not execute untrusted submissions in a public contest until sandbox isolation, enforced resource limits, and API authentication are implemented.
- Do not allow the browser to communicate directly with the judge engine.

## Completion checklist
- [ ] Laravel application starts successfully.
- [ ] Database connection works.
- [ ] Blade and Bootstrap layout is available.
- [ ] Administrator can log in and log out.
- [ ] Email configuration is tested.
- [ ] A queued job runs successfully.
- [ ] Private file storage is configured.
- [ ] `JudgeService` can reach the configured FastAPI service.
- [ ] Environment configuration is documented.
