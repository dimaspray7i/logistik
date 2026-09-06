<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Internal Shipment Prefix Fallback
    |--------------------------------------------------------------------------
    |
    | Used if a customer does not have a custom prefix assigned.
    |
    */
    'prefix' => env('SHIPMENT_PREFIX', 'PKM'),

    /*
    |--------------------------------------------------------------------------
    | Sequence Start & Padding Config
    |--------------------------------------------------------------------------
    |
    | Default starting sequence number for customer prefixes (e.g., ADJ-19001)
    |
    */
    'starting_sequence' => env('SHIPMENT_STARTING_SEQUENCE', 19001),
    'sequence_digits' => env('SHIPMENT_SEQUENCE_DIGITS', 5),

    /*
    |--------------------------------------------------------------------------
    | Supported External Expeditions / Carriers
    |--------------------------------------------------------------------------
    |
    | List of standard external carriers available for selection.
    | Custom carriers entered by admins are also preserved.
    |
    */
    'carriers' => [
        'JNE',
        'J&T',
        'SiCepat',
        'Pos Indonesia',
        'DHL',
        'FedEx',
    ],
];
