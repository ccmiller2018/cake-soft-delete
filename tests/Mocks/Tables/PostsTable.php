<?php

namespace SoftDelete\Test\Mocks\Tables;

use SoftDelete\Test\Mocks\Entities\Post;
use SoftDelete\Traits\UsesSoftDelete;
use Cake\ORM\Table;

class PostsTable extends Table
{
    use UsesSoftDelete;

    public function initialize(array $config): void
    {
        $this->setTable('posts');
        $this->setEntityClass(Post::class);

        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users');

        $this->belongsToMany(
            'Tags',
            [
                'through' => 'PostsTags',
                'joinTable' => 'posts_tags',
                'foreignKey' => 'post_id',
                'targetForeignKey' => 'tag_id'
            ]
        );
    }
}

