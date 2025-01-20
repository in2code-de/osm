<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'osm - OpenStreetMap',
    'description' => 'A smart OpenStreetMap solution in TYPO3',
    'category' => 'plugin',
    'version' => '13.0.0',
    'author' => 'Alex Kellner',
    'author_email' => 'alexander.kellner@in2code.de',
    'author_company' => 'in2code.de',
    'state' => 'stable',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0 - 13.4.99',
            'php' => '8.2.0 - 8.3.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
            'tt_address' => '0.0.0 - 0.0.0',
        ],
    ],
];
