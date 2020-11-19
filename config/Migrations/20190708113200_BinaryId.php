<?php

use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class BinaryId extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('token_tokens');

        $table
            ->changeColumn('id', 'string', ['limit' => 256, 'collation' => 'utf8_bin'])
            ->save();
    }

    public function down()
    {
        $table = $this->table('token_tokens');
    }
}
