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
            <?php echo (strpos(CURRENT_URL, 'categor') !== false) ? 'active' : '' ?>
            ">
                <a href="<?php echo BASE_URL . 'admin/categories.php' ?>" class="nav-link ">
                    <i class="nav-icon fa fa-list-alt"></i>
                    <p>
                        Types
                    </p>
                </a>
            </li>
            <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'product') !== false) ? 'active' : '' ?>
            ">
                <a href="<?php echo BASE_URL . 'admin/products.php' ?>" class=" nav-link ">
                    <i class="nav-icon fas fa-user-tie"></i>
                    <p>
                        Positions
                    </p>
                </a>
            </li>
            <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'product') !== false) ? 'active' : '' ?>
            ">
                <a href="<?php echo BASE_URL . 'admin/products.php' ?>" class=" nav-link ">
                    <i class="nav-icon fas fa-money-check"></i>
                    <p>
                        Loans
                    </p>
                </a>
            </li>

            <li class="nav-item menu-open
            <?php echo (strpos(CURRENT_URL, 'reservation') !== false) ? 'active' : '' ?>
            ">
                <a href="<?php echo BASE_URL . 'admin/reservations.php' ?>" class="nav-link">
                    <i class="nav-icon far fa-building"></i>
                    <p>
                        Departments
                    </p>
                </a>
            </li>

        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>