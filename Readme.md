#CakePHP Soft Delete

## Purpose
This package adds the ability to use Soft Delete within the ORM of CakePHP from Version 4 onwards.

## Installation
To make use of this package, you will need to require the package in your Composer.json file

`composer require ccmiller2018/cake-soft-delete`

## Making A Model Soft Deletable

use The UsesSoftDelete trait within the Table file.  For example:

```
// in src/Model/Table/UsersTable.php
...
use SoftDelete\Traits\SoftDeleteTrait;

class UsersTable extends Table
{
use SoftDeleteTrait;
```

After doing this, your model will now require two additional fields:

soft_delete => boolean
soft_delete_timestamp => date_time


## Use

### Soft deleting records

`delete` and `deleteAll` functions will now soft delete records by populating soft_delete_timestamp field with the date of the deletion and the soft_delete timestamp with true

```
// in src/Model/Table/UsersTable.php
$this->delete($user); // $user entity is now soft deleted if UsersTable uses SoftDeleteTrait.
```

### Restoring Soft deleted records

To restore a soft deleted entity into an active state, use the restore method:

```
// in src/Model/Table/UsersTable.php
// Let's suppose $user #1 is soft deleted.
$user = $this->Users->find('all', ['withDeleted'])->where('id', 1)->first();
$this->restore($user); // $user #1 is now restored.
```

### Finding records

Find, get or dynamic finders (such as findById) will only return non soft deleted records. To also return soft deleted records, $options must contain 'withDeleted'. Example:

```
// in src/Model/Table/UsersTable.php
$nonSoftDeletedRecords = $this->find('all');
$allRecords            = $this->find('all', ['withDeleted']);
```

### Hard deleting records

To hard delete a single entity:

```
// in src/Model/Table/UsersTable.php
$user = $this->get($userId);
$success = $this->hardDelete($user);
```

To mass hard delete records that were soft deleted before a given date, you can use hardDeleteAll($date):

```
// in src/Model/Table/UsersTable.php
$date = new \DateTime('some date');
$affectedRowsCount = $this->hardDeleteAll($date);
```

### Soft deleting & associations

Associations are correctly handled by SoftDelete plugin.

* Soft deletion will be cascaded to related models as usual. If related models also use SoftDelete Trait, they will be soft deleted.
* Soft deletes records will be excluded from counter caches.
