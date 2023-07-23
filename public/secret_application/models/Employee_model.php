<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model 
{
    private $table = 'employees';
    

    public function get($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->row();
    }

    public function get_by_code($code)
    {
        $query = $this->db->get_where($this->table, array('id_code' => $code));
        return $query->row();
    }

    public function get_by_qr_code($qr_code)
    {
        $query = $this->db->get_where($this->table, array('qr_code' => $qr_code));
        return $query->row();
    }
    public function get_total_records() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_filtered_data($start, $length, $search_value, $order_column, $order_dir) 
    {
        $this->db->select('employees.*, users.user_name');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = employees.created_by', 'left');

        if (!empty($search_value)) {
            $this->db->like('employees.qr_code', $search_value);
            $this->db->or_like('employees.first_name', $search_value);
            $this->db->or_like('employees.last_name', $search_value);
            $this->db->or_like('employees.created_by', $search_value);
        }

        $this->db->order_by($order_column, $order_dir);

        $this->db->limit($length, $start);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert()
    {
        $row = array(
            'qr_code' => $this->input->post('qr_code'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'created_by' => $this->session->userdata('id'),
        );

        $this->db->insert($this->table, $row);
    }

    public function update()
    {
        $row = array(
            'qr_code' => $this->input->post('qr_code'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
        );

        $this->db->update($this->table, $row, array('id' => intval($this->input->post('id'))));
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