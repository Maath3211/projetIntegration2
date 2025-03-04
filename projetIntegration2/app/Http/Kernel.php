// ...existing code...

protected $middleware = [
    // Add to the $middleware array:
    \App\Http\Middleware\SetLocale::class,
];

protected $middlewareGroups = [
    'web' => [
        // ...existing code...
        \App\Http\Middleware\VerifyCsrfToken::class,
        // ...existing code...
    ],

    'api' => [
        // ...existing code...
    ],
];

// ...existing code...
