<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle_model extends CI_Model
{
    protected $table = 'PK_kendaraan';

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }
    public function get_available()
    {
        return $this->db->get_where($this->table, ['status' => 'available'])->result();
    }
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }
    /**
     * Set kendaraan status as unavailable (e.g. upon assignment).
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
     * Set kendaraan status as available (e.g. when returned).
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
    /**
     * Insert new kendaraan with timestamps.
     */
    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }
    /**
     * Update existing kendaraan details.
     */
    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    /**
     * Delete kendaraan by id.
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}