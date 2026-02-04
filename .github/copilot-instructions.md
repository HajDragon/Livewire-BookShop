# Laravel + Livewire Project Instructions

## Tech Stack & Versions

- **PHP**: 8.2+, **Laravel**: 12.x, **Livewire**: 4.x, **Flux UI**: 2.x (Free)
- **Laravel Fortify**: 1.x for authentication
- **Pest**: 4.x for testing (not PHPUnit)
- **Tailwind CSS**: 4.x, **Vite**: 7.x for asset bundling
- **Laravel Pint**: Code style (PSR-12)

## Project Architecture

### Livewire Component Structure

- Components are **class-based** (not Volt) in `app/Livewire/`
- Organized by feature: `Actions/`, `Settings/`
- Views in `resources/views/livewire/` mirror component structure
- Components use Flux UI components (`flux::input`, `flux::button`, etc.)

### Route Organization

- Main routes: [routes/web.php](routes/web.php)
- **Settings routes**: Separate file [routes/settings.php](routes/settings.php) (imported in web.php)
- Settings routes use `Route::livewire()` syntax, not controllers
- Auth middleware: `['auth']` or `['auth', 'verified']`

### Authentication

- **Laravel Fortify** handles all auth (login, register, password reset, 2FA)
- Custom Livewire components for settings: [app/Livewire/Settings/](app/Livewire/Settings/)
- Settings components: `Profile`, `Password`, `Appearance`, `TwoFactor`, `DeleteUserForm`

### Data & Models

- Models in [app/Models/](app/Models/) with lowercase filenames (e.g., `book.php`)
- Factories in [database/factories/](database/factories/) for all models
- Seeders in [database/seeders/](database/seeders/)
- Use `HasFactory` trait with typed factory docblock: `/** @use HasFactory<\Database\Factories\BookFactory> */`

### Validation Patterns

- Shared validation logic in [app/Concerns/](app/Concerns/) as traits
- Example: `ProfileValidationRules` trait provides `profileRules()`, `nameRules()`, `emailRules()`
- Components use traits via `use ProfileValidationRules;`

### Policies

- Authorization in [app/Policies/](app/Policies/) (e.g., `BookPolicy.php`)
- Register in `AppServiceProvider` or use auto-discovery

## Development Workflow

### Running the App

```bash
# All-in-one dev server (server + queue + vite)
composer run dev

# Individual processes
php artisan serve
php artisan queue:listen --tries=1
npm run dev
```

### Testing

```bash
composer test        # Lint + run tests
php artisan test     # Run Pest tests only
pint                 # Fix code style
pint --test          # Check style without fixing
```

### Key Artisan Commands

```bash
php artisan livewire:make ComponentName              # Create Livewire component
php artisan livewire:layout                          # Setup layout
php artisan make:model ModelName -mfs                # Model + migration + factory + seeder
php artisan make:policy ModelPolicy --model=Model    # Create policy
```

## Code Conventions

### Naming

- **Models**: singular, lowercase files (`book.php`), PascalCase class (`Book`)
- **Livewire components**: PascalCase (`Profile.php`), nested in feature folders
- **Routes**: kebab-case (`settings/two-factor`)
- **View files**: kebab-case (`.blade.php`)

### Livewire Components

```php
// Mount method for initialization
public function mount(): void { ... }

// Use #[Computed] for derived properties
#[Computed]
public function propertyName(): bool { ... }

// Dispatch events for component communication
$this->dispatch('event-name', param: $value);
```

### Flux UI Usage

- Use `flux::` components instead of raw HTML forms
- Common: `flux:input`, `flux:button`, `flux:heading`, `flux:text`, `flux:link`
- Custom icons in [resources/views/flux/icon/](resources/views/flux/icon/)

### Testing with Pest

```php
test('description', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
  
    $response = $this->get(route('home'));
    $response->assertOk();
});
```

## File Organization

- **Don't create new top-level folders** without approval
- Settings components → `app/Livewire/Settings/`
- Action components → `app/Livewire/Actions/`
- Shared traits → `app/Concerns/`
- Custom requests → `app/Http/Requests/`

## Laravel Boost Integration

This project uses Laravel Boost MCP server with enhanced tools:

- Use `tinker` tool for debugging/querying Eloquent
- Use `database-query` tool for read-only DB queries
- Use `search-docs` tool before coding to verify approach
- Use `list-artisan-commands` to check available command parameters
- Use `get-absolute-url` for sharing project URLs

## Best Practices

- **Always check sibling files** for structure/naming before creating new files
- **Reuse existing components** before writing new ones
- **Don't create documentation files** unless explicitly requested
- **Be concise** in explanations - focus on what matters
- **Test coverage** is more important than verification scripts
- **Follow PSR-12** via Laravel Pint preset
- if the user uses ask mode provide only needed code, minimal and complete explanation
- your role is to be a teacher, not a code writer
- If frontend changes don't show, user may need to run `npm run dev` or `composer run dev`
