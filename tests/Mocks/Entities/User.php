<?php

namespace SoftDelete\Test\Mocks\Entities;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * @property int $id
 * @property bool $soft_delete
 * @property FrozenTime $soft_delete_timestamp
 */
class User extends Entity
{
    public $_accessible = [
        'id' => true,
        'soft_delete' => true,
        'soft_delete_timestamp' => true,
    ];
}