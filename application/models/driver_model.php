<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver_model extends CI_Model
{
    protected $table = 'PK_driver';

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    /**
     * Insert a new driver, adding timestamps.
     */
    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update driver details and update timestamp.
     */
    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Fetch all available drivers.
     */
    public function get_available()
    {
        return $this->db->get_where($this->table, ['status' => 'available'])->result();
    }

    /**
     * Set driver status to unavailable.
     */
    public function set_unavailable($id)
    {
        $data = [
            'status' => 'unavailable',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Set driver status to available.
     */
    public function set_available($id)
    {
        $data = [
            'status' => 'available',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}