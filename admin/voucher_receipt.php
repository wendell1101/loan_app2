<?php
require_once '../core.php';
$voucher = new Voucher();

$adminUser = new AdminUser();

$user = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $activeVoucher = $voucher->getVoucher($_GET['id']);
    // dump($activeVoucher);
    $date = shortDate($activeVoucher->created_at);
    $date2 = shortDate($activeVoucher->created_at);
    $voucher_amount = formatDecimal($activeVoucher->amount);
    $member =  ucfirst($voucher->getUser($activeVoucher->user_id)->firstname) . ' ' . ucfirst($voucher->getUser($activeVoucher->user_id)->lastname);

    $output = '';
    $output .= '
<h4>Receipt Number: ';

    $output .= $activeVoucher->receipt_number .= '</h4>
Date: ';
    $output .= $date
        .= '<br /><br />
Member: ';
    $output .= $member .= '<br><br />

<table border="1" cellspacing="0" cellpadding="5">

     <tr>
        <td>Voucher Amount</td>
        <td>PHP ';
    $output .= $activeVoucher->amount .= '</td>
        <td></td>
     </tr>

     <tr>
        <td>Total</td>
        <td colspan="2"> PHP ';
    $output .= $voucher_amount .= '</td>
     </tr>
</table/><br><br>


';
} else {
    redirect('vouchers.php');
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
$content .= '
<br><br><br>
<br><br><br>

';
$content .= $output;










$obj_pdf->writeHTML($content);
$obj_pdf->Output('official_receipt.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+