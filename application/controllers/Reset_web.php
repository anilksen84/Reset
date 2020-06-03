<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/REST_Controller.php';
require APPPATH . 'third_party/Format.php';
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header('Content-type: application/json');

class Reset_web extends CI_Controller {


 public function __construct()
    {
        parent::__construct();

        $this->load->database();
       // $this->load->library('REST_Controller','Format');
        $this->load->helper(['jwt', 'authorization']);    
      //  $this->load->model('user_model');
     $this->load->library('session');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        	if( ! ini_get('date.timezone') )
			{
   				date_default_timezone_set('GMT');
			} 
        

    }


    public function verify_token()
    {
    	$headers = $this->input->request_headers();

		$token = $headers['Authorization']; 

		if(!$token) {
			 $response = ['IsValid'=>false, 'msg' => 'Unauthorized Access! '];
        echo json_encode($response);
        exit();
		}

		$token = explode('Bearer',$token);

		$token = trim($token[1]);
/*echo $_SESSION['token_val'].'<br/>';
         echo $token;die;*/

         $data = AUTHORIZATION::validateToken($token);


        if ($data === false) {

        $response = ['IsValid'=>false, 'msg' => 'Unauthorized Access! '];
        echo json_encode($response);
        exit();

        }

        return true;

        /* print_r($data); die;
        
        if ($token === $_SESSION['token_val']) {
            return true;
        } else {
      
        $response = ['IsValid'=>false, 'msg' => 'Unauthorized Access! '];
        echo json_encode($response);
        exit();
    }*/
    }

	
	public function index()
	{
		$tokenData = 'Reset2020!';
        
        // Create a token
        $token = AUTHORIZATION::generateToken($tokenData);
        // Set HTTP status code
        //$status = parent::HTTP_OK;
        // Prepare the response
        $response = ['IsValid'=>false, 'token' => $token];
        // REST_Controller provide this method to send responses
        echo json_encode($response);
	}

	


