<?php

namespace SoftDelete\Test\Mocks\Tables;

use SoftDelete\Test\Mocks\Entities\User;
use SoftDelete\Traits\UsesSoftDelete;
use Cake\ORM\Table;

class UsersTable extends Table
{
    use UsesSoftDelete;

    public function initialize(array $config): void
    {
        $this->setTable('users');
        $this->setEntityClass(User::class);

        $this->hasMany(
            'Posts',
            [
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]
        );
    }
}
