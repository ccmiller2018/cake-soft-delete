<?php

namespace SoftDelete\Test\Mocks\Entities;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $post_id
 * @property int $tag_id
 * @property bool $soft_delete
 * @property FrozenTime $soft_delete_timestamp
 */
class PostTag extends Entity
{
    public $_accessible = [
        'id' => true,
        'post_id' => true,
        'tag_id' => true,
        'soft_delete_timestamp' => true,
        'soft_delete' => true,
    ];
}
