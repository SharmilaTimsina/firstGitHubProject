<?php
use Phalcon\Mvc\Url;

class SalesController extends ControllerBase
{ 
	private $click;
	
    public function initialize()
    {
        $this->tag->setTitle('sales stats');
        parent::initialize();
    }
	
	public function indexAction()
	{
		//get all countries list
		$countries=new Countries();
		$countries_list=$countries->getAllCountries();
		
		$this->view->setVar('countries',$countries_list);
		$this->view->pick('sales/sales');
		//$this->view->setVar('carriers',$carriers_list);
		
	}
	
	public function getCarriersAction(){
		$this->view->disable();
		if($this->request->isPost())
		{
			$countries=$this->request->getPost('countries');
			$carriers=new Carriers();
			$carriers_list=$carriers->getAllCarriers($countries);
			echo json_encode($carriers_list);
			
		}
	}
	
	
	
	public function getDataAction()
	{
		$this->view->disable();
		if($this->request->isPost())
		{
			$countries=$this->request->getPost('countries');
			$carriers=$this->request->getPost('carriers');
			$date=$this->request->getPost('date');
			$report=new Report();
			if($this->request->getPost('total')==1)
			{
				
				$report_data=$report->getData($countries,$carriers,$date,0);
				echo json_encode($report_data);
			}
			else
			{
				$report_data=$report->getTotal($countries,$carriers,$date,0);
				echo json_encode($report_data);
			}
				
		}
	}
		
	//for excel 
	public function createExcelAction() {
		$this->view->disable();
		if($this->request->isPost())
		{
			
			global $config;
			$loader = new \Phalcon\Loader();
			$loader->registerDirs(
			array(
				$config->application->libraryDir."PHPExcel/"
			)
			);

			$loader->register();
			$excel = new PHPExcel();
			$excel->setActiveSheetIndex(0);
			$excel->getActiveSheet()->setTitle('Sales Stats');
			$excel->getActiveSheet()->setCellValue('A1',"Parameters");
			$excel->getActiveSheet()->setCellValue('B1',"Clicks");
			$excel->getActiveSheet()->setCellValue('C1',"Conversions");
			$excel->getActiveSheet()->setCellValue('D1',"CPA");
			$excel->getActiveSheet()->setCellValue('E1',"CR(%)");
			$excel->getActiveSheet()->setCellValue('F1',"EPC");
			
			$countries=$this->request->getPost('countries');
			$carriers=$this->request->getPost('carriers');
			$date=$this->request->getPost('date');
			if(!empty($countries))
			{
			$report=new Report();
			$report_data=$report->getData($countries,$carriers,$date,1);
			$i=2;
			if(isset($report_data) && !empty($report_data))
			{	
				foreach($report_data as $value)
				{
					$affiliate=$value['affiliate'];
					$tp='';
					if($affiliate==0)
					{
						$tp='AD';		
					}
					else if($affiliate==1)
					{
						$tp='AF';
					}
					else if($affiliate==2)
					{
						$tp='MS';
					}
					$excel->getActiveSheet()->setCellValue('A'.$i,$tp);
					$excel->getActiveSheet()->setCellValue('B'.$i,$value['clicks']);
					$excel->getActiveSheet()->setCellValue('C'.$i,$value['conversions']);
					$excel->getActiveSheet()->setCellValue('D'.$i,$value['CPA']);
					$excel->getActiveSheet()->setCellValue('E'.$i,$value['CR']);
					$excel->getActiveSheet()->setCellValue('F'.$i,$value['EPC']);
					$i++;
				}
			
			//for total
			
				$report_data=$report->getTotal($countries,$carriers,$date,1);
				$tp='TOTAL';
				$excel->getActiveSheet()->setCellValue('A'.$i,$tp);
				$excel->getActiveSheet()->setCellValue('B'.$i,$report_data[0]['clicks']);
				$excel->getActiveSheet()->setCellValue('C'.$i,$report_data[0]['conversions']);
				$excel->getActiveSheet()->setCellValue('D'.$i,$report_data[0]['CPA']);
				$excel->getActiveSheet()->setCellValue('E'.$i,$report_data[0]['CR']);
				$excel->getActiveSheet()->setCellValue('F'.$i,$report_data[0]['EPC']);
		
				$br = 'sales_stats_'.rand(0,1000000);
				$file = $br.".csv";
				$objWriter = PHPExcel_IOFactory::createWriter($excel,'CSV'); //for exporting in csv format.. the header and objwriter is different based on the format you want to export
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$file.'"');
				// Write file to the browser
				$objWriter->save('php://output');
			}
			else
			{
				header('Location:http://mobisteinlp.com/sales_stats/sales');
				exit;
			}
			}
			
			else
			{
				header('Location:http://mobisteinlp.com/sales_stats/sales');
				exit;
			}
			
		}

	}
	
}




