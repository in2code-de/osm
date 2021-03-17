<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'osm - OpenStreetMap',
    'description' => 'A smart OpenStreetMap solution in TYPO3',
    'category' => 'plugin',
    'version' => '2.1.0',
    'author' => 'Alex Kellner',
    'author_email' => 'alexander.kellner@in2code.de',
    'author_company' => 'in2code.de',
    'state' => 'stable',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
            'php' => '7.2.0-7.99.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ]
];
