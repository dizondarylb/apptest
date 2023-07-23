<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_initial_tables extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'user_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
                'unique' => TRUE,
            ),
            'user_password' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ),
            'user_type' => array(
                'type' => 'INT',
                'constraint' => 5,
                'null' => FALSE,
            ),
            'datetime_added datetime default current_timestamp',
            'datetime_modified datetime default current_timestamp on update current_timestamp', 
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('users');

        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'qr_code' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
                'unique' => TRUE,
            ),
            'first_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ),
            'last_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ),
            'created_by' => array(
                'type' => 'BIGINT',
                'null' => FALSE,
            ),
            'datetime_added datetime default current_timestamp',
            'datetime_updated datetime default current_timestamp on update current_timestamp', 
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('employees');

        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'employee_id' => array(
                'type' => 'BIGINT',
                'null' => FALSE,
            ),
            'user_id' => array(
                'type' => 'BIGINT',
                'null' => FALSE,
            ),
            'date_added datetime default current_timestamp',
            'time_in datetime default current_timestamp',
            'time_out' => array(
                'type' => 'datetime',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('employee_time_records');
    }

    public function down()
    {
        $this->dbforge->drop_table('employee_time_records');
        $this->dbforge->drop_table('employees');
        $this->dbforge->drop_table('users');
    }
}