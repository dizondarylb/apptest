<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller 
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
        $this->load->model('user_model');
    }

    private function validate_fields($action)
    {
        if($action == 'create') {
            $this->form_validation->set_rules('user_name', 'Username', 'required|is_unique[users.user_name]|regex_match[/^[^\s]+$/]', 
                array(
                    'is_unique'     => 'This %s already exists.',
                    'regex_match'     => '%s required no spaces.'
                ));
            $this->form_validation->set_rules('user_password', 'Password', 'required|min_length[10]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/]',
                array(
                    'regex_match'     => 'This %s should contain a lowercase, uppercase, number, and special character.'
                ));
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[user_password]');
            $this->form_validation->set_rules('user_type', 'User Type', 'required|regex_match[/^(1|2)$/]',
                array(
                    'regex_match'     => 'Invalid User Type, Please select Super Admin or Admin only.'
                ));

        } else if($action == 'update') {
            $id = $this->input->post('id');
            $user = $this->user_model->get($id);

            $this->form_validation->set_rules('id', 'ID', 'required');
            if(!empty($id) && $user->user_name == $this->input->post('user_name')) {
                $this->form_validation->set_rules('user_name', 'Username', 'required');
            } else {
                $this->form_validation->set_rules('user_name', 'Username', 'required|is_unique[users.user_name]|regex_match[/^[^\s]+$/]', 
                    array(
                        'is_unique'     => 'This %s already exists.',
                        'regex_match'     => '%s required no spaces.'
                    ));
            }
            if (!empty($this->input->post('user_password'))) {
                $this->form_validation->set_rules('user_password', 'Password', 'required|min_length[10]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/]',
                    array(
                        'regex_match'     => 'This %s should contain a lowercase, uppercase, number, and special character.'
                    ));
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[user_password]');
            }
            $this->form_validation->set_rules('user_type', 'User Type', 'required|regex_match[/^(1|2)$/]',
                array(
                    'regex_match'     => 'Invalid User Type, Please select Super Admin or Admin only.'
                ));
        }
    }

    public function index() 
    {
        return $this->load->view('pages/users');
    }

    public function create()
    {
        if ($this->input->method(TRUE) === 'POST') {
            $this->validate_fields('create');

            $response = array();
            if ($this->form_validation->run() === FALSE) {
                $errors = '';
                if(form_error('user_name')) $errors .= form_error('user_name');
                if(form_error('user_password')) $errors .= form_error('user_password');
                if(form_error('confirm_password')) $errors .= form_error('confirm_password');
                if(form_error('user_type')) $errors .= form_error('user_type');

                $response = array('status' => false, 'message' => $errors);
            } else {
                if($this->session->userdata('id') === NULL) {
                    $response = array('status' => false, 'message' => 'Failed to create user due to user is not logged-in, Please try to re-login.');
                } else {
                    $this->user_model->insert();
                    $response = array('status' => true, 'message' => 'Successfully user created.');
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
            $filtered_data = array();
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

                $filtered_data = $this->user_model->get_filtered_data($start, $length, $search_value, $order_column, $order_dir);
                $total_records = $this->user_model->get_total_records();
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
            $this->validate_fields('update');

            $response = array();
            if ($this->form_validation->run() === FALSE) {
                $errors = '';
                if(form_error('id')) $errors .= form_error('id');
                if(form_error('user_name')) $errors .= form_error('user_name');
                if(form_error('user_password')) $errors .= form_error('user_password');
                if(form_error('confirm_password')) $errors .= form_error('confirm_password');
                if(form_error('user_type')) $errors .= form_error('user_type');

                $response = array('status' => false, 'message' => $errors);
            } else {
                if($this->session->userdata('id') === NULL) {
                    $response = array('status' => false, 'message' => 'Failed to update user due to user is not logged-in, Please try to re-login.');
                } else {
                    $this->user_model->update();
                    $response = array('status' => true, 'message' => 'Successfully user updated.');
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

            $id = $this->input->post('id');
            $session_user_id = $this->session->userdata('id');
            
            if((!is_array($id) && $session_user_id == $id) || (is_array($id) && in_array($session_user_id, $id))) {
                $response = array('status' => false, 'message' => "You are not able to delete yourself.");
            } else {
                if ($this->form_validation->run() === FALSE && !is_array($id)) {
                    $errors = form_error('id');
                    $response = array('status' => false, 'message' => $errors);
                } else {
                    if($session_user_id === NULL) {
                        $response = array('status' => false, 'message' => 'Failed to delete user due to user is not logged-in, Please try to re-login.');
    
                    } else {
                        $this->user_model->delete();
                        $response = array('status' => true, 'message' => 'Successfully user/s deleted.');
                    }
                }
            }
            
            echo json_encode($response);
        } else {
            show_404();
        }
    }
}