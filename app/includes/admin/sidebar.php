<div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="https://ui-avatars.com/api/?name=<?php echo $user->getFullName() ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="" class="d-block"><?php echo $user->getFullName() ?></a>
        </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                   with font-awesome or any other icon font library -->

            <?php if ($_SESSION['position_id'] == 1) : ?>
                <li class="nav-item menu-open
                <?php echo (strpos(CURRENT_URL, 'dashboard') !== false) ? 'active' : '' ?>
                ">
                    <a href="<?php echo BASE_URL . '/admin/dashboard.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin_user') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/admin_users.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'type') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/types.php' ?>" class="nav-link ">
                        <i class="nav-icon fa fa-list-alt"></i>
                        <p>
                            Loan Types
                        </p>
                    </a>
                </li>

                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'department') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/departments.php' ?>" class="nav-link">
                        <i class="nav-icon far fa-building"></i>
                        <p>
                            Departments
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'deposit') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/fixed_deposits.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>
                            Fixed Deposits
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'saving') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/savings.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-piggy-bank"></i>
                        <p>
                            Savings
                        </p>
                    </a>
                </li>


                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin/loan') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/loans.php' ?>" class=" nav-link ">
                        <i class="nav-icon fas fa-money-check"></i>
                        <p>
                            Loans
                        </p>
                    </a>
                </li>

                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'payment') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/payments.php' ?>" class=" nav-link ">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            Payments
                        </p>
                    </a>
                </li>
                <!-- Voucher -->
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin/categor') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/categories_voucher.php' ?>" class=" nav-link d-flex align-items-center">
                        <i class="nav-icon fa fa-list-alt"></i>
                        <p>
                            Voucher Categories
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin/voucher') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/vouchers.php' ?>" class=" nav-link d-flex align-items-center">
                        <i class="nav-icon">
                            <img src="../assets/img/voucher_icon.svg" alt="" width="20px" height="25px">
                        </i>
                        <p>
                            Petty Cash Vouchers
                        </p>
                    </a>
                </li>

            <?php endif ?>

            <?php if ($_SESSION['position_id'] == 3) : ?>
                <li class="nav-item menu-open
                <?php echo (strpos(CURRENT_URL, 'dashboard') !== false) ? 'active' : '' ?>
                ">
                    <a href="<?php echo BASE_URL . '/admin/dashboard.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'pending_membership') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/pending_memberships_president.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Pending Memberships
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'declined_memberships') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/declined_memberships.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Declined Memberships
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin/pending_president') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/pending_president_loans.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Pending Loans
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin/declined_loans') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/declined_loans.php' ?>" class=" nav-link ">
                        <i class="nav-icon fas fa-money-check"></i>
                        <p>
                            Declined Loans
                        </p>
                    </a>
                </li>

                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'type') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/types.php' ?>" class="nav-link ">
                        <i class="nav-icon fa fa-list-alt"></i>
                        <p>
                            Loan Types
                        </p>
                    </a>
                </li>

                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'department') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/departments.php' ?>" class="nav-link">
                        <i class="nav-icon far fa-building"></i>
                        <p>
                            Departments
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'deposit') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/fixed_deposits.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>
                            Fixed Deposits
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
                    <?php echo (strpos(CURRENT_URL, 'saving') !== false) ? 'active' : '' ?>
                ">
                    <a href="<?php echo BASE_URL . 'admin/savings.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-piggy-bank"></i>
                        <p>
                            Savings
                        </p>
                    </a>
                </li>


                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin/loan') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/loans.php' ?>" class=" nav-link ">
                        <i class="nav-icon fas fa-money-check"></i>
                        <p>
                            Loans
                        </p>
                    </a>
                </li>
            <?php endif ?>
            <!-- membership committee -->
            <?php if ($_SESSION['position_id'] == 6) : ?>
                <li class="nav-item menu-open
                <?php echo (strpos(CURRENT_URL, 'dashboard') !== false) ? 'active' : '' ?>
                ">
                    <a href="<?php echo BASE_URL . '/admin/dashboard.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'pending_membership') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/pending_memberships.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Pending Memberships
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'declined_membership') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/declined_memberships.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Declined Memberships
                        </p>
                    </a>
                </li>
            <?php endif ?>



            <!-- treasurer or assistant treasurer -->
            <?php if (($_SESSION['position_id'] == 4) || ($_SESSION['position_id'] == 5)) : ?>
                <li class="nav-item menu-open
                <?php echo (strpos(CURRENT_URL, 'dashboard') !== false) ? 'active' : '' ?>
                ">
                    <a href="<?php echo BASE_URL . '/admin/dashboard.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin_user') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/admin_users.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'type') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/types.php' ?>" class="nav-link ">
                        <i class="nav-icon fa fa-list-alt"></i>
                        <p>
                            Loan Types
                        </p>
                    </a>
                </li>

                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'department') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/departments.php' ?>" class="nav-link">
                        <i class="nav-icon far fa-building"></i>
                        <p>
                            Departments
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'deposit') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/fixed_deposits.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>
                            Fixed Deposits
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'saving') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/savings.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-piggy-bank"></i>
                        <p>
                            Savings
                        </p>
                    </a>
                </li>


                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin/loan') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/loans.php' ?>" class=" nav-link ">
                        <i class="nav-icon fas fa-money-check"></i>
                        <p>
                            Loans
                        </p>
                    </a>
                </li>

                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'payment') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/payments.php' ?>" class=" nav-link ">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            Payments
                        </p>
                    </a>
                </li>
                <!-- Voucher -->
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin/categor') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/categories_voucher.php' ?>" class=" nav-link d-flex align-items-center">
                        <i class="nav-icon fa fa-list-alt"></i>
                        <p>
                            Voucher Categories
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin/voucher') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/vouchers.php' ?>" class=" nav-link d-flex align-items-center">
                        <i class="nav-icon">
                            <img src="../assets/img/voucher_icon.svg" alt="" width="20px" height="25px">
                        </i>
                        <p>
                            Petty Cash Vouchers
                        </p>
                    </a>
                </li>

            <?php endif; ?>


            <!-- Financial Commitee -->
            <?php if ($_SESSION['position_id'] == 7) : ?>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'deposit') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/fixed_deposits.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>
                            Fixed Deposits
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'saving') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/savings.php' ?>" class="nav-link">
                        <i class="nav-icon fas fa-piggy-bank"></i>
                        <p>
                            Savings
                        </p>
                    </a>
                </li>


                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin/pending_financial') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/pending_financial_loans.php' ?>" class=" nav-link ">
                        <i class="nav-icon fas fa-money-check"></i>
                        <p>
                            Pending Loans
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin/declined_loans') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/declined_loans.php' ?>" class=" nav-link ">
                        <i class="nav-icon fas fa-money-check"></i>
                        <p>
                            Declined Financial Loans
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'admin/declined_comaker_loans') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/declined_comaker_loans.php' ?>" class=" nav-link ">
                        <i class="nav-icon fas fa-money-check"></i>
                        <p>
                            Declined Comaker Loans
                        </p>
                    </a>
                </li>

                <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'payment') !== false) ? 'active' : '' ?>
            ">
                    <a href="<?php echo BASE_URL . 'admin/payments.php' ?>" class=" nav-link ">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            Payments
                        </p>
                    </a>
                </li>
            <?php endif; ?>


        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>