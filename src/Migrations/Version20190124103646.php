<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Types\Type;

/**
 * 20190124103646
 */
final class Version20190124103646 extends AbstractMigration
{
    const USER = 'user';
    const ROLE = 'role';
    const USER_TO_ROLE = 'user_to_role';
    const TOPIC = 'topic';
    const BLOG = 'blog';

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'Build base user tables';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) : void
    {
        $userTable = $schema->createTable(self::USER);
        $userTable->addColumn('id', Type::INTEGER, ['unsigned' => true, 'notnull' => true, 'autoincrement' => true]);
        $userTable->addColumn('email', Type::STRING, ['notnull' => true, 'length' => 128]);
        $userTable->addColumn('password', Type::STRING, ['notnull' => true, 'length' => 128]);
        $userTable->addColumn('salt', Type::STRING, ['notnull' => true, 'length' => 256]);
        $userTable->addColumn('first_name', Type::STRING, ['notnull' => true, 'length' => 128]);
        $userTable->addColumn('last_name', Type::STRING, ['notnull' => true, 'length' => 128]);
        $userTable->setPrimaryKey(['id']);

        $roleTable = $schema->createTable(self::ROLE);
        $roleTable->addColumn('id', Type::INTEGER, ['unsigned' => true, 'notnull' => true, 'autoincrement' => true]);
        $roleTable->addColumn('role', Type::STRING, ['notnull' => true, 'length' => 256]);
        $roleTable->setPrimaryKey(['id']);

        $userToRoleTable = $schema->createTable(self::USER_TO_ROLE);
        $userToRoleTable->addColumn('user_id', Type::INTEGER, ['notnull' => true, 'unsigned' => true]);
        $userToRoleTable->addColumn('role_id', Type::INTEGER, ['notnull' => true, 'unsigned' => true]);
        $userToRoleTable->addForeignKeyConstraint($userTable, ['user_id'], ['id'], ['onUpdate' => 'cascade', 'onDelete' => 'cascade']);
        $userToRoleTable->addForeignKeyConstraint($roleTable, ['role_id'], ['id'], ['onUpdate' => 'cascade', 'onDelete' => 'cascade']);
        $userToRoleTable->setPrimaryKey(['user_id', 'role_id']);

        $topicTable = $schema->createTable(self::TOPIC);
        $topicTable->addColumn('id', Type::INTEGER, ['unsigned' => true, 'notnull' => true, 'autoincrement' => true]);
        $topicTable->addColumn('name', Type::STRING, ['notnull' => true, 'length' => 128]);
        $topicTable->setPrimaryKey(['id']);

        $blogTable = $schema->createTable(self::BLOG);
        $blogTable->addColumn('id', Type::INTEGER, ['unsigned' => true, 'notnull' => true, 'autoincrement' => true]);
        $blogTable->addColumn('title', Type::STRING, ['notnull' => true, 'length' => 256]);
        $blogTable->addColumn('description', Type::TEXT, ['notnull' => true, 'length' => 65535]);
        $blogTable->addColumn('topic_id', Type::INTEGER, ['notnull' => true, 'unsigned' => true]);
        $blogTable->addColumn('user_id', Type::INTEGER, ['notnull' => true, 'unsigned' => true]);
        $blogTable->addColumn('is_published', Type::BOOLEAN, ['notnull' => true]);
        $blogTable->addForeignKeyConstraint($topicTable, ['topic_id'], ['id'], ['onUpdate' => 'cascade', 'onDelete' => 'restrict']);
        $blogTable->addForeignKeyConstraint($userTable, ['user_id'], ['id'], ['onUpdate' => 'cascade', 'onDelete' => 'restrict']);
        $blogTable->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function postUp(Schema $schema): void
    {
        $this->connection->insert(self::ROLE, ['id' => 1, 'role' => 'ROLE_ADMIN']);
        $this->connection->insert(self::ROLE, ['id' => 2, 'role' => 'ROLE_USER']);

        $this->connection->insert(self::TOPIC, ['id' => 1, 'name' => 'News']);
        $this->connection->insert(self::TOPIC, ['id' => 2, 'name' => 'Article']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) : void
    {
        $schema->dropTable(self::TOPIC);
        $schema->dropTable(self::USER_TO_ROLE);
        $schema->dropTable(self::ROLE);
        $schema->dropTable(self::USER);
        $schema->dropTable(self::BLOG);
    }
}
