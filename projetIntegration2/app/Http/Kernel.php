// ...existing code...

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
