<?php
/**
 * Ecole - Subject Model
 * 
 * Handles DB Functionalities of the subject component
 * 
 * @author  Thomas A.P.
 * @copyright (c) 2015, Ecole. (http://projectecole.com)
 * @link http://projectecole.com
 */

class Subject_model extends CI_Model {

    
    /**
     * To get subject details  by given id
     * 
     * @param type $Subject_id
     * @return boolean or query result
     */
    public function get_details($Subject_id) {
        $query = $this->db->query("SELECT * FROM subject WHERE id='{$Subject_id}' LIMIT 1");
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    /**
     * to search subjects by given keyword
     * 
     * @param type $keyword
     * @param type $limit
     * @param type $offset
     * @return type
     */
   public function search_subjects($keyword, $limit = 1, $offset = null) {
        $sql = "SELECT * FROM subjects WHERE subject_name LIKE '%{$keyword}%' OR subject_code LIKE '%{$keyword}%' LIMIT {$limit} ";
        
        if (isset($offset)) {
            $sql .= " OFFSET {$offset}";
        }
        $query = $this->db->query($sql);
        return $query;
    }

   /**
    * Update subject details
    * 
    * @param type $update_data
    * @return boolean
    */
    public function update_info($update_data) {
               
        $query = "UPDATE users SET first_name='{$update_data['first_name']}', last_name='{$update_data["last_name"]}', profile_img='{$update_data['image']}' WHERE id='{$update_data['user_id']}'";
        $result = $this->db->query($query);

        if (!$result) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Add new subject into database
     * 
     * @param type $subject_data
     * @return boolean
     */
    public function create($subject_data) {


        $subjectname = $subject_data['subjectname'];
        $subjectcode = $subject_data['subjectcode'];
        $sectionid = $subject_data['sectionid'];
        $subjectinchargeid = $subject_data['subjectinchargeid'];
        
        $sql = "INSERT INTO subjects (subject_name, subject_code, section_id, subject_incharge_id) VALUES ('{$subjectname}', '{$subjectcode}', '{$sectionid}', '{$subjectinchargeid}')";

        

       

        $result = $this->db->query($sql);

        if (!$result) {
            return FALSE;
        } else {
            return $this->db->insert_id();
        }
    }

    /**
     * get all the subject resuls from subjects table
     * 
     * @param type $limit
     * @param type $offset
     * @return type Query result
     */
    public function get_subjects( $limit = 1, $offset = null) {
        $sql = "SELECT * FROM subjects  LIMIT {$limit}";
        //if ofset is not null
        if (isset($offset)) {
            $sql .= " OFFSET {$offset}";
        }
        $query = $this->db->query($sql);
        return $query;
    }

    /**
     * Get total row count of the subjects table .. this is needed for pagination
     * 
     * @return type Query result
     */
    public function get_subject_total() {
        $sql = "SELECT * FROM subjects";
        $query = $this->db->query($sql);

        return $query->num_rows();
    }
/*
 * 
 */
    /**
     * delete subject
     * 
     * @param type $id
     * @return boolean
     */
    public function delete($id) {
        $sql = "DELETE FROM subjects WHERE id='{$id}'";
        $query = $this->db->query($sql);

        if ($query) {
            return TRUE;
        }
    }
    

    /**
     * get all the subject resuls from subjects table 
     * 
     * @return type Query result
     */
    public function get_all_subjects() {
        $sql = "SELECT s.*,t.full_name FROM subjects s inner join teachers t on s.subject_incharge_id=t.id";
        //if ofset is not null
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    

    /**
     * get all the subject resuls from subjects table 
     * 
     * @param type $sub_id
     * @return type query result
     */
    public function get_subject_by_id($sub_id) {
        $sql = "SELECT *FROM subjects where id=$sub_id";
        //if ofset is not null
        
        $query = $this->db->query($sql);
        return $query->row();
    }    
    

    /**
     * Add new subject into database
     * 
     * @param type $subject_data
     * @return boolean
     */
    public function edit($subject_data) {


        $subjectid = $subject_data['subjectid'];
        $subjectinchargeid = $subject_data['subjectinchargeid'];
        
        //$sql = "INSERT INTO subjects (subject_name, subject_code, section_id, subject_incharge_id) VALUES ('{$subjectname}', '{$subjectcode}', '{$sectionid}', '{$subjectinchargeid}')";

        $sql="UPDATE subjects SET subject_incharge_id=$subjectinchargeid where id= $subjectid";
        

       

        $result = $this->db->query($sql);
      

        if (!$result) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

   
    
   
}
    