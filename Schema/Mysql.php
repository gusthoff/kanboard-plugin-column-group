<?php

namespace Kanboard\Plugin\ColumnGroup\Schema;

use PDO;

const VERSION = 1;

function version_1(PDO $pdo)
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS column_groups  (
        code VARCHAR(30) NOT NULL,
        title VARCHAR(255),
        description TEXT,
        PRIMARY KEY(code)
    ) ENGINE=InnoDB CHARSET=utf8');

    $pdo->exec('ALTER TABLE columns ADD COLUMN column_group_code VARCHAR(30)');
    
    $pdo->exec('ALTER TABLE columns ADD CONSTRAINT fk_column_group_code
        FOREIGN KEY(column_group_code) 
        REFERENCES column_groups(code)');
}
