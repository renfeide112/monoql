<?php
Helix::load("External/PHPExcel/Classes/PHPExcel.php");
Helix::load("External/PHPExcel/Classes/PHPExcel/Writer/Excel5.php");
class Excel extends Object {
	
	public static function testPHPExcel() {
		$excel = new PHPExcel();
		$excel->setActiveSheetIndex(0);
		$excel->getActiveSheet()->setCellValue("A1","Testing PHP Excel Class");
		$excel->getActiveSheet()->setTitle("Test PHPExcel Sheet");
		$writer = new PHPExcel_Writer_Excel5($excel);
		$writer->save("c:/data/temp/PHPExcel Test 2.xls");
	}
	
}
?>
