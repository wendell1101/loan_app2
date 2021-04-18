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
    $comaker1_id = $loan->comaker1_id;
    $comaker2_id = $loan->comaker2_id;
    $comaker1_fullname = ucfirst($adminLoan->getUser($comaker1_id)->firstname)
        . ' ' . ucfirst($adminLoan->getUser($comaker1_id)->lastname);
    $comaker2_fullname = ucfirst($adminLoan->getUser($comaker2_id)->firstname)
        . ' ' . ucfirst($adminLoan->getUser($comaker2_id)->lastname);
} else {
    redirect('loans.php');
}


?>


<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Loan Detail</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <a href="loans.php"><i class="fas fa-arrow-circle-left text-success mb-2" style="font-size: 1.5rem"></i></a>
                <?php if ($loan) : ?>
                    <table class="table">
                        <thead>
                            <tr>

                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Contact Number</th>
                                <th scope="col">Type</th>
                                <th scope="col">Comakers</th>
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
                                <td>
                                    <span><?php echo $comaker1_fullname ?>,</span><br>
                                    <span><?php echo $comaker2_fullname ?></span>
                                </td>
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