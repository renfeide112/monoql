<?php
class PDF extends Object {
	
	public static function testTCPDF() {
        // TCPDF is not included in the Helix repository anymore -- download and put in External folder to use
        Helix::load("External/TCPDF/config/lang/eng.php");
        Helix::load("External/TCPDF/tcpdf.php");
        
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Johnathan Hebert');
		$pdf->SetTitle('TCPDF Example 001');
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
		
		// ---------------------------------------------------------
		
		// set font
		$pdf->SetFont('times', 'BI', 16);
		
		// add a page
		$pdf->AddPage();
		
		// print a line using Cell()
		$pdf->Cell(0, 12, 'Johnathan Hebert Example 001 - €� èéìòù', 1, 1, 'C');
		
		// ---------------------------------------------------------
		
		//Close and output PDF document
		$pdf->Output('example_001.pdf', 'I');
	}
	
}
?>
