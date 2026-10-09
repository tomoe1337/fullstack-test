<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'text' => ['type' => 'TEXT', 'null' => false],
            // date хранится строкой 'Y-m-d H:i' по ТЗ, такой формат корректно сортируется как строка
            'date' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => false],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('date');
        $this->forge->createTable('comments', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('comments', true);
    }
}
