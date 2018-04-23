<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_Email extends CI_Model{

	var $type		= '';
	var $receiver	= '';
	var $default	= 0;
	var $subject	= '';
	var $body		= '';

	public function __construct()
	{
		// Llamando al contructor del Modelo
		parent::__construct();
		$this->load->database();
	}
	
	public function create($data)
	{
		$this->type 	= $data['type'];
		$this->receiver = $data['receiver'];
		$this->default 	= $data['default'];
		$this->subject 	= $data['subject'];
		$this->body 	= $data['body'];

		$query = $this->db->insert('email', $this);
		return $this->db->insert_id();
	}

	public function get_emails_by_project($id_project)
	{
		$this->db->select('email.*');
		$this->db->from('email');
		$this->db->join('email_in_project', 'email.id=email_in_project.id_email');
		$this->db->where('email_in_project.id_project', $id_project);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_default_emails()
	{
		$query = $this->db->get_where('email', array('default' => 1));
		return ( $query->num_rows() >0 ) ? $query->result() : NULL;
	}

	public function actual_email($id_project, $type, $receiver)
	{
		$this->db->select('email.id, email.default');
		$this->db->from('email');
		$this->db->join('email_in_project', 'email.id=email_in_project.id_email');
		$this->db->where('email_in_project.id_project', $id_project);
		$this->db->where('email.type', $type);
		$this->db->where('email.receiver', $receiver);
		$query = $this->db->get();

		return $query->first_row();
	}

	public function get_default_email_by_type_and_receiver($type, $receiver)
	{
		$this->db->where('type', $type);
		$this->db->where('receiver', $receiver);
		$this->db->where('default', 1);
		$query = $this->db->get('email');

		return $query->first_row();
	}

	public function get_email_data($id_project, $type, $receiver)
	{
		$this->db->select('email.subject, email.body');
		$this->db->from('email');
		$this->db->join('email_in_project', 'email.id=email_in_project.id_email');
		$this->db->where('email_in_project.id_project', $id_project);
		$this->db->where('email.type', $type);
		$this->db->where('email.receiver', $receiver);
		$query = $this->db->get();

		return $query->first_row();		
	}

	public function update($actual_id, $data)
	{
		$this->db->where('id', $actual_id);
		$this->db->update('email', $data);		
	}

	public function update_email_in_project($id_project, $actual_id, $new_id)
	{
		$this->db->where('id_project', $id_project);
		$this->db->where('id_email', $actual_id);
		$this->db->update('email_in_project', array('id_email' => $new_id));
	}

	/**
	 * Deletes the selected email.
	 * Due to the reset email functionality, the ids of default emails shoud not be the input of this function
	 * 
	 * @param integer $id The id of the email
	 * 
	 * @return boolean The result of the delete
	 */
	
	public function delete($id)
	{
		return $this->db->delete('email', array('id' => $id));
	}

}
/* End of file M_Email.php */
/* Location: ./application/models/m_email.php */