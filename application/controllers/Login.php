<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->helper('security');
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->isLoggedIn();
    }

    /**
     * This function used to check the user is logged in or not
     */
    function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');

        if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            $this->load->view('login');
        } else {
            redirect('/dashboard ');
        }
    }


    /**
     * This function used to logged in user
     */
    public function loginMe()
    {
        $this->load->library('form_validation');
        $this->load->helper('security');

        $this->form_validation->set_rules('user_name', 'Email', 'required|max_length[50]|xss_clean|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]|xss_clean|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $user_name = $this->input->post('user_name');
            $password = $this->input->post('password');

            $ecryptPassword = md5($password);

            $result = $this->login_model->loginMe($user_name, $ecryptPassword);

            if (count($result) > 0) {
                foreach ($result as $res) {
                    $sessionArray = array(
                        'userId' => $res->userId,
                        'role' => $res->roleId,
                        'roleText' => $res->role,
                        //'user_name' => $res->user_name,
			'user_name' =>$user_name,
                        'name' => $res->name,
                        'isLoggedIn' => TRUE
                    );

                    $this->session->set_userdata($sessionArray);

                    redirect('/dashboard');
                }
            } else {
                $this->session->set_flashdata('error', 'Username or password mismatch');

                redirect('/login');
            }
        }
    }
}
