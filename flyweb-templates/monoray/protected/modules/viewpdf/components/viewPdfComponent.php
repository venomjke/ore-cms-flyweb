<?php
/**********************************************************************************************
*                            CMS Open Real Estate
*                              -----------------
*	version				:	1.2.0
*	copyright			:	(c) 2012 Monoray
*	website				:	http://www.monoray.ru/
*	contact us			:	http://www.monoray.ru/contact
*
* This file is part of CMS Open Real Estate
*
* Open Real Estate is free software. This work is licensed under a GNU GPL.
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
* Open Real Estate is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* Without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
***********************************************************************************************/

class viewPdfComponent extends CWidget {
	public $id;
	public $fromAdmin;
	public $modulePathBase;
	public $libraryPath;
	public $sitePath;
	public $pdfCachePath;
	public $filePrefix = 'listing_';
	
	public function __construct() {
		$this->preparePaths();
		$this->getViewPath();
	}
	
	public function run(){
		$this->preparePaths();
		$this->getViewPath();
		$this->generateFile($this->id, $this->fromAdmin);
	}
	
	public function getViewPath($checkTheme=false){
		return Yii::getPathOfAlias('application.modules.viewpdf.views');
	}
	  
	public function preparePaths() {
		$this->modulePathBase = dirname(__FILE__) . '/../';
		$this->libraryPath = $this->modulePathBase . '/library';
		$this->sitePath = Yii::app()->basePath . '/../';
		$this->pdfCachePath = $this->sitePath . '/uploads/pdfcache';
	}
		
	public function getFile($id) {
		$filePdf = $this->pdfCachePath.'/'.$this->filePrefix . $id . '.pdf';
				
		if (!file_exists($filePdf)) {
			$this->generateFile($id);
		}
		
		header('Content-Type: application/pdf');
		header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
		header('Pragma: public');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Content-Disposition: inline; filename='.$this->filePrefix.$id.'.pdf;');
		header('Content-Length: ' . filesize($filePdf));
		readfile($filePdf);
}
	
	public function generateFile($id, $fromAdmin = false) {		
		$apartment = Apartment::model()
				->cache(param('cachingTime', 1209600), Apartment::getFullDependency($id))
				->with('windowTo', 'comments', 'images')
				->findByPk($id);
		
		if (!$fromAdmin) {
			if (!$apartment->active) {
				$this->redirect(Yii::app()->homeUrl);
			}
			if ($apartment === null) {
				throw new CHttpException(404, 'The requested page does not exist.');
			}
		}

//      Вызывает рекурсию т.к. у Apartment afterSave
//		$dateFree = CDateTimeParser::parse($apartment->is_free_to, 'yyyy-MM-dd');
//		if ($dateFree && $dateFree < (time() - 60 * 60 * 24)) {
//			$apartment->is_special_offer = 0;
//			$apartment->update(array('is_special_offer'));
//		}
				
		require_once $this->libraryPath . '/tcpdf/config/lang/rus.php';
		require_once $this->libraryPath . '/tcpdf/tcpdf.php';
		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle('PDF apartment id: ' . $id);
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->AddPage();
		$pdf->SetFont('dejavusans', '', 8);
		$pdf->SetTextColor(90, 90, 90);

		$content = $this->render('viewpdf', array('model' => $apartment), true);

		$pdf->writeHTML($content, true, 0, true, 0);
		$pdf->lastPage();

		$file_name = $this->pdfCachePath.'/'.$this->filePrefix . $id . '.pdf';
		$pdf->Output($file_name, 'F');
	}
}