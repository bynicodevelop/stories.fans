<?php

return [
    'accept' => env("MEDIA_MIME"),

    'temporary_file_upload' => [
        'rules' => env("MEDIA_RULES"),
        'directory' => 'tmp',
        'disk' => 'local',
        'disk' => 'local',
        'max_upload_time' => 60
    ],
];
