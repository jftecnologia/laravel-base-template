# Tech Stack

**Purpose**: Define the complete technical stack for this Laravel application template.

---

## Quick Reference

| Layer              | Technology                     | Version  |
| ------------------ | ------------------------------ | -------- |
| **Language**       | PHP                            | 8.4+     |
| **Framework**      | Laravel                        | 12       |
| **Database**       | MySQL                          | 9        |
| **Frontend**       | React + TypeScript             | 19       |
| **SPA Bridge**     | Inertia.js                     | 2        |
| **Styling**        | Tailwind CSS                   | 4        |
| **Bundler**        | Vite                           | 7        |
| **Auth**           | Laravel Fortify (headless)     | 1        |
| **Route Gen**      | Laravel Wayfinder              | 0.x      |
| **Testing**        | PestPHP                        | 4        |
| **Static Analysis**| Larastan (PHPStan)             | 3        |
| **Code Formatting**| Pint (PHP) + Prettier (JS/TS)  | -        |
| **Refactoring**    | Rector                         | 2        |
| **Linting (JS)**   | ESLint                         | 9        |

---

## Project Type

**Laravel Application Template** — installed via:

```bash
composer create-project jftecnologia/laravel-base-template <project-name>
```

This is a **full application**, not a library or package.

---

## Backend Stack

### Core

- **PHP 8.4+** with Laravel 12
- **MySQL 9** for database, cache, queue, and session
- **Fortify** for headless authentication (login, register, password reset, 2FA)

### Internal Packages

| Package                          | Facade              | Purpose                                          |
| -------------------------------- | -------------------- | ------------------------------------------------ |
| `jftecnologia/laravel-context`   | `LaravelContext`     | Global context (request, correlation, app info)   |
| `jftecnologia/laravel-tracing`   | `LaravelTracing`     | Distributed tracing via HTTP headers              |
| `jftecnologia/laravel-exceptions`| `LaravelExceptions`  | AppException, error pages, error_id, DB storage   |

### Observability & Debugging

| Tool                        | Purpose                         |
| --------------------------- | ------------------------------- |
| `sentry/sentry-laravel`    | Error tracking (production)     |
| `opcodesio/log-viewer`     | Log viewer UI                   |
| `spatie/laravel-activitylog`| Activity/audit log              |
| `laradumps/laradumps`      | Debug dumps (development)       |
| `barryvdh/laravel-debugbar`| Debug toolbar (development)     |
| `laravel/pail`             | Real-time log tailing           |

### Development Utilities

| Tool                           | Purpose                        |
| ------------------------------ | ------------------------------ |
| `barryvdh/laravel-ide-helper`  | IDE autocompletion generation  |
| `laravel/tinker`               | REPL for debugging             |
| `laravel/sail`                 | Docker development environment |
| `laravel/boost`                | MCP server for AI-assisted dev |

---

## Frontend Stack

### Core

- **React 19** with **TypeScript 5.7+**
- **Inertia.js v2** — SPA bridge (no separate API layer)
- **Tailwind CSS v4** via `@tailwindcss/vite`
- **Vite 7** — bundler with `laravel-vite-plugin`

### UI Components

- **Radix UI** — headless primitives (dialog, dropdown, select, tooltip, etc.)
- **Headless UI** — additional headless components
- **Lucide React** — icons
- **class-variance-authority** + **clsx** + **tailwind-merge** — styling utilities

### Route Generation

- **Laravel Wayfinder** — generates TypeScript functions from Laravel routes
- Import from `@/actions/` (controllers) or `@/routes/` (named routes)

---

## Quality Tools

### PHP

| Tool       | Config File    | Purpose                              |
| ---------- | -------------- | ------------------------------------ |
| **Pint**   | `pint.json`    | Code formatting (PSR-12 + Laravel)   |
| **Larastan**| `phpstan.neon` | Static analysis (Level 5)            |
| **Rector** | `rector.php`   | Automated refactoring                |

### JavaScript / TypeScript

| Tool         | Config File          | Purpose                            |
| ------------ | -------------------- | ---------------------------------- |
| **ESLint**   | `eslint.config.js`   | Linting (React, hooks, TS)         |
| **Prettier** | `.prettierrc`        | Formatting (imports, Tailwind)     |
| **TypeScript**| `tsconfig.json`     | Type checking                      |

---

## Available Scripts

### PHP (composer)

```bash
composer dev          # Start server + queue + pail + vite (all-in-one)
composer dev:ssr      # Same as dev but with SSR
composer test         # Run PestPHP test suite
composer format       # Run Pint (code formatter)
composer rector       # Run Rector (automated refactoring)
composer analyze      # Run Larastan/PHPStan (static analysis)
composer lint         # Run format + rector + analyze (all quality checks)
composer setup        # Full project setup (install, migrate, build)
```

### JavaScript (npm)

```bash
npm run dev           # Start Vite dev server
npm run build         # Build frontend assets
npm run build:ssr     # Build with SSR support
npm run lint          # Run ESLint with auto-fix
npm run format        # Run Prettier on resources/
npm run format:check  # Check Prettier formatting
npm run types         # TypeScript type checking
```

### Script Usage

| When                   | Run                              |
| ---------------------- | -------------------------------- |
| **Starting dev**       | `composer dev`                   |
| **Before commits**     | `composer lint && npm run lint`  |
| **Before PRs**         | `composer lint && composer test && npm run lint && npm run types` |
| **Frontend not updating** | `npm run build` or restart `npm run dev` |

---

## Application Structure

```
app/
├── Actions/             # Single-purpose action classes
├── Enums/               # PHP enums
├── Extensions/          # Package customizations (non-domain)
│   ├── ActivityLog/     # Spatie ActivityLog overrides
│   ├── LaravelContext/  # Context providers and middleware
│   ├── LaravelExceptions/ # Exception channels
│   ├── LaravelTracing/  # Custom tracing sources
│   └── System/          # System-level middleware
├── Http/
│   ├── Controllers/     # HTTP controllers
│   ├── Middleware/       # Application middleware
│   └── Requests/        # Form request validation
├── Models/              # Eloquent models
├── Providers/           # Service providers
├── Services/            # Multi-step orchestration services (create as needed)
└── Support/             # Helpers (helpers.php)

resources/
├── js/
│   ├── components/      # Reusable React components
│   ├── layouts/         # Page layouts
│   ├── pages/           # Inertia page components
│   └── types/           # TypeScript type definitions
└── views/               # Blade views (email, error pages)

tests/
├── Feature/             # Feature/integration tests
├── Unit/                # Isolated unit tests
├── Pest.php             # Pest configuration
└── TestCase.php         # Base test case

docs/
├── PRD.md               # Product requirements (template)
├── architecture/        # System architecture docs
├── tasks/               # Task breakdown docs
├── progress/            # Progress tracking
└── engineering/         # Engineering guidelines (this folder)
```

---

## Related Documentation

- **[CODE_STANDARDS.md](CODE_STANDARDS.md)** — Code quality and conventions
- **[WORKFLOW.md](WORKFLOW.md)** — Git workflow, commits, PRs
- **[TESTING.md](TESTING.md)** — Testing guidelines
- **[../PRD.md](../PRD.md)** — Product requirements template
