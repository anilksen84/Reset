<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

/*header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');*/

/*header("Access-Control-Expose-Headers: Location");*/

/*header("Access-Control-Allow-Headers: http://13.126.8.176");*/
header("Access-Control-Allow-Headers: *");
//header('Access-Control-Allow-Credentials: false');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, HEAD, PATCH");
//header('Content-type: application/json');
//defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/REST_Controller.php';
require APPPATH . 'third_party/Format.php';
use Restserver\Libraries\REST_Controller;

//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Origin: http://13.126.8.176/");
//header("Access-Control-Allow-Headers: http://13.126.8.176/");
//header("Access-Control-Allow-Headers: *");
//header('Access-Control-Allow-Credentials: false');

header('Content-type: application/json');
class Login extends CI_Controller {





 public function __construct()
    {
        parent::__construct();

        //header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Origin: http://13.126.8.176/");
//header("Access-Control-Allow-Headers: http://13.126.8.176/");
//header("Access-Control-Allow-Headers: *");
//header('Access-Control-Allow-Credentials: false');
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        $this->load->database();
       // $this->load->library('REST_Controller','Format');
      //  $this->load->helper(['jwt', 'authorization']);    
      //  $this->load->model('user_model');
        $this->load->library('session');
        //$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
       // $this->output->set_header('Pragma: no-cache');

        	if( ! ini_get('date.timezone') )
			{
   				date_default_timezone_set('GMT');
			} 
        

    }

	
	public function index()
	{
		/*$tokenData = 'Reset2020!';
        
        // Create a token
        $token = AUTHORIZATION::generateToken($tokenData);
        // Set HTTP status code
        //$status = parent::HTTP_OK;
        // Prepare the response
        $response = ['status' => 'true', 'token' => $token];
        // REST_Controller provide this method to send responses
        echo json_encode($response);*/
        $status ='false';
            $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
            echo json_encode($response);
	}

	public function login()
	{

		header('Access-Control-Allow-Origin: *');

		$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		if(!empty($input_data)) {

		if(trim(empty($input_data['emp_id']))) {

			$data = array('IsValid'=>false, 'message'=> 'Employee if field is required');
			echo json_encode($data);
			exit();
		}

		/*if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $input_data['email'])){ 

			$data = array('IsValid'=>false, 'message'=> 'Email not vaild');
			echo json_encode($data);
			exit();

		}*/

		if(trim(empty($input_data['password']))) {

			$data = array('IsValid'=>false, 'message'=> 'Password field is required');
			echo json_encode($data);
			exit();
		}
		
		//$email = $input_data['email'];
		//$password = $input_data['password'];

		$org_id = $input_data['org_id'];
		$emp_id = $input_data['emp_id'];
		$password = md5($input_data['password']);
		$password1 = $input_data['password'];


		if($emp_id == 'admin@reset.com' && $password1 == '123456')
		{
			
            $token = AUTHORIZATION::generateToken(['email' => 'reset@reset.com']);
            
            
			$data = array('IsValid'=>true, 'role'=> 'admin', 'msg'=>'Login Successful', 'token' => $token);
			echo json_encode($data);
			exit();

		}elseif($emp_id == 'emp@reset.com' && $password1 == '123456')
		{
			
            $token = AUTHORIZATION::generateToken(['email' => 'reset@reset.com']);
            
            
			$data = array('IsValid'=>true, 'role'=> 'emp', 'msg'=>'Login Successful', 'token' => $token);
			echo json_encode($data);
			exit();

		}else{



		if(!is_numeric($emp_id)) {
			$data = array('IsValid'=>false, 'role'=> '','msg'=>'Incorrect credentials');
			echo json_encode($data);
			exit();
		}
		$this->db->select('*');
		$this->db->where('org_id',$org_id);
		$this->db->where('emp_id',$emp_id);
		$this->db->where('password',$password);

		$query = $this->db->get('cov19_employee_login');

		if($query->num_rows() > 0){

			$res = $query->row();

			$token = AUTHORIZATION::generateToken(['email' => 'function@reset.com']);

			if($res->type_name == 'func')
			{
				$this->db->select('id');
				$this->db->where('org_id',$org_id);
				$this->db->where('emp_id',$emp_id);
				$query2 = $this->db->get('cov19_function_value');
				$res2 = $query2->row();
				$fun_id = $res2->id;

				$data = array('IsValid'=>true, 'role'=> "$res->type_name", 'msg'=>"Login Successful", 'org_id'=> "$res->org_id", 'emp_id'=> "$res->emp_id", 'fun_id'=>$fun_id , 'token' => $token);
			echo json_encode($data);
			exit();

			}
                       
			$data = array('IsValid'=>true, 'role'=> "$res->type_name", 'msg'=>"Login Successful", 'org_id'=> "$res->org_id", 'emp_id'=> "$res->emp_id", 'token' => $token);
			echo json_encode($data);


		}else{


			$data = array('IsValid'=>false, 'role'=> '','msg'=>'Incorrect credentials');
			echo json_encode($data);


		}

	}


		}else{

		$data = array('IsValid'=>false, 'role'=> '', 'token' => '','msg'=> 'Incorrect credentials');
			echo json_encode($data);


	}

	}


	/**
     * Reset the password of the employee.
     *
     * @return Response
     */


public function reset_password()
{
	

		$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		if(!empty($input_data)) {

		if(trim(empty($input_data['org_id']))) {

			$data = array('IsValid'=>false, 'message'=> 'Organization id is required');
			echo json_encode($data);
			exit();
		}

		if(trim(empty($input_data['emp_id']))) {

			$data = array('IsValid'=>false, 'message'=> 'Employee id field is required');
			echo json_encode($data);
			exit();
		}

		if(trim(empty($input_data['password']))) {

			$data = array('IsValid'=>false, 'message'=> 'Password field is required');
			echo json_encode($data);
			exit();
		}

		if(empty(trim($input_data['cpassword']))) {

			$data = array('IsValid'=>false, 'message'=> 'Re-enter field is required');
			echo json_encode($data);
			exit();
		}

		if(trim($input_data['password']) != trim($input_data['cpassword'])) {

			$data = array('IsValid'=>false, 'message'=> 'Password and re-enter password not matched');
			echo json_encode($data);
			exit();
		}

		

		$org_id = $input_data['org_id'];
		$emp_id = $input_data['emp_id'];
		$password = md5($input_data['password']);
		


		$data = array(
			'password' => strip_tags($password),
			);

		$this->db->where('org_id',$org_id);
		$this->db->where('emp_id',$emp_id);
		$update = $this->db->update('cov19_employee_login',$data);	


		if($update) {
			$data1['IsValid'] = true;
        	$data1['org_id'] = $org_id;
        	$data1['emp_id'] = $emp_id;
        	$data1['msg'] = 'Password Updated';
        	echo json_encode($data1);
		}else{

			$data1 = array('IsValid'=>false, 'msg'=> 'Something went wrong');
			echo json_encode($data1);

		}

	

		}else{

		$data1 = array('IsValid'=>false, 'msg'=> 'Something went wrong');
			echo json_encode($data1);

		}

}





	



}
