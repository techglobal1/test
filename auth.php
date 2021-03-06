<?php defined('BASEPATH') || exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->load->model('Common_model');
		$this->load->model('Crud_model');
			
		
		$this->lang->load('auth');
	}
	//redirect if needed, otherwise display the user list
	function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
			//redirect them to the home page because they must be an administrator to view this
			//return show_error('You must be an administrator to view this page.');
			
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->data['users'] = $this->ion_auth->users()->result();
			
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}
            $userid=(array_slice($this->session->userdata,9,1));
			
			$user_id=$userid['user_id'];
			
			$check = $this->Common_model->get_data_by_query("select cash_amt,from_user,status_in_out from pd_cashier_test where from_user=$user_id and status_in_out=0 ");
				
			if($check){
				redirect('admin/CGHS0007/handoverIntest', $this->data['users']);
			}
			if($this->ion_auth->is_admin()){
				$this->template->set_template('user');
				$this->template->write_view('header', 'default/header', $this->session->userdata);
				$this->template->write_view('sidebar', 'default/sidebar');
			   
			   
			    $today  = date('Y-m-d');
					
				$this->data['resource']=$this->Common_model->get_data_by_query("select * from ipd_admit a left join patient p on a.admit_uhid=p.id 	where admit_status ='DISCHARGED' and  date_format(a.admit_exitdt,'%Y-%m-%d')='$today' ");
		
				$this->data['patient']=$this->Common_model->get_data_by_query("select * from ipd_admit a left join patient p on a.admit_uhid = p.id where admit_status in ('CP','NA','OT')");
					
				$this->data['doctor']=$this->Common_model->get_data_by_query("select * from doctor");

				$this->template->write_view('content', 'auth/index',$this->data);
			
				$this->template->render();
			}
			elseif($this->ion_auth->in_group('opd')){
			
				redirect('admin/CNCR0003/dashbord', $this->data['users']);	
			}
			elseif($this->ion_auth->in_group('OverTime')){
		
				redirect('admin/HRD00017/manage_assign_ot', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('Cancer_dep')){
		
				redirect('admin/CNCR0003/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('Purchase_Admin')){
		
				redirect('admin/STORE00047/index', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('vicram')){
		
				redirect('admin/FOPD0003/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('attendant')){
				
				redirect('admin/PDF00011/show_attendance1', $this->data['users']);
			}			
			elseif($this->ion_auth->in_group('ctscan')){
		
				redirect('admin/USG00019/usg', $this->data['users']);
			}		
			elseif($this->ion_auth->in_group('employee')){
			
				redirect('admin/HRD00017/staff_profile', $this->data['users']);	
			}
			elseif($this->ion_auth->in_group('meeting')){
			
				redirect('admin/RAJI0014/manage_meeting', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('pathology')){
				
				redirect('admin/PATH0005/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('refreshment')){
				
				redirect('admin/HRD00017/refresment_report', $this->data['users']);
			}		 
			elseif($this->ion_auth->in_group('lab_collection')){
			
				redirect('admin/PATH0005/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('lab_rgenrate')){
				
				redirect('admin/PATH0005/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('lab_admin')){
				
				redirect('admin/PATH0005/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('lab_pathologist')){
				
				redirect('admin/PATH0005/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('lab_hemotology')){
				
				redirect('admin/PATH0005/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('lab_biochemidtry')){
				
				redirect('admin/PATH0005/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('lab_clinical')){
				
				redirect('admin/PATH0005/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('lab_reception')){
				
				redirect('admin/PATH0005/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('Nursing-admin')){
				
				redirect('admin/NURS0004/choose_ward', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('doctor')){
				
				redirect('admin/DOC0016/', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('bplddy')){
				
				redirect('admin/BPLD0008', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('CGHS-BPL')){
				
				redirect('admin/BPLD0008', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('BPL_MEDICINE')){
				
				redirect('admin/BPLD0008', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('collact')){
				
				redirect('admin/PATH0005/collected', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('cghs')){
				
				redirect('admin/CGHS0007/cghsDashboard', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('pathology')){
				
				redirect('admin/PATH0005/dashbord', $this->data['users']);
			}			
			elseif($this->ion_auth->in_group('admin_pathology')){
			
				redirect('admin/PATH0005/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('xray')){
				
				redirect('admin/XRAY0009/dashbord', $this->data['users']);
			}		
			elseif($this->ion_auth->in_group('xrayemployee')){
				
				redirect('admin/XRAY0009/dashbord', $this->data['users']);
			}			
			elseif($this->ion_auth->in_group('rajeev')){
				
				redirect('admin/RAJI0014', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('usg')){
				
				redirect('admin/USG00019/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('usg_admin')){
				
				redirect('admin/USG00019/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('CT-Scan')){
				
				redirect('admin/USG00019/dashboardctscan', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('store')){
				
				redirect('admin/STORE00047/index', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('BioMedical')){
			
				redirect('admin/BIOMED0010/index', $this->data['users']);
			}		
			elseif($this->ion_auth->in_group('casualty')){
				
				redirect('admin/CASU00010/casualty_getdata', $this->data['users']);
			}	
			elseif($this->ion_auth->in_group('admin_casualty')){
				
				redirect('admin/CASU00010/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('frontoffice')){
				
				redirect('admin/IPD0015', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('floormanager')){
				
				redirect('admin/FREG0006/enquiry', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('ot_manager')){
				
				redirect('admin/OT00026/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('nursing')){
				
				redirect('admin/NURS0004/choose_ward', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('cathlab')){
				
				redirect('admin/NURS0004/choose_cath_ward', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('cath_admin')){
				
				redirect('admin/NURS0004/choose_cath_ward', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('schems')){
				
				redirect('admin/BPLD0008', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('IPD_OPD')){
				
				redirect('admin/FREG0006', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('hr')){
				
				redirect('admin/HRD00017/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('assistent_director')){
				
				redirect('admin/MAIN00020/admin_purchase', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('billing')){
				
				redirect('admin/FREG0006/frontview', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('cash')){
				
				redirect('admin/CASH00019/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('maintenance')){
				
				redirect('admin/MAIN00020/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('marketing')){
				
				redirect('admin/MARKET00028/ipdPatient', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('discharge')){
				
				redirect('admin/BIOMED0010/dischargeDash', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('CAS-PREG')){
				
				redirect('admin/FREG0006/newRegisterDashboard', $this->data['users']);
			}			
			elseif($this->ion_auth->in_group('Acounts')){
				
				redirect('admin/ACCO00030/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('yatin')){
				
				redirect('admin/FREG0006/enquiry', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('billing-dis')){
			
			    redirect('admin/BILL00018/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('bill-reg')){
				
			    redirect('admin/BILL00018/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('Payroll')){
				
				redirect('admin/PAYROLE/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('Account_head')){
				
				redirect('admin/ACCO00030/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('Req_Payment')){
				
				redirect('admin/ACCO00030/dashbord', $this->data['users']);
			}			
			elseif($this->ion_auth->in_group('HRHead')){
				
				redirect('admin/HRD00017/dashbord', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('nabh')){
				
				redirect('admin/NABH00032/dashboard', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('chairman')){
				
				redirect('admin/CHAIRMAN/alldepview', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('management')){
				
				redirect('admin/CHAIRMAN/alldepview', $this->data['users']);
			}			
			elseif($this->ion_auth->in_group('mrd')){
				
				redirect('admin/MRD033/regPatientCounter', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('CALLCENTER')){
				
				redirect('admin/CALLCENTER', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('discharge_policy')){
				
				redirect('admin/DISPOLY01', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('ICN')){
				
				redirect('admin/NURS0004/allPatient', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('physiotherapy')){
				
				redirect('admin/PHYSIO051/physioDashboard', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('Purchase-Admin')){
				
				redirect('admin/STORE00047/index', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('Software')){
				
				redirect('admin/SOFT0001/index', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('dietitian')){
				
				redirect('admin/DIET00029/index', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('Network')){
				
				redirect('admin/NETWORK002/index', $this->data['users']);
			}	
			elseif($this->ion_auth->in_group('assignment')){
				
				redirect('admin/COMP00021/chairmanDashboard', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('PARAMEDICAL')){
				
				redirect('admin/MAIN00020/View_Store_Req', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('sardar-patel')){
				
				redirect('admin/MAIN00020/View_Store_Req', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('RMO')){
				
				redirect('admin/NURS0004/choose_ward', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('DISCHARGE-ADMIN')){
				
	            redirect('admin/BILL00018/pdischarge', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('SCHEME-ADMIN')){
		
	            redirect('admin/BPLD0008/dashboardMain', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('Housekeeping')){
				
	            redirect('admin/HRD00017/manage_assign_ot', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('O2')){
				
	            redirect('admin/HRD00017/manage_assign_ot', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('cardic_surgery')){
				
	            redirect('admin/OT00026/dashbord_cardic', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('blood-bank')){
				
	            redirect('admin/PDF00011/blood_bank', $this->data['users']) ;
			}
			elseif($this->ion_auth->in_group('VISITOR-PASS')){
				
	            redirect('admin/FREG0006/gatepass_relatv', $this->data['users']) ;
			}
			elseif($this->ion_auth->in_group('pharma-admin')){
				
				redirect('admin/PHY00022/dashboard', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('pharma-demand')){
				
				redirect('admin/PHY00022/dashboard', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('Approve_doc')){
				
				redirect('admin/CGHS0007/app_doc_dashboard', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('pharma')){
				
				redirect('admin/PHY00022/dashboard', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('pharma-nursing')){
				
				redirect('admin/PHY00022/dashboard', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('PHARMA-SCHEMES')){
				
				redirect('admin/PHY00022/dashboard', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('PHARMA-SCHEMES-NEW')){
				
				redirect('admin/PHY00022SCHEME/mediReq', $this->data['users']);
			}
			elseif($this->ion_auth->in_group('pharmacy')){
				
				redirect('admin/CASU00010', $this->data['users']);
			} 
			elseif($this->ion_auth->in_group('BILLING-ADMIN')){
				
				redirect('admin/FREG0006/frontview', $this->data['users']);
			} elseif($this->ion_auth->in_group('Account_bill')){
				
				redirect('admin/ACCO00030/acc_totalbill', $this->data['users']);
			} 
			
			
			elseif($this->ion_auth->in_group('pharma-cashier')){
				
				$userid=(array_slice($this->session->userdata,9,1));
			
				$user_id=$userid['user_id'];
				$check = $this->Common_model->get_data_by_query("select cash_amt,from_user,status_in_out from pd_cashier_test where from_user=$user_id and status_in_out=0");
					   
				if(!$check){
					redirect('admin/PHYCASH22/dashboard', $this->data['users']);
				}
				elseif($check['cash_amt']==0){
					redirect('admin/CGHS0007/handoverIntest', $this->data['users']);
				}
				else{
					redirect('admin/PHYCASH22/dashboard', $this->data['users']);
				}
				
			}
			
			$data['Allresource'] = $this->Common_model->get_data_by_query("select r_short_name 
			from resource where r_level = 3");
			foreach($data['Allresource'] as $val){
				$r_short_name = $val['r_short_name'];
				if($this->ion_auth->in_group($r_short_name)){
				
				$data['resource'] = $this->Common_model->get_data_by_query("select r_id from resource
						where r_short_name = '$r_short_name'");
				$ward_id = 	$data['resource'][0]['r_id'];
				
				redirect('admin/NURS0004/dashboard/'.$ward_id, $this->data['users']);
			
				}
			}
		}

	}
	
	
	
   function match_security() {
        $sec_key = $this->input->post('sec_key');
        $userid = (array_slice($this->session->userdata, 9, 1));
        $uid = $userid['user_id'];
        $data = $this->Common_model->get_data_by_query("select * from users where id=$uid");
        if($data[0]['security_key']==$sec_key){
            echo '1';
        }else{
            echo '0';
        }
        
    }

	//log the user in
	function login()
	{
		$this->data['title'] = "Login";
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == true)
		{
			$remember = (bool) $this->input->post('remember');
			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				$data2['log_sessionid'] = $this->session->userdata('session_id');
				$data2['log_utype']  	= $this->session->userdata('group');
				$data2['log_uid']  		= $this->session->userdata('user_id');
				$data2['log_uname']  	= $this->session->userdata('username');
				$data2['log_ip']  		= $this->session->userdata('ip_address');
				$data2['log_lastlogin'] = $this->session->userdata('last_activity');
				$data2['log_entrydt']  	= date("Y-m-d H:i:s");
		    	$this->Crud_model->insert_record('user_log',$data2);
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('/', 'refresh');
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'class' => 'm-wrap span12',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'class' => 'm-wrap span12',
			);

			$this->_render_page('auth/login', $this->data);
		}
	}
	
	
	function loginWebApp()
	{
		
		$identity="admin@admin.com" ;
		$password="1234mhcrc";
		$remember="1";

	
	}
	
	//log the user out
	function addnewuser()
	{
				if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//list the users
			$this->data['users'] = $this->ion_auth->users()->result();
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}

			if($this->ion_auth->is_admin()){
			$this->template->set_template('user');
			$this->template->write_view('header', 'default/header', $this->session->userdata);
			$this->template->write_view('sidebar', 'default/sidebar');
			$this->template->write_view('content', 'auth/create_user',$this->data);
			$this->template->write_view('footer', 'default/footer');
			$this->template->render();
			}
			
		}
	}
	//log the user out
	function logout()
	{
		$this->data['title'] = "Logout";
		$sessionid=$this->session->userdata('session_id');
		$data['log_logout']	= date('Y-m-d H:i:s');
		$this->Crud_model->edit_record_by_any_id('user_log','log_sessionid',$sessionid,$data);
		$logout = $this->ion_auth->logout();
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('auth/login', 'refresh');
	}
	
	function change_password()
	{
		$old=$_POST['oldpass'];
		$new=$_POST['newpass'];
		$conf=$_POST['confpass'];
		
		$this->form_validation->set_rules('oldpass','oldpass', 'required');
		$this->form_validation->set_rules('newpass','newpass', 'required|min_length[8]|max_length[45]|matches[confpass]');
		$this->form_validation->set_rules('confpass','confpass', 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
		$user = $this->ion_auth->user()->row();
		if ($this->form_validation->run() == false)
		{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = 8;
			$this->data['old_password'] = array(
				'name' => 'oldpass',
				'id'   => 'oldpass',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'newpass',
				'id'   => 'newpass',
				'type' => 'password',
				'pattern' => '^.{8}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'confpass',
				'id'   => 'confpass',
				'type' => 'password',
				'pattern' => '^.{8}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect('/','refresh');
		}
		else
		{
			$identity = $this->session->userdata('identity');
			$change = $this->ion_auth->change_password($identity, $this->input->post('oldpass'), $this->input->post('newpass'));
			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
				//redirect('/','refresh');
				//echo "Password Changed ";
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				//redirect('auth/change_password', 'refresh');
				redirect('/','refresh');
				//echo $data['message'];
			}
		}
	}
	
	
	function change_securitykey()
	{
		$old=$_POST['oldkey'];
		$new=$_POST['newkey'];
		$conf=$_POST['confkey'];
		
		$this->form_validation->set_rules('oldkey','oldkey', 'required');
		$this->form_validation->set_rules('newkey','newkey', 'required|min_length[8]|max_length[45]|matches[confkey]');
		$this->form_validation->set_rules('confkey','confkey', 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
		$user = $this->ion_auth->user()->row();
		if ($this->form_validation->run() == false)
		{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = 8;
			$this->data['old_password'] = array(
				'name' => 'oldkey',
				'id'   => 'oldkey',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'newkey',
				'id'   => 'newkey',
				'type' => 'password',
				'pattern' => '^.{8}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'confkey',
				'id'   => 'confkey',
				'type' => 'password',
				'pattern' => '^.{8}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect('/','refresh');
		}
		else
		{
			$identity = $this->session->userdata('identity');
			$change = $this->ion_auth->change_password($identity, $this->input->post('oldkey'), $this->input->post('newkey'));
			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
				//redirect('/','refresh');
				//echo "Password Changed ";
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				//redirect('auth/change_password', 'refresh');
				redirect('/','refresh');
				//echo $data['message'];
			}
		}
	}
	//forgot password
	function forgot_password()
	{
		//setting validation rules by checking wheather identity is username or email
		if($this->config->item('identity', 'ion_auth') == 'username' )
		{
		   $this->form_validation->set_rules('email', $this->lang->line('forgot_password_username_identity_label'), 'required');
		}
		else
		{
		   $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() == false)
		{
			//setup the input
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
			);

			if ( $this->config->item('identity', 'ion_auth') == 'username' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('auth/forgot_password', $this->data);
		}
		else
		{
			// get identity from username or email
			if ( $this->config->item('identity', 'ion_auth') == 'username' ){
				$identity = $this->ion_auth->where('username', strtolower($this->input->post('email')))->users()->row();
			}
			else
			{
				$identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();
			}
	            	if(empty($identity)) {

	            		if($this->config->item('identity', 'ion_auth') == 'username')
		            	{
                                   $this->ion_auth->set_message('forgot_password_username_not_found');
		            	}
		            	else
		            	{
		            	   $this->ion_auth->set_message('forgot_password_email_not_found');
		            	}

		                $this->session->set_flashdata('message', $this->ion_auth->messages());
                		redirect("auth/forgot_password", 'refresh');
            		}

			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				//if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	//reset password - final step for forgotten password
	public function reset_password($code=NULL)
	{
		
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);
		
		if ($user)
		{
			//if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				//display the form

				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
				'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id'   => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				//render
				$this->_render_page('auth/reset_password', $this->data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					//something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						//if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("auth/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}


	//activate the user
	function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	//deactivate the user
	function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			//redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			$this->_render_page('auth/deactivate_user', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			//redirect them back to the auth page
			redirect('auth', 'refresh');
		}
	}


	//create a new user
	function create_user()
	{
		$this->data['title'] = "Create User";

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
		// $data['group'] = $group;
		$tables = $this->config->item('tables','ion_auth');
		//validate form input
		$this->form_validation->set_rules('emp_id', $this->lang->line('create_user_validation_empid_label'), 'required');
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique['.$tables['users'].'.email]');
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required');
		$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() == true)
		{
			$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
			$email    = strtolower($this->input->post('email'));
			$password = $this->input->post('password');
			$additional_data = array(
			'emp_id' => $this->input->post('emp_id'),
			'first_name' => $this->input->post('first_name'),
			'last_name'  => $this->input->post('last_name'),
			'company'    => $this->input->post('company'),
			'phone'      => $this->input->post('phone'),
			);
		}
		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data))
		{
			//check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		}
		else
		{
			//display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = array(
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			);
			$this->data['last_name'] = array(
				'name'  => 'last_name',
				'id'    => 'last_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['company'] = array(
				'name'  => 'company',
				'id'    => 'company',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company'),
			);
			$this->data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone'),
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);
			$this->data['usgtest'] = $this->Common_model->get_data_by_query('select * from employee where emp_status=1 order by emp_name');
			//echo $this->db->last_query();
			//die;
             
			$this->_render_page('auth/create_user', $this->data);
		}
		// echo $this->db->last_query();
			// die; 
	}

	//edit a user
	function edit_user($id)
	{
		$this->data['title'] = "Edit User";

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			// if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			// {
				// show_error($this->lang->line('error_csrf'));
			// }

			//update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'company'    => $this->input->post('company'),
					'phone'      => $this->input->post('phone'),
				);

				//update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}
				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin())
				{
					//Update the groups user belongs to
					$groupData = $this->input->post('groups');

					if (isset($groupData) && !empty($groupData)) {

						$this->ion_auth->remove_from_group('', $id);

						foreach ($groupData as $grp) {
							$this->ion_auth->add_to_group($grp, $id);
						}

					}
				}

			//check to see if we are updating the user
			   if($this->ion_auth->update($user->id, $data))
			    {
			    	//redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->messages() );
				    if ($this->ion_auth->is_admin())
					{
						redirect('auth', 'refresh');
					}
					else
					{
						redirect('/', 'refresh');
					}

			    }
			    else
			    {
			    	//redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->errors() );
				    if ($this->ion_auth->is_admin())
					{
						redirect('auth', 'refresh');
					}
					else
					{
						redirect('/', 'refresh');
					}

			    }

			}
		}

		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		// $this->_render_page('auth/edit_user', $this->data);
		$this->template->set_template('user');
			   $this->template->write_view('header', 'default/header',$this->session->userdata);
			   $this->template->write_view('sidebar', 'default/sidebar');
			   $this->template->write_view('content','auth/edit_user', $this->data);
			   $this->template->write_view('footer', 'default/footer');
               $this->template->render();
	}
	
	
	public function addlink()
	{
		  if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
		$data['link']=$this->Common_model->get_data_by_query("select * from sidebar_links");
		$this->template->set_template('user');
			   $this->template->write_view('header', 'default/header',$this->session->userdata);
			   $this->template->write_view('sidebar', 'default/sidebar');
			   $this->template->write_view('content','auth/addlink',$data);
			   $this->template->write_view('footer', 'default/footer');
               $this->template->render();
	    }
	}
	
	 public function showajax()
	{
	$updateid=$this->input->post('updateid');
	$link=$this->input->post('link');
	$data['links']=$this->input->post('link');
	$data['linknames']=$this->input->post('linkname');
	
	
	if($updateid=='')
	{
		  $ii=$this->Common_model->get_data("select * from sidebar_links where links='$link'");
		  
		  if($ii->num_rows()>0)
		  {
			    echo 'Links can not be duplicate' ;
			  
		  }
		  else 
		  {
			  $this->Common_model->insert1('sidebar_links',$data);
			    echo 'Data saved Successfully  ' ;
		  }
		
		
		
	}
	elseif($updateid!='')
	{
			
			$this->Common_model->update_record('sidebar_links',$updateid,$data);
			  echo 'Data Modified Successfully  ' ;	
		
	}
	
	$data['link']=$this->Common_model->get_data_by_query("select * from sidebar_links");
	
	foreach($data['link'] as $link2)
	{
		?>
		<tr>
		<td><?php echo $link2['links'];?></td>
		<td style="font-size:18px"><?php echo $link2['linknames'];?></td>
        <td><button onclick="editajax('<?php echo $link2['id'];?>')" class="btn red">Edit Link</button></td>
		<td><button onclick="deleteajax('<?php echo $link2['id'];?>')" class="btn red">Delete Link</button></td>
		</tr>
		<?php
	}
	
 
}

	public function searchlink()
	{
		$links=$_POST['links'];
	
		if($links!='')
{
	$links=$links.'@links';
}
$s=array($links);
$q='';
foreach($s as $k1)
{
	if($k1=='')
	{
		$data['link']=$this->Common_model->get_data_by_query("select * from sidebar_links");
	
	foreach($data['link'] as $link2)
	{
		?>
		<tr>
		<td><?php echo $link2['links'];?></td>
		<td style="font-size:18px"><?php echo $link2['linknames'];?></td>
        <td><button onclick="editajax('<?php echo $link2['id'];?>')" class="btn red">Edit Link</button></td>
		<td><button onclick="deleteajax('<?php echo $link2['id'];?>')" class="btn red">Delete Link</button></td>
		</tr>
		<?php
	}
										
									
	}
	else{
		$ii=explode('@',$k1);
		$ij=$ii[0];
		$ik=$ii[1];
		if($ik=='links')
		{
			$q.=$ik." like "."'%".$ij."%'";
			
		}
		
		
		$data['link']=$this->Common_model->get_data_by_query("select * from sidebar_links where $q");
	
	foreach($data['link'] as $link2	){
		?>
		<tr>
		<td><?php echo $link2['links'];?></td>
		<td style="font-size:18px"><?php echo $link2['linknames'];?></td>
        <td><button onclick="editajax('<?php echo $link2['id'];?>')" class="btn red">Edit Link</button></td>
		<td><button onclick="deleteajax('<?php echo $link2['id'];?>')" class="btn red">Delete Link</button></td>
		</tr>
		<?php
	}
}
}
	}


    public function edit()
	{
		$id=$this->input->post('id');
		$data['link1']=$this->Common_model->get_data_by_query("select * from sidebar_links where id='$id'");
		
		$links=$data['link1'][0]['links'];
		$linkname=$data['link1'][0]['linknames'];
		
		$json=array("link"=>$links,"linkname"=>$linkname,"updateid"=>$id);
		
		echo json_encode($json);
		
	}
	
	public function deleteajax()
	{
		$id=$this->input->post('id');
		$this->Common_model->get_data("delete from sidebar_links where id='$id'");
		$data['link']=$this->Common_model->get_data_by_query("select * from sidebar_links");
	
	foreach($data['link'] as $link2)
	{
		?>
		<tr>
		<td><?php echo $link2['links'];?></td>
		<td style="font-size:18px"><?php echo $link2['linknames'];?></td>
        <td><button onclick="editajax('<?php echo $link2['id'];?>')" class="btn red">Edit Link</button></td>
		<td><button onclick="deleteajax('<?php echo $link2['id'];?>')" class="btn red">Delete Link</button></td>
		</tr>
		<?php
	}
	
	}
	
	public function grouplink()
	{
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
		$data['grouplist']=$this->Common_model->get_data_by_query("select * from groups");
		$data['linklist']=$this->Common_model->get_data_by_query("select * from sidebarlist");
		$data['linkslist']=$this->Common_model->get_data_by_query("select * from sidebar_links");
		$this->template->set_template('user');
			   $this->template->write_view('header', 'default/header',$this->session->userdata);
			   $this->template->write_view('sidebar', 'default/sidebar');
			   $this->template->write_view('content','auth/grouplink',$data);
			   $this->template->write_view('footer', 'default/footer');
               $this->template->render();
	    }
	}

	public function submitgroup_l()
	{
		$data['grup_l_groupid']=$this->input->post('grouplist');
		$data['grup_l_listid']=$this->input->post('linklist');
		$data['grup_l_linkid']=$this->input->post('linkslist');
		
		$g_id=$this->input->post('grouplist');
		$li_id=$this->input->post('linklist');
		$l_id=$this->input->post('linkslist');
		
		$ik=$this->Common_model->get_data("select * from group_links where grup_l_groupid='$g_id' and grup_l_listid='$li_id' and grup_l_linkid='$l_id'");
		if($ik->num_rows()>0)
		{
			echo "<script>alert('link data already exist')</script>";
		}
		else
		{
        $this->Common_model->insert1('group_links',$data);	
        echo "data added";		
	    }
		
	}
	
	
	public function groupdata()
	{
		$id=$this->input->post('id');
		$data['g_data']=$this->Common_model->get_data_by_query("select * from group_links gl join groups g on gl.grup_l_groupid=g.id join sidebar_links s on gl.grup_l_linkid=s.id join sidebarlist sls on gl.grup_l_listid=sls.id where grup_l_groupid='$id'");
		foreach($data['g_data'] as $gdata)
	{
		?>
		<tr>
		<td><?php echo $gdata['grup_l_id'];?></td>
		<td style="font-size:18px"><?php echo $gdata['description'];?></td>
		<td style="font-size:18px"><?php echo $gdata['list'];?></td>
		<td style="font-size:18px"><?php echo $gdata['linknames'];?></td>
		<td style="font-size:18px"><?php echo $gdata['links'];?></td>
		</tr>
		<?php
	}
	}


	// create a new group
	function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}
		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash');

		if ($this->form_validation->run() == TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
			}
		}
		else
		{
			//display the create group form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			$this->_render_page('auth/create_group', $this->data);
		}
	}

	//edit a group
	function edit_group($id)
	{
		// bail if no group id given
		if(!$id || empty($id))
		{
			redirect('auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("auth", 'refresh');
			}
		}

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['group'] = $group;

		$readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

		$this->data['group_name'] = array(
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name', $group->name),
			$readonly => $readonly,
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);

		$this->_render_page('auth/edit_group', $this->data);
	}


	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function _render_page($view, $data=null, $render=false)
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $render);

		if (!$render) return $view_html;
	}

}
