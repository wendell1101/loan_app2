<?php
if (!$user->isAdmin() && !$user->isTreasurer() && !$user->isFinancialCommitee()) {
    message('danger', 'You are not authorized to access this page');
    redirect(BASE_URL . 'admin/dashboard.php');
}
