<?php
ob_start();
require_once '../path.php';
require_once '../core.php';
require_once  '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';

if ($_SESSION['position_id'] == 2) {
    redirect('../index.php');
}


$auth = new Auth();
$auth->restrict();



$adminLoan = new AdminLoan();


$loans = $adminLoan->getPendingLoans();
?>


<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Approved Loans by comakers </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if ($loans) : ?>
                    <table class="table" id="myTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Transaction_id</th>
                                <th scope="col">Term</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Interest Rate</th>
                                <th scope="col">Total Balance</th>
                                <th scope="col">Approved</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Loan Form</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include '../app/includes/message.php' ?>

                            <?php foreach ($loans as $key => $singleLoan) : ?>
                                <tr class="align-items-center">
                                    <th scope="row"><?php echo $key + 1 ?></th>
                                    <td>
                                        <a class="text-info" href="loan_detail.php?id=<?php echo $singleLoan->id ?>"><?php echo $singleLoan->transaction_id ?></a>
                                    </td>
                                    <td>
                                        <?php echo $singleLoan->term ?> months
                                    </td>

                                    <td>PHP <?php echo formatDecimal($singleLoan->amount) ?></td>
                                    <?php
                                    $interest = $adminLoan->getInterest($singleLoan->loan_type_id)->interest;
                                    ?>
                                    <td><?php echo $interest ?> %</td>
                                    <td>PHP <?php echo formatDecimal($singleLoan->total_amount) ?></td>
                                    <td class="text-center">
                                        <?php if ($adminLoan->checkIfActiveFinancialCommitteeHasApproved($singleLoan->id) != 0) : ?>
                                            <i class="fas fa-check text-success"></i>
                                        <?php else : ?>
                                            <i class="fas fa-times text-danger"></i>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?php echo formatDate($singleLoan->created_at) ?>
                                    </td>
                                    <td>
                                        <a href="loan_form.php?id=<?php echo $singleLoan->id ?>" class="text-info">View</a>
                                    </td>

                                    <td class="d-flex">
                                        <form action="pending_financial_update.php" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $singleLoan->id ?>">
                                            <button type="submit" class="text-warning mr-3" style="border:none; background:none">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                        </form>
                                        <form action="pending_financial_delete.php" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $singleLoan->id ?>">
                                            <button type="submit" style="border:none; background:none">
                                                <i class="fas fa-trash text-danger"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            <?php endforeach; ?>

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