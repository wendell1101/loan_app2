<?php
require_once '../core.php';


$adminUser = new AdminUser();

$user = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $activeLoan = $adminUser->getLoan($_GET['id']);
    $user = $adminUser->getUser($activeLoan->user_id);
    $department = $adminUser->getDepartment($user->department_id);



    $user_fullname = '               ' . ucfirst($user->firstname) . ' ' . ucfirst($user->lastname) . '                 ';
    $user_department = '               ' . ucfirst($department->name) . '                 ';

    $amount = formatDecimal($activeLoan->amount);
    $date = shortDate($user->created_at);
    // print_r($activePayment);
} else {
    redirect('loans.php');
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
$obj_pdf->setCellHeightRatio(1.2);
$obj_pdf->AddPage();
$content = '';
$content .= '
    <p style="text-align:center;font-weight:bold">LSPU SC - FACULTY AND EMPLOYEES ASSOCIATION </p>
    <p style="text-align:center;font-weight:bold">SAVINGS AND CREDIT SERVICES</p>
    <p style="text-align:center">Siniloan, Laguna</p><br>
    <p style="text-align:center">L   O  A  N  I N G	F  O  R M</p><br>

    <br><br>
    <span>Date:  <u>';
$content .= $date .= '</u></span><br><br>

<p style="text-indent: 50px">
    I <u>';
$content .= $user_fullname .= '</u>
hereby apply for Regular/Character Loan amounting to <u> PHP ';
$content .= $amount .= ' .</u>
<p>
<p style="text-indent: 50px">
I promise to pay the said amount to the Association in accordance with the terms and conditions directly to the FEA Treasurer/Assistant Treasurer every
15th and end of the month or not exceeding to 7 days thereafter.
</p>
<p style="text-indent: 50px">
I hereby agree that in the event, this obligation is not paid by me in accordance with the terms and conditions of this agreement, I hereby authorize the Treasurer to collect a surcharge of 10% of the unpaid amortization and a penalty of 24% per annum. I also agree that the loan will be deducted from the fixed and/or savings deposit with the association and will disqualify me
to borrow again for a period of one (1) fiscal year if I failed to pay my obligation.
</p><br>

';
$content .= '
<div style="text-align:center">
<p>
_____________________<br>
<span>Signature of Borrower
</p><br>
<span style="text-indent:0">
__________________________________<br><br>
Signature over printed name of Co-Maker<br><br>
</span><br>
<span>
__________________________________<br><br>
Signature over printed name of Co-Maker	</span><br><br><br>
</div>
';


$obj_pdf->writeHTML($content);
$obj_pdf->Output('official_receipt.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+