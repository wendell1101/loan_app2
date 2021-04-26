<?php
require_once '../core.php';
$payment = new Payment();

$adminUser = new AdminUser();

$user = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $activePayment = $payment->getPayment($_GET['id']);
    if (!is_null($activePayment->user_id)) {
        $user = $adminUser->getUser($activePayment->user_id);
    }
    $payment_fixed_deposit = '0.00';
    $payment_saving = '0.00';
    $payment_saving_withdraw = '0.00';
    if ($activePayment->payment_fixed_deposit != 0) {
        $payment_fixed_deposit = $activePayment->payment_fixed_deposit;
    }
    if ($activePayment->payment_saving != 0) {
        $payment_saving = $activePayment->payment_saving;
    }
    if ($activePayment->payment_saving_withdraw != 0) {
        $payment_saving_withdraw = $activePayment->payment_saving_withdraw;
    }
    $activeLoan = '';
    $regular_loan_payment = '0.00';
    $character_loan_payment = '0.00';
    $total = '0.00';
    if (!is_null($activePayment->loan_id)) {
        $activeLoan = $payment->getLoan($activePayment->loan_id);
        $user = $adminUser->getUser($activeLoan->user_id);
        if ($activeLoan->loan_type_id == 1) {
            $regular_loan_payment = formatDecimal($activePayment->payment_amount);
            $total = $activePayment->payment_fixed_deposit + $activePayment->payment_saving + $activePayment->payment_amount;
            $total = formatDecimal($total);
        } else if ($activeLoan->loan_type_id == 3) {
            $character_loan_payment = formatDecimal($activePayment->payment_amount);
            $total = $activePayment->payment_fixed_deposit + $activePayment->payment_saving + $activePayment->payment_amount;
            $total = formatDecimal($total);
        }
    }
    $total = $activePayment->payment_fixed_deposit + $activePayment->payment_saving + $regular_loan_payment + $character_loan_payment + $payment_saving_withdraw;
    $total = formatDecimal($total);


    $fixed_deposit_amount = formatDecimal($adminUser->getUserDeposit($user->id));
    $savings_deposit_amount = formatDecimal($adminUser->getUserSavings($user->id));
    $total_regular_loan_balance = formatDecimal($adminUser->getUserRegularLoans($user->id));
    $total_character_loan_balance = formatDecimal($adminUser->getUserCharacterLoans($user->id));


    $payer = ucwords($activePayment->payment_by);

    $amount = formatDecimal($activePayment->payment_amount);
    $date = shortDate($activePayment->paid_at);
    // print_r($activePayment);
} else {
    redirect('payments.php');
}
require_once('../tcpdf/tcpdf.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF
{

    //Page header
    public function Header()
    {
        // Logo
        // $image_file = K_PATH_IMAGES . 'logo_dark.png';
        // $this->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        // $this->SetFont('helvetica', 'B', 20);
        // // Title
        // $this->Cell(0, 15, 'Official Receipt', 0, false, 'L', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);
$obj_pdf->SetTitle("Export HTML Table data to PDF using TCPDF in PHP");
$obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
$obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$obj_pdf->SetDefaultMonospacedFont('helvetica');
$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
$obj_pdf->setPrintHeader(false);
$obj_pdf->setPrintFooter(false);
$obj_pdf->SetAutoPageBreak(TRUE, 10);
$obj_pdf->SetFont('helvetica', '', 12);
$obj_pdf->AddPage();
$content = '';
$content .= '
<h4>Receipt Number: ';

$content .= $activePayment->reference_number .= '</h4>
Date: ';
$content .= $date
    .= '<br /><br />
Received from: ';
$content .= $payer .= '<br><br />
Amount paid: <br><br>
<table border="1" cellspacing="0" cellpadding="5">

     <tr>
        <td>Fixed Deposit</td>
        <td>PHP ';
$content .= $payment_fixed_deposit .= '</td>
        <td></td>
     </tr>
<tr>
     <td>Savings Deposit</td>
     <td> PHP ';
$content .= $payment_saving .= '</td>
     <td></td>
  </tr>



     <tr>
        <td>Total</td>
        <td colspan="2"> PHP ';
$content .= $total .= '</td>
     </tr>
</table/>

';
$content .= '<br><br>
Current Balance: <br><br>
<table border=".5" cellspacing="0" cellpadding="5">
    <tr>
        <td>Fixed Deposit</td>
        <td>PHP';
$content .= $fixed_deposit_amount .= '</td>
        <td colspan="2" style="text-align:center;"> Received Payment</td>
    </tr>
    <tr>
        <td>Savings Deposit</td>
        <td>PHP ';
$content .= $savings_deposit_amount .= '</td>
        <td colspan="2" style="text-align:center; border:none">____________</td>
    </tr>
    <tr>
        <td>Regular Loan</td>
        <td>PHP ';
$content .= $total_regular_loan_balance .= '</td>
        <td colspan="2" style="text-align:center; border:none">Treasurer</td>
    </tr>
    <tr>
        <td>Character Loan</td>
        <td>PHP ';
$content .= $total_character_loan_balance .= '</td>
        <td colspan="2" style="text-align:center">By:</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="2" style="text-align:center">_______________</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="2" style="text-align:center">Asst. Treasurer</td>
    </tr>

</table>

';


$obj_pdf->writeHTML($content);
$obj_pdf->Output('official_receipt.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+