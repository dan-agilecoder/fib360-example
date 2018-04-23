<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_project extends CI_Model{

	var $name 			= '';
	var $start_date    	= 0;
	var $finish_date    = 0;
	var $organization	= '';
	var $logo			= NULL;

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * Creates a new project and the neccesary relationships
	 * 
	 * @param array $data The data received by form
	 * 
	 * @return integer The id of the new project
	 */
	
	public function create($data)
	{
		$this->load->model('m_rol');
		$this->load->model('m_email');
		
		$this->db->trans_start();

		$this->name 		= $data['name'];
		$this->start_date 	= $data['start_date'];
		$this->finish_date 	= $data['finish_date'];
		$this->organization = $data['organization'];
		$this->logo 		= $data['logo'];

		$this->db->insert('project', $this);

		$id_project = $this->db->insert_id();

		// Insert relationship between default roles and the new project
		foreach($this->m_rol->get_default_roles() as $rol){
			$rel_data = array(
				'id_project' 	=> $id_project,
				'id_rol'		=> $rol->id
			);
			$this->db->insert('rol_in_project', $rel_data);
		}

		// Insert relationship between default emails and the new project
		foreach($this->m_email->get_default_emails() as $email){
			$rel_data = array(
				'id_project' 	=> $id_project,
				'id_email'		=> $email->id
			);
			$this->db->insert('email_in_project', $rel_data);
		}		

		// Insert relationship between admin and project
		$this->db->insert('admin_in_project', array('id_user' => $this->session->userdata('id'), 'id_project' => $id_project));

		$this->db->trans_complete();

		return ( $this->db->trans_status() ) ? $id_project : NULL;
	}

	public function modify($data)
	{
		$this->name 		= $data['name'];
		$this->start_date 	= $data['start_date'];
		$this->finish_date 	= $data['finish_date'];
		$this->organization = $data['organization'];
		$this->logo 		= $data['logo'];

		$this->db->where('id', $data['id']);;

		return $this->db->update('project', $this);
	}

	/**
	 * Deletes a project and all of his related objects
	 * Delete Cascade: admin_in_project, email_in_project, process, rol_in_project, survey_in_project
	 * 
	 * @param integer $id_project The id of the project
	 * 
	 * @return boolean The result of the transaction
	 */
	
	public function delete($id_project)
	{
		$this->db->trans_start();

		// As the weak entities are not delete on cascade, these entities are saved to delete further
		
		// The emails are not delete on cascade to prevent deleting default emails
		$this->db->select('email_in_project.id_email');
		$this->db->from('email_in_project');
		$this->db->join('email', 'email_in_project.id_email=email.id');
		$this->db->where('email.default', 0);
		$this->db->where('email_in_project.id_project', $id_project);
		$emails = $this->db->get();

		// The surveys are not delete on cascade
		$this->db->select('survey.id');
		$this->db->from('survey');
		$this->db->join('survey_in_project', 'survey.id = survey_in_project.id_survey');
		$this->db->where('survey_in_project.id_project', $id_project);
		$surveys = $this->db->get();

		// The roles are not delete on cascade to prevent deleting default roles
		$this->db->select('rol_in_project.id_rol');
		$this->db->from('rol_in_project');
		$this->db->join('rol', 'rol_in_project.id_rol=rol.id');
		$this->db->where('rol.default', 0);
		$this->db->where('rol_in_project.id_project', $id_project);
		$roles = $this->db->get();

		// As the users are not delete on cascade, we save the users before the project delete
		$this->db->select('user_in_project.id_user');
		$this->db->from('user_in_project');
		$this->db->join('user', 'user_in_project.id_user=user.id');
		$this->db->where('user.system_rol', 'User');
		$this->db->where('user_in_project.id_project', $id_project);
		$users = $this->db->get();

		// Start deleting the project and his relationships and also the process
		$this->db->delete('project', array('id' => $id_project));

		// Delete the emails for this project
		if ( $emails->num_rows() > 0 )
		{
			$emails_to_delete = array();
			foreach($emails->result() as $email){
				array_push($emails_to_delete, $email->id_email);
			}
			$this->db->where_in('id', $emails_to_delete);
			$this->db->delete('email');
		}

		// Delete the surveys for this project
		if ( $surveys->num_rows() > 0 )
		{
			$surveys_to_delete = array();
			foreach($surveys->result() as $survey){
				array_push($surveys_to_delete, $survey->id);
			}
			$this->db->where_in('id', $surveys_to_delete);
			$this->db->delete('survey');
		}

		// Delete the roles for this project
		if ( $roles->num_rows() > 0 )
		{
			$roles_to_delete = array();
			foreach($roles->result() as $rol){
				array_push($roles_to_delete, $rol->id_rol);
			}
			$this->db->where_in('id', $roles_to_delete);
			$this->db->delete('rol');
		}
		
		// Delete the users for this project
		if ( $users->num_rows() > 0 )
		{
			$users_to_delete = array();
			foreach($users->result() as $user){
				array_push($users_to_delete, $user->id_user);
			}
			$this->db->where_in('id', $users_to_delete);
			$this->db->delete('user');
		}

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function get_process_status_by_project($id_project)
	{
		$this->db->select('process.id, user_in_process.filled_date');
		$this->db->from('process');
		$this->db->join('user_in_process', 'process.id=user_in_process.id_process');
		$this->db->where('process.id_project', $id_project);
		$query = $this->db->get();

		return ( $query->num_rows() > 0 ) ? $query->result() : NULL;
	}

	function get_entries($projects)
	{
		$this->db->where_in('id', $projects);
		$query = $this->db->get('project');
		
		return $query->result();	
	}
	
	function get_roles_in_project_keyvalue($id_project)
	{
		$this->db->select('rol.id,rol.name');
		$this->db->from('rol');
		$this->db->join('rol_in_project', 'rol.id = rol_in_project.id_rol');
		$this->db->where('id_project', $id_project);
		$query = $this->db->get();
		$data = array ();
		foreach ($query->result() as $rol) {
			$data[$rol->id]= $rol->name;
		}
		return $data;	
	}

	public function get_project_data($id_project)
	{
		$query = $this->db->get_where('project', array('id' => $id_project));
		return $query->first_row();		
	}

	public function get_project_logo($id_project)
	{
		$this->db->select('logo');
		$query = $this->db->get_where('project', array('id' => $id_project));
		return $query->first_row()->logo;		
	}
}