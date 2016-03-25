<?php

class m160323_180015_content_tools_page extends \humanized\clihelpers\components\Migration
{

    protected $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    public function safeUp()
    {
      //  $this->createLookupTable('content_type');

        $this->createTable('content_page', [
            'id' => 'pk',
            'type_id' => $this->integer()->notNull(),
            'title' => $this->string(100)->notNull(),
            'is_published' => $this->boolean(FALSE)->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
                ]
                , $this->tableOptions);

        $this->addForeignKey('fk_content_page_type', 'content_page', 'type_id', 'content_type', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('content_container', [
            'id' => 'pk',
            'page_id' => $this->integer()->notNull(),
            'language_id' => $this->string(2)->defaultValue('en'),
            'is_published' => $this->boolean(FALSE)->notNull(),
            'position' => $this->integer()->notNull()->defaultValue(0),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
                ], $this->tableOptions);

        $this->addForeignKey('fk_content_container_page', 'content_container', 'page_id', 'content_page', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {

        $this->dropForeignKey('fk_content_container_page', 'content_container');
        $this->dropForeignKey('fk_content_page_type', 'content_page');
        $this->dropTable('content_container');
        $this->dropTable('content_page');

        return false;
    }

    /*
      // Use safeUp/safeDown to run migration code within a transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
