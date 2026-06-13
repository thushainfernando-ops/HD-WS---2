<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Cloudinary image storage and delivery service
    |
    */

    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Delivery URL
    |--------------------------------------------------------------------------
    |
    | The base URL for delivering images
    |
    */

    'delivery_url' => env('CLOUDINARY_DELIVERY_URL', 'https://res.cloudinary.com'),

    /*
    |--------------------------------------------------------------------------
    | Folders
    |--------------------------------------------------------------------------
    |
    | Default folders for organizing uploads
    |
    */

    'folders' => [
        'menu_items' => 'hotdish/menu',
        'user_profiles' => 'hotdish/profiles',
        'banners' => 'hotdish/banners',
        'logos' => 'hotdish/logos',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Optimization
    |--------------------------------------------------------------------------
    |
    | Default transformation settings
    |
    */

    'quality' => 'auto',
    'format' => 'auto',
    'default_width' => 400,
    'default_height' => 300,
    'thumb_width' => 200,
    'thumb_height' => 200,
];
