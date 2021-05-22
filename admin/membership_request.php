<?php
require_once '../core.php';


$adminUser = new AdminUser();

$user = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $user = $adminUser->getUser($_GET['id']);
    $department = $adminUser->getDepartment($user->department_id);

    $reason1 = '';
    $reason2 = '';
    $reason3 = '';

    $reason1 =  $user->reason1;
    $reason2 =  $user->reason2;
    $reason3 =  $user->reason3;


    $user_fullname = '               ' . ucfirst($user->firstname) . ' ' . ucfirst($user->lastname) . '                 ';
    $user_department = '               ' . ucfirst($department->name) . '                 ';

    $date = shortDate($user->created_at);
    // print_r($activePayment);
} else {
    redirect('admin_users.php');
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
    <p style="text-align:center">Republic of the Philippines</p>
    <p style="text-align:center;font-weight:bold">Laguna State Polytechnic University</p>
    <p style="text-align:center">Province of Laguna</p><br>

    <p style="text-align:center;font-weight:bold">LSPU SC - FEA MEMBERSHIP APPLICATION LETTER</p>
    <br><br>
    <span>Date:  <u>';
$content .= $date .= '</u></span><br><br>
<span><b>JOSEFINA T. DE JESUS, Ed.D.</b></span><br>
<span>President</span><br>
<span>LSPU SC - FEA</span><br>
<span>Siniloan, Laguna</span>

<p>Dear Dr. De Jesus:</p><br>

<p>
    I would like to formally convey my intention and decision to apply as a member of the association,
    Laguna State Polytechnic University Siniloan Campus - Faculty and Employees Association (LSPU SC FEA).
    I am a Faculty/Employee at LSPU-Siniloan Campus with a Part-time/Regular/Casual/Job Order position,
    assigned at <u>';
$content .= $user_department .= '</u>.
</p><br>
<p>
The reason/s why I want to be a part of the association is/are as follows:
<p style="text-indent:50px"><u> - ';
$content .= $reason1 .= '

</u><br> <u> - ';
$content .= $reason2 .= '
</u><br> <u> - ';
$content .= $reason3 .= '</u> </p>

</p><br>
<p>Thank you for your consideration.</p>
<p>Kind Regards,</p>
<br>
<u>';
$content .= $user_fullname .= '</u>
<p>Signature over Printed Name</p>
<br> ';
// $content .= '
// <p style="text-align:center">Recommending Approval:</p><br>
// <span style="margin-left:100px">___________________</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
// &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
// &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
// &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
// &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
// &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
// &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
// &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

// <span style="margin-right:50px">___________________</span>
// <p style="text-align:center">___________________</p>
// <p style="text-align:center">Approved:</p><br><br>

// <p style="font-weight:bold; text-align:center">JOSEFINA T. DE JESUS, Ed.D.</p>
// <p style="text-align:center">LSPU SC - FEA President</p>




// ';


$obj_pdf->writeHTML($content);
$obj_pdf->Output('official_receipt.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+