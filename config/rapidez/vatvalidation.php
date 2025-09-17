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

    // Should validation be forced on everything put into the box?
    // This will automatically count any VAT id invalid if it's not from one of the supported countries (i.e. EU)
    // Any exclusions that should still bypass the check can be added to the `force_exclusions` option.
    'force_validation' => false,
    'force_exclusions' => [
        // 'GB',
    ],
];
