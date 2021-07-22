<?php
ob_start();
require_once '../core.php';
require_once '../path.php';
require_once  '../app/includes/admin/header.php';
require_once '../app/middlewares/AuthMiddleware.php';
$auth = new Auth();
$auth->restrict();

$dashboard = new Dashboard();

$usersCount = $dashboard->getUsersCount();
$departmentsCount = $dashboard->getDepartmentsCount();
$paymentsCount = $dashboard->getPaymentsCount();
$loanTypesCount = $dashboard->getLoanTypesCount();
$loansCount = $dashboard->getLoansCount();
$loans = $dashboard->getLoans();
$departments = $dashboard->getDepartments();
$users = $dashboard->getUsers();


?>

<!-- Main content -->

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <?php include '../app/includes/message.php' ?>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo $usersCount; ?></h3>

                        <p>Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="admin_users.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo $loanTypesCount ?></h3>
                        <p>Types</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-list-alt"></i>
                    </div>
                    <a href="types.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo $loansCount ?></h3>
                        <p>Loans</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check"></i>
                    </div>
                    <a href="loans.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3><?php echo $paymentsCount ?></h3>

                        <p>Payments</p>
                    </div>
                    <div class="icon">
                        <i class="far fa-credit-card"></i>
                    </div>
                    <a href="payments.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->


            <!--<div class="col-md-6 mt-5">
                <h5 class="text-center">Loan Summary (<?php echo date("Y") ?>)</h5>
                <canvas id="lineChart" width="400" height="200"></canvas>
            </div> -->

        </div>
        <div class="row my-5">
            <div class="col-md-12 mb-2">
                <h2>Loans Per Department - Total Loan : <?php echo $totalLoanCount = $loansCount ?></h2>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <?php if ($loans) : ?>

                        <?php foreach ($loans as $loan) : ?>
                            <div class="col-md-6 p-3">
                                <span class="font-weight-bold"><?php echo $dashboard->getDepartment($loan->department_id)->name ?> -
                                    <!-- <?php echo $loanCount = $dashboard->getLoanCountPerDepartment($loan->department_id) ?><br> -->
                                    <?php echo $loanCount ?> <span><br>
                                        <!-- <?php echo $percent = $dashboard->getLoanCountPercentagePerDepartment($loanCount, $totalLoanCount) ?> % <span><br> -->
                                        <div class="progress">
                                            <div class="progress-bar
                            <?php if ($percent == 0) : ?>
                            text-dark w-100 bg-light border
                        <?php endif ?>
                        " role="progressbar" style="width: <?php echo $percent ?>%; " aria-valuenow="<?php echo $percent ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percent ?>%</div>
                                        </div>
                            </div>

                        <?php endforeach; ?>

                    <?php else : ?>
                        <h2 class="text-secondary ml-2">No department loan yet</h2>
                    <?php endif ?>
                </div>

            </div>
            <?php if ($loans) : ?>
                <div class="col-12 mb-2">
                    <h2>Regular Loans Per Term </h2>
                </div>
                <div class="col-md-12 ">
                    <div class="row">
                        <div class="col-md-6">

                            6 MONTHS: <?php echo $loanTermCount = $dashboard->getLoanTermCount(6) ?>
                            <!-- <?php echo $percent = $dashboard->getLoanCountPercentagePerTerm($loanTermCount, $totalLoanCount) ?> % -->
                            <div class="progress">
                                <div class="progress-bar
                            <?php if ($percent == 0) : ?>
                            text-dark w-100 bg-light border
                        <?php endif ?>
                        " role="progressbar" style="width: <?php echo $percent ?>%; " aria-valuenow="<?php echo $percent ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percent ?>%
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">

                            12 MONTHS: <?php echo $loanTermCount = $dashboard->getLoanTermCount(12) ?>
                            <!-- <?php echo $percent = $dashboard->getLoanCountPercentagePerTerm($loanTermCount, $totalLoanCount) ?> % -->
                            <div class="progress">
                                <div class="progress-bar
                            <?php if ($percent == 0) : ?>
                            text-dark w-100 bg-light border
                        <?php endif ?>
                        " role="progressbar" style="width: <?php echo $percent ?>%; " aria-valuenow="<?php echo $percent ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percent ?>%</div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">

                            18 MONTHS: <?php echo $loanTermCount = $dashboard->getLoanTermCount(18) ?>
                            <!-- <?php echo $percent = $dashboard->getLoanCountPercentagePerTerm($loanTermCount, $totalLoanCount) ?> % -->
                            <div class="progress">
                                <div class="progress-bar
                            <?php if ($percent == 0) : ?>
                            text-dark w-100 bg-light border
                        <?php endif ?>
                        " role="progressbar" style="width: <?php echo $percent ?>%; " aria-valuenow="<?php echo $percent ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percent ?>%</div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">

                            24 MONTHS: <?php echo $loanTermCount = $dashboard->getLoanTermCount(24) ?>
                            <!-- <?php echo $percent = $dashboard->getLoanCountPercentagePerTerm($loanTermCount, $totalLoanCount) ?> % -->
                            <div class="progress">
                                <div class="progress-bar
                            <?php if ($percent == 0) : ?>
                            text-dark w-100 bg-light border
                        <?php endif ?>
                        " role="progressbar" style="width: <?php echo $percent ?>%; " aria-valuenow="<?php echo $percent ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percent ?>%</div>
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <h2>Character Loans Per Term </h2>
                        </div>
                        <!-- 5 months -->
                        <div class="col-md-6">

                            5 MONTHS: <?php echo $loanTermCount = $dashboard->getLoanTermCount(5) ?>
                            <!-- <?php echo $percent = $dashboard->getLoanCountPercentagePerTerm($loanTermCount, $totalLoanCount) ?> % -->
                            <div class="progress">
                                <div class="progress-bar
                            <?php if ($percent == 0) : ?>
                            text-dark w-100 bg-light border
                        <?php endif ?>
                        " role="progressbar" style="width: <?php echo $percent ?>%; " aria-valuenow="<?php echo $percent ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percent ?>%
                                </div>
                            </div>
                        </div>

                    </div>


                <?php else : ?>
                    <!-- <h2 class="text-secondary ml-2">No departments yet</h2> -->
                <?php endif ?>
                </div>

        </div>
        <div class="col-md-6 mt-5 mx-auto">
            <h5 class="text-center">Loan Summary</h5>
            <canvas id="myChart" width="400" height="200"></canvas>
        </div>

    </div>
    </div>
