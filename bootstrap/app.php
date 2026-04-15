<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Custom Public Path (shared hosting / non-standard docroot)
|--------------------------------------------------------------------------
|
| Some deployments use a document root like "public_html" instead of the
| default "public" directory. When that's the case, Laravel's public_path()
| points to a folder that may not exist, causing runtime failures when code
| tries to write public assets (e.g. WhatsApp images).
|
| Set APP_PUBLIC_PATH to an absolute path (recommended) or a path relative
| to APP_BASE_PATH (e.g. "public_html") to override.
|
*/
$publicPath = $_ENV['APP_PUBLIC_PATH'] ?? $_SERVER['APP_PUBLIC_PATH'] ?? null;
if (!empty($publicPath)) {
    $basePath = $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__);
    // If a relative path is provided, resolve it against the app base path.
    if (!preg_match('~^/|^[A-Za-z]:\\\\~', $publicPath)) {
        $publicPath = $basePath . DIRECTORY_SEPARATOR . $publicPath;
    }

    $app->usePublicPath($publicPath);
}

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
