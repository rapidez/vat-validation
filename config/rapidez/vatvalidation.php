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

    // Should we allow non-VIES-validateable countries to skip validation?
    // We can only validate VAT numbers that come from EU countries. This means that anything that doesn't come from here will get skipped by default.
    // If you want to force validation on everything that's written into the VAT input, enable this option.
    'force_validation' => false,

    // Depending on your customer base, you might want to allow certain non-EU countries through without being validated through VIES.
    // A good example of this would be VAT numbers starting with `GB`. These may be valid but can't be validated through the VIES API.
    'force_exclusions' => [
        // 'GB',
    ],
];
