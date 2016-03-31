<?php

class m160323_180015_content_tools_page extends \humanized\clihelpers\components\Migration
{

    protected $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    public function safeUp()
    {
        $this->createLookupTable('content_type');
        $this->createTable('content_page', [
            'id' => 'pk',
            'uid' => $this->string(30)->notNull(),
            'parent_id' => $this->integer(),
            'type_id' => $this->integer()->notNull(),
            'title' => $this->string(100)->notNull(),
            'is_published' => $this->boolean()->defaultValue(FALSE)->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
                ]
                , $this->tableOptions);

        $this->addForeignKey('fk_content_page_type', 'content_page', 'type_id', 'content_type', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_content_page_parent', 'content_page', 'parent_id', 'content_page', 'id', 'CASCADE', 'CASCADE');
        $this->createLookupTable('container_type');
        $this->createTable('container', [
            'id' => 'pk',
            'uid' => $this->string(30)->notNull(),
            'page_id' => $this->integer()->notNull(),
            'type_id' => $this->integer(),
            'is_published' => $this->boolean()->defaultValue(FALSE)->notNull(),
            'position' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
                ], $this->tableOptions);

        $this->addForeignKey('fk_container_page', 'container', 'page_id', 'content_page', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_container_type', 'container', 'type_id', 'container_type', 'id', 'CASCADE', 'CASCADE');

        //Static Content Container (For Charts & Maps)
        $this->createTable('static_container', [
            'id' => $this->integer(),
            'name' => $this->string()->notNull()->unique(),
        ]);

        $this->addForeignKey('fk_static_container', 'static_container', 'id', 'container', 'id', 'CASCADE', 'CASCADE'); //CASCADE IMPORTANT
        $this->addPrimaryKey('pk_static_container', 'static_container', 'id');

        //Simple HTML Text Container
        $this->createTable('content_container', [
            'id' => $this->integer(),
            'language_id' => $this->string(2)->defaultValue('EN'),
            'data' => $this->text()], $this->tableOptions);
        $this->addPrimaryKey('pk_content_container', 'content_container', 'id');
        $this->addForeignKey('fk_content_container', 'content_container', 'id', 'container', 'id', 'CASCADE', 'CASCADE'); //CASCADE IMPORTANT
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
