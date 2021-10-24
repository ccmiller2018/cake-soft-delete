<?php

namespace SoftDelete\Test\Mocks\Entities;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $user_id
 * @property bool $soft_delete
 * @property FrozenTime $soft_delete_timestamp
 */
class Post extends Entity
{
    protected $_accessible = [
        'id' => true,
        'user_id' => true,
        'soft_delete_timestamp' => true,
        'soft_delete' => true,
    ];
}
