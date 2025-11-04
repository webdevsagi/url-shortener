# Laravel URL Shortener

### 1. Prerequisites

- PHP 8.2+
- Composer

### 2. Clone the Repository

### 3. Install Dependencies
composer install

### 4. Create database


### 4. Seed into the database
php artisan migrate --seed

**Default Admin Credentials:**
- **Email:** `admin@example.com`
- **Password:** `password`

### 5. Run the Queue Worker

php artisan queue:work

### 6. Start the Development Server
php artisan serve

The application will be available at `http://localhost:8000`.


## API Usage

### Create a New Link
- **Endpoint:** `POST /api/links`

- **Endpoint:** POST http://localhost:8000/api/links

Content-Type: application/json
X-Api-Key: secret123
{
target_url": "https://www.google.com/search?q=laravel
}

### Get Link Statistics

- **Endpoint:** `GET /api/links/{slug}/stats`
**Response:**
{
    "slug": "MgABIL",
    "target_url": "https://www.google.com/search?q=laravel",
    "total_hits": 0,
    "last_hits": []
}

## Admin Panel

Access the admin panel by navigating to `/admin/links` in your browser. You will be prompted to log in.
- **URL:** `http://localhost:8000/admin/links`
- **Login:** Use the seeded admin credentials (`admin@example.com` / `password`).

## Running Tests
php artisan test
