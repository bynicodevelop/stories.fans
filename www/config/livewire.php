<?php

return [
    'accept' => env("MEDIA_MIME"),

    'temporary_file_upload' => [
        'rules' => env("MEDIA_RULES"),
        'directory' => env("MEDIA_TMP_DIRECTORY", "tmp"),
        'disk' => env("MEDIA_DISK", "local"),
        'max_upload_time' => env("MEDIA_MAX_UPLOAD_TIME", 60),
    ],
];
