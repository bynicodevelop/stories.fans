<?php

return [
    'accept' => env("MEDIA_MIME"),

    'temporary_file_upload' => [
        'rules' => env("MEDIA_RULES"),
        'directory' => 'tmp',
        'disk' => 'local',
    ],
];
