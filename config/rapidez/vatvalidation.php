<?php

return [
    // How long to cache the result of a validation attempt
    'cache_duration' => 604800, // 604800 seconds = 1 week

    // The validation endpoint by default only responds:
    // - On the registration page;
    // - When a customer is logged in;
    // - When a guest cart is active.
    // Enable this setting to allow the validation to be used anywhere.
    'allow_anywhere' => false,
];
