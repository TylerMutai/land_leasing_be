### Setting Up the Project

**Note:** The project requires a working [Laravel](https://laravel.com/) installation.

- Clone the repository `git clone https://github.com/TylerMutai/land_leasing_be.git`

- Clone the frontend repository if you haven't already `git clone https://github.com/TylerMutai/land-leasing.git`

- Run `composer install`

- Run `cp .env.example .env`
- 
- Run `touch database/land-leasing.db`

- Run `php artisan key:generate`

- Run `php artisan migrate`

- Run `php artisan passport:install`

- Run `php artisan serve`

- Run the frontend app, and login with:
- admin: `admin@test.com` as email and `admin` as password
- merchant: `metchant@test.com` as email and `merchant` as password
- farmer: `lessor@test.com` as email and `lessor` as password
- user: `user@test.com` as email and `user` as password
