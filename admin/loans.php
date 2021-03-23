<?php
ob_start();
require_once '../path.php';
require_once '../core.php';
require_once  '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();


$adminLoan = new AdminLoan();


$loans = $adminLoan->index();
?>


<div class="container">
    <div class="card shadow">
        <div class="card-header d-flex align-items-center">
            <h4>Loans</h4>
            <a href="loan_create.php" class="btn btn-primary ml-auto"><i class="fas fa-plus text-light"></i></a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php if ($loans) : ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Transaction_id</th>
                                <th scope="col">Term</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Interest Rate</th>
                                <th scope="col">Total Amount</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include '../app/includes/message.php' ?>

                            <?php foreach ($loans as $key => $singleLoan) : ?>
                                <tr class="align-items-center">
                                    <th scope="row"><?php echo $key + 1 ?></th>
                                    <td>
                                        <a href="loan_detail.php?id=<?php echo $singleLoan->id ?>"><?php echo $singleLoan->transaction_id ?></a>
                                    </td>
                                    <td>
                                        <?php echo $singleLoan->term ?> months
                                    </td>

                                    <td>PHP <?php echo formatDecimal($singleLoan->amount) ?></td>
                                    <td><?php echo formatDecimal($adminLoan->getInterest($singleLoan->loan_type_id)->interest) ?> %</td>
                                    <td>PHP <?php echo formatDecimal($adminLoan->getTotalAmount($singleLoan->id)) ?></td>
                                    <td>
                                        <?php echo $singleLoan->status ?>
                                    </td>

                                    <td class="d-flex">
                                        <form action="loan_update.php" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $singleLoan->id ?>">
                                            <button type="submit" class="text-warning mr-3" style="border:none; background:none">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                        </form>
                                        <form action="loan_delete.php" method="POST">
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
                    <h2 class="text-secondary text-center">No User Yet</h2>
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