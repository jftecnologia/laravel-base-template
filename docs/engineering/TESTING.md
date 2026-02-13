# Testing Guidelines

**Purpose**: Define testing philosophy, practices, and execution for this Laravel application.

---

## Testing Philosophy

- **Every change must be tested** — write or update tests, then run them
- **Test business behavior**, not framework internals
- **Tests must pass before task completion**
- **Use PestPHP syntax exclusively**
- **Prefer tests over verification scripts** — do not create tinker scripts or verification files when tests cover the functionality

---

## Testing Framework

- **PestPHP 4** — expressive, modern PHP testing
- **Pest Plugin Laravel** — Laravel-specific assertions and helpers
- **Faker** — test data generation
- **Mockery** — mocking library

---

## Test Structure

```
tests/
├── Pest.php           # Pest configuration and shared setup
├── TestCase.php       # Base test case class
├── Feature/           # Feature/integration tests (most tests)
└── Unit/              # Isolated unit tests
```

### Feature vs Unit

- **Feature tests** (`tests/Feature/`): test complete features through HTTP, database, queue, etc. Most tests should be feature tests.
- **Unit tests** (`tests/Unit/`): test individual classes/methods in isolation, no Laravel bootstrapping needed.

---

## Running Tests

```bash
# Run all tests
composer test

# Run with compact output
php artisan test --compact

# Run specific file
php artisan test --compact tests/Feature/Auth/LoginTest.php

# Run specific test by name
php artisan test --compact --filter="can login with valid credentials"
```

---

## Creating Tests

Use artisan to create tests:

```bash
# Feature test (default)
php artisan make:test --pest Auth/LoginTest

# Unit test
php artisan make:test --pest --unit Services/PaymentServiceTest
```

Or use the **`/generate-test`** skill for AI-assisted test generation.

---

## Writing Tests

### Naming

Use descriptive `it()` blocks that explain **what** is being tested:

```php
// Good
it('redirects to dashboard after successful login')
it('returns validation error when email is missing')
it('sends notification when order is placed')

// Bad
it('tests login')
it('works correctly')
```

### Arrange-Act-Assert

```php
it('creates a new user with valid data', function () {
    // Arrange
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
    ];

    // Act
    $response = post('/register', $data);

    // Assert
    $response->assertRedirect('/dashboard');
    assertDatabaseHas('users', ['email' => 'john@example.com']);
});
```

### Using Factories

Always use factories to create test models — check factory states before manually setting attributes:

```php
it('shows user profile', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/profile')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Settings/Profile')
            ->has('user')
        );
});
```

### Datasets for Multiple Scenarios

```php
it('validates required fields', function (string $field) {
    $data = User::factory()->make()->toArray();
    unset($data[$field]);

    post('/register', $data)
        ->assertSessionHasErrors($field);
})->with(['name', 'email', 'password']);
```

### Faker

Follow existing conventions — use either `$this->faker` or `fake()`:

```php
it('stores a post', function () {
    $user = User::factory()->create();

    actingAs($user)->post('/posts', [
        'title' => fake()->sentence(),
        'body' => fake()->paragraphs(3, true),
    ])->assertRedirect();
});
```

---

## What to Test

### Test

- HTTP endpoints (status codes, redirects, response structure)
- Validation rules (required fields, formats, custom rules)
- Authorization (policies, gates, middleware)
- Business logic in Actions and Services
- Model relationships and scopes
- Queue jobs and their effects
- Events and listeners
- Notifications
- Inertia page rendering and props

### Don't Test

- Laravel framework internals (Eloquent, routing engine, etc.)
- Third-party package behavior
- Trivial getters/setters
- Private methods directly — test through public API

### Happy Path + Unhappy Path

Every feature should cover both scenarios:

```php
// Happy path
it('logs in with valid credentials', function () {
    $user = User::factory()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect('/dashboard');
});

// Unhappy path
it('fails login with wrong password', function () {
    $user = User::factory()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors();
});
```

---

## Inertia Testing

Test Inertia responses using the `assertInertia` method:

```php
it('renders the settings page', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/settings/profile')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Settings/Profile')
            ->has('user')
            ->where('user.email', $user->email)
        );
});
```

---

## Mocking and Fakes

Use Laravel's built-in fakes:

```php
// HTTP
Http::fake(['api.example.com/*' => Http::response(['data' => 'test'])]);

// Queue
Queue::fake();
// ... action ...
Queue::assertPushed(SendNotification::class);

// Notifications
Notification::fake();
// ... action ...
Notification::assertSentTo($user, OrderConfirmation::class);

// Time
$this->travel(5)->minutes();
// or
Carbon::setTestNow(now()->addHour());

// Events
Event::fake();
// ... action ...
Event::assertDispatched(OrderPlaced::class);
```

---

## Test Isolation

- Each test must be independent — no shared state between tests
- Use `RefreshDatabase` trait for database tests (configured in `Pest.php`)
- Use `beforeEach()` for common setup within a file

```php
beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can update profile', function () {
    actingAs($this->user)
        ->put('/settings/profile', ['name' => 'New Name'])
        ->assertRedirect();
});
```

---

## Test Coverage

- **Do not aim for 100% coverage** — test meaningful behavior
- Focus on: critical paths, public APIs, edge cases, error handling
- Use the **`pest-testing`** skill for advanced patterns

---

## Debugging Tests

```bash
# Verbose output
php artisan test --compact -v

# Stop on first failure
php artisan test --compact --stop-on-failure
```

Within tests, use `dump()` or `dd()` temporarily — **remove before committing**.

---

## Pre-Commit Test Checklist

- [ ] All tests pass (`composer test`)
- [ ] New/updated tests cover the change
- [ ] No `dd()`, `dump()`, or debug code in tests
- [ ] Test names are descriptive
- [ ] Tests are isolated and repeatable
- [ ] Factories used for model creation

---

## Related Documentation

- **[STACK.md](STACK.md)** — Tech stack and dependencies
- **[CODE_STANDARDS.md](CODE_STANDARDS.md)** — Code quality and conventions
- **[WORKFLOW.md](WORKFLOW.md)** — Git workflow, commits, PRs
- Skill: **`pest-testing`** — Advanced PestPHP patterns
- Skill: **`generate-test`** — AI-assisted test generation
