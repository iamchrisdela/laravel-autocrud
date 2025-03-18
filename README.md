# Laravel AUTO CRUD Package

A Laravel package to generate CRUD scaffolding inspire by Symfony's make:crud.
---
## Features

- Generates **laravel  controller, Blade, Route** for CRUD operations (Index, Create, Edit, Show).
- Supports dynamic form generation based on the model's fillable fields.
- Easy to use with a single Artisan command.
---  

## Requirements

- PHP 8.1 or higher
- Laravel 11 or 12

## Installation

1. **Install via Composer**:
   Run the following command in your Laravel project:
   ```bash

     composer require iamchris/laravel-autocrud

  ```
  Publish the Package Configuration (if applicable):

     php artisan vendor:publish --provider="iamchris\LaravelCrud\LaravelCrudServiceProvider"

 

```
To generate CRUD scaffolding for a model, run:
```

      php artisan make:crud ModelName

   ```
    After running the make:crud command, the following will be generated:

      Controller: App\Http\Controllers\ModelNameController

       Views: resources/views/model-name/ (index, create, edit, show)

       Routes: Resource routes in routes/web.php
