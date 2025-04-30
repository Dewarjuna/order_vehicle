<?php

class Auth_model extends CI_Model
{
	private $_table = "users";
	const SESSION_KEY = 'user_id';

	public function rules()
	{
		return [
			[
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'required'
			],
			[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'required|max_length[255]'
			]
		];
	}

	public function login($username, $password)
{
    $this->db->where('username', $username);
    $query = $this->db->get($this->_table);
    $user = $query->row();

    if (!$user) {
        return FALSE;
    }

    if ($password !== $user->password) {
        return FALSE;
    }

	$this->session->sess_regenerate(TRUE);

    $this->session->set_userdata([
        self::SESSION_KEY => $user->id,
        'username' => $user->username, 
        'nama' => $user->nama,         
        'role' => $user->role
    ]);
    $this->_update_last_login($user->id);

    return $this->session->has_userdata(self::SESSION_KEY);
}
	public function current_user()
	{
		if (!$this->session->has_userdata(self::SESSION_KEY)) {
			return null;
		}

		$user_id = $this->session->userdata(self::SESSION_KEY);
		$query = $this->db->get_where($this->_table, ['id' => $user_id]);
		return $query->row();
	}

		public function logout()
	{
		$this->session->sess_destroy();
		redirect('auth');
	}

	private function _update_last_login($id)
	{
		$data = [
			'last_login' => date("Y-m-d H:i:s"),
		];

		return $this->db->update($this->_table, $data, ['id' => $id]);
	}

	public function getusers_all()
{
    return $this->db->get('users')->result();
}

	public function getuser_by_id($id)
	{
		return $this->db->get_where($this->_table, ['id' => $id])->row();
	}

	public function update_user($id, $data)
	{
		return $this->db->update($this->_table, $data, ['id' => $id]);
	}

	public function delete_user($id)
	{
		return $this->db->delete($this->_table, ['id' => $id]);
	}
	public function add_user($data)
	{
		return $this->db->insert($this->_table, $data);
	}
}