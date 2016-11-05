<?php

namespace Kanboard\Plugin\ColumnGroup\Schema;

use PDO;

const VERSION = 2;

function version_2(PDO $pdo)
{
    $pdo->exec('ALTER TABLE column_groups ADD COLUMN project_id INTEGER');

    $pdo->exec('ALTER TABLE column_groups ADD FOREIGN KEY(project_id)
        REFERENCES projects(id)');
}

function version_1(PDO $pdo)
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS column_groups  (
        "code" VARCHAR(30) PRIMARY KEY,
        "title" VARCHAR(255),
        "description" TEXT
    )');

    $pdo->exec('ALTER TABLE columns ADD COLUMN column_group_code VARCHAR(30)');
    
    $pdo->exec('ALTER TABLE columns ADD FOREIGN KEY(column_group_code) 
        REFERENCES column_groups(code)');
}
