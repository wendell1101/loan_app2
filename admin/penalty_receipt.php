<?php
require_once '../core.php';
$payment = new Payment();

$adminUser = new AdminUser();

$user = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $activePayment = $payment->getPayment($_GET['id']);
    $activeLoan = $payment->getLoan($activePayment->loan_id);



    $user = $adminUser->getUser($activeLoan->user_id);
    $activePenalty = $payment->getPenalty($activeLoan->id, $activePayment->id);
    $reason = strtoupper($activePenalty->reason);
    $payer = ucwords($activePayment->payment_by);

    $service_fee = formatDecimal($activePenalty->service_fee);
    // dump($service_fee);
    $amount = formatDecimal($activePenalty->amount);
    $totalAmount = formatDecimal($activePenalty->amount + $activePenalty->service_fee);

    $ref = $activePayment->reference_number;

    $date = shortDate($activePenalty->created_at);
    // print_r($activePayment);

    $output = '';
    $output .= '
    <p style="text-align:center; font-weight:bold">LSPU-SC FEA Savings and Credit Services</p>
    <p style="text-align:center">LSPU-Siniloan, Laguna</p><br><br>


    <br><br>
    Receipt Number:';
    $output .= $activePayment->reference_number .= '<br>
    Date: ';
    $output .= $date .=  '<br /><br />
Received from: ';
    $output .= $payer .= '<br><br />
    Reason for penalty: ';
    $output .= $reason .= '<br><br />
<table border="1" cellspacing="0" cellpadding="5">


    <tr>
     <td>Penalty Amount</td>
     <td> PHP ';
    $output .= $amount .= '</td>

  </tr>
     <tr>
        <td>Total Penalty Fee Paid</td>
        <td> PHP ';
    $output .= $totalAmount .= ' </td>
     </tr>
</table/>
<br><br>
<p>Received Payment: </p><br>
<span style="margin-left:100px">___________________</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


<span style="margin-right:50px">___________________</span>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span style="margin-left:100px!important">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Treasurer</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



<span style="margin-right:50px">Asst. Treasurer</span>
<br><br>
';
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
$content .= $output;
$content .= '<br><hr><br><br>';
$content .= $output;




$obj_pdf->writeHTML($content);
$obj_pdf->Output($ref . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+