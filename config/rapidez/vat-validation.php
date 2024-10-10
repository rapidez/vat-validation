<?php

return [
    // How long to cache the result of a validation attempt
    'cache-duration' => 604800, // 604800 seconds = 1 week

    // The maximum amount of API request calls per minute
    'rate-limit' => 12,

    // The validation endpoint by default only responds:
    // - On the registration page;
    // - When a customer is logged in;
    // - When a guest cart is active.
    // Enable this setting to allow the validation to be used anywhere.
    'allow-anywhere' => false,
];
