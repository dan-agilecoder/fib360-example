<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_Rol extends CI_Model{

	var $name			= '';
	var $plural_name	= '';
	var $default		= 0;

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function get_rol_name($id_rol)
	{
		$query = $this->db->get_where('rol', array('id' => $id_rol));
		return $query->first_row()->name;
	}

	public function get_plural_rol_name($id_rol)
	{
		$query = $this->db->get_where('rol', array('id' => $id_rol));
		return $query->first_row()->plural_name;
	}

	public function get_roles_by_project($id_project)
	{
		$this->db->select('rol.*');
		$this->db->from('rol');
		$this->db->join('rol_in_project', 'rol.id=rol_in_project.id_rol');
		$this->db->where('rol_in_project.id_project', $id_project);
		$query = $this->db->get();

		return $query->result();	
	}

	public function get_default_roles()
	{
		$query = $this->db->get_where('rol', array('default' => 1));

		return ( $query->num_rows() > 0 ) ? $query->result() : NULL;
	}

	public function is_used($id_rol)
	{
		$query = $this->db->get_where('user_in_process', array('id_rol' => $id_rol));

		return ( $query->num_rows() > 0 );
	}

	public function create($id_project, $data)
	{
		$this->db->trans_start();

		$this->db->insert('rol', $data);

		$id_rol = $this->db->insert_id();

		$rel_data = array(
			'id_project' 	=> $id_project,
			'id_rol'		=> $id_rol
			);

		$this->db->insert('rol_in_project', $rel_data);

		$this->db->trans_complete();

		return ( $this->db->trans_status() ) ? $id_rol : FALSE;
	}

	public function modify($rol_data)
	{
		$data = array(
			'name' 			=> $rol_data['name'],
			'plural_name' 	=> $rol_data['plural_name'],
			'default' 		=> $rol_data['default']
		);

		$this->db->where('id', $rol_data['id']);
		
		return ( $this->db->update('rol', $data) ); 
	}

	/**
	 * Delete the selected rol and his relationships in cascade
	 * If a populated rol is deleted, the users of the group are assigned to the rol 'colega'
	 * 
	 * @param integer $id_rol The id of the rol
	 * 
	 * @return boolean The result of the transaction
	 */
	
	public function delete($id_rol)
	{
		$this->db->trans_start();

		// Assign the users with the rol that will be deleted to the colegas rol
		$this->db->where('id_rol', $id_rol);
		$this->db->update('user_in_process', array('id_rol' => 3));

		// Delete the selected rol
		$this->db->delete('rol', array('id' => $id_rol));

		$this->db->trans_complete();

		return ( $this->db->trans_status() );
	}
}
/* End of file M_Rol.php */
/* Location: ./application/models/m_rol.php */