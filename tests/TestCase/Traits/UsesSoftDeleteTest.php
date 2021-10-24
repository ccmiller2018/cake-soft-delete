<?php
namespace SoftDelete\Test\TestCase\Traits;

use Cake\ORM\Locator\TableLocator;
use Cake\TestSuite\TestCase;
use SoftDelete\Test\Mocks\Tables\PostsTable;
use SoftDelete\Test\Mocks\Tables\PostsTagsTable;
use SoftDelete\Test\Mocks\Tables\TagsTable;
use SoftDelete\Test\Mocks\Tables\UsersTable;

/**
 * @group SoftDelete
 */
class UsesSoftDeleteTest extends TestCase
{
    private $usersTable;
    private $postsTable;
    private $tagsTable;
    private $postsTagsTable;

    /**
     * fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.SoftDelete.Users',
        'plugin.SoftDelete.Posts',
        'plugin.SoftDelete.Tags',
        'plugin.SoftDelete.PostsTags'
    ];

    public function setUp(): void
    {
        parent::setUp();

        $tableLocator = new TableLocator();

        $this->usersTable = $tableLocator->get(
            'Users',
            [
                'className' => UsersTable::class,
            ]
        );

        $this->postsTable = $tableLocator->get(
            'Posts',
            [
                'className' => PostsTable::class,
            ]
        );

        $this->tagsTable = $tableLocator->get(
            'Tags',
            [
                'className' => TagsTable::class,
            ]
        );

        $this->postsTagsTable = $tableLocator->get(
            'PostsTags',
            [
                'className' => PostsTagsTable::class,
            ]
        );
    }

    public function tearDown(): void
    {
        unset($this->usersTable);
        unset($this->postsTable);
        unset($this->tagsTable);
        parent::tearDown();
    }

    /**
     * @test
     */
    public function find_will_not_return_soft_deleted_entities(): void
    {
        $user = $this->usersTable->get(1);
        $user->soft_delete = true;
        $user->soft_delete_timestamp = date('Y-m-d H:i:s');
        $this->usersTable->save($user);

        $user = $this->usersTable->find()->where(['id' => 1])->first();
        $this->assertEquals(null, $user);

    }

    /**
     * @test
     */
    public function find_by_id_will_not_return_deleted_entity(): void
    {
        $user = $this->usersTable->get(1);
        $user->soft_delete = true;
        $user->soft_delete_timestamp = date('Y-m-d H:i:s');

        $this->usersTable->save($user);

        $user = $this->usersTable->findById(1)->first();
        $this->assertEquals(null, $user);
    }

    /**
     * @test
     */
    public function find_with_belongs_to_many_will_ignore_soft_deleted_associations(): void
    {
        $post = $this->postsTable->findById(1)->contain(['Tags'])->first();
        $this->assertEquals(1, count($post->tags));
    }

    /**
     * @test
     */
    public function find_matching_will_ignore_soft_deleted_associations(): void
    {
        $users = $this->usersTable->find()
            ->matching('Posts', function($q) {
                return $q->where(['Posts.id' => 1]);
            });
        $this->assertEquals(1, $users->count());

        $post = $this->postsTable->get(1);
        $this->postsTable->delete($post);

        $posts = $this->postsTable->find('all', ['withDeleted'])->where(['id' => 1]);
        $this->assertEquals(1, $posts->count());

        $users = $this->usersTable->find()
            ->matching('Posts', function($q) {
                return $q->where(['Posts.id' => 1]);
            });
        $this->assertEquals(0, $users->count());
    }


    /**
     * @test
     */
    public function delete_all_will_not_hard_delete()
    {
        $this->usersTable->deleteAll([]);
        $this->assertEquals(0, $this->usersTable->find()->count());
        $this->assertNotEquals(0, $this->usersTable->find('all', ['withDeleted'])->count());

        $this->postsTable->deleteAll([]);
        $this->assertEquals(0, $this->postsTable->find()->count());
        $this->assertNotEquals(0, $this->postsTable->find('all', ['withDeleted'])->count());
    }

    /**
     * @test
     */
    public function delete_will_not_hard_delete(): void
    {
        $user = $this->usersTable->get(1);
        $this->usersTable->delete($user);
        $user = $this->usersTable->findById(1)->first();
        $this->assertEquals(null, $user);

        $user = $this->usersTable->find('all', ['withDeleted'])->where(['id' => 1])->first();
        $this->assertNotEquals(null, $user);
        $this->assertTrue($user->soft_delete);
    }

    /**
     * @test
     */
    public function soft_delete_will_also_soft_delete_belonging_entities(): void
    {
        $user = $this->usersTable->get(1);
        $this->usersTable->delete($user);

        $count = $this->postsTable->find()->where(['user_id' => 1])->count();
        $this->assertEquals(0, $count);

        $count = $this->postsTable->find('all', ['withDeleted'])->where(['user_id' => 1])->count();
        $this->assertEquals(2, $count);
    }

    /**
     * @test
     */
    public function hard_delete_will_fully_remove_entities(): void
    {
        $user = $this->usersTable->get(1);
        $this->usersTable->hardDelete($user);
        $user = $this->usersTable->findById(1)->first();
        $this->assertEquals(null, $user);

        $user = $this->usersTable->find('all', ['withDeleted'])->where(['id' => 1])->first();
        $this->assertEquals(null, $user);
    }

    /**
     * @test
     */
    public function hard_delete_all_will_fully_remove_multiples(): void
    {
        $affectedRows = $this->postsTable->hardDeleteAll(new \DateTime('now'));
        $this->assertEquals(0, $affectedRows);

        $postsRowsCount = $this->postsTable->find('all', ['withDeleted'])->count();

        $this->postsTable->delete($this->postsTable->get(1));
        $affectedRows = $this->postsTable->hardDeleteAll(new \DateTime('now'));
        $this->assertEquals(1, $affectedRows);

        $newpostsRowsCount = $this->postsTable->find('all', ['withDeleted'])->count();
        $this->assertEquals($postsRowsCount - 1, $newpostsRowsCount);
    }

    public function with_deleted_will_return_expected_values(): void
    {
        $query = $this->tagsTable->find();
        $this->assertEquals(2, $query->count());

        $query = $this->tagsTable->find('all', ['withDeleted' => true]);
        $this->assertEquals(3, $query->count());
    }

    /**
     * @test
     */
    public function restore_will_restore_soft_deleted_value(): void
    {
        $user = $this->usersTable->findById(1)->first();
        $this->assertNotNull($user);
        $this->usersTable->delete($user);
        $user = $this->usersTable->findById(1)->first();
        $this->assertNull($user);

        $user = $this->usersTable->find('all', ['withDeleted'])->where(['id' => 1])->first();
        $this->usersTable->restore($user);
        $user = $this->usersTable->findById(1)->first();
        $this->assertNotNull($user);
    }
}
