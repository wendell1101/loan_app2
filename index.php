<?php
require_once 'path.php';
require_once 'core.php';

include 'app/includes/main-header.php';

$loan = new Loan();
if (isset($_SESSION['id'])) {
    $allowed = $loan->checkifUserIsAllowedToLoan();
}


?>
<div class="hero-wrapper"></div>
<div class="wrapper">
    <div class="hero">
        <div class="container">
            <span class="text-center"><?php include 'app/includes/message.php'; ?></span>
            <?php if (isset($_SESSION['id'])) : ?>
                <?php if ($allowed) : ?>
                    <h2 class="text-white text-center bg-success rounded px-2">Notice: You are now allowed to loan!</h2>
                <?php else : ?>
                    <h2 class="text-white text-center bg-danger rounded px-2">Notice : You are not allowed to loan unless you have a fixed deposit!</h2>
                <?php endif ?>
            <?php endif ?>
            <div class="row" style="min-height:50vh">
                <div class="col-md-6">
                    <h1 class="text-white">FACULTY AND EMPLOYEE ASSOCIATION</h1>
                    <h3 class="text-success">Laguna State Polytechnic University - Siniloan Campus</h3>
                    <!-- <ul class="text-white">
                        <li class="loan-list">Let us know how big of a loan you need</li>
                        <li class="loan-list">Process your loans in easy steps</li>
                        <li class="loan-list">You’ll get your money quickly & on the best possible terms</li>
                    </ul> -->
                </div>
                <div class="col-md-6 align-items-center hero-left p-3">
                    <div>
                        <a href="select_loan.php" class="btn btn-success btn-block mb-2">Request your loan</a>
                        <small>
                            Faculty and Employee Association is an independent financial Loan Institute who are dedicated to helping members from all walks of life build secure, stress free financial futures
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--section2 -->
    <div class="section2">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mx-auto text-center w">
                    <h2 class="text-success">Here’s how to get an online loan FAST</h2>
                    <h4 class="text-success">All it takes is five simple steps.</h4>
                    <div class="row text-left">

                        <div class="col-md-6">
                            <div class="card-body mb-2 section2-li">
                                <li class="list-group-item mb-2 section2-li">
                                    <h5>1. Create an account.</h5>
                                    <p>Account approval need to undergo several process.</p>
                                    <ol class="text-left">
                                        <li>a. Approval of membership committee</li>
                                        <li>b. Approval of president</li>
                                        <li>c. Approval of treasurer/asst.treasurer/administrator</li>
                                    </ol>
                                </li>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body mb-2 section2-li">
                                <li class="list-group-item mb-2 section2-li">
                                    <h5>2. You need to have an initial fixed deposit to be allowed to request a loan</h5>
                                    <p>Go to treasurer and pay for initial fixed deposit it can be any amount</p>
                                </li>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body mb-2 section2-li">
                                <li class="list-group-item mb-2 section2-li">
                                    <h5>3. Choose a type of loan according to your need</h5>
                                    <p>You may choose between regular or character loan</p>
                                </li>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body mb-2 section2-li">
                                <li class="list-group-item mb-2 section2-li">
                                    <h5>4. Fill up the required information for your loan</h5>
                                    <p>You must provide the necessary informations such as loan type, amount, term and you also need to choose two comakers</p>
                                </li>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body mb-2 section2-li">
                                <li class="list-group-item mb-2 section2-li">
                                    <h5>5. Finalize your loan</h5>
                                    <p>You should review the details and if everything is fine, you can finalize your loan and wait for further notice</p>
                                </li>
                            </div>
                        </div>


                    </div>


                    <a href="select_loan.php" class="btn btn-success mx-auto btn-lg">Get Started</a>
                </div>
            </div>
        </div>
    </div>

    <!--section3 -->
    <div class="section3">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto text-white text-center">
                    <h1 class="text-success mb-5 ">Principles and Objectives</h1>
                    <p>
                        Section 1. The ASSOCIATION shall be an independent, non-stock and non-profit organization of the faculty members and employees of Laguna State Polytechnic University Siniloan Campus.
                    </p>
                    <p>
                        Section 2. The main objective of the ASSOCIATION is to promote the moral, social and economic well-being of all its members.
                    </p>
                    <p>
                        Section 3. The ASSOCIATION shall seek to promote solidarity, camaraderie, equity and professional relationship among its members.
                    </p>
                    <p>
                        Section 4. The ASSOCIATION shall promote collaboration and harmonious relationship among faculty, employees, students, the administration and the community particularly on matters concerning the welfare of its members.
                    </p>
                    <p>
                        Section 5. The ASSOCIATION shall observe transparency, dialogue and active communication within the organization.
                    </p>








                </div>
            </div>
        </div>
    </div>

</div>

<?php include 'app/includes/footer.php' ?>