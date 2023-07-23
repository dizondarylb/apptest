<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration extends CI_Controller
{
        public function __construct()
        {
                parent::__construct();
                $this->load->library('migration');
        }

        public function index()
        {
                show_404();
        }

        public function setup()
        {
                if ($this->migration->latest() === FALSE) {
                        show_error($this->migration->error_string());
                } else {
                        $this->load->database();
                        $this->load->model('user_model');
                        $this->user_model->setup_initial_users();
                        echo 'Migration successful!';
                }
        }

        public function rollback()
        {
                if ($this->migration->version(0) === FALSE) {
                        show_error($this->migration->error_string());
                } else {
                        echo 'Rollback successful!';
                }
        }

}