<?php

namespace SoftDelete\Orm;

use Cake\ORM\Query as CakeQuery;

class SoftDeleteQuery extends CakeQuery
{
    public function triggerBeforeFind(): void
    {
        if (!$this->_beforeFindFired && $this->_type === 'select') {
            parent::triggerBeforeFind();

            $repository = $this->getRepository();
            $options = $this->getOptions();

            if (!is_array($options) || !in_array('withDeleted', $options)) {
                $aliasedField = $repository->aliasField($repository->getSoftDeleteField());
                $this->andWhere($aliasedField . ' = 0');
            }
        }
    }
}
