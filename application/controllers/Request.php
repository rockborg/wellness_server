<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller {

	/**
	 * Index Page for this controller.
	 * Does nothing really.
	 */
	public function index()
	{
	 	$result = array(
			"status"=>1,
			"Data"=>array(
				"message"=>"web services are running..."
			)
		);
		
		echo json_encode($result);
	}
	
	/* User Functions */
	
	/**
	 * RegisterUser
	 * Registers a new user with a unique email address
	 * Usage: RegisterUser?firstname={firstname}&lastname={lastname}&email={email}&pass={password}
	 * @param string firstname 	Firstname of the new user to be registered
	 * @param string lastname 	Lastname of the new user
	 * @param string email		Email address of the new user. Must be unique
	 * @param string pass		Password of the new user. At least 8 characters
	 * 
	 * @return object with status and data. data contains userdata if successful and errormessages if failed 
	 */
	public function RegisterUser()
	{
		$result = array();
		
		//CopyGetToPost(array('fname'=>$fname,'lname'=>$lname,'email'=>$email,'pass'=>$pass));
		$this->form_validation->set_data($this->input->get());
		$this->form_validation->set_rules('firstname','First Name','required|min_length[3]');
		$this->form_validation->set_rules('lastname',"Last Name",'required|min_length[3]');
		$this->form_validation->set_rules('email',"Email",'required|valid_email|callback_email_check');
		$this->form_validation->set_rules('pass',"Password",'required|min_length[8]');
		
		if($this->form_validation->run() == FALSE)
		{
			//Build result array for form failure
			$result = array(
			"status"=>0,
			"data"=>$this->form_validation->error_array()
			);
		}
		else {
			//Create User in the database
			$user = new user();
			$user->firstname = $this->input->get('firstname');
			$user->lastname = $this->input->get('lastname');
			$user->email = $this->input->get('email');
			$user->pass = $this->input->get('pass');
			$user->regdate = local_to_gmt(time());
			$user->SaveNew();
			
			//Build result array for form success
			$result = array(
			"status"=>1,
			"data"=>$this->input->get()
			);
		}
		
		echo json_encode($result);
	}
	
	/**
	 * LoginUser
	 * takes user credential and authenticates user returning a token to make future calls
	 * Usage: LoginUser?email={user_email}&pass={user_password}
	 * @param string username username/email of the user
	 * @param string pass password of the user
	 * @return object contains status of the request and array of user data including token
	 */
	public function LoginUser()
	{
		$result = array();
		
		$this->form_validation->set_data($this->input->get());
		$this->form_validation->set_rules('email',"Email",'required|valid_email');
		$this->form_validation->set_rules('pass',"Password",'required');
		
		if($this->form_validation->run() == FALSE)
		{
			//Build result array for form failure
			$result = array(
			"status"=>0,
			"data"=>$this->form_validation->error_array()
			);			
		}
		else {
			$user = new user();
			$userList = $user->GetList(array(array('email','=',$this->input->get('email')),array('pass','=',$this->input->get('pass'))));
			if(count($userList))
			{
				$authuser = $userList[0];
				$authuser->lastlogin = local_to_gmt(time());
				$authuser->token = hash('md5',time().uniqid().$authuser->email);
				$authuser->Save();
				
				//Build result array for form success
				$result = array(
				"status"=>1,
				"data"=>$authuser
				);
			}
			else {
				$result = array(
				"status" => 0,
				"data" => array(
					"username" => "username or password is incorrect",
					"message" => "username or password is incorrect"
					)
				);
			}
		}
		
		echo json_encode($result);
		
	}
	
	public function email_check($str)
	{
		$user = new user();
		$userList = $user->GetList(array(array('email',"=",$str)));
		
		//echo count($userList);
		if(count($userList) > 0)
		{
			$this->form_validation->set_message('email_check',"The email is already registered");
			return FALSE;
		}
		else 
			return TRUE;
	}

	/* Category Functions */
	
	/**
	 * Get Categories
	 * returns all the main categories of information in the database
	 * Usage: GetCategories?
	 * @return objectarray array of category objects
	 */
	 
	public function GetCategories()
	{
		$result = array("status"=>1,"data"=>array());
		
		$category = new category();
		
		if($catList = $category->GetList(array(),'categoryname',TRUE))
		{
			$resultarray = array();
			foreach($catList as $c)
			{
				$resultarray[] = array(
					"categoryid"=> $c->categoryId,
					"categoryname" => $c->categoryname,
					"categorydesc" => $c->categorydesc,
					"categoryimage" => prep_url(base_url('images/'.$c->categoryimage))
				);
			}
			
			$result = array(
			"status"=>1,
			"count"=>count($catList),
			"data"=>$resultarray
			);			
			
		}
		else {
			//Build result array for form failure
			$result = array(
			"status"=>0,
			"data"=>array(
					"error" => "Could not get categories"
				)
			);						
		}
		
		echo json_encode($result);
		
		
	}
	
	/* Data Functions */
	
	/**
	 * GetCategoryData
	 * used to get apps, blogs, events, and websites in a category
	 * Usage: GetCategoryData?categoryid={categoryid|0}&datatype={APP|BLOG|WEBSITE|EVENT}
	 * @param int categoryId 
	 * @param string datatype APP, BLOG, WEBSITE, EVENT
	 * @return array of items
	 */
	
	public function GetCategoryData()
	{
		$result = array("status"=>1,"data"=>array());
		
		$this->form_validation->set_data($this->input->get());
		$this->form_validation->set_rules('categoryid','Category ID','required');
		$this->form_validation->set_rules('datatype', 'Item Type', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
			//Build response array for form failure
			$result = array(
			"status"=>0,
			"data"=>$this->form_validation->error_array()
			);			
		}
		else {
			$catid = $this->input->get('categoryid');
			$itype = $this->input->get('datatype');
			
			switch ($itype)
			{
				case "APP":
					$app = new app();
					$appList = array();
					
					if($this->input->get('categoryid') > 0)
					{
						$appList = $app->GetList(array(array('categoryid','=',$catid)));
					}
					else {
						$appList = $app->GetList();
					}
					
					$resultarray = array();
					foreach($appList as $a)
					{
						$resultarray[] = array(
							'appid' => $a->appId,
							'appname' => $a->appname,
							'appdesc' => $a->appdesc,
							'appimage' => prep_url(base_url('images/'.$a->appimage)),
							'applink' => $a->applink,
							'appplatform'=> $a->appplatform,
							'categoryid'=>$a->categoryId
						);
					}
					$result = array(
						'status'=>1,
						'data'=>$resultarray
					);
					
					break;
				case "BLOG":
					$blog = new blog();
					$blogList = array();
					
					if($this->input->get('categoryid') > 0)
					{
						$blogList = $blog->GetList(array(array('categoryid','=',$catid)));
					}
					else {
						$blogList = $blog->GetList();	
					}
					
					$resultarray = array();
					foreach($blogList as $b)
					{
						$resultarray[] = array(
							'blogid'=>$b->blogId,
							'blogname'=>$b->blogname,
							'blogdesc'=>$b->blogdesc,
							'blogimage'=> prep_url(base_url('images/'.$b->blogimage)),
							'bloglink'=>$b->bloglink,
							'categoryid' =>$b->categoryId
						);
					}
					$result = array(
						'status' => 1,
						'data' => $resultarray
					);
					
					break;
				case 'WEB':
					$web = new web();
					$webList = array();
					
					if($this->input->get('categoryid') > 0)
					{
						$webList = $web->GetList(array(array('categoryid','=',$catid)));
					}
					else {
						$webList = $web->GetList();
					}
					
					$resultarray = array();
					foreach($webList as $w)
					{
						$resultarray[]  = array(
							'webid'=>$w->websiteId,
							'webname' => $w->webname,
							'webdesc' => $w->webdesc,
							'webimage' => prep_url(base_url('images/'.$w->webimage)),
							'weblink' => $w->weblink,
							'categoryid' => $w->categoryId
						);
					}
					$result = array(
						'status' => 1,
						'data' => $resultarray
					);
					break;
				case 'EVENT':
					$event = new event();
					$eventList = array();
					
					if($this->input->get('categoryid') > 0)
					{
						$eventList = $event->GetList(array(array('categoryid','=',$catid)));
					}
					else {
						$eventList = $event->GetList();
					}
					
					$resultarray = array();
					foreach($eventList as $e)
					{
						$resultarray[] = array(
							'eventid'=>$e->eventId,
							'eventname'=>$e->eventname,
							'eventdesc'=>$e->eventdesc,
							'eventimage'=> prep_url(base_url('images/'.$e->eventimage)),
							'eventlink'=> $e->eventlink,
							'eventdata'=> $e->eventdate,
							'categoryid'=>$e->categoryId
						);
					}
					$result = array(
						'status' => 1,
						'data' => $resultarray
					);
					break;
			}
			
		}
		
		echo json_encode($result);
				
	}
	
}