<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpFoundation\Request;

/**
 * 20190204173538
 */
final class Version20190204173538 extends AbstractMigration
{
    const ACCESS_RIGHT = 'access_right';
    const ACCESS_RIGHT_TYPE = 'access_right_type';
    const ACCESS_RIGHT_ACTION = 'access_right_action';
    const DTO_ACCESS_RIGHT_MAP = 'dto_access_right';
    const ROLE_TO_ACCESS_RIGHT = 'role_to_access_right';
    const DTO_MAP_TO_ACCESS_RIGHT = 'dto_to_access_right';

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'Build base access right tables';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) : void
    {
        $accessRightType = $schema->createTable(self::ACCESS_RIGHT_TYPE);
        $accessRightType->addColumn('id', Type::INTEGER, ['unsigned' => true, 'notnull' => true, 'autoincrement' => true]);
        $accessRightType->addColumn('name', Type::STRING, ['notnull' => true, 'length' => 64]);
        $accessRightType->setPrimaryKey(['id']);

        $accessRightAction = $schema->createTable(self::ACCESS_RIGHT_ACTION);
        $accessRightAction->addColumn('id', Type::INTEGER, ['unsigned' => true, 'notnull' => true, 'autoincrement' => true]);
        $accessRightAction->addColumn('name', Type::STRING, ['notnull' => true, 'length' => 64]);
        $accessRightAction->setPrimaryKey(['id']);

        $accessRight = $schema->createTable(self::ACCESS_RIGHT);
        $accessRight->addColumn('id', Type::INTEGER, ['unsigned' => true, 'notnull' => true, 'autoincrement' => true]);
        $accessRight->addColumn('name', Type::STRING, ['notnull' => true, 'length' => 256]);
        $accessRight->addColumn('ar_type_id', Type::INTEGER, ['notnull' => true, 'unsigned' => true]);
        $accessRight->addColumn('ar_action_id', Type::INTEGER, ['notnull' => true, 'unsigned' => true]);
        $accessRight->addForeignKeyConstraint($accessRightType, ['ar_type_id'], ['id']);
        $accessRight->addForeignKeyConstraint($accessRightAction, ['ar_action_id'], ['id']);
        $accessRight->setPrimaryKey(['id']);

        $dtoAccessRight = $schema->createTable(self::DTO_ACCESS_RIGHT_MAP);
        $dtoAccessRight->addColumn('id', Type::INTEGER, ['unsigned' => true, 'notnull' => true, 'autoincrement' => true]);
        $dtoAccessRight->addColumn('name', Type::STRING, ['notnull' => true, 'length' => 256]);
        $dtoAccessRight->setPrimaryKey(['id']);

        $roleToAccessRight = $schema->createTable(self::ROLE_TO_ACCESS_RIGHT);
        $roleToAccessRight->addColumn('role_id', Type::INTEGER, ['notnull' => true, 'unsigned' => true]);
        $roleToAccessRight->addColumn('ar_id', Type::INTEGER, ['notnull' => true, 'unsigned' => true]);
        $roleToAccessRight->addForeignKeyConstraint('role', ['role_id'], ['id']);
        $roleToAccessRight->addForeignKeyConstraint(self::ACCESS_RIGHT, ['ar_id'], ['id']);
        $roleToAccessRight->setPrimaryKey(['role_id', 'ar_id']);

        $dtoToAccessRight = $schema->createTable(self::DTO_MAP_TO_ACCESS_RIGHT);
        $dtoToAccessRight->addColumn('dto_id', Type::INTEGER, ['notnull' => true, 'unsigned' => true]);
        $dtoToAccessRight->addColumn('ar_id', Type::INTEGER, ['notnull' => true, 'unsigned' => true]);
        $dtoToAccessRight->addForeignKeyConstraint(self::DTO_ACCESS_RIGHT_MAP, ['dto_id'], ['id']);
        $dtoToAccessRight->addForeignKeyConstraint(self::ACCESS_RIGHT, ['ar_id'], ['id']);
        $dtoToAccessRight->setPrimaryKey(['dto_id', 'ar_id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) : void
    {
        $schema->dropTable(self::DTO_MAP_TO_ACCESS_RIGHT);
        $schema->dropTable(self::ROLE_TO_ACCESS_RIGHT);
        $schema->dropTable(self::DTO_ACCESS_RIGHT_MAP);
        $schema->dropTable(self::ACCESS_RIGHT);
        $schema->dropTable(self::ACCESS_RIGHT_ACTION);
        $schema->dropTable(self::ACCESS_RIGHT_TYPE);
    }

    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function postUp(Schema $schema): void
    {
        $this->connection->insert(self::ACCESS_RIGHT_TYPE, ['id' => 2, 'name' => Request::METHOD_PUT]);
        $this->connection->insert(self::ACCESS_RIGHT_TYPE, ['id' => 3, 'name' => Request::METHOD_POST]);
        $this->connection->insert(self::ACCESS_RIGHT_TYPE, ['id' => 4, 'name' => Request::METHOD_DELETE]);

        $this->connection->insert(self::ACCESS_RIGHT_ACTION, ['id' => 1, 'name' => 'MINE']);
        $this->connection->insert(self::ACCESS_RIGHT_ACTION, ['id' => 2, 'name' => 'ALL']);

        $this->connection->insert(self::ACCESS_RIGHT, ['id' => 1, 'name' => 'AR_CREATE_MINE', 'ar_type_id' => 3, 'ar_action_id' => 1]);
        $this->connection->insert(self::ACCESS_RIGHT, ['id' => 2, 'name' => 'AR_CREATE_ANY', 'ar_type_id' => 3, 'ar_action_id' => 2]);
        $this->connection->insert(self::ACCESS_RIGHT, ['id' => 3, 'name' => 'AR_UPDATE_MINE', 'ar_type_id' => 2, 'ar_action_id' => 1]);
        $this->connection->insert(self::ACCESS_RIGHT, ['id' => 4, 'name' => 'AR_UPDATE_ANY', 'ar_type_id' => 2, 'ar_action_id' => 2]);
        $this->connection->insert(self::ACCESS_RIGHT, ['id' => 5, 'name' => 'AR_DELETE_MINE', 'ar_type_id' => 4, 'ar_action_id' => 1]);
        $this->connection->insert(self::ACCESS_RIGHT, ['id' => 6, 'name' => 'AR_DELETE_ANY', 'ar_type_id' => 4, 'ar_action_id' => 2]);

        $this->connection->insert(self::DTO_ACCESS_RIGHT_MAP, ['id' => 1, 'name' => 'App\DTO\BlogDTO']);
        $this->connection->insert(self::DTO_ACCESS_RIGHT_MAP, ['id' => 2, 'name' => 'App\DTO\UserDTO']);

        $this->connection->executeQuery("INSERT INTO `".self::DTO_MAP_TO_ACCESS_RIGHT."` (`dto_id`, `ar_id`) VALUES
        (1,1),
        (1,2),
        (1,3),
        (1,4),
        (1,5),
        (1,6),
        (2,1),
        (2,2),
        (2,3),
        (2,4),
        (2,5),
        (2,6)");

        $this->connection->executeQuery("INSERT INTO `".self::ROLE_TO_ACCESS_RIGHT."` (`role_id`, `ar_id`) VALUES
        (1,2),
        (1,4),
        (1,6),
        (2,1),
        (2,3),
        (2,5),
        (3,4),
        (3,6)");
    }
}
