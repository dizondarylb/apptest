<?php

function is_logged_in() {
    $CI = &get_instance();
    return $CI->session->userdata('id') !== NULL;
}