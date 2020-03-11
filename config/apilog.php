<?php

return [
    //currently supported drivers are 'db' and 'file'
    "driver" => "file",
    "filename" => "api-{Y-m-d}.log",
    "dont_log" => [
        'password', 'password_confirmation', 'new_password', 'old_password',
    ]
];
