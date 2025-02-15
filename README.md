# Laravel AUTO CRUD Package

A Laravel package to generate CRUD scaffolding inspire by Symfony's make:crud.`.

---

## Installation

1. **Install via Composer**:
   Run the following command in your Laravel project:
   ```bash

     composer require iamchris/laravel-autocrud

  ```
  Publish the Package Configuration (if applicable):

     php artisan vendor:publish --provider="iamchris\LaravelCrud\LaravelCrudServiceProvider"

 

   **Usage***:
   ```
Generate CRUD scaffolding for a model:

      php artisan make:crud ModelName

   ```
    After running the make:crud command, the following will be generated:

      Controller: App\Http\Controllers\ModelNameController

       Views: resources/views/model-name/ (index, create, edit, show)

       Routes: Resource routes in routes/web.php
