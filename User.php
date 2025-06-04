<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Load PHPExcel
require_once(APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->library('Mpdf_lib');
    }

    public function index()
    {
        if (!$this->session->userdata('emp_id')) {
            redirect('index.php/user/loginPage');
        }
        $data = [];
        $data['empData'] = $this->User_model->view_employee();
        $this->load->view('read_employee', $data);
    }

    public function add_edit_ops()
    {
        $emp_id = $this->input->post('edit_emp_id');
        $emp_details = $this->input->post();

        if ($emp_id) {
            $this->User_model->edit_employee($emp_details);
        } else {
            $this->form_validation->set_rules('name', 'Name', 'required|alpha');
            $this->form_validation->set_rules('department', 'Department', 'required');
            $this->form_validation->set_rules('role', 'Role', 'required');
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|numeric|min_length[10]|max_length[10]');
            $this->form_validation->set_rules('username', 'Username', 'required|alpha_dash');
            $this->form_validation->set_rules('password', 'Password', 'required|alpha_dash');

            if ($this->form_validation->run() == FALSE) {
                $data = [];
                $data['departments'] = $this->User_model->fetchDepartment();
                $this->load->view('user_form', $data);
            } else {
                // Validation passed: do DB operation
                $data = [
                    'name'         => $this->input->post('name'),
                    'department'   => $this->input->post('department'),
                    'role'         => $this->input->post('role'),
                    'phone_number' => $this->input->post('phone_number'),
                    'username'     => $this->input->post('username'),
                    'password'     => $this->input->post('password')
                ];
                $this->User_model->add_employee($data);
            }
        }
    }
    public function editEmployeeDetails($emp_id)
    {
        if (!$this->session->userdata('emp_id')) {
            redirect('index.php/user/loginPage');
        }
        $data = [];
        $data['fetchData'] = $this->User_model->fetchData($emp_id);
        $this->load->view('user_form', $data);
    }
    public function delete_employee($emp_id)
    {
        $delete_emp = $this->User_model->delete_employee($emp_id);
    }

    public function loginPage()
    {
        $this->load->view('login_form');
    }
    public function chkLogin()
    {
        $login_credentials = $this->input->post();
        $this->User_model->check_login();
    }
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('index.php/user/loginPage');
    }

    public function download_excel()
    {
        // Load model and get employee data
        $empData = $this->User_model->getAllEmployees();
        $spreadsheet = new PHPExcel();
        $sheet = $spreadsheet->getActiveSheet();
        // Set custom width for specific columns
        $sheet->getColumnDimension('A')->setWidth(5);   // For SNo.
        $sheet->getColumnDimension('B')->setWidth(25);  // For Name
        $sheet->getColumnDimension('C')->setWidth(20);  // For Department
        $sheet->getColumnDimension('D')->setWidth(20);  // For Role
        $sheet->getColumnDimension('E')->setWidth(25);  // For Username
        $sheet->getColumnDimension('F')->setWidth(20);  // For Phone Number

        $styleArray = [
            'borders' => [
                'allborders' => [ // Can also use 'outline', 'inside', etc.
                    'style' => PHPExcel_Style_Border::BORDER_THIN, // or BORDER_MEDIUM, etc.
                    'color' => ['argb' => 'FF000000'], // Black
                ],
            ],
        ];

        $sheet->getStyle('A1:F10')->applyFromArray($styleArray);


        // Set Headers
        $sheet->fromArray(['SNo.', 'Name', 'Department', 'Role', 'Username', 'Phone Number'], NULL, 'A1');

        // Fill Data
        $row = 2;
        $i = 1;
        foreach ($empData as $employee) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $employee['name']);
            $sheet->setCellValue('C' . $row, $employee['department']);
            $sheet->setCellValue('D' . $row, $employee['role']);
            $sheet->setCellValue('E' . $row, $employee['username']);
            $sheet->setCellValue('F' . $row, $employee['ph_num']);
            $row++;
            $i++;
        }

        $filename = "employee_list.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    public function download_pdf()
    {
        // $this->load->library('Mpdf_lib');
        $data['empData'] = $this->User_model->getAllEmployees();

        // Load view as string
        $html = $this->load->view('pdf_employee_data', $data, true);

        // Generate PDF
        $this->mpdf_lib->generate($html, 'Employee_List.pdf', 'I'); // 'I' for inline, 'D' for download
    }
    public function send_email()
    {
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'menonsaurav98@gmail.com',
            // 'smtp_pass' => 'your_app_password', // App Password, not Gmail login
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        );

        $this->email->initialize($config);

        $this->email->from('your_email@gmail.com', 'Your Name');
        $this->email->to('receiver@example.com');
        $this->email->subject('Test Email from CI3');
        $this->email->message('<h3>This is a test email</h3><p>Sent using CodeIgniter 3.</p>');

        if ($this->email->send()) {
            echo 'Email sent successfully!';
        } else {
            show_error($this->email->print_debugger());
        }
    }
}
