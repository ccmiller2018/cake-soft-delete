<?php

namespace SoftDelete\Test\Mocks\Tables;

use SoftDelete\Test\Mocks\Entities\Tag;
use SoftDelete\Traits\UsesSoftDelete;
use Cake\ORM\Table;

class TagsTable extends Table
{
    use UsesSoftDelete;

    public function initialize(array $config): void
    {
        $this->setTable('tags');
        $this->setEntityClass(Tag::class);

        $this->belongsToMany(
            'Posts',
            [
                'through' => 'PostsTags',
                'joinTable' => 'posts_tags',
                'foreignKey' => 'tag_id',
                'targetForeignKey' => 'post_id',
            ]
        );

        $this->hasMany('PostsTags');
    }
}
