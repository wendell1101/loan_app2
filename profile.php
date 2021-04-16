<?php
ob_start();
require_once 'path.php';
require_once 'core.php';
require_once  'app/includes/header.php';
require_once 'app/middlewares/AuthMiddleware.php';

$auth = new Auth();
$auth->restrict();



$adminUser = new AdminUser();


$users = $adminUser->index('');
if (isset($_GET['id'])) {
    $user = $adminUser->getUser($_GET['id']);
    $total_amount = $adminUser->getUserDeposit($_GET['id']);
    $total_savings = $adminUser->getUserSavings($_GET['id']);
    $total_regular_loan_balance = formatDecimal($adminUser->getUserRegularLoans($_GET['id']));
    $total_character_loan_balance = formatDecimal($adminUser->getUserCharacterLoans($_GET['id']));
} else {
    redirect('admin_users.php');
}

?>


<div class="container mt-3">
    <div class="card shadow">
        <div class="card-header">
            <p>User Profile</p>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 border-bottom mb-2">
                    <h2 class="font-weight-normal mb-2 mb-3">Account Information</h2>
                    <p><small class="mr-2">Fixed Deposit:</small><b>PHP <?php echo formatDecimal($total_amount) ?></b></p>
                    <p><small class="mr-2">Savings:</small><b>PHP <?php echo formatDecimal($total_savings) ?></b></p>
                    <p><small class="mr-2">Regular Loan Balance:</small><b>PHP <?php echo $total_regular_loan_balance ?></b></p>
                    <p><small class="mr-2">Character Loan Balance:</small><b>PHP <?php echo $total_character_loan_balance ?></b></p>
                    <p><small class="mr-2">Account Number:</small><b><?php echo $user->account_number ?></b></p>
                    <p><small class="mr-2">Email:</small><b><?php echo $user->email ?></b></p>

                </div>
                <div class="col-md-6 border-bottom mb-2">
                    <h2 class="font-weight-normal mb-2">Personal Information</h2>
                    <p><small class="mr-2">Name:</small><b><?php echo ucfirst($user->firstname) . ' ' . ucfirst($user->middlename) . ' ' . ucfirst($user->lastname) ?></b></p>
                    <p><small class="mr-2">Gender:</small><b><?php echo $user->gender ?></b></p>
                    <p><small class="mr-2">Birth Date:</small><b><?php echo shortDate($user->birth_date) ?></b></p>
                    <p><small class="mr-2">Contact Number:</small><b><?php echo $user->contact_number ?></b></p>
                    <p><small class="mr-2">Home Address:</small><b><?php echo $user->home_address ?></b></p>
                    <p><small class="mr-2">Permanent Address:</small><b><?php echo $user->permanent_address ?></b></p>
                </div>


            </div>
            <div class="row mt-4">
                <div class="col-md-6 border-bottom mb-2">
                    <h2 class="font-weight-normal mb-2">Employment Information</h2>
                    <p><small class="mr-2">Position:</small><b><?php echo $adminUser->getPosition($user->position_id) ?></b></p>
                    <p><small class="mr-2">SG:</small><b><?php echo $user->sg ?></b></p>
                    <p><small class="mr-2">Employment Status:</small><b><?php echo $user->employment_status ?></b></p>
                    <p><small class="mr-2">Department:</small><b><?php echo $adminUser->getDepartment($user->department_id)->name ?></b></p>
                </div>
                <div class="col-md-6 border-bottom mb-2">
                    <h2 class="font-weight-normal mb-2">Other Information</h2>
                    <?php if ($user->name_of_spouse) : ?>
                        <p><small class="mr-2">Spouse Name:</small><b><?php echo $user->name_of_spouse ?></b></p>
                    <?php endif; ?>
                    <p><small class="mr-2">Fathers Name:</small><b><?php echo $user->fathers_name ?></b></p>
                    <p><small class="mr-2">Mothers Maiden Name:</small><b><?php echo $user->mothers_maiden_name ?></b></p>

                </div>
            </div>

        </div>
    </div>
</div>
<!-- /.content -->

<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include 'app/includes/footer.php' ?>
<?php ob_flush() ?>