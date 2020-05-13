<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper PDF Helper
 *
 * This has some functions and access to the dompdf and fpdf classes which we use to
 * make PDFs on the fly. Woo!.	
 *
 * Version 1.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

function pdf_create_html($html, $filename='', $stream=TRUE, $orientation='portrait') 
{
	require_once("dompdf/dompdf_config.inc.php");

	$dompdf = new DOMPDF();
	$dompdf->set_paper('letter',$orientation);
	$dompdf->load_html($html);
	$dompdf->render();
	if ($stream) {
		$dompdf->stream($filename.".pdf");
	} else {
		return $dompdf->output();
	}
}

function pdf_create_bluecard($content, $filename='bluecard') 
{
	// Start the PDF
	require_once("fpdf/fpdf.php");
	require_once("fpdf/fpdi.php");
	$pdf = new FPDI('P','mm','Letter');

	// Format the input data into sets of 3 (3 cards per page)
	$sheets=array();
	$i = 0;
	$j = 'a';
	if ($content) {
		foreach ($content as $c) {
			$sheets[$i][$j] = $c;
			if ($j == 'c') {
				$j = 'a';
				$i++;
			} else {
				$j++;
			}
		}
	}

	// Setup
	$pdf->SetFont('Helvetica');
	$pdf->SetFontSize(10);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->setSourceFile('application/helpers/fpdf/source/bluecard.pdf');
	$back = $pdf->importPage(1);
	$front = $pdf->importPage(2);

	// Add the pages to the document
	if (!empty($sheets)) : foreach ($sheets as $c) :
		// Start the Front Page
		$pdf->AddPage();
		$pdf->useTemplate($front, 3, 3, 209);
		$offset = array(
			'a'=>0,
			'b'=>88,
			'c'=> 178
		);
		foreach (array('a','b','c') as $i) {
			if (!isset($c[$i])) continue;
			$pdf->SetXY(155	, $offset[$i]+22); $pdf->Write(0, $c[$i]['name']); // Name
			$pdf->SetXY(157	, $offset[$i]+26); $pdf->Write(0, $c[$i]['address']); // Address
			$pdf->SetXY(155	, $offset[$i]+30); $pdf->Write(0, $c[$i]['city']); // City
			$pdf->SetXY(163	, $offset[$i]+42.5); $pdf->Write(0, $c[$i]['unittype']); // Unit type
			$pdf->SetXY(196	, $offset[$i]+42.5); $pdf->Write(0, $c[$i]['number']); // Number
			$pdf->SetXY(156	, $offset[$i]+51); $pdf->Write(0, $c[$i]['district']); // District
			$pdf->SetXY(156	, $offset[$i]+56.5); $pdf->Write(0, $c[$i]['council']); // City
			switch ($c[$i]['unittype'])
			{
				case 'Crew': case 'Ship':
					$pdf->SetXY(189 , $offset[$i]+39); $pdf->Write(0, 'X'); // Scout type
				break;
				case 'Team':
					$pdf->SetXY(166	, $offset[$i]+39); $pdf->Write(0, 'X'); // Scout type
				break;
				default:
					$pdf->SetXY(146.5, $offset[$i]+39); $pdf->Write(0, 'X'); // Scout type
			}
		}

		// Start the Back Page
		$pdf->AddPage();
		$pdf->useTemplate($back, 3, 3, 209);
		$offset = array(
			'a'=>0,
			'b'=>88,
			'c'=> 178
		);
		foreach (array('a','b','c') as $i) {
			if (!isset($c[$i])) continue;
			// Left
			//$c[$i]['meritbadge'] = 'Fire Safety';
			$pdf->SetXY(13, $offset[$i]+19.5); $pdf->Write(0, $c[$i]['meritbadge']); // Merit Badge
			if ($c[$i]['completed']) {
				$pdf->SetXY(13, $offset[$i]+26.5); $pdf->Write(0, $c[$i]['cname']); // Counselor Name
				$pdf->SetXY(13, $offset[$i]+34); $pdf->Write(0, $c[$i]['caddress']); // Counselor Address
				$pdf->SetXY(13, $offset[$i]+41); $pdf->Write(0, $c[$i]['ccity']); // Counselor City
				$pdf->SetXY(13, $offset[$i]+48); $pdf->Write(0, $c[$i]['cphone']); // Counselor Phone
				$pdf->SetXY(47, $offset[$i]+55.5); $pdf->Write(0, $c[$i]['cdm']); // Completed date month
				$pdf->SetXY(55, $offset[$i]+55.5); $pdf->Write(0, $c[$i]['cdd']); // Completed date month
				$pdf->SetXY(61, $offset[$i]+55.5); $pdf->Write(0, $c[$i]['cdy']); // Completed date month
			}
			// Middle
			$pdf->SetXY(84, $offset[$i]+18.5); $pdf->Write(0, $c[$i]['name']); // Name
			$pdf->SetXY(84, $offset[$i]+35); $pdf->Write(0, $c[$i]['meritbadge']); // Merit Badge
			if ($c[$i]['completed']) {
				$pdf->SetXY(95, $offset[$i]+46.5); $pdf->Write(0, $c[$i]['cdm']); // Completed date month
				$pdf->SetXY(110, $offset[$i]+46.5); $pdf->Write(0, $c[$i]['cdd']); // Completed date month
				$pdf->SetXY(122, $offset[$i]+46.5); $pdf->Write(0, $c[$i]['cdy']); // Completed date month
			}
			// Right
			$pdf->SetXY(157, $offset[$i]+18.5); $pdf->Write(0, $c[$i]['name']); // Name
			$pdf->SetXY(180, $offset[$i]+32); $pdf->Write(0, $c[$i]['number']); // Number
			$pdf->SetXY(152, $offset[$i]+45); $pdf->Write(0, $c[$i]['meritbadge']); // Merit Badge

			$pdf->SetXY(147, $offset[$i]+65.5); // Remarks, wrapped
			$pdf->MultiCell(56,3.2,$c[$i]['remarks'],0,'L');

			if ($c[$i]['completed']) {
				$pdf->SetXY(167, $offset[$i]+56); $pdf->Write(0, $c[$i]['cdm']); // Completed date month
				$pdf->SetXY(182, $offset[$i]+56); $pdf->Write(0, $c[$i]['cdd']); // Completed date month
				$pdf->SetXY(194, $offset[$i]+56); $pdf->Write(0, $c[$i]['cdy']); // Completed date month
			}
			switch ($c[$i]['unittype'])
			{
				case 'Crew': case 'Ship':
					$pdf->SetXY(146.5, $offset[$i]+37); $pdf->Write(0, 'X'); // Scout type
				break;
				case 'Team':
					$pdf->SetXY(146.5, $offset[$i]+32); $pdf->Write(0, 'X'); // Scout type
				break;
				default:
					$pdf->SetXY(146.5, $offset[$i]+27); $pdf->Write(0, 'X'); // Scout type
			}
		}

	endforeach; endif;
	
	// Return our document
	return $pdf->Output($filename.'.pdf','D');
	//$pdf->Output();

}

