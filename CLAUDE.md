# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is "Apotek Sakura", a Laravel 11 web application for a pharmacy business. The project combines a traditional Laravel backend with a modern frontend landing page using Bootstrap and Tailwind CSS.

## Architecture

### Backend Structure
- **Framework**: Laravel 11 with PHP 8.2+
- **Database**: SQLite (database.sqlite)
- **Frontend**: Blade templating with dual CSS frameworks (Bootstrap for landing page, Tailwind CSS for admin)
- **Build Tools**: Vite for asset compilation

### Key Directories
- `app/Http/Controllers/` - Laravel controllers (currently minimal with base Controller)
- `resources/views/landing/` - Landing page Blade templates with Bootstrap styling
- `resources/views/admin/` - Admin interface templates (structure exists but empty)
- `public/landing/` - Static assets for the landing page (CSS, JS, images)
- `routes/web.php` - Web routes (currently only root route)
- `database/migrations/` - Database schema migrations
- `tests/` - PHPUnit test suites (Unit and Feature)

### Frontend Architecture
The application has a dual frontend approach:
1. **Landing Page**: Uses Bootstrap 5.2.3 with custom styles in `public/landing/css/styles.css`
2. **Admin Interface**: Configured for Tailwind CSS via Vite

## Common Development Commands

### Backend Development
```bash
# Start development server with all services
composer dev

# Individual services
php artisan serve                    # Start Laravel server
php artisan queue:listen --tries=1  # Start queue worker
php artisan pail --timeout=0        # Start log viewer

# Database
php artisan migrate                  # Run migrations
php artisan migrate:fresh --seed    # Fresh migration with seeding

# Code quality
vendor/bin/pint                     # Laravel Pint (code formatter)
```

### Frontend Development
```bash
# Asset compilation
npm run dev    # Development build with watch
npm run build  # Production build

# Full development stack (runs all services concurrently)
composer dev   # Runs server, queue, logs, and Vite simultaneously
```

### Testing
```bash
# Run all tests
vendor/bin/phpunit

# Run specific test suites
vendor/bin/phpunit tests/Unit     # Unit tests only
vendor/bin/phpunit tests/Feature  # Feature tests only
```

## Project-Specific Notes

### Styling Approach
- Landing page uses Bootstrap with custom CSS in `public/landing/css/styles.css`
- Admin interface is set up for Tailwind CSS via `resources/css/app.css` and Vite
- Assets are served from both `public/landing/` (static) and compiled via Vite

### Database Configuration
- Uses SQLite by default (`database/database.sqlite`)
- Default migrations include users, cache, and jobs tables

### Development Workflow
The project is configured for concurrent development with `composer dev` command that runs:
- Laravel development server
- Queue listener
- Log viewer (Pail)
- Vite development server with HMR

### File Structure Notes
- Landing page templates follow a component-based structure in `resources/views/landing/components/`
- Layout files are in `resources/views/landing/layout/`
- Partials (navbar, footer, etc.) are in `resources/views/landing/partials/`