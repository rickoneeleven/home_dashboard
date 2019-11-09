<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partytest extends CI_Controller {

    public function index() {
        $this->load->model('Sqlqu');
        $this->Sqlqu->getDarknetYesterday();
        echo "dave";
    }

}
