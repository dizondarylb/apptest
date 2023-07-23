<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        if (!is_logged_in()) {
            redirect('auth/login');
        }
        if($this->session->userdata('user_type') != 1) {
            if ($this->input->method(TRUE) === 'POST') {
                echo json_encode('Unauthorized');
            } else {
                show_404();
            }
        }
        $this->load->database();
        $this->load->model('employee_model');
    }

    public function index() 
    {
        return $this->load->view('pages/employees');
    }

    public function create()
    {
        if ($this->input->method(TRUE) === 'POST') {
            $response = array();
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('qr_code', 'Employee No.', 'required|is_unique[employees.qr_code]',
                array(
                    'is_unique'     => 'This %s already exists.',
                ));

            if ($this->form_validation->run() === FALSE) {
                $errors = '';
                if(form_error('first_name')) $errors .= form_error('first_name');
                if(form_error('last_name')) $errors .= form_error('last_name');
                if(form_error('qr_code')) $errors .= form_error('qr_code');

                $response = array('status' => false, 'message' => $errors);

            } else {
                if($this->session->userdata('id') === NULL) {
                    $response = array('status' => false, 'message' => 'Failed to create employee due to user is not logged-in, Please try to re-login.');
                } else {
                    $this->employee_model->insert();
                    $response = array('status' => true, 'message' => 'Successfully employee created.');
                }
            }

            echo json_encode($response);
        } else {
            show_404();
        }
    }

    public function read()
    {
        if ($this->input->method(TRUE) === 'POST') {
            $filtered_data = [];
            $total_records = 0;
            $error = null;
            $draw = 1;
            
            try {
                $draw = $this->input->post('draw');
                $start = $this->input->post('start');
                $length = $this->input->post('length');
                $search_value = $this->input->post('search')['value'];
                $order_column = $this->input->post('order')[0]['column'];
                $order_dir = $this->input->post('order')[0]['dir'];

                $filtered_data = $this->employee_model->get_filtered_data($start, $length, $search_value, $order_column, $order_dir);
                $total_records = $this->employee_model->get_total_records();
            } catch(Exception $e) {
                $error = $e->getMessage();
            }

            $response = array(
                'draw' => $draw,
                'data' => $filtered_data,
                'recordsTotal' => $total_records,
                'recordsFiltered' => $total_records,
                'error' => $error,
            );

            echo json_encode($response);
        } else {
            show_404();
        }
    }

    public function update()
    {
        if ($this->input->method(TRUE) === 'POST') {
            $response = array();
            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('qr_code', 'Employee No.', 'required');
            
            if ($this->form_validation->run() === FALSE) {
                $errors = '';
                if(form_error('id')) $errors .= form_error('id');
                if(form_error('first_name')) $errors .= form_error('first_name');
                if(form_error('last_name')) $errors .= form_error('last_name');
                if(form_error('qr_code')) $errors .= form_error('qr_code');

                $response = array('status' => false, 'message' => $errors);
            } else {
                if($this->session->userdata('id') === NULL) {
                    $response = array('status' => false, 'message' => 'Failed to update employee due to user is not logged-in, Please try to re-login.');
                } else {
                    $this->employee_model->update();
                    $response = array('status' => true, 'message' => 'Successfully employee updated.');
                }
            }

            echo json_encode($response);
        } else {
            show_404();
        }
    }
    public function delete()
    {
        if ($this->input->method(TRUE) === 'POST') {
            $response = array();
            $this->form_validation->set_rules('id', 'ID', 'required');

            if (!is_array($this->input->post('id')) && $this->form_validation->run() === FALSE) {
                $errors = form_error('id');
                $response = array('status' => false, 'message' => $errors);

            } else {
                if($this->session->userdata('id') === NULL) {
                    $response = array('status' => false, 'message' => 'Failed to delete employee due to user is not logged-in, Please try to re-login.');

                } else {
                    $this->employee_model->delete();
                    $response = array('status' => true, 'message' => 'Successfully employee/s deleted.');
                }
            }
            
            echo json_encode($response);
        } else {
            show_404();
        }
    }
}