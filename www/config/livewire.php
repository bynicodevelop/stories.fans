<?php

return [
    'accept' => env("MEDIA_MIME"),

    'temporary_file_upload' => [
        'rules' => env("MEDIA_RULES"), // (100MB max, and only images and videos)
        'directory' => 'tmp',
    ],
];
