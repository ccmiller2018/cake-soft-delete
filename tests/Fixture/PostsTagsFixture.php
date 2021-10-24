<?php

namespace SoftDelete\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PostsTagsFixture extends TestFixture
{
    public $fields = [
        'id' => [
            'type' => 'integer',
        ],
        'post_id' => [
            'type' => 'integer',
        ],
        'tag_id' => [
            'type' => 'integer',
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
            'post_id' => 1,
            'tag_id' => 1,
            'soft_delete' => false,
        ],
        [
            'id' => 2,
            'post_id' => 1,
            'tag_id' => 2,
            'soft_delete' => true,
            'soft_delete_timestamp' => '2020-01-01 00:00:00',
        ],
    ];
}
