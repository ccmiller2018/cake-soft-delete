<?php

namespace SoftDelete\Test\Mocks\Entities;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $name
 * @property bool $soft_delete
 * @property FrozenTime $soft_delete_timestamp
 */
class Tag extends Entity
{
    public $_accessible = [
        'id' => true,
        'name' => true,
        'soft_delete_timestamp' => true,
        'soft_delete' => true,
    ];
}