</section>

<!-- /.content -->
<!-- /.content-wrapper -->
<?php include '../app/includes/admin/footer.php' ?>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script> -->
<script src="../assets/js/Chart.min.js"></script>
<script>
    // BAR GRAPH
    var ctx = document.getElementById('myChart').getContext('2d');
    var usersCount = "<?php echo $usersCount ?>";
    var typesCount = "<?php echo $loanTypesCount ?>";
    var loansCount = "<?php echo $loansCount ?>";
    var paymentsCount = "<?php echo $paymentsCount ?>";
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Types', 'Loans', 'Users', 'Payments'],
            datasets: [{
                label: 'Total Result',
                data: [typesCount, loansCount, usersCount, paymentsCount],
                backgroundColor: [
                    '#17a2b8',
                    '#28a745',
                    '#ffc107',
                    '#6c757d',
                ],
                borderColor: [
                    '#333',
                    '#333',
                    '#333',
                    '#333',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>

<script>
    // LINE GRAPH
    var ctx = document.getElementById('lineChart').getContext('2d');
    var jan = "<?php echo $data['jan'] ?>";
    var feb = "<?php echo $data['feb'] ?>";
    var mar = "<?php echo $data['mar'] ?>";
    var april = "<?php echo $data['april'] ?>";
    var may = "<?php echo $data['may'] ?>";
    var june = "<?php echo $data['june'] ?>";
    var july = "<?php echo $data['july'] ?>";
    var aug = "<?php echo $data['aug'] ?>";
    var sept = "<?php echo $data['sept'] ?>";
    var oct = "<?php echo $data['oct'] ?>";
    var nov = "<?php echo $data['nov'] ?>";
    var dec = "<?php echo $data['dec'] ?>";

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Total Result',
                data: [jan, feb, mar, april, may, june, july, aug, sept, oct, nov, dec],
                backgroundColor: [
                    '#17a2b8',
                    '#17a2b8',
                    '#17a2b8',
                    '#17a2b8',
                ],
                borderColor: [
                    '#333',
                    '#333',
                    '#333',
                    '#333',
                ],
                borderWidth: 1,
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>
<?php ob_flush(); ?>