<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_time_records extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        if (!is_logged_in()) {
            redirect('auth/login');
        }
        $this->load->database();
        $this->load->model('employee_model');
        $this->load->model('employee_time_record_model');
    }

    public function index() 
    {
        return $this->load->view('pages/employee_time_records');
    }

    public function create()
    {
        if ($this->input->method(TRUE) === 'POST') {
            $response = array();
            $this->form_validation->set_rules('qr_code', 'Employee No.', 'required');

            if ($this->form_validation->run() === FALSE) {
                $errors = form_error('qr_code');
                $response = array('status' => false, 'message' => $errors);

            } else {
                if($this->session->userdata('id') === NULL) {
                    $response = array('status' => false, 'message' => 'Failed to scan qr due to user is not logged-in, Please try to re-login.');

                } else {
                    $qr_code = $this->input->post('qr_code');
                    $employee = $this->employee_model->get_by_qr_code($qr_code);

                    if($employee) {
                        $employee_time_record = $this->employee_time_record_model->get_pending_time_in($employee->id);

                        if ($employee_time_record) {
                            $response = array('status' => true, 'message' => 'Scanned QR Code is currently TIME IN, Please confirm if your going to TIME OUT.', 'data' => $employee_time_record);
                        } else {
                            $this->employee_time_record_model->insert($employee->id);
                            $response = array('status' => true, 'message' => 'Successfully Time In.');
                        }
                    } else {
                        $response = array('status' => false, 'message' => 'Invalid QR Code or Employee No., Please try again!');
                    }
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

                $filtered_data = $this->employee_time_record_model->get_filtered_data($start, $length, $search_value, $order_column, $order_dir);
                $total_records = $this->employee_time_record_model->get_total_records();
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
            
            if ($this->form_validation->run() === FALSE) {
                $errors = '';
                if(form_error('id')) $errors .= form_error('id');

                $response = array('status' => false, 'message' => $errors);
            } else {
                if($this->session->userdata('id') === NULL) {
                    $response = array('status' => false, 'message' => 'Failed to update employee time record due to user is not logged-in, Please try to re-login.');
                } else {
                    $this->employee_time_record_model->update();
                    $response = array('status' => true, 'message' => 'Successfully TIME OUT.');
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
                    $this->employee_time_record_model->delete();
                    $response = array('status' => true, 'message' => 'Successfully employee/s deleted.');
                }
            }
            
            echo json_encode($response);
        } else {
            show_404();
        }
    }
}