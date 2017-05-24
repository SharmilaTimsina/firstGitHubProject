<?php

class RatesController extends ControllerBase {

	public function initialize()
    {
        $this->tag->setTitle('Exchange Rates');
        parent::initialize();
    }
	
    public function indexAction() {
        try {
        	$rates = new Rates();
			$arrayRate = $rates->getRates();
       		$this->view->setVar("rateInfo", json_encode($arrayRate));
        } catch (Exception $e) {
            
        }
    }

    public function getRateThreeMonthsAction() {
        
        $data_array = array(
                'eur' => $this->request->get('eur'),
                'mxn' => $this->request->get('mxn'),
                'brl' => $this->request->get('brl'),
                'gbp' => $this->request->get('gbp')
            );

        $title = "ConversionRate";
        ini_set("memory_limit", "2048M");
        
        $columns = array('Date', 'Currency', 'Rate');

        $rates = new Rates(); 
        $data = $rates->getThreeMonthsInfo($data_array);
        
        try {
            $temp = tmpfile();
            $exColumns = "sep=|\n";
            for ($i = 0; $i < sizeof($columns); $i++) {
                $exColumns .= $columns[$i] . '|';
            }
            fwrite($temp, $exColumns . "\n");


            for ($j = 0; $j < sizeof($data); $j++) {
                $resultRow = '';
                foreach ($data[$j] as $k => $v) {
                    $resultRow .= $data[$j][$k] . "|";
                }

                fwrite($temp, $resultRow . "\n");
            }

            fseek($temp, 0);
            while (($buffer = fgets($temp, 4096)) !== false) {
                echo $buffer;
            }
            fclose($temp);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            $myContentDispositionHeader = 'Content-Disposition: attachment;filename="' . $title . '.csv"';
            header($myContentDispositionHeader);
            header('Expires: 0');
            header('Cache-Control: max-age=0');
            header('Pragma: public');
            exit();

        } catch (Exception $e) {
            Tracer::writeTo('E', $e->getMessage(), 'e', $e->getLine(), __METHOD__, __CLASS__);
            $this->generateErrorResponse();
        }
    }
}
