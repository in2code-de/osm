<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'osm - OpenStreetMap',
    'description' => 'A smart OpenStreetMap solution in TYPO3',
    'category' => 'plugin',
    'version' => '4.0.3',
    'author' => 'Alex Kellner',
    'author_email' => 'alexander.kellner@in2code.de',
    'author_company' => 'in2code.de',
    'state' => 'stable',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0 - 12.4.99',
            'php' => '8.1.0 - 8.2.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
            'tt_address' => '0.0.0 - 0.0.0',
        ],
    ],
];
