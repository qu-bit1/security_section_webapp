# Security Section Web Application

A web application for managing security reports and incidents at IIT Kanpur.

## Features

- User authentication and role-based access control
- Create, view, edit and delete security reports
- Attach files and tags to reports
- Add comments and remarks to reports
- Export reports to PDF
- Approval workflow for reports
- Dashboard for monitoring security incidents

## Tech Stack

- **Backend**: Laravel 10.x
- **Frontend**: Vue.js 3 with Inertia.js
- **UI Components**: PrimeVue
- **Database**: MySQL
- **Authentication**: Laravel Breeze

## Getting Started

### Prerequisites

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL

### Installation

1. Clone the repository and change your directory
```bash
cd security_section_webapp
```

2. Install PHP dependencies
```bash
composer install
```

3. Install Node dependencies
```bash
npm install
```

4. Copy environment file and configure your database credentials
```bash
cp .env.example .env
```

5. Generate application key
```bash
php artisan key:generate
```

6. Run database migrations and seeders
```bash
php artisan migrate --seed
```

7. Start the development server
```bash
php artisan serve
npm run dev
```

The application will be available at `http://localhost:8000`

## Project Structure

- `app/Http/Controllers/` - Contains all controllers
- `app/Models/` - Database models and relationships
- `app/Enums/` - Enums for status and permissions
- `database/migrations/` - Database structure
- `resources/js/` - Vue components and frontend code
- `routes/` - API and web routes
- `tests/` - Feature and unit tests

## Testing

Run the test suite:
```bash
php artisan test
```

## Role Management

The application uses a role-based access control system with three main roles:
- super-admin: Full access to all features
- admin: Administrative access with some restrictions
- user: Basic access to create and manage own reports

### Default Roles and Permissions

First, seed the default roles and permissions:
```bash
php artisan db:seed --class=PermissionSeeder
```

### Managing Roles

#### View User Roles
To see all users and their roles, use tinker:
```bash
php artisan tinker
```
Then run:
```php
foreach(App\Models\User::with('roles')->get() as $user) { 
    print("\nEmail: " . $user->email . "\nRoles: " . $user->roles->pluck('name')->join(', ') . "\n"); 
}
```

#### Assign Roles
To assign a role to a user via tinker:
```php
// Assign super-admin role
User::where('email', 'user@example.com')->first()->assignRole('super-admin');

// Assign user role
User::where('email', 'user@example.com')->first()->assignRole('user');
```

### Role Permissions

1. Super Admin
   - Full system access
   - Manage users and their roles
   - Access and manage all reports
   - Approve reports
   - Manage roles and permissions

2. User
   - Create and view own reports
   - Edit own reports
   - Add comments to reports
   - Basic dashboard access

Note: New users are automatically assigned the 'user' role upon registration.

