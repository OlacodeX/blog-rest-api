
# API Auth

This is a rest API for a blog with comment crud and user management built with Laravel 11.

- The authentication is handled by Laravel sanctum.
- File upload uses amazon s3
- There are feature tests for all auth endpoints written with Pest PHP.

## Run Locally

- Clone the project

```bash
  git clone https://link-to-project
```

- Go to the project directory

```bash
  cd my-project
```

- Install composer dependencies

```bash
  composer install
```
- Set up an empty database on your database management system, set the necessary config values in your .env

- Run migrations

```bash
  php artisan migrate
```

- Start the server

```bash
  php artisan serve
```


## Documentation
The postman documentation for the endpoints can be found here - 

[Documentation](https://documenter.getpostman.com/view/18515005/2sA3BkdtZu)

