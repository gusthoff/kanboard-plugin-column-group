<?php

namespace Kanboard\Plugin\ColumnGroup\Schema;

use PDO;

const VERSION = 3;

function version_3(PDO $pdo)
{
    $pdo->exec('PRAGMA foreign_keys=off');

    $pdo->exec('ALTER TABLE column_groups RENAME TO column_groups_old');

    $pdo->exec('CREATE TABLE IF NOT EXISTS column_groups  (
        "code" VARCHAR(30) PRIMARY KEY,
        "title" VARCHAR(255) NOT NULL UNIQUE,
        "description" TEXT,
        "project_id" INTEGER REFERENCES projects(id)
    )');

    $pdo->exec('INSERT INTO column_groups (code, title, description, project_id)
        SELECT code, title, description, project_id
        FROM column_groups_old');

    $pdo->exec('DROP TABLE column_groups_old');

    $pdo->exec('PRAGMA foreign_keys=on');
}

function version_2(PDO $pdo)
{
    $pdo->exec('ALTER TABLE column_groups ADD COLUMN project_id INTEGER
        REFERENCES projects(id)');
}

function version_1(PDO $pdo)
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS column_groups  (
        "code" VARCHAR(30) PRIMARY KEY,
        "title" VARCHAR(255),
        "description" TEXT
    )');

    $pdo->exec('ALTER TABLE columns ADD COLUMN column_group_code VARCHAR(30)
        REFERENCES column_groups(code)');
}
