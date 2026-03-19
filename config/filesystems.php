<?php

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        // 🔥 เพิ่มอันนี้
        'cloudinary' => [
            'driver' => 'cloudinary',
            'url' => env('CLOUDINARY_URL'),
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];