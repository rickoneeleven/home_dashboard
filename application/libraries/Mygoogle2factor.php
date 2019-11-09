<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MyGoogle2factor {

    public function __construct()
    {
        require_once APPPATH.'third_party/GoogleAuthenticator/PHPGangsta/GoogleAuthenticator.php';
    }

}

?>
