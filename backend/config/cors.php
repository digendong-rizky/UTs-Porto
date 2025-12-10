<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'auth/google', 'auth/google/callback'],
    
    'allowed_methods' => ['*'],
    
    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:5173'),
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'https://porto-connect-mu.vercel.app', // Production Vercel domain
        'https://porto-connect-git-main-lucinus-projects-268766af.vercel.app', // Preview deployment
    ],
    
    'allowed_origins_patterns' => [
        '#^https://.*\.vercel\.app$#', // Allow all Vercel preview deployments
        '#^https://porto-connect.*\.vercel\.app$#', // Allow porto-connect Vercel domains
    ],
    
    'allowed_headers' => ['*'],
    
    'exposed_headers' => [],
    
    'max_age' => 0,
    
    'supports_credentials' => true,
];