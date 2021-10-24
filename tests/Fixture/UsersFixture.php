<?php

namespace SoftDelete\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class UsersFixture extends TestFixture
{
    public $fields = [
        'id' => [
            'type' => 'integer'
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
            'soft_delete' => false,
            'posts_count' => 2,
        ],
        [
            'id' => 2,
            'soft_delete' => false,
            'posts_count' => 0,
        ],
    ];
}