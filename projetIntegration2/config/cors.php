<?php
// filepath: /C:/Users/Utilisateur/Documents/React Native/projetIntegration2/projetIntegration2/config/cors.php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    // Use a regex pattern to allow any origin
    'allowed_origins' => [],
    'allowed_origins_patterns' => ['/^.*$/'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    // For token-based auth you don't need to support credentials:
    'supports_credentials' => false,
];