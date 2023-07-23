<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SessionCheck
{
    public function checkSession($CI)
    {
        $CI->load->library('session');

        if (!$CI->session->userdata('id')) {
            redirect('auth/login');
        }
    }
}