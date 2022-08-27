<?php

return [
    'driver' => 'mongodb',
    'connection' => [
        'host' => env('DB_HOST_SECOND'),
        'port' => env('DB_PORT_SECOND'),
        'database' => env('DB_DATABASE_SECOND'),
        'username' => env('DB_USERNAME_SECOND'),
        'password' => env('DB_PASSWORD_SECOND'),
        'auth' => [
            'source' => env('DB_AUTHENTICATION_DATABASE'),
            'mechanism' => env('DB_AUTHENTICATION_MECHANISM')
        ]
    ],
    'bucketName' => env('DB_GFS_BUCKET_NAME'),
    'chunkSize' => intval(env('DB_GFS_CHUNK_SIZE'), 16),
    'fileUploadPath' => storage_path(env('GFS_UPLOAD_PATH'))
];
