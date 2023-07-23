<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model 
{
    private $table = 'users';

    public function setup_initial_users()
    {
        $this->db->insert('users',
            array(
                'user_name' => 'daryl',
                'user_password' => password_hash('dizon', PASSWORD_BCRYPT),
                'user_type' => 1
        ));

        $this->db->insert('users', 
            array(
                'user_name' => 'admin',
                'user_password' => password_hash('Password1234+', PASSWORD_BCRYPT),
                'user_type' => 1
        ));
    }

    public function get($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->row();
    }

    public function get_by_user_name($user_name)
    {
        $query = $this->db->get_where($this->table, array('user_name' => $user_name));
        return $query->row();
    }

    public function get_total_records() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_filtered_data($start, $length, $search_value, $order_column, $order_dir) 
    {
        // exclude user_password for security purpose.
        $excludeFields = array('user_password');
        $tableFields = $this->db->list_fields($this->table);
        $includeFields = array_diff($tableFields, $excludeFields);
        $selectFields = implode(',', $includeFields);
        
        $this->db->select($selectFields);
        $this->db->from($this->table);

        if (!empty($search_value)) {
            $this->db->like('users.user_name', $search_value);
        }

        $this->db->order_by($order_column, $order_dir);

        $this->db->limit($length, $start);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert()
    {
        $this->load->library('encryption');

        $row = array(
            'user_name' => $this->input->post('user_name'),
            'user_type' => $this->input->post('user_type'),
        );

        $user_password = $this->input->post('user_password');
        if(!empty($user_password)) {
            $row['user_password'] = password_hash($user_password, PASSWORD_BCRYPT);
        }

        $this->db->insert($this->table, $row);
    }

    public function update()
    {
        $this->load->library('encryption');

        $row = array(
            'user_name' => $this->input->post('user_name'),
            'user_type' => $this->input->post('user_type'),
        );

        $user_password = $this->input->post('user_password');
        if(!empty($user_password)) {
            $row['user_password'] = password_hash($user_password, PASSWORD_BCRYPT);
        }

        $this->db->update($this->table, $row, array('id' => $this->input->post('id')));
    }

    public function delete()
    {
        $id = $this->input->post('id');

        if(is_array($id)) {
            $this->db->where_in('id', $id);
            $this->db->delete($this->table);
        } else {
            $this->db->delete($this->table, array('id' => $id));
        }
    }
}