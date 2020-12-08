<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Employee_model (User Model)
 * User model class to get to handle user related data 

 */
class Employee_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function userListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.id, BaseTbl.email, BaseTbl.fname, BaseTbl.lname, BaseTbl.phone, BaseTbl.salary, Role.role, Company.name');
        $this->db->from('tbl_employee as BaseTbl');
        $this->db->join('tbl_company as Company', 'Company.id = BaseTbl.company','left');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.fname  LIKE '%".$searchText."%'
                            OR  BaseTbl.salary  LIKE '%".$searchText."%'
                            OR  BaseTbl.phone  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function userListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.id, BaseTbl.email, BaseTbl.fname, BaseTbl.lname, BaseTbl.phone, BaseTbl.salary, Role.role, Company.name');
        $this->db->from('tbl_employee as BaseTbl');
        $this->db->join('tbl_company as Company', 'Company.id = BaseTbl.company','left');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.fname  LIKE '%".$searchText."%'
                            OR  BaseTbl.phone  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        
        $this->db->order_by('BaseTbl.id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function getUserRoles()
    {
        $this->db->select('roleId, role');
        $this->db->from('tbl_roles');
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();
        
        return $query->result();
    }

    function getUserCompany(){
        $this->db->select('id, name');
        $this->db->from('tbl_company');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email, $userId = 0)
    {
        $this->db->select("email");
        $this->db->from("tbl_users");
        $this->db->where("email", $email);   
        $this->db->where("isDeleted", 0);
        if($userId != 0){
            $this->db->where("userId !=", $userId);
        }
        $query = $this->db->get();

        return $query->result();
    }
    
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewUser($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_employee', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId)
    {
        $this->db->select('id, fname,lname,phone, email, salary, roleId,company');
        $this->db->from('tbl_employee');
        $this->db->where('id', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUser($userInfo, $userId)
    {
        $this->db->where('id', $userId);
        $this->db->update('tbl_employee', $userInfo);
        
        return TRUE;
    }
    
    
    
    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser($userId, $userInfo)
    {
        $this->db->where('id', $userId);
        $this->db->update('tbl_employee', $userInfo);
        
        return $this->db->affected_rows();
    }


   

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfoById($userId)
    {
        $this->db->select('id, fname, lname,email, phone,salary,company, roleId');
        $this->db->from('tbl_employee');
        $this->db->where('id', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }

    /**
     * This function used to get user information by id with role
     * @param number $userId : This is user id
     * @return aray $result : This is user information
     */
    function getUserInfoWithRole($userId)
    {
        $this->db->select('BaseTbl.id, BaseTbl.email, BaseTbl.fname, BaseTbl.phone, BaseTbl.roleId, Roles.role,Company.name,salary');
        $this->db->from('tbl_employee as BaseTbl');
        $this->db->join('tbl_roles as Roles','Roles.roleId = BaseTbl.roleId');
        $this->db->join('tbl_company as Company','Company.id = BaseTbl.company');
        $this->db->where('BaseTbl.employee', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }

}

  