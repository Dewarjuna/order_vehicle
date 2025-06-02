<?php

/**
 * Authentication Model
 * 
 * Separates user authentication logic from business models to:
 * - Maintain clear security boundaries
 * - Allow for easy auth method changes
 * - Centralize credential handling
 */
class Auth_model extends CI_Model
{
    // Table used for authentication. Only modify here if schema changes.
    private $_table = "PK_user";
    // Key used to track logged-in user in session.
    const SESSION_KEY = 'user_id';

    /**
     * Validation rules for login form.
     */
    public function rules()
    {
        return [
            ['field' => 'username', 'label' => 'Username', 'rules' => 'required'],
            ['field' => 'password', 'label' => 'Password', 'rules' => 'required|max_length[255]']
        ];
    }

    /**
     * Validates credentials in database
     * Returns full user data to avoid multiple queries during session creation
     */
    public function login($username, $password)
    {
        $this->db->where('username', $username);
        $query = $this->db->get($this->_table);
        $user = $query->row();

        if (!$user) return FALSE;
        if ($password !== $user->password) return FALSE;

        $this->session->sess_regenerate(TRUE); // Prevent session fixation

        $this->session->set_userdata([
            self::SESSION_KEY => $user->id,
            'username' => $user->username, 
            'nama' => $user->nama,         
            'role' => $user->role
        ]);
        $this->_update_last_login($user->id);

        return $this->session->has_userdata(self::SESSION_KEY);
    }

    /**
     * Retrieve the currently logged-in user object, or null.
     */
    public function current_user()
    {
        if (!$this->session->has_userdata(self::SESSION_KEY)) return null;
        $user_id = $this->session->userdata(self::SESSION_KEY);
        $query = $this->db->get_where($this->_table, ['id' => $user_id]);
        return $query->row();
    }

    /**
     * Destroy session and redirect to login.
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }

    /**
     * Update last login timestamp for user (called on successful login).
     */
    private function _update_last_login($id)
    {
        $data = ['last_login' => date("Y-m-d H:i:s")];
        return $this->db->update($this->_table, $data, ['id' => $id]);
    }

    // CRUD user methods
    public function getusers_all()
    {
        return $this->db->get($this->_table)->result();
    }

    /**
     * Separate method for user retrieval
     * Prevents mixing of auth and general user operations
     */
    public function get_user_by_id($id)
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

    /**
     * Find user using employee number (nomor_karyawan).
     */
    public function get_user_by_nomor_karyawan($nomor_karyawan)
    {
        return $this->db->get_where('PK_user', ['nomor_karyawan' => $nomor_karyawan])->row();
    }

    /**
     * Insert new user record.
     */
    public function add_user($data)
    {
        return $this->db->insert('PK_user', $data);
    }
}