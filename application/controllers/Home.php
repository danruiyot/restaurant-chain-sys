<?php
class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('restaurant_model');
		$this->load->model('menu_model');
		$this->load->model('food_model');
	}

	public function index()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
 		$data['products'] = $this->restaurant_model->get_restaurants();
		$data['title']= 'Restaurant list';
		$data['restaurants'] = $this->restaurant_model->get_restaurants();

		$this->load->view('templates/header', $data);
		$this->load->view('pages/restaurant_list', $data);
		$this->load->view('templates/footer');
	}
	public function home($slug)
	{

		 $data['restaurant'] = $this->restaurant_model->get_restaurants($slug);
		 $data['menuslist'] = $this->menu_model->get_menus($slug);
		 $data['food'] = $this->food_model->get_food($slug);
		$data['title']= 'Restaurant list';

		$this->load->view('templates/header', $data);
		$this->load->view('pages/restaurant_home', $data);
		$this->load->view('templates/footer');

	}

	public function view($slug = NULL)
	{
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		 $data['item'] = $this->restaurant_model->get_restaurants($slug);
		 $data['menus'] = $this->menu_model->get_menus($slug);
		$data['title']= 'Restaurant view';

		$this->load->view('templates/header', $data);
		$this->load->view('pages/restaurant_view', $data);
		$this->load->view('templates/footer');
	}

	public function create()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('upload');
		$data['title'] = 'New Restaurant';
	
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade show">', '</div>');
            
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('location', 'Location', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header', $data);
			$this->load->view('pages/new_restaurant');
			$this->load->view('templates/footer');

		}
		else
		{
			
   $config['upload_path'] = './assets/images/';
   $config['allowed_types'] = 'gif|jpg|png|jpeg';
   $config['max_size'] = '2048000'; // max size in KB
   $config['max_width'] = '20000'; //max resolution width
   $config['max_height'] = '20000';  //max resolution height
   // load CI libarary called upload
  
   $this->upload->initialize($config);
   
   // body of if clause will be executed when image uploading is failed
   if(!$this->upload->do_upload('logo')){
	$errors = array('errors' => $this->upload->display_errors());

	$this->session->set_flashdata('error', 'wronng image type');
	 $this->load->view('templates/header', $data);
	 $this->load->view('pages/new_restaurant');
	 $this->load->view('templates/footer');
	 
   }
   // body of else clause will be executed when image uploading is succeeded
   else{
	$upload_data = $this->upload->data();
	//get the uploaded file name
	$data['logo'] = $upload_data['file_name'];

	
	 //store pic data to the db
	 $this->restaurant_model->new_restaurant($data);

	 $this->session->set_flashdata('success', 'Restaurant succefully');

	 $this->load->view('templates/header', $data);
	 $this->load->view('pages/new_restaurant');
	 $this->load->view('templates/footer');
    
   }
			//redirect('');
			

		
		}
	}

}
