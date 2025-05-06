<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle_model extends CI_Model
{
    protected $table = 'vehicles';

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_available()
    {
        return $this->db->get_where($this->table, ['status_kendaraan' => 'available'])->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function set_unavailable($id)
    {
        $data = [
            'status_kendaraan' => 'unavailable',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function set_available($id)
    {
        $data = [
            'status_kendaraan' => 'available',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    // Add if you want to insert or update vehicles
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}