<?php

ini_set("memory_limit", "8048M");

class SourcesapiController extends ControllerBase {

	public function initialize()
    {
        $this->tag->setTitle('Sources Report');
        parent::initialize();
    }
	
    public function indexAction() {
        try {
			$auth = $this->session->get('auth');

			$sources = new SourcesApi();

			$sourcesList = $sources->getSourcesByUser($auth['dailyControl']);
			
			$combo = '';
			foreach ($sourcesList as $source) {
				$combo .= "<option value='$source[id]'>$source[sourceName]-$source[id]</option>";
			}

            $countriesList = $sources->getCountriesByUser($auth['countries']);
            
            $comboC = '';
            foreach ($countriesList as $country) {
                $comboC .= "<option value='$country[id]'>$country[name]</option>";
            }

            $lastEdits = $sources->getLastEdits($auth['dailyControl']);

			$this->view->setVar("sourcesList", $combo);
            $this->view->setVar("countriesList", $comboC);
            $this->view->setVar("lastedits", $lastEdits);

        } catch (Exception $e) {
            
        }
    }

    public function downloadReportAction() {
    	$data_array = array(
			'date' => $this->request->getPost('date'),
			'sources' => $this->request->getPost('sources'),
            'countries' => $this->request->getPost('countries'),
            'minified' => $this->request->getPost('minified'),
            'format' => $this->request->getPost('format'),
            'blanks' => $this->request->getPost('blanks')
		);
        $auth = $this->session->get('auth');
        $userC = $auth['countries'];
        $userS = $auth['dailyControl'];

        if($userS == null && $userS == '') {
           $userS = '57,148,43,170,366,83,70,167,98,154,364,174,166,91,386,7,363,59,168,288'; 
        }

		$sources = new SourcesApi();
		$info = $sources->downloadReport($data_array, $userC, $userS);

        $info_result = array();
        if($data_array['minified'] == 'true') {
             $columns = array("dateInsert", "country", "sourceId", "sub_id", "revenue", "investment", "timeDate");
        } else {
             $columns = array("dateInsert", "campaignId", "campaignName", "country", "sourceId", "sourceName", "sub_id", "sub_source", "sub_camp", "sub_ban", "sub_rep", "bannerType", "status", "impressionsSource", "clicksSource", "clicksMobistein",  "ctr", "conversionsMobistein", "cr", "investment", "cpc", "cpm", "revenue",  "margin", "roi", "epc", "ecpa", "cpa", "spotname", "timeDate");
        }

        foreach ($info as $row) {
            
            $thousands = ''; 
            $decimal = '';
            if($data_array['format'] == 1) {
                 $thousands = ','; 
                 $decimal = '.';
            } else if($data_array['format'] == 0) {
                 $thousands = '.'; 
                 $decimal = ',';
            }

            if($data_array['minified'] == 'false') {
                $n1 = ($row['impressionsSource'] != 0) ? (floatval($row['clicksSource'])/floatval($row['impressionsSource'])) * 100 : 0;
    			//$numD1 = $this->numberOfDecimals($n1);
    			$row['ctr'] = number_format($n1,2,$decimal, $thousands) . '%';      

    			$n3 = ($row['clicksMobistein'] != 0) ? (floatval($row['revenue'])/floatval($row['clicksMobistein'])) : 0;
    			$numD3 = $this->numberOfDecimals($n3);
    			$row['epc'] = number_format($n3,$numD3,$decimal, $thousands);   
                
                $n2 = $row['revenue'] - $row['investment'];
                $numD2 = $this->numberOfDecimals($n2);
                $row['margin'] = number_format($n2,$numD2,$decimal, $thousands);  

                $n4 = ($row['investment'] != 0) ? (($row['revenue'] - $row['investment'])/$row['investment']) * 100 : 0;
                //$numD4 = $this->numberOfDecimals($n4);
                $row['roi'] = number_format($n4,2,$decimal, $thousands) . '%';      
        
    			$n5 = ($row['clicksSource'] != 0) ? (floatval($row['investment'])/floatval($row['clicksSource'])): 0;
    			$numD5 = $this->numberOfDecimals($n5);
    			$row['cpc'] = number_format($n5,$numD5,$decimal, $thousands);    

    			$n6 = ($row['conversionsMobistein'] != 0) ? (floatval($row['investment'])/floatval($row['conversionsMobistein'])) : 0;
    			$numD6 = $this->numberOfDecimals($n6);
    			$row['ecpa'] = number_format($n6,$numD6,$decimal, $thousands); 

    			$n7 = ($row['conversionsMobistein'] != 0) ? (floatval($row['revenue'])/floatval($row['conversionsMobistein'])) : 0;
    			$numD7 = $this->numberOfDecimals($n7);
    			$row['cpa'] = number_format($n7,$numD7,$decimal, $thousands);      

    			$n8 = ($row['clicksMobistein'] != 0) ? ((floatval($row['conversionsMobistein'])/floatval($row['clicksMobistein'])) * 100)  : 0;
    			//$numD8 = $this->numberOfDecimals($n8);
    			$row['cr'] = number_format($n8,2,$decimal, $thousands) . '%';  

    			$n8 = ($row['impressionsSource'] != 0) ? ((floatval($row['investment'])/floatval($row['impressionsSource'])) * 1000) : 0;
    			$numD8 = $this->numberOfDecimals($n8);
    			$row['cpm'] = number_format($n8,$numD8,$decimal, $thousands);      

    			$n10 = floatval($row['revenue']);
    			$numD10 = $this->numberOfDecimals($n10);
    			$row['revenue'] = number_format($n10,$numD10,$decimal, $thousands); 

    			$n9 = floatval($row['investment']);
                $numD9 = $this->numberOfDecimals($n9);
                $row['investment'] = number_format($n9,$numD9,$decimal, $thousands);  

    			$row['impressionsSource'] = number_format($row['impressionsSource'],0,$decimal, $thousands); 
    			$row['clicksSource'] = number_format($row['clicksSource'],0,$decimal, $thousands); 
    			$row['clicksMobistein'] = number_format($row['clicksMobistein'],0,$decimal, $thousands); 
    			$row['conversionsMobistein'] = number_format($row['conversionsMobistein'],0,$decimal, $thousands); 

              	           	
    			/*  
                $row['ctr'] = ($row['impressionsSource'] != 0) ? ($row['clicksSource']/$row['impressionsSource']) * 100 . '%' : 0;
                $row['margin'] = $row['revenue'] - $row['investment'];
                $row['epc'] =  ($row['clicksMobistein'] != 0) ? $row['revenue']/$row['clicksMobistein'] : 0;
                $row['roi'] =  ($row['investment'] != 0) ? ($row['margin']/$row['investment']) * 100 . '%' : 0;
                
                $row['cpc'] =  ($row['clicksSource'] != 0) ? $row['investment']/$row['clicksSource']: 0;
                $row['ecpa'] =  ($row['conversionsMobistein'] != 0) ? $row['investment']/$row['conversionsMobistein'] : 0;
                $row['cpa'] =  ($row['conversionsMobistein'] != 0) ? $row['revenue']/$row['conversionsMobistein'] : 0;
                $row['cr'] =  ($row['clicksMobistein'] != 0) ? ($row['conversionsMobistein']/$row['clicksMobistein']) * 100 . '%' : 0;
                $row['cpm'] =  ($row['impressionsSource'] != 0) ? ($row['investment']/$row['impressionsSource']) * 1000 : 0;
                $row['investment'] =  floatval($row['investment']);
    			*/

                $sub_explode = explode('_', $row['sub_id']);
                $row['sub_source'] = (isset($sub_explode[0])) ? $sub_explode[0] : '';
                $row['sub_camp'] = (isset($sub_explode[1])) ? $sub_explode[1] : '';
                $row['sub_ban'] = (isset($sub_explode[2])) ? $sub_explode[2] : '';
                $row['sub_rep'] = (isset($sub_explode[0]) && isset($sub_explode[1])) ? $sub_explode[0] . "_" . $sub_explode[1] : '';
        
                $properOrderedArray = array_merge(array_flip($columns), $row);
                array_push($info_result, $properOrderedArray);
            } else {
                $n10 = floatval($row['revenue']);
                $numD10 = $this->numberOfDecimals($n10);
                $row['revenue'] = number_format($n10,$numD10,$decimal, $thousands); 

                $n9 = floatval($row['investment']);
                $numD9 = $this->numberOfDecimals($n9);
                $row['investment'] = number_format($n9,$numD9,$decimal, $thousands); 

                $properOrderedArray = array_merge(array_flip($columns), $row);
                array_push($info_result, $properOrderedArray); 
            }
        }

        $title = "SourcesReport" . $data_array['sources'];
		$this->sendExcel($info_result, $columns, $title, $data_array['format']);
    }

    private function numberOfDecimals($value) {
	    if ((int)$value == $value) {
	        return 0;
	    } else if (! is_numeric($value)) {
	        return false;
	    }

	    return strlen($value) - strrpos($value, '.') - 1;
	}

    private function sendExcel($data, $columns, $title, $format) {
				
        try {
            $temp = tmpfile();
            $exColumns = "sep=|\n";
            //$exColumns = "";
            for ($i = 0; $i < sizeof($columns); $i++) {
                $exColumns .= $columns[$i] . '|';
            }
            fwrite($temp, $exColumns . "\n");


            for ($j = 0; $j < sizeof($data); $j++) {
                $resultRow = '';
                foreach ($data[$j] as $k => $v) {
                    if($format == 0) {
                    	$resultRow .= '="' . $data[$j][$k] . '"|';
                    } else {
                    	$resultRow .= $data[$j][$k] . '|';
                    }
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