	public function create_org()
	{


		$this->verify_token();


		$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		if(!empty($input_data)) {

		if(trim(empty($input_data['org_name']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Organization field is required');
			echo json_encode($data);
			exit();
		}

		if(trim(empty($input_data['org_email']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Email field is required');
			echo json_encode($data);
			exit();
		}

		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $input_data['org_email'])){ 

			$data = array('IsValid'=>false, 'msg'=> 'Email not vaild');
			echo json_encode($data);
			exit();

		}

		/*if(trim(empty($input_data['org_password']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Password field is required');
			echo json_encode($data);
			exit();
		}*/

		if(empty(trim($input_data['department']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Department field is required');
			echo json_encode($data);
			exit();
		}

	/*	if(empty(trim($input_data['emp_form']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Employee form field is required');
			echo json_encode($data);
			exit();
		}

		if(empty(trim($input_data['self_dec']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Self declaration field is required');
			echo json_encode($data);
			exit();
		}*/
		if(empty(trim($input_data['emp_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Employee id field is required');
			echo json_encode($data);
			exit();
		}
	/*	if(empty(trim($input_data['self_dec']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Employee name field is required');
			echo json_encode($data);
			exit();
		}*/

		if(empty(trim($input_data['total_emp']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Total employee field is required');
			echo json_encode($data);
			exit();
		}


		$this->db->where('email',$input_data['org_email']);
		$res1 = $this->db->get('cov19_emp_org');
		//$res1 = $res->query($res);
		if($res1->num_rows() > 0){

			$data = array('IsValid'=>false, 'msg'=> 'Email already exits');
			echo json_encode($data);
			exit();

		}



		$org_name = $input_data['org_name'];
		$org_id = rand(1000,9999);
		$org_email = $input_data['org_email'];
		$org_password = '';//$input_data['org_password'];
		$department = $input_data['department'];
		$emp_form = '';//$input_data['emp_form'];
		$self_dec = '';//$input_data['self_dec'];
		$emp_id = $input_data['emp_id'];
		$emp_name = $input_data['emp_name'];
		$total_emp = $input_data['total_emp'];
		$table_name = 'cov19_'.$org_id.'_org';
		$create_at = date('Y-m-d H:i:s');


		$data = array(
			'org_name' => strip_tags($org_name),
			'org_id' => strip_tags($org_id),
			'emp_id' => strip_tags($emp_id),
			'emp_name' => strip_tags($emp_name),
			'email' => strip_tags($org_email),
			//'password' => md5($org_password),
			'department' => strip_tags($department),
			//'emp_form' => strip_tags($emp_form),
			//'self_dec' => strip_tags($self_dec),
			'table_name' => $table_name,
			'total_emp' => $total_emp,
			'created_at' => $create_at,

		);


		/*$data = array(
			'org_name' => strip_tags($org_name),
			'org_id' => strip_tags($org_id),
			'org_email' => strip_tags($org_email),
			'org_password' => strip_tags($org_password),
			'org_address' => strip_tags($org_address),
			'org_pin' => strip_tags($org_pin),
			'total_employee' => '50';//strip_tags($total_employee),
			'table_name' => $table_name,
			'create_at' => $create_at,

		);*/

		$insert = $this->db->insert('cov19_emp_org',$data);

		if($insert) {

			$data2 = array(
			'org_id' => strip_tags($org_id),
			'emp_id' => strip_tags($emp_id),
			'email' => strip_tags($org_email),
			'password' =>'',
			'type' => '2',
			'type_name' => 'org',
			'created_at' => $create_at,

		);

			$insert2 = $this->db->insert('cov19_employee_login',$data2);

			$sql = "CREATE TABLE $table_name(
						   ID serial PRIMARY KEY,
						   emp_id CHAR(100),
						   org_id INT NOT NULL,	
						   dept           CHAR(255),
						   work_exp       CHAR(255),
						   role           CHAR(255),
						   milestone      CHAR(255),
						   peak_load_month      CHAR(255),
						   emp_activity_11      CHAR(255),
						   emp_activity_21      CHAR(255),
						   sub_function         CHAR(100),
						   sub_function_id      CHAR(100),
						   created_at      CHAR(255)
						   
						);";

						$this->db->query($sql);	

		}

		//echo file_get_contents("https://codersspace.com/irma_new/login/send_email?email=$org_email&password=$org_password&org_id=$org_id");

		
        
        $data1['IsValid'] = true;
        $data1['data'] = $data;
        $data1['msg'] = "Organization added. organization id is $org_id";

		//$data = array('IsValid'=>true, 'role'=> 'Org');
			echo json_encode($data1);

		}else{

		$data = array('IsValid'=>false, 'msg'=> 'Something went wrong');
			echo json_encode($data);

		}

	}


	public function create_workplace()
	{

		$this->verify_token();


		$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		if(!empty($input_data)) {

			/*print_r($input_data['subfunction']);

			die;*/

		if(trim(empty($input_data['org_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Organization id is required');
			echo json_encode($data);
			exit();
		}

		if(trim(empty($input_data['emp_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Employee id field is required');
			echo json_encode($data);
			exit();
		}


		if(trim(empty($input_data['workplace_name']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Workplace name field is required');
			echo json_encode($data);
			exit();
		}

		/*if(trim(empty($input_data['type_of_work']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Type of work field is required');
			echo json_encode($data);
			exit();
		}*/

		if(trim(empty($input_data['locality']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Locality field is required');
			echo json_encode($data);
			exit();
		}

		if(empty(trim($input_data['pincode']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Pincode field is required');
			echo json_encode($data);
			exit();
		}

		if(empty(trim($input_data['state']))) {

			$data = array('IsValid'=>false, 'msg'=> 'State field is required');
			echo json_encode($data);
			exit();
		}

		if(empty(trim($input_data['city']))) {

			$data = array('IsValid'=>false, 'msg'=> 'City field is required');
			echo json_encode($data);
			exit();
		}

		if(empty(trim($input_data['spatial_details']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Spatial details field is required');
			echo json_encode($data);
			exit();
		}

		if(empty(trim($input_data['total_capcity']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Total capcity field is required');
			echo json_encode($data);
			exit();
		}

		if(empty($input_data['subfunction'])) {

			$data = array('IsValid'=>false, 'msg'=> 'Department details field is required');
			echo json_encode($data);
			exit();
		}

		if(empty(trim($input_data['org_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Org Id is required');
			echo json_encode($data);
			exit();
		}

		$name = $input_data['workplace_name'];
		$type_of_work = $input_data['type_of_work'];
		$locality = $input_data['locality'];
		$pincode = $input_data['pincode'];
		$state = $input_data['state'];
		$city = $input_data['city'];
		$spatial_details = $input_data['spatial_details'];
		$total_capcity = $input_data['total_capcity'];
		$dep_details = json_encode($input_data['subfunction']);
		$org_id = $input_data['org_id'];
		$emp_id = $input_data['emp_id'];
		
		
		$create_at = date('Y-m-d H:i:s');


		$data = array(
			'name' => strip_tags($name),
			'type_of_work' => strip_tags($type_of_work),
			'locality' => strip_tags($locality),
			'city' => strip_tags($city),
			'pincode' => strip_tags($pincode),
			'state' => strip_tags($state),
			'spatial_details' => strip_tags($spatial_details),
			'total_capcity' => strip_tags($total_capcity),
			'dep_details' => $dep_details,
			'emp_id' => $emp_id,
			'org_id' => $org_id,
			'created_at' => $create_at,

		);

		//print_r($input_data['subfunction']);

		
		//print_r($data); die;

		$this->db->insert('cov19_workplace',$data);

		$insert_id = $this->db->insert_id();

		$depar_value = $input_data['subfunction'];

		$totald = count($input_data['subfunction']);

		for($k=0; $k<$totald; $k++) {

        $dd_de = $depar_value[$k]['subfunction'];
        $dd_empid = $depar_value[$k]['emp_id'];
        $dd_email = $depar_value[$k]['emailid'];

        $data23 = array('org_id' => $org_id,'emp_id'=> $dd_empid,'email'=> $dd_email,'type'=>'3','type_name'=>'func','created_at'=>$create_at);
        $data24 = array('org_id' => $org_id,'emp_id'=> $dd_empid,'name'=> $dd_de,'type'=>'function','created_at'=>$create_at,'fun_id'=>'0','wp_id'=>$insert_id);
        $this->db->insert('cov19_employee_login',$data23);
        $this->db->insert('cov19_function_value',$data24);

		}


		$data1['IsValid'] = true;

		$data1['data'] = $data;

		$data1['msg'] = 'Workplace Added';


		//$data = array('IsValid'=>true, 'Message'=>"Workplace Added");
			echo json_encode($data1);

		}else{

			$data1 = array('IsValid'=>false, 'msg'=>'Something went wrong');
			echo json_encode($data1);

		}

	}




	public function get_address()
	{
		

		$this->verify_token();


		$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		if(!empty($input_data)) {

			/*print_r($input_data['subfunction']);

			die;*/

		if(trim(empty($input_data['pincode']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Pincode id is required');
			echo json_encode($data);
			exit();
		}

		if(!is_numeric($input_data['pincode'])) {
			$data = array('IsValid'=>false, 'message'=> 'Pincode not vaild');
			echo json_encode($data);
			exit();
		}

		$pincode = $input_data['pincode'];

		$this->db->select('*');
		$this->db->where('pincode',$pincode);
		$query = $this->db->get('cov19_pincode');
		if($query->num_rows() > 0){
			$res = $query->row();
		
        $token = $_SESSION['token_val'];
		$data = array('status'=>true, 'id'=> "$res->id", 'pincode'=>"$res->pincode", 'office_type'=>"$res->office_type", 'division'=>"$res->division", 'region'=>"$res->region", 'circle'=>"$res->circle", 'taluk'=>"$res->taluk", 'district'=>"$res->district", 'state'=>"$res->state", 'token' => $token);
		echo json_encode($data);
		}else{
			$data = array('status'=>false, 'msg'=>"Something went wrong");
			echo json_encode($data);
		}

	}else{
		$data = array('status'=>false, 'msg'=>"Something went wrong");
			echo json_encode($data);
	}
	}


	public function get_workplace_details()
	{
			$this->verify_token();

		$org_id =  $_GET['org_id'];
		//$emp_id =  $_GET['emp_id'];
	/*	$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		print_r($input_data); die;*/

		if(!empty($org_id)) {

			/*print_r($input_data['subfunction']);

			die;*/

		if(trim(empty($org_id))) {

			$data = array('IsValid'=>false, 'msg'=> 'Organization id is required');
			echo json_encode($data);
			exit();
		}

		/*if(trim(empty($emp_id))) {

			$data = array('IsValid'=>false, 'msg'=> 'Employee id is required');
			echo json_encode($data);
			exit();
		}
*/
		//$org_id  =  $input_data['org_id'];

		$this->db->select('id,name');
		$this->db->where('org_id',$org_id);
		//$this->db->where('emp_id',$emp_id);

		$query = $this->db->get('cov19_workplace');


		if($query->num_rows() > 0) {

		$res = $query->result();


			//print_r(json_decode(json_encode($data2))); die;

			$data1['IsValid'] = true;

		    $data1['data'] = json_decode(json_encode($res));

		    $data1['msg'] = 'Workplace List';
		
       
		//$data = array('status'=>true, 'details'=>"$res");
		echo json_encode($data1,true);
	}else{

		$data = array('status'=>true, 'msg'=>"No data found");
		echo json_encode($data);
	}



		

	}else{
		$data = array('status'=>false, 'msg'=>"Something went wrong");
			echo json_encode($data);
	}
	}






	public function get_workplaceName()
	{
			$this->verify_token();

		$org_id =  $_GET['org_id'];
	/*	$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		print_r($input_data); die;*/

		if(!empty($org_id)) {

			/*print_r($input_data['subfunction']);

			die;*/

		if(trim(empty($org_id))) {

			$data = array('IsValid'=>false, 'msg'=> 'Organization id is required');
			echo json_encode($data);
			exit();
		}

		//$org_id  =  $input_data['org_id'];

		$this->db->select('id,name');
		$this->db->where('org_id',$org_id);
		
		$query = $this->db->get('cov19_workplace');
	//	echo $this->db->last_query();
		if($query->num_rows() > 0){
			$res = $query->result();

	
			$data1['IsValid'] = true;

		    $data1['data'] = json_decode(json_encode($res));

		    $data1['msg'] = 'Workplace List';
		
       
		//$data = array('status'=>true, 'details'=>"$res");
		echo json_encode($data1,true);
		}else{
			$data = array('status'=>false, 'msg'=>"No data found");
			echo json_encode($data,JSON_UNESCAPED_SLASHES);
		}

	}else{
		$data = array('status'=>false, 'msg'=>"Something went wrong");
			echo json_encode($data);
	}
	}


	public function get_functionNamefromWorkplace()
	{
			$this->verify_token();

		$org_id =  $_GET['org_id'];
		$wp_id =  $_GET['wp_id'];
	/*	$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		print_r($input_data); die;*/

		if(!empty($org_id)) {

			/*print_r($input_data['subfunction']);

			die;*/

		if(trim(empty($org_id))) {

			$data = array('IsValid'=>false, 'msg'=> 'Organization id is required');
			echo json_encode($data);
			exit();
		}

		if(trim(empty($wp_id))) {

			$data = array('IsValid'=>false, 'msg'=> 'Workplace id is required');
			echo json_encode($data);
			exit();
		}

		//$org_id  =  $input_data['org_id'];

		$this->db->select('id,name');
		$this->db->where('org_id',$org_id);
		$this->db->where('wp_id',$wp_id);
		
		$query = $this->db->get('cov19_function_value');
	//	echo $this->db->last_query();
		if($query->num_rows() > 0){
			$res = $query->result();

	
			$data1['IsValid'] = true;

		    $data1['data'] = json_decode(json_encode($res));

		    $data1['msg'] = 'Function List';
		
       
		//$data = array('status'=>true, 'details'=>"$res");
		echo json_encode($data1,true);
		}else{
			$data = array('status'=>false, 'msg'=>"No data found");
			echo json_encode($data,JSON_UNESCAPED_SLASHES);
		}

	}else{
		$data = array('status'=>false, 'msg'=>"Something went wrong");
			echo json_encode($data);
	}
	}



	public function get_subfunctionNamefromWorkplace()
	{
			$this->verify_token();

		$org_id =  $_GET['org_id'];
		$wp_id =  $_GET['fun_id'];
	/*	$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		print_r($input_data); die;*/

		if(!empty($org_id)) {

			/*print_r($input_data['subfunction']);

			die;*/

		if(trim(empty($org_id))) {

			$data = array('IsValid'=>false, 'msg'=> 'Organization id is required');
			echo json_encode($data);
			exit();
		}

		if(trim(empty($wp_id))) {

			$data = array('IsValid'=>false, 'msg'=> 'Function id is required');
			echo json_encode($data);
			exit();
		}

		//$org_id  =  $input_data['org_id'];

		$this->db->select('id,name');
		$this->db->where('org_id',$org_id);
		$this->db->where('fun_id',$wp_id);
		
		$query = $this->db->get('cov19_function_value');
	//	echo $this->db->last_query();
		if($query->num_rows() > 0){
			$res = $query->result();

	
			$data1['IsValid'] = true;

		    $data1['data'] = json_decode(json_encode($res));

		    $data1['msg'] = 'Sub function List';
		
       
		//$data = array('status'=>true, 'details'=>"$res");
		echo json_encode($data1,true);
		}else{
			$data = array('status'=>false, 'msg'=>"No data found");
			echo json_encode($data,JSON_UNESCAPED_SLASHES);
		}

	}else{
		$data = array('status'=>false, 'msg'=>"Something went wrong");
			echo json_encode($data);
	}
	}





	public function get_department_list()
	{

		$this->verify_token();

		
		$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		//print_r($input_data); die;*/

		if(!empty($input_data)) {

			/*print_r($input_data['subfunction']);

			die;*/

		if(trim(empty($input_data['org_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Organization id is required');
			echo json_encode($data);
			exit();
		}

		$org_id  =  $input_data['org_id'];

		$this->db->select('*');
		$this->db->where('org_id',$org_id);
		$query = $this->db->get('cov19_workplace');
	//	echo $this->db->last_query();
		if($query->num_rows() > 0){
			$res = $query->result();
			$functions = array();
			foreach ($res as $res1) {
				$dep_name = json_decode($res1->dep_details,true);
				$total =  count($dep_name);
				for($k=0;$k<$total;$k++) {
				$functions[] = $dep_name[$k]['dep_details'];
			}
			}
//print_r($functions);
			$data1['IsValid'] = true;

		    $data1['data'] = json_decode(json_encode($functions));

		    $data1['msg'] = 'Department List';
		
       
		//$data = array('status'=>true, 'details'=>"$res");
		echo json_encode($data1,true);
		}else{
			$data = array('IsValid'=>false, 'msg'=>"No data found");
			echo json_encode($data);
		}

	}else{
		$data = array('IsValid'=>false, 'msg'=>"Something went wrong");
			echo json_encode($data);
	}
	}




	public function excel_import()
	{

		$this->verify_token();

		
		$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		/*print_r($input_data);*/ //die;*/

		if(!empty($input_data)) {

			/*print_r($input_data['data']);

			die;*/

		if(trim(empty($input_data['org_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Organization id is required');
			echo json_encode($data);
			exit();
		}

		if(trim(empty($input_data['subfun_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Sub function id is required');
			echo json_encode($data);
			exit();
		}

		/*if(trim(empty($input_data['subfun_name']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Sub function name is required');
			echo json_encode($data);
			exit();
		}*/




		$org_id = $input_data['org_id'];
		$subfun_id = $input_data['subfun_id'];
		$subfun_name = $input_data['subfun_name'];

		$this->db->select('table_name,total_emp');
		$this->db->where('org_id',$org_id);
		$query = $this->db->get('cov19_emp_org');
		$res = $query->row();

		$tableName = $res->table_name;
		$imp_val = $input_data['data'];
		$total = count($input_data['data']);

		$error = array();

		/*for($l=0;$k<$total;$l++) {


		if(trim(empty($input_data[$l]['Emp_id']))) {

			$error[] = "Employee id is required on row $l+1";

			
		}
		if(trim(empty($input_data[$l]['Emp_Work_Experience']))) {

			$error[] = "Work Experience is required row $l+1";
			
		}
		if(trim(empty($input_data['Emp_Current_Role']))) {

			$error[] = "Current Role is required row $l+1";
		}
		if(trim(empty($input_data['Emp_Deliverable_milestone']))) {

			$error[] = "Deliverable milestone is required row $l+1";
		}
		if(trim(empty($input_data['Emp_Peak_load_1_10']))) {

			$error[] = "Peak load in a month (1-10) is required row $l+1";
		}
		if(trim(empty($input_data['Emp_Peak_load_11_20']))) {

			$error[] = "Peak Load in a month (11-20) is required row $l+1";
		}
		if(trim(empty($input_data['Emp_Peak_load_21_Eom']))) {

			$error[] = "Peak Load in a month (21-EOM) is required row $l+1";
		}


}*/
//echo $total die;

for($k=0;$k<$total;$k++) {

		
		$emp_id1 = strip_tags($imp_val[$k]['Emp_id']);
		$work_exp = strip_tags($imp_val[$k]['Emp_Work_Experience']);
		$role = strip_tags($imp_val[$k]['Emp_Current_Role']);
		$milestone = strip_tags($imp_val[$k]['Emp_Deliverable_milestone']);
		$peak_load_month = strip_tags($imp_val[$k]['Emp_Peak_load_1_10']);
		$emp_activity_11 = strip_tags($imp_val[$k]['Emp_Peak_load_11_20']);
		$emp_activity_21 = strip_tags($imp_val[$k]['Emp_Peak_load_21_Eom']);
		
		
		
		$create_at = date('Y-m-d H:i:s');



		$data = array(
			'emp_id' => $emp_id1,
			'org_id' => $org_id,
			'work_exp' => $work_exp,
			'dept' => 'NA',
			'role' => $role,
			'milestone' => $milestone,
			'peak_load_month' => $peak_load_month,
			'emp_activity_11' => $emp_activity_11,
			'emp_activity_21' => $emp_activity_21,
			'sub_function' => 'NA',
			'sub_function_id' => $subfun_id,
			'created_at' => $create_at,

		);

	 $insert =  $this->db->insert("$tableName",$data);
	 if(!$insert){
		$data = array('IsValid'=>false, 'msg'=>"Data not insert due to some problem");
			echo json_encode($data);
			exit();
	}

	 $data23 = array('org_id' => $org_id,'emp_id'=> $emp_id1,'email'=> 'NA','type'=>'4','type_name'=>'emp');
     $this->db->insert('cov19_employee_login',$data23);
	//  echo $this->db->last_query();

	}

		$data1['IsValid'] = true;

		//$data1['data'] = json_decode(json_encode($data));

		$data1['msg'] = 'Employe list imported successfully';

		//$data = array('status'=>true, 'token' => $token);
		echo json_encode($data1);

		}else{
		$data = array('IsValid'=>false, 'msg'=>"Something went wrong");
			echo json_encode($data);
	}

	}
 

	public function report()
	{


		$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		//print_r($input_data); die;*/

		if(!empty($input_data)) {


		$org_id =  $input_data['org_id'];
		$workplace = $input_data['workplace'];
		$dep = $input_data['department'];
		$subdep = $input_data['sub_department'];

		$this->db->select('*');
		//$this->db->where('name','Headquater - Mum');
		$this->db->where('org_id',$org_id);
		$query = $this->db->get('cov19_emp_details');


		//$query = $this->db->query("SELECT * FROM `cov19_workplace` where name = 'Headquater - Mum' and org_id = '1'");

        $res = $query->result();

        $data1['IsValid'] = true;

		$data1['data'] = json_decode(json_encode($res));

		$data1['msg'] = 'Report List';

		echo json_encode($data1);


	}else{

			$data = array('IsValid'=>false, 'msg'=>"Something went wrong");
			echo json_encode($data);

	}


	}


	public function get_org()
	{


		$this->db->select('org_id,org_name,emp_id,emp_name,department,email,total_emp,created_at');
		
		$query = $this->db->get('cov19_emp_org');

        $res = $query->result();

        $data1['IsValid'] = true;

		$data1['data'] = json_decode(json_encode($res));

		$data1['msg'] = 'Organization List';

		echo json_encode($data1);


	}


	
	public function send_email()
	{

		//$query = $this->db->query('SELECT * FROM `cov19_workplace` WHERE JSON_CONTAINS(tags, '["dep_details"]')');

		


	//echo file_get_contents("https://codersspace.com/irma_new/login/send_email?email=anilksen84@gmail.com&password=123456&org_id=1"); die;

require_once(APPPATH."third_party/PHPMailer/src/PHPMailer.php");
require_once(APPPATH."third_party/PHPMailer/src/SMTP.php");
require_once(APPPATH."third_party/PHPMailer/src/Exception.php");
$mail = new PHPMailer\PHPMailer\PHPMailer();

$mail->IsSMTP();

try {
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
  $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
  $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
  $mail->Username   = "anilksen84@gmail.com";  // GMAIL username
  $mail->Password   = "kuber12-42";            // GMAIL password

  //This is the "Mail From:" field
  $mail->SetFrom('aniksen84@gmail.com', 'Anil');
  //This is the "Mail To:" field
  $mail->AddAddress('aniksen84@yahoo.com', 'Anil Doe');
  $mail->Subject = 'PHPMailer  Subject via mail(), advanced';
  $mail->Body     = "Hi! \n\n This is my first e-mail sent through PHPMailer.";

  $mail->Send();
  echo "Message Sent OK<p></p>\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
	/*	$config = Array(    

      'protocol' => 'sendmail',
      'smtp_host' => 'email-smtp.us-west-2.amazonaws.com',
      'smtp_port' => 587,
      'smtp_user' => 'anilksen84@gmail.com',
      'smtp_pass' => 'kuber12-42',
      'smtp_timeout' => '4',
      'mailtype' => 'html',
      'charset' => 'iso-8859-1'

    );

    $this->load->library('email', $config);
    $this->email->set_newline("\r\n");

  

    $this->email->from('anilksen84@gmail.com', 'Anil Labs');

    $data = array(

       'userName'=> 'Anil Kumar Sen'

         );

    $this->email->to('anil.sen@dbcorp.in'); // replace it with receiver mail id
    $this->email->subject('Welcome Anil'); // replace it with relevant subject

  

    $body = $this->load->view('welcome_email.php',$data,TRUE);

  $this->email->message($body); 

    $this->email->send();
echo '11111'; 
echo $this->email->print_debugger();
	}*/


	     $from_email = "anilksen84@gmail.com";
        $to_email = 'anil.sen@dbcorp.in';
        //Load email library
        $this->load->library('email');
        $this->email->from($from_email, 'Identification');
        $this->email->to($to_email);
        $this->email->subject('Send Email Codeigniter');
        $this->email->message('The email send using codeigniter library');
        //Send mail
        if($this->email->send()) {
           echo '11111';
        }
        else {
            echo '2222';
        }
       


}





/**
     * Create function.
     *
     * @return Response
     */


public function create_function()
{
	$this->verify_token();


		$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);


	//	print_r($input_data['sub_function']); die;

		if(!empty($input_data)) {



			//echo '------------- Subfunction post value ----- '

			//echo $input_data['sub_function'].'-----All Data-- <br/>';

			//print_r($input_data);die;



		if(trim(empty($input_data['org_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Organization id is required');
			echo json_encode($data);
			exit();
		}

		if(trim(empty($input_data['emp_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Employee id field is required');
			echo json_encode($data);
			exit();
		}

		/*if(trim(empty($input_data['department']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Department/Function field is required');
			echo json_encode($data);
			exit();
		}*/

	/*	if(empty(trim($input_data['is_the_function']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Is this function a core function of your business field is required');
			echo json_encode($data);
			exit();
		}

		if(empty(trim($input_data['space_allocated']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Space allocated to this function in the workplace field is required');
			echo json_encode($data);
			exit();
		}
		if(empty(trim($input_data['total_capacity']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Total Capacity of the function field is required');
			echo json_encode($data);
			exit();
		}*/

		

		$org_id = $input_data['org_id'];
		$emp_id = $input_data['emp_id'];
		$department = $input_data['department'];
		$is_the_function = $input_data['is_the_function'];
		$extremely_critical = $input_data['extremely_critical'];
		$space_allocated = $input_data['space_allocated'];
		$delivering_outputs = $input_data['delivering_outputs'];
		$space_allocated = $input_data['space_allocated'];
		$total_capacity = $input_data['total_capacity'];
		$sub_function = json_encode($input_data['sub_function']);
		$general_nature = $input_data['general_nature'];
		$sub_function = $input_data['sub_function'];
		$selected_sub_function = $input_data['selected_sub_function'];
		$required_laptops = $input_data['required_laptops'];
		$close_proximity = $input_data['close_proximity'];
		$sub_function_have_many = $input_data['sub_function_have_many'];
		$in_person_or_both = $input_data['in_person_or_both'];
		$protect_employees = $input_data['protect_employees'];
		$space_allocated_sub = $input_data['Space_allocated'];
		$headcount = $input_data['Total_Capacity'];
		$fun_id = $input_data['fun_id'];


		$create_at = date('Y-m-d H:i:s');


		$data = array(
			'emp_id' => $emp_id,
			'org_id' => $org_id,
			'fun_id' => $fun_id,
			'department' => strip_tags($department),
			'is_the_function' => strip_tags($is_the_function),
			'extremely_critical' => strip_tags($extremely_critical),
			'space_allocated' => strip_tags($space_allocated),
			'delivering_outputs' => strip_tags($delivering_outputs),
			'space_allocated' => strip_tags($space_allocated),
			'sub_function' => strip_tags($sub_function),
			'selected_sub_function' => strip_tags($selected_sub_function),
			'total_capacity' => strip_tags($total_capacity),
			'general_nature' => strip_tags($general_nature),
			'required_laptops' => strip_tags($required_laptops),
			'close_proximity' => strip_tags($close_proximity),
			'sub_function_have_many' => strip_tags($sub_function_have_many),
			'in_person_or_both' => strip_tags($in_person_or_both),
			'screening_points' => strip_tags($protect_employees),
			'space_allocated_sub' => strip_tags($space_allocated_sub),
			'headcount' => strip_tags($headcount),
			'total_estimated' => strip_tags($total_estimated),
			'created_at' => $create_at,
			);


		$data2 = array(
			'emp_id' => $emp_id,
			'org_id' => $org_id,
			'department' => strip_tags($department),
			'is_the_function' => strip_tags($is_the_function),
			'extremely_critical' => strip_tags($extremely_critical),
			'space_allocated' => strip_tags($space_allocated),
			'delivering_outputs' => strip_tags($delivering_outputs),
			'space_allocated' => strip_tags($space_allocated),
			'sub_function' => $sub_function,
			'selected_sub_function' => $selected_sub_function,
			'total_capacity' => strip_tags($total_capacity),
			'general_nature' => strip_tags($general_nature),
			'required_laptops' => strip_tags($required_laptops),
			'close_proximity' => strip_tags($close_proximity),
			'sub_function_have_many' => strip_tags($sub_function_have_many),
			'in_person_or_both' => strip_tags($in_person_or_both),
			'protect_employees' => strip_tags($protect_employees),
			'Space_allocated' => strip_tags($space_allocated_sub),
			'Total_Capacity' => strip_tags($headcount),
			'Total_estimated' => strip_tags($total_estimated),
			'created_at' => $create_at,
			);

		
		//$insert = $this->db->insert('cov19_function_details',$data);

		$totald = count($input_data['sub_function']);

		$sub_function = $input_data['sub_function'];
 
		for($k=0; $k<$totald; $k++) {

        $dd_de = $sub_function[$k]['subfunction'];
       

        $data24 = array('org_id' => $org_id,'emp_id'=> $emp_id,'name'=> $dd_de,'type'=>'subfunction','created_at'=>$create_at,'fun_id'=>$fun_id,'wp_id'=>'0');
        
        $this->db->insert('cov19_function_value',$data24);

		}


		if($insert) {
			$data1['IsValid'] = true;
        	$data1['data'] = $data2;
        	$data1['msg'] = 'Function Added';
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



/**
     * Create function for Get sub sunction
     *
     * @return Response
     */

public function get_subfunction_name()
{

	$this->verify_token();


		$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		if(!empty($input_data)) {

		if(trim(empty($input_data['org_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Organization id is required');
			echo json_encode($data);
			exit();
		}

		if(trim(empty($input_data['emp_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Employee id field is required');
			echo json_encode($data);
			exit();
		}

		if(trim(empty($input_data['fun_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Function id field is required');
			echo json_encode($data);
			exit();
		}


		$org_id = $input_data['org_id'];
		$emp_id = $input_data['emp_id'];
		$fun_id = $input_data['fun_id'];


		$this->db->select('id as subfun_id,name');
		$this->db->where('org_id',$org_id);
		$this->db->where('emp_id',$emp_id);
		$this->db->where('fun_id',$fun_id);

		$query = $this->db->get('cov19_function_value');

		
		if($query->num_rows() > 0)
		{
			$res = $query->result();


            $data1['IsValid'] = true;
        	$data1['data'] = json_decode(json_encode($res));
        	$data1['msg'] = 'Sub function list';
        	echo json_encode($data1);
		}else{

			$data1 = array('IsValid'=>false, 'msg'=> 'No data found');
			echo json_encode($data1);

		}

	

		}else{

		$data1 = array('IsValid'=>false, 'msg'=> 'Something went wrong11');
			echo json_encode($data1);

		}





	}


	/*function rollback_data()
	{

		$this->db->select('table_name');
		$this->db->where('org_id',$org_id);
		$query = $this->db->get('cov19_emp_org');
		$res = $query->row();
		$table = $res->table_name;

		$this->db->query("DELETE from $table where added_by = $emp_id and creation_date < DATE_SUB(NOW(),INTERVAL 15 MINUTE)");
	}*/



	/**
     * Create function for Get sunction name org wise
     *
     * @return Response
     */

public function get_emp_function_name()
{

	$this->verify_token();


		$input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream),true);

		if(!empty($input_data)) {

		if(trim(empty($input_data['org_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Organization id is required');
			echo json_encode($data);
			exit();
		}

		if(trim(empty($input_data['emp_id']))) {

			$data = array('IsValid'=>false, 'msg'=> 'Employee id field is required');
			echo json_encode($data);
			exit();
		}


		$org_id = $input_data['org_id'];
		$emp_id = $input_data['emp_id'];
	


		$this->db->select('id,name');
		$this->db->where('org_id',$org_id);
		$this->db->where('emp_id',$emp_id);
		$this->db->where('fun_id',0);

		$query = $this->db->get('cov19_function_value');

		
		if($query->num_rows() >0)
		{
			$res = $query->result();


            $data1['IsValid'] = true;
        	$data1['data'] = json_decode(json_encode($res));
        	$data1['msg'] = 'function name';
        	echo json_encode($data1);
		}else{

			$data1 = array('IsValid'=>false, 'msg'=> 'Something went wrong');
			echo json_encode($data1);

		}

	

		}else{

		$data1 = array('IsValid'=>false, 'msg'=> 'Something went wrong11');
			echo json_encode($data1);

		}





	}





/**
     * Create function for employee self declaration
     *
     * @return Response
     */


public function emp_self_decl()
{
	$this->verify_token();


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

		if(trim(empty($input_data['gender']))) {

			$data = array('IsValid'=>false, 'message'=> 'Gender field is required');
			echo json_encode($data);
			exit();
		}

		if(empty(trim($input_data['age']))) {

			$data = array('IsValid'=>false, 'message'=> 'Age field is required');
			echo json_encode($data);
			exit();
		}

		if(empty(trim($input_data['City']))) {

			$data = array('IsValid'=>false, 'message'=> 'City field is required');
			echo json_encode($data);
			exit();
		}
		if(empty(trim($input_data['State']))) {

			$data = array('IsValid'=>false, 'message'=> 'State field is required');
			echo json_encode($data);
			exit();
		}

		if(!is_numeric($input_data['Pincode'])) {
			$data = array('IsValid'=>false, 'message'=> 'Pincode not vaild');
			echo json_encode($data);
			exit();
		}

		/*if(empty(trim($input_data['work_from_home']))) {

			$data = array('IsValid'=>false, 'message'=> ' If given an option, would you prefer to work from home/remotely field is required');
			echo json_encode($data);480
			exit();
		}*/

		
				

		$org_id = $input_data['org_id'];
		$emp_id = $input_data['emp_id'];
		$use_aarogya_setu = $input_data['use_aarogya_setu'];
		$Pincode = $input_data['Pincode'];
		$City = $input_data['City'];
		$State = $input_data['State'];
		$Location = json_encode($input_data['Location']);
		$age = $input_data['age'];
		$gender = $input_data['gender'];
		$cough = $input_data['cough'];
		$cold = $input_data['cold'];
		$fever = $input_data['fever'];
		$breadthingdiffculty = $input_data['breadthingdiffculty'];
		$symptoms_no = $input_data['symptoms_no'];
		$bphypertension = $input_data['bphypertension'];
		$diabetes = $input_data['diabetes'];
		$heartlungdieases = $input_data['heartlungdieases'];
		$pregentwomen = $input_data['pregentwomen'];
		$medicalhistory = $input_data['medicalhistory_no'];
		$travelhistory = $input_data['travelhistory'];
		$listtravelcountry = $input_data['listtravelcountry'];
		$recentlyinteracted = $input_data['recentlyinteracted'];
		$aarogya_setu_residence = $input_data['aarogya_setu_residence'];
		$aarogya_setu_workplace = $input_data['aarogya_setu_workplace'];
		$prefer_towork_from_home = $input_data['prefer_towork_from_home'];
		$internet_connectivity = $input_data['internet_connectivity'];
		$facility_details = $input_data['facility_details'];
		//$family_listoffacility = $input_data['family_listoffacility'];
		$tested_POSITIVE = $input_data['tested_POSITIVE'];
		$vicinity_of_your_workplace = $input_data['vicinity_of_your_workplace'];


		
		
		$create_at = date('Y-m-d H:i:s');


		$data = array(
			'emp_id' => $emp_id,
			'org_id' => $org_id,
			'use_aarogya_setu' => strip_tags($use_aarogya_setu),
			'Pincode' => strip_tags($Pincode),
			'City' => strip_tags($City),
			'type' => 'self',
			'State' => strip_tags($State),
			'Location' => $Location,
			'age' => strip_tags($age),
			'gender' => strip_tags($gender),
			'cough' => strip_tags($cough),
			'cold' => strip_tags($cold),
			'fever' => strip_tags($fever),
			'breadthingdiffculty' => strip_tags($breadthingdiffculty),
			'symptoms_no' => strip_tags($symptoms_no),
			'bphypertension' => strip_tags($bphypertension),
			'diabetes' => strip_tags($diabetes),
			'heartlungdieases' => strip_tags($heartlungdieases),
			'pregentwomen' => strip_tags($pregentwomen),
			'medicalhistory-no' => strip_tags($medicalhistory),
			'travelhistory' => strip_tags($travelhistory),
			'listtravelcountry' => json_encode($listtravelcountry),
			'recentlyinteracted' => strip_tags($recentlyinteracted),
			'aarogya_setu_residence' => strip_tags($aarogya_setu_residence),
			'aarogya_setu_workplace' => strip_tags($aarogya_setu_workplace),
			'prefer_towork_from_home' => strip_tags($prefer_towork_from_home),
			'internet_connectivity' => strip_tags($internet_connectivity),
			'facility_details' => strip_tags($facility_details),
			'prefer_towork_from_home' => strip_tags($prefer_towork_from_home),
			//'family_listoffacility' => '',//json_encode($family_listoffacility),
			'tested_POSITIVE' => strip_tags($tested_POSITIVE),
			'vicinity_of_your_workplace' => strip_tags($vicinity_of_your_workplace),
			'created_at' => $create_at,
			);

		$this->db->where('org_id',$org_id);
		$this->db->where('emp_id',$emp_id);
		$this->db->where('type','self');
		$query = $this->db->get('cov19_emp_details');

		if($query->num_rows()) {

		$this->db->where('org_id',$org_id);
		$this->db->where('emp_id',$emp_id);
		$insert = $this->db->update('cov19_emp_details',$data);

		}else{
		
		$insert = $this->db->insert('cov19_emp_details',$data);

		}	


		if($insert) {
			$data1['IsValid'] = true;
        	$data1['data'] = $data;
        	$data1['msg'] = 'Data added succussfully';
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



/**
     * Create function for employee self declaration
     *
     * @return Response
     */




function export_self_decl()
 {
    
    $file_name = 'reset_employee_on_'.date('Ymd').'.csv'; 
     header("Content-Description: File Transfer"); 
     header("Content-Disposition: attachment; filename=$file_name"); 
     header("Content-Type: application/csv;");
   
     // get data 
     $this->db->select('emp_id,org_id,gender,age,symptoms_no,medicalhistory-no,travelhistory,listtravelcountry,recentlyinteracted,aarogya_setu_residence,aarogya_setu_workplace');

     $this->db->from('cov19_emp_details');
     $student_data = $this->db->get();

     // file creation 
     $file = fopen('php://output', 'w');
 
     $header = array("emp_id","org_id","gender","age","symptoms","medical_history","travel_history","travel_history_details","recently_interacted_with_positive","covid_positive_vicintiy_home_meters","covid_positive_vicintiy_work_meters"); 
     fputcsv($file, $header);
     foreach ($student_data->result_array() as $key => $value)
     { 
       fputcsv($file, $value); 
     }
     fclose($file); 
     exit; 
 }

 function export_employe_functions()
 {
    
    $file_name = 'reset_employeefunctions_on_'.date('Ymd').'.csv'; 
     header("Content-Description: File Transfer"); 
     header("Content-Disposition: attachment; filename=$file_name"); 
     header("Content-Type: application/csv;");
   
     // get data 
     $this->db->select('emp_id,org_id,department,is_the_function,extremely_critical,delivering_outputs,space_allocated,total_capacity,sub_function,general_nature,required_laptops,close_proximity,sub_function_have_many,in_person_or_both,screening_points,space_allocated_sub,headcount,total_estimated');

     $this->db->from('cov19_function_details');
     $student_data = $this->db->get();

     // file creation 
     $file = fopen('php://output', 'w');
 
     $header = array("emp_id","org_id","department","Is this function a core function of your business","If no, is this function extremely critical for supporting core functions","Statutory/Regulatory Compliance","function in the workplace","Total Capacity of the function","sub-function","general nature of interactions for the employees","employees of this sub-function have required laptops","require employees to work in close proximity to deliver their tasks","do the employees of this sub-function have many interactions","long durations for each of the interactions","Are there screening points and mechanism to protect employees ","Space allocated to this sub-function ","Total Capacity in sub-function ","Total estimated/desired capacity "); 
     fputcsv($file, $header);
     foreach ($student_data->result_array() as $key => $value)
     { 
       fputcsv($file, $value); 
     }
     fclose($file); 
     exit; 
 }


 function export_employee_details()
 {
    
    $file_name = 'employeeDetails_on_'.date('Ymd').'.csv'; 
     header("Content-Description: File Transfer"); 
     header("Content-Disposition: attachment; filename=$file_name"); 
     header("Content-Type: application/csv;");

     $this->db->select('table_name');
     $query =$this->db->get('cov19_emp_org');
     
    
     $res = $query->result();

     foreach($res as $res) {

    	$table_name = $res->table_name; 

     
   
     // get data 
     $this->db->select('emp_id,org_id,dept,work_exp,role,milestone,peak_load_month,emp_activity_11,emp_activity_21');
     
     $this->db->from($table_name);
     $student_data = $this->db->get();

     // file creation 
     $file = fopen('php://output', 'w');
 
     $header = array("emp_id","org_id","Work Experience","Current role","Deliverable milestone","Peak load in a month (1-10)","Peak Load in a month (11-20)","Peak Load in a month (21-EOM)"); 
     fputcsv($file, $header);
     foreach ($student_data->result_array() as $key => $value)
     { 
       fputcsv($file, $value); 
     }

 }
     fclose($file); 
     exit; 
 }



 /**
     * Create function for employee self declaration
     *
     * @return Response
     */


public function emp_family_decl()
{
	$this->verify_token();


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

		if(trim(empty($input_data['gender']))) {

			$data = array('IsValid'=>false, 'message'=> 'Gender field is required');
			echo json_encode($data);
			exit();
		}

		if(empty(trim($input_data['age']))) {

			$data = array('IsValid'=>false, 'message'=> 'Age field is required');
			echo json_encode($data);
			exit();
		}

	/*	if(empty(trim($input_data['symptoms']))) {

			$data = array('IsValid'=>false, 'message'=> 'Symptoms of Illness field is required');
			echo json_encode($data);
			exit();
		}
		if(empty(trim($input_data['medical_history']))) {

			$data = array('IsValid'=>false, 'message'=> 'Medical history field is required');
			echo json_encode($data);
			exit();
		}*/

		

	    $org_id = $input_data['org_id'];
		$emp_id = $input_data['emp_id'];
		//$use_aarogya_setu = $input_data['use_aarogya_setu'];
		//$Pincode = $input_data['Pincode'];
		//$City = $input_data['City'];
	//	$State = $input_data['State'];
		//$Location = json_encode($input_data['Location']);
		$age = $input_data['age'];
		$gender = $input_data['gender'];
		$cough = $input_data['cough'];
		$cold = $input_data['cold'];
		$fever = $input_data['fever'];
		$breadthingdiffculty = $input_data['breadthingdiffculty'];
		$symptoms_no = $input_data['symptoms_no'];
		$bphypertension = $input_data['bphypertension'];
		$diabetes = $input_data['diabetes'];
		$heartlungdieases = $input_data['heartlungdieases'];
		$pregentwomen = $input_data['pregentwomen'];
		$medicalhistory = $input_data['medicalhistory_no'];
		$travelhistory = $input_data['travelhistory'];
		$listtravelcountry = $input_data['listtravelcountry'];
		$recentlyinteracted = $input_data['recentlyinteracted'];
		//$aarogya_setu_residence = $input_data['aarogya_setu_residence'];
		$aarogya_setu_workplace = $input_data['aarogya_setu_workplace'];
	/*	$prefer_towork_from_home = $input_data['prefer_towork_from_home'];
		$internet_connectivity = $input_data['internet_connectivity'];
		$facility_details = $input_data['facility_details'];
		$family_listoffacility = $input_data['family_listoffacility'];
		$tested_POSITIVE = $input_data['tested_POSITIVE'];
		$vicinity_of_your_workplace = $input_data['vicinity_of_your_workplace'];*/


		
		
		$create_at = date('Y-m-d H:i:s');


		$data = array(
			'emp_id' => $emp_id,
			'org_id' => $org_id,
			'use_aarogya_setu' => strip_tags($use_aarogya_setu),
			//'Pincode' => strip_tags($Pincode),
			//'City' => strip_tags($City),
			'type' => 'famaily',
			//'State' => strip_tags($State),
			'Location' => $Location,
			'age' => strip_tags($age),
			'gender' => strip_tags($gender),
			'cough' => strip_tags($cough),
			'cold' => strip_tags($cold),
			'fever' => strip_tags($fever),
			'breadthingdiffculty' => strip_tags($breadthingdiffculty),
			'symptoms_no' => strip_tags($symptoms_no),
			'bphypertension' => strip_tags($bphypertension),
			'diabetes' => strip_tags($diabetes),
			'heartlungdieases' => strip_tags($heartlungdieases),
			'pregentwomen' => strip_tags($pregentwomen),
			'medicalhistory-no' => strip_tags($medicalhistory),
			'travelhistory' => strip_tags($travelhistory),
			'listtravelcountry' => json_encode($listtravelcountry),
			'recentlyinteracted' => strip_tags($recentlyinteracted),
			'aarogya_setu_workplace' => strip_tags($aarogya_setu_workplace),
		
			'created_at' => $create_at,
			);




		$this->db->where('org_id',$org_id);
		$this->db->where('emp_id',$emp_id);
		$this->db->where('type','famaily');
		$query = $this->db->get('cov19_emp_details');

		if($query->num_rows() > 3) {

			$data2 = array('IsValid'=>false, 'msg'=> 'You have already added 4 famaily members details.');
			echo json_encode($data2);
			exit();

		}


		
		$insert = $this->db->insert('cov19_emp_details',$data);;		


		if($insert) {
			$data1['IsValid'] = true;
        	$data1['data'] = $data;
        	$data1['msg'] = 'Famaily Details Added';
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
