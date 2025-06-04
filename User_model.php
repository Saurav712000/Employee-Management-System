<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function User_model()
    {
        $this->load->database();
        $this->load->library('encryption');
    }

    public function add_employee($data)
    {
        $name = $data["name"];
        $department = $data["department"];
        $role = $data["role"];
        $phone_number = $data["phone_number"];
        $username = $data["username"];
        $password = $data["password"];
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_insert = "INSERT INTO  employee(name,department,role,ph_num,username,password)VALUES('$name','$department','$role','$phone_number','$username','$hash_password')";
        $exec_sql = $this->db->query($sql_insert);

        echo "<script>
        alert('Data Inserted Successfully');
        window.location.href='" . base_url('index.php/User') . "';
        </script>";
    }
    public function view_employee()
    {
        $sql_read = "SELECT emp_id,name,department,role,ph_num,username,password FROM employee WHERE is_delete='0'";
        $sql_run = $this->db->query($sql_read)->result_array();
        return $sql_run;
    }

    public function fetchData($emp_id)
    {
        $sql_fetch = "SELECT e.emp_id,e.name,e.department,e.role,e.ph_num,d.dept_name,e.username,e.password,d.dept_id FROM employee As e INNER JOIN department As d ON e.department = d.dept_id WHERE emp_id='$emp_id'";
        $sql_exec = $this->db->query($sql_fetch)->row_array();
        return  $sql_exec;
    }
    public function edit_employee($emp_details)
    {
        $name = $emp_details["name"];
        $department = $emp_details["department"];
        $role = $emp_details["role"];
        $phone_number = $emp_details["phone_number"];
        $emp_id = $emp_details["edit_emp_id"];
        $sql_update = "UPDATE employee SET name='$name',department='$department',role='$role',ph_num ='$phone_number' WHERE emp_id='$emp_id'";
        $sql_run = $this->db->query($sql_update);
        if ($sql_run) {
            echo "<script>
            alert('Details Updated Successfully');
            window.location.href='" . base_url('index.php/User') . "';
            </script>";
        }
    }
    public function delete_employee($emp_id)
    {
        $sql_delete = "UPDATE employee SET is_delete='1' WHERE emp_id='$emp_id'";
        $sql_run = $this->db->query($sql_delete);
        if ($sql_run) {
            echo "<script>
            alert('Details deleted Successfully');
            window.location.href='" . base_url('index.php/User') . "';
            </script>";
        }
    }
    public function fetchDepartment()
    {
        $sql_department = "SELECT dept_id,dept_name FROM department";
        $sql_run = $this->db->query($sql_department)->result_array();
        return $sql_run;
    }
    public function check_login()
    {
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $sql_check = "SELECT emp_id,username,password FROM employee WHERE username = '$username' AND password = '$password'";
        $sql_run = $this->db->query($sql_check)->row_array();
        $this->session->set_userdata('emp_id', $sql_run['emp_id']);

        if ($sql_run > 0) {
            echo "<script>
                alert('Login Successfully')
                window.location.href='" . base_url('index.php/User') . "';
                </script>";
        } else {
            echo "<script>
                alert('Invalid Credentials')
                window.location.href='" . base_url('index.php/User/loginPage') . "';
                </script>";
        }
    }
    public function getAllEmployees()
    {
        $sql_read = "SELECT emp_id,name,department,role,ph_num,username,password FROM employee WHERE is_delete='0'";
        $sql_run = $this->db->query($sql_read)->result_array();
        return $sql_run;
    }
}
