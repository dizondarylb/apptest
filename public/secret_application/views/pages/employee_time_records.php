<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    $title = 'Employee Time Records';

    $this->load->view('templates/admin_master', array('title' => $title, 'content' => $this->load->view('contents/employee_time_records', '', true)));
?>