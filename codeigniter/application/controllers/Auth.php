<?php  
    
    class Auth extends CI_Controller{


        public function index(){

            $this->load->model('Auth_model');
            $this->load->library('encryption');
            if ($this->Auth_model->authorized() == true){
                redirect(base_url().'index.php/Auth/list');
            }
            $this->form_validation->set_rules('email','Email','required|valid_email');
            $this->form_validation->set_rules('password','Password','required');

            if ($this->form_validation->run()== true){
                // No Error
                $email=$this->input->post('email');
                $user = $this->Auth_model->checkUser($email);
                if(!empty($user)){
                    $password=$this->input->post('password');
                    $text=openssl_decrypt($user['password'],"AES-128-ECB","Wh4t3ver__$");
                    if($password==$text){
                        $formArray['id']=$user['id'];
                        $formArray['name']=$user['name'];
                        $formArray['email']=$user['email'];
                        $formArray['mobile']=$user['mobile'];
                        $this->input->set_cookie('email',$user['email'],'36000');
                        $this->input->set_cookie('pass',openssl_decrypt($user['password'],"AES-128-ECB","Wh4t3ver__$"),'36000');
                        $this->session->set_userdata('user',$formArray);
                        $this->session->set_flashdata('success','You are login Successfully!');
                        redirect(base_url().'index.php/Auth/list');
                    }else{
                        $this->session->set_flashdata('Failed','Eighter email or possword is incorrect, please try again!');
                        redirect(base_url().'index.php/Auth/index');
                    }
                }else{
                $this->session->set_flashdata('Failed','Eighter email or possword is incorrect, please try again!');
                redirect(base_url().'index.php/Auth/index');
                }
            }else{
            $this->load->view('index');
            }              
        }
/////////////////////////////////////////////////////////////////////////////
        // this function will show the register page

        public function register(){

            $this->load->model('Auth_model');
            $this->load->library('encryption');
            if ($this->Auth_model->authorized() == true){
                redirect(base_url().'index.php/Auth/list');
            }
            $this->form_validation->set_message('is_unique','Email address already exist, please try Another.');
            // $this->form_validation->set_message('is_unique','Mobile_No already exist, please try Another.');
            $this->form_validation->set_rules('name','Name','required');
            $this->form_validation->set_rules('email','Email','required|valid_email|is_unique[Ashish_User1.email]');
            $this->form_validation->set_rules('mobile','Mobile','required|max_length[10]|min_length[10]|regex_match[/^[0-9]{10}$/]');
            $this->form_validation->set_rules('password','Password','required');

            if ($this->form_validation->run()== false){
                // we will load our view
                $this->load->view('register');
            }else{
                //saverecord in to database 
                $formArray= array();
                $formArray['name']=$this->input->post('name');
                $formArray['email']=$this->input->post('email');
                $formArray['mobile']=$this->input->post('mobile');
                $formArray['password']=openssl_encrypt($this->input->post('password'),"AES-128-ECB","Wh4t3ver__$");
                $formArray['created_at']=date('Y-m-d');
                $this->Auth_model->create($formArray);
                $this->session->set_flashdata('success','You are Resistered Successfully!');
                redirect(base_url().'index.php/Auth/index');
            }
        }///////////////////////////////////////////////////////////////

        public function create(){

            $this->load->model('Auth_model');
            $this->load->library('encryption');
            if ($this->Auth_model->authorized() == true){
                // redirect(base_url().'index.php/Auth/list');
            // }
            $this->form_validation->set_message('is_unique','Email address already exist, please try Another.');
            // $this->form_validation->set_message('is_unique','Mobile_No already exist, please try Another.');
            $this->form_validation->set_rules('name','Name','required');
            $this->form_validation->set_rules('email','Email','required|valid_email|is_unique[Ashish_User1.email]');
            $this->form_validation->set_rules('mobile','Mobile','required|max_length[10]|min_length[10]|regex_match[/^[0-9]{10}$/]');
            $this->form_validation->set_rules('password','Password','required');

            if ($this->form_validation->run()== false){
                // we will load our view
                $this->load->view('create');
            }else{
                //saverecord in to database 
                $formArray= array();
                $formArray['name']=$this->input->post('name');
                $formArray['email']=$this->input->post('email');
                $formArray['mobile']=$this->input->post('mobile');
                $formArray['password']=openssl_encrypt($this->input->post('password'),"AES-128-ECB","Wh4t3ver__$");
                $formArray['created_at']=date('Y-m-d');
                $this->Auth_model->create($formArray);
                $this->session->set_flashdata('success','New Record Created Successfully!');
                redirect(base_url().'index.php/Auth/list');
                }
            }else{
                redirect(base_url().'index.php/Auth/index');
            }
        } 

//////////////////////////////////////////////////////////////////////////////
        function list()
        {
            $this->load->model('Auth_model');
            if ($this->Auth_model->authorized() == false){
                $this->session->set_flashdata('Failed','You are not Authorized  to access this section');
                redirect(base_url().'index.php/Auth/index');
            }
            $this->load->model('Auth_model');
            $result['data']=$this->Auth_model->all();//go to all() model for data display 
            $this->load->view('list',$result);//go to view and run  list.php
            $user=$this->session->userdata('user');
        }
/////////////////////////////////////////////////////////////////////////////////////////////////

        function edit($userId){

            $this->load->model('Auth_model');
            $this->load->library('encryption');
            if ($this->Auth_model->authorized() == true){
            
            $user = $this->Auth_model->getUser($userId);
            $data=array();
            $data['user'] = $user;
            $this->form_validation->set_rules('name','Name','required');
            $this->form_validation->set_rules('email','Email','required|valid_email');
            $this->form_validation->set_rules('mobile','Mobile','required|max_length[10]|min_length[10]|regex_match[/^[0-9]{10}$/]');
            $this->form_validation->set_rules('password','Password','required');
    
            if ($this->form_validation->run()== false){
                $this->load->view('edit',$data);
            }else{
                //update record in to database
                $formArray= array();
                $formArray['name']=$this->input->post('name');
                $formArray['email']=$this->input->post('email');
                $formArray['mobile']=$this->input->post('mobile');
                $formArray['password']=openssl_encrypt($this->input->post('password'),"AES-128-ECB","Wh4t3ver__$");
                $formArray['updated_at']=date('Y-m-d');

                $this->Auth_model->updateUser($userId,$formArray);
                $this->session->set_flashdata('success','Record updated Successfully!');
                redirect(base_url().'index.php/Auth/list');
            }
            }else{
                redirect(base_url().'index.php/Auth/index');
            }
        }
        ///////////////////////////////////////////////////////////////

        function delete($userId){
            $this->load->model('Auth_model');
            if ($this->Auth_model->authorized() == true){

            $user = $this->Auth_model->getUser($userId);
            if(empty($user)){
                $this->session->set_flashdata('failure','Record Not Found in Database!');
                redirect(base_url().'index.php/Auth/list');
            }else{
                $this->Auth_model->deleteUser($userId);
                $this->session->set_flashdata('success','Record deleted Successfully!');
                redirect(base_url().'index.php/Auth/list');
                }
            }else{
                redirect(base_url().'index.php/Auth/index');
            }
        }
////////////////////////////////////////////////////////////////////////////////

        function logout(){

            // $this->input->set_cookie('email','','0');
            // $this->input->set_cookie('pass','','0');
            $this->session->unset_userdata('user');
            redirect(base_url().'index.php/Auth/index');
        }
    }
?>
