<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function login() 
    {
        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('user_name', 'Username', 'required');
            $this->form_validation->set_rules('user_password', 'Password', 'required');

            if ($this->form_validation->run() === FALSE) {
                $response = array('status' => false, 'message' => 'Incorrect username or password, Please try again!');
            } else {
                $this->load->model('user_model');
                $user_name = $this->input->post('user_name');
                $user_password = $this->input->post('user_password');

                $user = $this->user_model->get_by_user_name($user_name);

                if ($user && password_verify($user_password, $user->user_password)) {
                    $this->session->set_userdata(array('id' => $user->id, 'user_name' => $user->user_name, 'user_type' => $user->user_type));
                    $response = array('status' => true, 'message' => 'Login success! You are about to redirect to dashboard page.');
                } else {
                    $response = array('status' => false, 'message' => 'Incorrect username or password, Please try again!');
                }
            }

            echo json_encode($response);
        } else {
            if (is_logged_in()) {
                redirect('dashboard');
            }
            $this->load->view('pages/login');
        }
    }

    public function logout() 
    {
        $this->session->unset_userdata('id');
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}