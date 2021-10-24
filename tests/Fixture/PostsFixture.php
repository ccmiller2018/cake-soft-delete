<?php

namespace SoftDelete\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PostsFixture extends TestFixture
{
    public $fields = [
        'id' => [
            'type' => 'integer',
        ],
        'user_id' => [
            'type' => 'integer',
            'default' => '0',
            'null' => false,
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
            'user_id' => 1,
            'soft_delete' => false,
        ],
        [
            'id' => 2,
            'user_id' => 1,
            'soft_delete' => false,
        ],
    ];
}
