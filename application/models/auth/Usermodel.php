<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserModel extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    function login($email,$password, $twofac)
    {
        $this->db->where("email",$email);
        $this->db->where("password",$password);

        $query=$this->db->get("user");
        if($query->num_rows()>0) {
            foreach($query->result() as $rows) {
                if($this->checkTwoFactor($rows->id, $twofac)) {
                    //add all data to session
                    $newdata = array(
                        'user_id'  => $rows->id,
                        'user_name'  => $rows->username,
                        'user_email'    => $rows->email,
                        'lastlogin' => $rows->lastlogin,
                        'hideOffline' => $rows->hideOffline,
                        'logged_in'  => TRUE,
                    );
                } else {
                    return false;
                }
                $this->session->set_userdata($newdata);
                $data2 = array( //update table status
                    'lastlogin' => date('Y-m-d H:i:s')
                );//if the staus is null, add it as it must be a new IP and we want a status. we run the if as we don't want to be updating this table every single time the the same status when things are okay
                $this->db->where('id', $newdata['user_id']);
                $this->db->update('user', $data2);
                return true;
            }
        }
        return false;
    }

    private function checkTwoFactor($user_id, $code_from_phone) {
        $this->load->library('mygoogle2factor');
        $this->load->library('encrypt');

        $ga = new PHPGangsta_GoogleAuthenticator();

        $this->db->where('id', $user_id);
        $this->db->select('google_2fac_code');
        $secretRowObject = $this->db->get('user');
        $secret = $secretRowObject->row('google_2fac_code');
        $secret_decoded = $this->encrypt->decode($secret);
        $code_from_google = $ga->getCode($secret_decoded);
        if($code_from_google == $code_from_phone) {
            return true;
        } else {
            return false;
        }
    }

}
?>
