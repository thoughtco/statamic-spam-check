<?php

return [
    'api_key' => env('STATAMIC_SPAM_CHECK_API_KEY', ''),
    
    'forms' => 'all', // or add an array of form handles eg ['form1', 'form2']

    'fail_silently' => true,

    'test_mode' => env('STATAMIC_SPAM_CHECK_TEST_MODE', 'off'),
    
    'threshold' => 5,
];
