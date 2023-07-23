<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_time_record_model extends CI_Model 
{
    private $table = 'employee_time_records';
    

    public function get($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->row();
    }

    public function get_by_employee_id($employee_id)
    {
        $query = $this->db->get_where($this->table, array('employee_id' => $employee_id));
        return $query->row();
    }

    public function get_by_user_id($user_id)
    {
        $query = $this->db->get_where($this->table, array('user_id' => $user_id));
        return $query->row();
    }

    public function get_pending_time_in($id) 
    {
        $this->db->select('employee_time_records.*, employees.qr_code, employees.first_name, employees.last_name');
        $this->db->from($this->table);
        $this->db->join('employees', 'employees.id = employee_time_records.employee_id', 'left');

        $where = array('employee_time_records.employee_id' => $id, 'employee_time_records.time_in !=' => null, 'employee_time_records.time_out' => null);
        $this->db->where($where);

        $query = $this->db->get();
        return $query->row();
    }

    public function get_total_records() 
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_filtered_data($start, $length, $search_value, $order_column, $order_dir) 
    {
        $this->db->select('employee_time_records.*, users.user_name, employees.qr_code, employees.first_name, employees.last_name');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = employee_time_records.user_id', 'left');
        $this->db->join('employees', 'employees.id = employee_time_records.employee_id');

        if (!empty($search_value)) {
            $this->db->like('employees.qr_code', $search_value);
            $this->db->or_like('employees.first_name', $search_value);
            $this->db->or_like('employees.last_name', $search_value);
            $this->db->or_like('users.user_name', $search_value);
        }

        $this->db->order_by($order_column, $order_dir);

        $this->db->limit($length, $start);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert($employee_id)
    {
        $row = array(
            'employee_id' => $employee_id,
            'user_id' => $this->session->userdata('id'),
        );

        $this->db->insert($this->table, $row);
    }

    public function update()
    {
        $timezoneUTC = new DateTimeZone('UTC');
        $datetimeUTC = new DateTime('now', $timezoneUTC);
        $time_out = $datetimeUTC->format('Y-m-d H:i:s');

        $row = array(
            'time_out' => $time_out,
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