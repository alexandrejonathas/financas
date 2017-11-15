<?php


use Phinx\Migration\AbstractMigration;

class AddUserToCategoryCosts extends AbstractMigration
{
    function up()
    {
        $this->table("category_costs")
            ->addColumn("user_id", "integer")
            ->addForeignKey("user_id","users", "id")
            ->save();
    }

    function down()
    {
        $this->table("category_costs")
            ->dropForeignKey("user_id")
            ->removeColumn("user_id")
            ->save();
    }
}
