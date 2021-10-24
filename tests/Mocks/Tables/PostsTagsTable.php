<?php

namespace SoftDelete\Test\Mocks\Tables;

use SoftDelete\Test\Mocks\Entities\PostTag;
use SoftDelete\Traits\UsesSoftDelete;
use Cake\ORM\Table;

class PostsTagsTable extends Table
{
    use UsesSoftDelete;

    public function initialize(array $config): void
    {
        $this->setTable('posts_tags');
        $this->setEntityClass(PostTag::class);

        $this->belongsTo('Tags');
        $this->belongsTo('Posts');
    }
}
