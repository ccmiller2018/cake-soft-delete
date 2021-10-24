<?php

namespace SoftDelete\Traits;

use Cake\ORM\Query;
use SoftDelete\Orm\SoftDeleteQuery;
use Cake\ORM\RulesChecker;
use Cake\Datasource\EntityInterface;
use SoftDelete\Exceptions\MissingColumnException;
use DateTime;
use InvalidArgumentException;

trait UsesSoftDelete
{
    public $softDeleteField = 'soft_delete';
    public $softDeleteDateField = 'soft_delete_timestamp';

    public function getSoftDeleteField(): string
    {
        if ($this->getSchema()->getColumn($this->softDeleteField) === null) {
            throw new MissingColumnException(
                __('Configured field `{0}` is missing from the table `{1}`.',
                    $this->softDeleteField,
                    $this->getAlias()
                )
            );
        }

        return $this->softDeleteField;
    }

    public function getSoftDeleteDateField(): string
    {
        if ($this->getSchema()->getColumn($this->softDeleteDateField) === null) {
            throw new MissingColumnException(
                __('Configured field `{0}` is missing from the table `{1}`.',
                    $this->softDeleteDateField,
                    $this->getAlias()
                )
            );
        }

        return $this->softDeleteDateField;
    }

    public function query(): Query
    {
        return new SoftDeleteQuery($this->getConnection(), $this);
    }

    protected function _processDelete($entity, $options): bool
    {
        if ($entity->isNew()) {
            return false;
        }

        $primaryKey = (array)$this->getPrimaryKey();
        if (!$entity->has($primaryKey)) {
            $msg = 'Deleting requires all primary key values.';
            throw new InvalidArgumentException($msg);
        }

        if ($options['checkRules'] && !$this->checkRules($entity, RulesChecker::DELETE, $options)) {
            return false;
        }

        $event = $this->dispatchEvent(
            'Model.beforeDelete',
            [
                'entity' => $entity,
                'options' => $options
            ]
        );

        if ($event->isStopped()) {
            return $event->result;
        }

        $this->_associations->cascadeDelete(
            $entity,
            ['_primary' => false] + $options->getArrayCopy()
        );

        $query = $this->query();
        $conditions = (array)$entity->extract($primaryKey);
        $statement = $query->update()
            ->set([$this->getSoftDeleteField() => true])
            ->set([$this->getSoftDeleteDateField() => date('Y-m-d H:i:s')])
            ->where($conditions)
            ->execute();

        $success = $statement->rowCount() > 0;
        if (!$success) {
            return false;
        }

        $this->dispatchEvent(
            'Model.afterDelete',
            [
                'entity' => $entity,
                'options' => $options
            ]
        );

        return true;
    }

    public function deleteAll($conditions): int
    {
        $query = $this->query()
            ->update()
            ->set([$this->getSoftDeleteField() => true])
            ->set([$this->getSoftDeleteDateField() => date('Y-m-d H:i:s')])
            ->where($conditions);
        $statement = $query->execute();
        $statement->closeCursor();
        return $statement->rowCount();
    }

    public function hardDelete(EntityInterface $entity): bool
    {
        if(!$this->delete($entity)) {
            return false;
        }
        $primaryKey = (array)$this->getPrimaryKey();
        $query = $this->query();
        $conditions = (array)$entity->extract($primaryKey);
        $statement = $query->delete()
            ->where($conditions)
            ->execute();

        return $statement->rowCount() > 0;
    }

    public function hardDeleteAll(Datetime $until): int
    {
        $query = $this->query()
            ->delete()
            ->where(
                [
                    $this->getSoftDeleteDateField() . ' <=' => $until->format('Y-m-d H:i:s')
                ]
            );

        $statement = $query->execute();
        $statement->closeCursor();

        return $statement->rowCount();
    }

    public function restore(EntityInterface $entity): bool
    {
        $softDeleteField = $this->getSoftDeleteField();
        $softDeleteDateField = $this->getSoftDeleteDateField();
        $entity->$softDeleteField = false;
        $entity->$softDeleteDateField = null;

        return $this->save($entity) instanceof EntityInterface;
    }
}
