# Claude Code — Project Guidelines

This is a **Laravel 12 application template** (`jftecnologia/laravel-base-template`).
All detailed behavior is loaded from **skills** and **docs/** files.

---

## 1. Source of Truth Hierarchy

1. Explicit user instructions
2. Active skill files under `.claude/skills/**/SKILL.md`
3. Project documentation under `docs/`
4. This `CLAUDE.md`
5. Existing code patterns
6. Framework conventions

Never override a skill or documentation rule using assumptions.

---

## 2. Template Development Workflow

This template supports a structured AI-assisted development flow:

1. **`/generate-prd`** — Define product requirements in `docs/PRD.md`
2. **`/generate-architecture`** — Create system architecture in `docs/architecture/`
3. **`/generate-task-breakdown`** — Break requirements into tasks in `docs/tasks/`
4. **`/implement-task`** — Implement individual tasks with quality gates

Additional skills for development:

| Skill                       | When to use                                          |
| --------------------------- | ---------------------------------------------------- |
| `inertia-react-development` | React pages, forms, navigation with Inertia v2       |
| `tailwindcss-development`   | Styling and UI changes with Tailwind CSS v4          |
| `wayfinder-development`     | Referencing backend routes in frontend (Wayfinder)   |
| `pest-testing`              | Writing or debugging PestPHP tests                   |
| `generate-test`             | Generating PestPHP tests for existing code           |
| `developing-with-fortify`   | Authentication features (login, 2FA, password reset) |
| `security-analyst`          | OWASP Top 10 security review                         |
| `skill-creator`             | Creating new skills                                  |

Before performing a task: search for a relevant skill, follow it strictly, do not mix responsibilities.

---

## 3. How to Find Information

### Documentation (`docs/`)

| Topic                   | Location                         |
| ----------------------- | -------------------------------- |
| Product requirements    | `docs/PRD.md`                    |
| System architecture     | `docs/architecture/*.md`         |
| Development tasks       | `docs/tasks/*.md`                |
| Progress tracking       | `docs/progress/*.md`             |
| Tech stack              | `docs/engineering/STACK.md`      |
| Code standards          | `docs/engineering/CODE_STANDARDS.md` |
| Git workflow            | `docs/engineering/WORKFLOW.md`   |
| Testing guidelines      | `docs/engineering/TESTING.md`    |

Search `docs/` before asking questions. Prefer existing documents over assumptions.

### Laravel Boost (MCP Tools)

Always use `search-docs` before making code changes to ensure correct approach. Use multiple broad queries: `['rate limiting', 'routing rate limiting', 'routing']`. Do not add package names to queries.

Other useful tools: `tinker`, `database-schema`, `database-query`, `last-error`, `browser-logs`, `list-routes`, `get-absolute-url`.

---

## 4. Development Philosophy

- No DDD — no unnecessary abstraction
- Prefer clarity over cleverness
- SOLID, pragmatically applied
- Configuration over hard-coded logic
- Extensible and pluggable by default
- Fluent classes
- Design for internationalization (i18n)

**Complete standards**: `docs/engineering/CODE_STANDARDS.md`

---

## 5. Application Structure

```
app/
├── Actions/          # Single-purpose actions (grouped by domain)
├── Enums/            # PHP enums
├── Extensions/       # Package customizations (non-domain logic)
├── Http/             # Controllers, Middleware, Requests
├── Models/           # Eloquent models
├── Providers/        # Service providers
└── Support/          # Helpers (helpers.php)
```

Key conventions:
- **Actions** for single-purpose operations, **Services** for orchestration
- **Extensions** for package customizations only (not domain logic)
- **Exceptions** via `php artisan make:app-exception` (uses `jftecnologia/laravel-exceptions`)
- **Form Requests** for all validation (never inline in controllers)
- **Named routes** + Wayfinder for frontend route references

---

## 6. Quality Gates

Before commits:
```bash
composer lint                    # PHP: format + rector + analyze (MANDATORY)
npm run lint && npm run types    # JS/TS: ESLint + TypeScript
```

Before PRs: also run `composer test`.

During AI development: `vendor/bin/pint --dirty --format agent`

---

## 7. Language Rules

- Code, commits, branches, PRs: **English**
- Conversation with the user: **Portuguese (pt-BR)**

---

## 8. Execution Boundaries

Claude Code must NOT:
- Introduce new architectural layers without asking
- Change existing folder structures without confirmation
- Add new dependencies unless explicitly requested
- Introduce design patterns by default
- Create documentation files unless explicitly requested

Communication: be concise — focus on what's important, not obvious details.

If information is missing or ambiguous: do not guess, ask **one clear question**.

---

## 9. Documentation Maintenance

After changes that affect requirements or architecture:
- Update relevant docs in `docs/`
- Keep `docs/PRD.md` as a **template** (filled by `/generate-prd` skill)
- Write new documentation only inside `docs/`
