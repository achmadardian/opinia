<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Posts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'post_type_id' => [
                'type' => 'int',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'jenis' => [
                'type'           => 'varchar',
                'constraint'     => 255,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ]
        ]);
        $this->forge->addKey('post_type_id', true);
        $this->forge->createTable('posts_types');

        $this->forge->addField([
            'user_id' => [
                'type' => 'int',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'fullname' => [
                'type'           => 'varchar',
                'constraint'     => 255,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => 255
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ]
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->createTable('users');

        $this->forge->addField([
            'post_id' => [
                'type' => 'int',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'title' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'post_type_id' => [
                'type' => 'int',
                'unsigned' => true,
                'auto_increment' => false
            ],
            'user_id' => [
                'type' => 'int',
                'unsigned' => true,
                'auto_increment' => false
            ],
        ]);
        $this->forge->addKey('post_id', true);
        $this->forge->addForeignKey('post_type_id', 'posts_types', 'post_type_id');
        $this->forge->addForeignKey('user_id', 'users', 'user_id');
        $this->forge->createTable('posts');
    }

    public function down()
    {
        //
    }
}
