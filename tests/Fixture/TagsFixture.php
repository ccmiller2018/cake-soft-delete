<?php

namespace SoftDelete\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class TagsFixture extends TestFixture
{
    public $fields = [
        'id' => [
            'type' => 'integer',
        ],
        'name' => [
            'type' => 'string',
        ],
        'soft_delete_timestamp' => [
            'type' => 'datetime',
            'default' => null,
            'null' => true,
        ],
        'soft_delete' => [
            'type' => 'boolean',
            'default' => null,
            'null' => true,
        ],
        '_constraints' => [
            'primary' => [
                'type' => 'primary',
                'columns' => [
                    'id',
                ],
            ],
        ],
    ];

    public $records = [
        [
            'id' => 1,
            'name' => 'Cat',
            'soft_delete' => false,
        ],
        [
            'id' => 2,
            'name' => 'Dog',
            'soft_delete' => false,
        ],
        [
            'id' => 3,
            'name' => 'Fish',
            'soft_delete' => false,
        ],
    ];
}
