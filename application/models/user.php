<?php

class User extends CI_Model {

    public function login($username, $password) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->where('password', MD5($password));
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    public function get_details($user_id) {
        $query = $this->db->query("SELECT first_name, last_name FROM users WHERE id='{$user_id}' LIMIT 1");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    public function change_password($user_id, $new_password) {
        $hashed_password = md5($new_password);
        $query = "UPDATE users SET password='{$hashed_password}' WHERE id='{$user_id}'";
        $result = $this->db->query($query);

        if (!$result) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function get_password_hash($user_id) {
        $query = $this->db->query("SELECT password FROM users WHERE id='{$user_id}'");
        $row = $query->row();
        return $row->password;
    }

    public function update_info($user_id, $first_name, $last_name) {
        $query = "UPDATE users SET first_name='{$first_name}', last_name='{$last_name}' WHERE id='{$user_id}'";
        $result = $this->db->query($query);

        if (!$result) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function create($new_user_data, $user_type) {
        $this->load->helper('date');
        $datestring = "%Y-%m-%d %h:%i:%s";
        $time = time();
        $created_at = mdate($datestring, $time);

        $username = $new_user_data['username'];
        $email = $new_user_data['email'];
        $first_name = $new_user_data['first_name'];
        $last_name = $new_user_data['last_name'];
        $password = $new_user_data['password'];
        $sql = "INSERT INTO users (username, password, first_name, last_name, email, user_type, created_at";

        if ($user_type === "A") {
            $sql .= ", superuser";
        }

        $sql .= ") VALUES ('{$username}', '{$password}', '{$first_name}', '{$last_name}', '{$email}', '{$user_type}', '{$created_at}'";

        if ($user_type === "A") {
            $sql .= ", 1";
        }

        $sql .= ")";

        $result = $this->db->query($sql);

        if (!$result) {
            return FALSE;
        } else {
            return $this->db->insert_id();
        }
    }

    public function get_user_list($keyword = '', $user_type, $limit=1, $offset=null) {
        $sql = "SELECT * FROM users WHERE id LIKE '%{$keyword}%' OR username LIKE '%{$keyword}%' ";
        $sql .= "OR first_name LIKE '%{$keyword}%' OR last_name LIKE '%{$keyword}%' OR email LIKE '%{$keyword}%' AND user_type = '{$user_type}' LIMIT {$limit}";
        if(isset($offset)){
            $sql .= " OFFSET {$offset}";
        }
        $query = $this->db->query($sql);
        return $query;
    }
    
    public function get_user_total(){
        $sql = "SELECT * FROM users";
        $query = $this->db->query($sql);
        
        return $query->num_rows();
    }

}
