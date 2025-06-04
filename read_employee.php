<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">Employee Management</h2>
            <div class="btn-group">
                <a href="<?= base_url(); ?>index.php/User/add_edit_ops" class="btn btn-success">Add Employee</a>
                <a href="<?= base_url(); ?>index.php/User/logout" class="btn btn-danger">Log Out</a>
                <a href="<?= base_url(); ?>index.php/User/download_excel" class="btn btn-info text-white">Download Excel</a>
                <a href="<?= base_url(); ?>index.php/User/download_pdf" class="btn btn-secondary" target="_blank">Download PDF</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>SNo.</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Username</th>
                        <th>Phone Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($empData as $employee) {
                        $emp_id = $employee['emp_id'];
                    ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $employee['name']; ?></td>
                            <td><?= $employee['department']; ?></td>
                            <td><?= $employee['role']; ?></td>
                            <td><?= $employee['username']; ?></td>
                            <td><?= $employee['ph_num']; ?></td>
                            <td>
                                <a href="<?= base_url(); ?>index.php/User/editEmployeeDetails/<?= $emp_id; ?>" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                <a href="<?= base_url(); ?>index.php/User/delete_employee/<?= $emp_id; ?>" class="btn btn-sm btn-outline-danger">Delete</a>
                            </td>
                        </tr>
                    <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>