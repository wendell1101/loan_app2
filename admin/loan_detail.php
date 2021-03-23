<?php
ob_start();
require_once '../path.php';
require_once '../core.php';
require_once  '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();


$adminLoan = new AdminLoan();

if (isset($_GET['id'])) {
    $loan = $adminLoan->getLoan($_GET['id']);
    $user = $adminLoan->getUser($loan->user_id);
    $type = $adminLoan->getLoanTypeName($loan->loan_type_id);
} else {
    redirect('loans.php');
}


?>


<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Loans</h4>
            <a href="loan_create.php" class="btn btn-primary ml-auto"><i class="fas fa-plus text-light"></i></a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if ($loan) : ?>
                    <table class="table">
                        <thead>
                            <tr>

                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Contact Number</th>
                                <th scope="col">Type</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include '../app/includes/message.php' ?>


                            <tr class="align-items-center">
                                <td>
                                    <?php echo $user->firstname . ' ' . $user->lastname ?>
                                </td>
                                <td>
                                    <?php echo $user->email ?>
                                </td>

                                <td><?php echo $user->contact_number ?></td>
                                <td><?php echo $type ?></td>
                                <td><?php echo $loan->status ?></td>
                                <td>
                                    <?php echo shortDate($loan->created_at) ?>
                                </td>
                            </tr>


                        </tbody>
                    </table>
                <?php else : ?>
                    <h2 class="text-secondary text-center">No Loan Yet</h2>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->

<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once BASE . '/app/includes/admin/footer.php' ?>
<?php ob_flush() ?>