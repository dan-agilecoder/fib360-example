<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_project extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('m_project');
	}

	private function pre_load()
	{
		$header_data['page_title'] 			= 'Fib360 - projectos';
		$header_data['additional_css'][] 	= 'jquery-ui-1.10.3.custom.css';
		$menu_data['active'] 				= 'project';

		$this->load->view('partials/_v_header', $header_data);
		$this->load->view('partials/_v_menu', $menu_data);
		$this->load->view('modals/v_modal_modify_project');
		$this->load->view('modals/v_modal_new_process');
		$this->load->view('modals/v_modal_new_participant');
		$this->load->view('modals/v_modal_process');
		$this->load->view('modals/v_modal_participants');
		$this->load->view('modals/v_modal_display_charts');
		$this->load->view('modals/v_modal_alert');
	}

	/**
	 * Create a new project using the recived post data
	 * 
	 * @return view v_project
	 */
	public function create()
	{
		if ( $this->input->is_ajax_request() )
		{
			$this->load->library('form_validation');

			$this->form_validation->set_error_delimiters('<span>', '</span>');

			if ( $this->form_validation->run('create_project') )
			{
				$to_insert = array('name' 	=> $this->input->post('name', TRUE),
							'start_date' 	=> strtotime($this->input->post('start_date', TRUE)),
							'finish_date' 	=> strtotime($this->input->post('finish_date', TRUE)),
							'organization' 	=> $this->input->post('organization', TRUE),
							'logo'			=> ( ! empty($_FILES['logo']) ) ? $_FILES['logo']['name'] : NULL
						);

				$new_project_id = $this->m_project->create($to_insert);

				$data['status'] = ( $new_project_id ) ? 'success' : 'error_db';

				if ( $data['status'] === 'success' )
				{
					$data['entries'] = $this->load->view('partials/_v_projects_table', $this->get_entries(), TRUE);

					$config['upload_path'] 		= LOGO.$new_project_id;
					$config['allowed_types'] 	= 'gif|jpg|png';
					$config['overwrite'] 		= TRUE;
					$config['max_size']			= '100';
					$config['max_width'] 		= '1024';
					$config['max_height'] 		= '768';

					$this->load->library('upload', $config);

					if ( ! empty($_FILES['logo']) )
					{
						if ( ! is_dir(LOGO.$new_project_id) )
						{
							mkdir(LOGO.$new_project_id);
						}

						if ( $this->upload->do_upload('logo') )
						{
							$logo_data = $this->upload->data();
							$data['status'] = 'success';
						}
						else
						{
							$data['status'] = 'error_logo';
						}
					}
				}
			}
			else
			{
				$data['status'] = 'error_validation';

				if ( form_error('name') ) 			$data['validation_errors']['name'] = form_error('name');
				if ( form_error('organization') ) 	$data['validation_errors']['organization'] = form_error('organization');
				if ( form_error('start_date') ) 	$data['validation_errors']['start_date'] = form_error('start_date');
				if ( form_error('finish_date') ) 	$data['validation_errors']['finish_date'] = form_error('finish_date');
			}

			$status_code = ( $data['status'] === 'success' ) ? '200' : '400';
			
			$this->output
				->set_status_header($status_code)
				->set_content_type('application/json')
				->set_output(json_encode($data));
		}
	}

	public function modify()
	{
		if ( $this->input->is_ajax_request() )
		{
			$this->load->library('form_validation');

			$this->form_validation->set_error_delimiters('<div class="popover-error"><span class="glyphicon glyphicon-remove"></span> ', '</div>');

			if ( $this->form_validation->run('create_project') )
			{
				$to_update = array(
					'id'			=> $this->input->post('id', TRUE),
					'name' 			=> $this->input->post('name', TRUE),
					'start_date' 	=> strtotime($this->input->post('start_date', TRUE)),
					'finish_date' 	=> strtotime($this->input->post('finish_date', TRUE)),
					'organization' 	=> $this->input->post('organization', TRUE),
					'logo'			=> $this->input->post('logo', TRUE)
				);

				$data['status'] = ( $this->m_project->modify($to_update) ) ? 'success' : 'error';
			}
			else
			{
				$data['status'] = 'validation_errors';

				if ( form_error('name') ) 			$data['validation_errors']['name'] = form_error('name');
				if ( form_error('organization') ) 	$data['validation_errors']['organization'] = form_error('organization');
				if ( form_error('start_date') ) 	$data['validation_errors']['start_date'] = form_error('start_date');
				if ( form_error('finish_date') ) 	$data['validation_errors']['finish_date'] = form_error('finish_date');
				if ( form_error('logo') ) 			$data['validation_errors']['logo'] = form_error('logo');
			}

			$status_code = ( $data['status'] === 'success' ) ? '200' : '400';
			
			$this->output
				->set_status_header($status_code)
				->set_content_type('application/json')
				->set_output(json_encode($data));
		}
	}

	/**
	 * Deletes all the related data of the selected project
	 * 
	 * @return json The status of the operation
	 */
	
	public function delete()
	{
		if ( $this->input->is_ajax_request() )
		{
			$id_project = $this->input->post('id', TRUE);
			// Gets the url of the project logo to delete it after remove the project data from database
			$logo = $this->m_project->get_project_logo($id_project);

			$status = $this->m_project->delete($this->input->post('id', TRUE));

			if ( $status && file_exists(LOGO.$id_project.'/'.$logo))
			{
				$status = ( unlink(LOGO.$id_project.'/'.$logo) && rmdir(LOGO.$id_project) );
			}

			$data['status'] = ( $status ) ? 'success' : 'error';

			$status_code = ( $status ) ? '200' : '400';

			$this->output
				->set_status_header($status_code)
				->set_content_type('application/json')
				->set_output(json_encode($data));
		}
	}

	/**
	 * Checks if the selected project can be deleted
	 * 
	 * @param integer $id_project The id of the project
	 * 
	 * @return json The status of the operation
	 */
	
	public function check_to_delete($id_project)
	{
		if ( $this->input->is_ajax_request() )
		{
			// Get the process ids and the filled dates for the selected project
			$processes = $this->m_project->get_process_status_by_project($id_project);

			if ( $processes )
			{
				$is_superadmin = ( $this->session->userdata('system_rol') === 'Superadmin' );
				$data['status'] = ( $is_superadmin ) ? 'warning-superadmin' : 'warning';
				foreach($processes as $process){
					$data['status'] = ( $process->filled_date && ! $is_superadmin ) ? 'error' : $data['status'];
				}
				$status_code = ( $data['status'] === 'error' ) ? '400' : '200';
			}
			else
			{
				$data['status'] = 'success';
				$status_code = '200';
			}

			$this->output
				->set_status_header($status_code)
				->set_content_type('application/json')
				->set_output(json_encode($data));
		}
	}

	/**
	 * Return the existing projects for the logged user
	 * 
	 * @return array [description]
	 */
	public function get_entries()
	{
		$this->load->model('m_user');

		$projects = $this->m_user->get_projects_by_user($this->session->userdata('id'));

		$data['existing_projects'] = $this->m_project->get_entries(array_keys($projects));

		return $data;
	}

	public function get_percent_completed($projects)
	{
		$this->load->model('m_process');

		foreach($projects as $project){

			$data[$project->id]['process_in_project'] = $this->m_process->get_process_in_project($project->id);

			$projects_percent[$project->id]['num_surveys'] = 0;
			$projects_percent[$project->id]['num_filled_surveys'] = 0;

			foreach($data[$project->id]['process_in_project'] as $process){
				$projects_percent[$project->id]['num_surveys'] += $this->m_process->get_num_surveys_in_process($process->id);
				$projects_percent[$project->id]['num_filled_surveys'] += $this->m_process->get_num_completed_surveys_in_process($process->id);				
			}
		}

		return $projects_percent;
	}

	public function get_roles_in_project($id_project)
	{
		if ($this->input->is_ajax_request()){
			$data['existing_roles_in_project'] = $this->m_project->get_roles_in_project_keyvalue($id_project);
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($data));
		}
	}

	public function get_initial_project_data($id_project)
	{
		if ($this->input->is_ajax_request()){
			$this->load->model('m_survey');
			$data['existing_roles_in_project'] = $this->m_project->get_roles_in_project_keyvalue($id_project);
			$data['existing_surveys'] = $this->m_survey->get_entries_by_project_key_value($id_project);
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($data));
		}
	}
	
	public function get_project_data($id_project)
	{
		if ( $this->input->is_ajax_request() )
		{
			$data = $this->m_project->get_project_data($id_project);

			// Timestamp to date format before returning data
			// If not exists returns emprty string to prevent jQuery UI Autocomplete to show timestamp start date
			$data->start_date 	= ( $data->start_date ) ? date('d-m-Y', $data->start_date) : '';
			$data->finish_date 	= ( $data->finish_date ) ? date('d-m-Y', $data->finish_date) : '';

			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($data));
		}
	}

	public function get_html_projects_table()
	{
		if ( $this->input->is_ajax_request() )
		{
			$data = $this->get_entries();
			$data['projects_percent'] = $this->get_percent_completed($data['existing_projects']);

			$html = $this->load->view('partials/_v_projects_table', $data, TRUE);

			// Disable cache because some browsers like IE don`t work properly
			$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
			$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
			$this->output->set_header("Pragma: no-cache");

			$this->output
				->set_content_type('text/html')
				->set_output($html);
		}
	}

	public function index()
	{
		$this->load->helper('form');

		$data = $this->get_entries();
		$data['projects_percent'] = $this->get_percent_completed($data['existing_projects']);

		$data['status'] = 'initial';

		$this->pre_load();
		$this->load->view('v_project', $data);
	}
}

/* End of file C_project.php */
/* Location: ./application/controllers/c_project.php */