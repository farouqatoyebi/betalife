<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '191',
                'unique' => true,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
            ],
            'date_created' => [
                'type' => 'DATETIME',
                'default' => '0000-00-00 00:00:00',
            ],
        ]);

        $this->forge->addKey('user_id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
