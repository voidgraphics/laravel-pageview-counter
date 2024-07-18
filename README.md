# laravel-pageview-counter

A stupid simple analytics tool to keep track of page views in Laravel projects. It logs requests to a database table and you can access the data with an Eloquent model.

## Installation

```bash
composer require voidgraphics/laravel-pageview-counter
```

You need to publish the migration that will add the pageviews table: 

```bash
php artisan vendor:publish --tag=pageview-counter-migrations
```

Then run the migrations

```bash
php artisan migrate
```

After this, register the `LogRequest` middleware. You can do it globally or on specific routes. Here's how to install it globally in Laravel 11

```php
// bootstrap/app.php

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use PageviewCounter\Middleware\LogRequest;

return Application::configure(basePath: dirname(__DIR__))
    // ...
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(LogRequest::class);
    })
    // ...
    ->create();
```

## Accessing the data

Every request hitting those routes will now be logged. You can access that data using the `PageviewCounter\Models\Pageview` model. It has 3 scopes to help you prepare the data for display.

```php
Pageview::daily()->get(); // Returns the number of views grouped by date and path.

/*
Illuminate\Database\Eloquent\Collection {
    all: [
        PageviewCounter\Models\Pageview {
            views: 156,
            path: "/",
            date: "2024-07-18",
        },
        PageviewCounter\Models\Pageview {
            views: 68,
            path: "about",
            date: "2024-07-18",
        }
        PageviewCounter\Models\Pageview {
            views: 289,
            path: "/",
            date: "2024-07-17",
        },
        PageviewCounter\Models\Pageview {
            views: 32,
            path: "about",
            date: "2024-07-17",
        }
    ],
}
*/
```

```php
Pageview::withoutBots()->get(); // Exclude most popular bots by user-agent

/*
Shorthand for: 

$query->where('useragent', 'not like', '%bot%')
    ->where('useragent', 'not like', '%python-requests%')
    ->where('useragent', 'not like', '%http%')
    ->where('useragent', 'not like', '%node-fetch%')
    ->where('useragent', 'not like', '%postman%')
    ->where('useragent', 'not like', '%curl%')
*/
```

```php
Pageview::uniqueVisitors()->get(); // Returns the count of unique visitors, grouped by date

/*
Illuminate\Database\Eloquent\Collection {
    all: [
        PageviewCounter\Models\Pageview {
            unique_visitors: 176,
            date: "2024-07-18",
        },
        PageviewCounter\Models\Pageview {
            unique_visitors: 302,
            date: "2024-07-17",
        }
    ],
}
*/
```