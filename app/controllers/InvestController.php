<?php

class InvestController extends ControllerBase {

    public function indexAction() {
        /*
		$comboarray = array('59' => '<option value="59">Traffic Factory-59</option>',
            '168' => '<option value="168">Traffic Factory-168</option>',
            '219' => '<option value="219">Facebook-219</option>',
            '224' => '<option value="224">Facebook-224</option>',
			'325' => '<option value="325">Facebook-325</option>',
			'226' => '<option value="226">Facebook-226</option>',
            '232' => '<option value="232">Google-232</option>',
            '373' => '<option value="373">FacebookRic-373</option>',
            '381' => '<option value="381">FacebookTiago-381</option>',
			'343' => '<option value="343">FacebookTania</option>',
			'399' => '<option value="399">Facebook-andreJesus</option>',
			'401' => '<option value="401">Facebook-Martim</option>',
			'402' => '<option value="402">facebookmartim2</option>',
			'406' => '<option value="406">FacebookDuarte</option>',
			'407' => '<option value="407">FacebookVania</option>',
			'412' => '<option value="412">TESTETIAGO</option>'
		);
		
        $auth = $this->session->get('auth');
        $allowed_networks = explode(',', $auth['invest']);
        $combo = '';
        foreach ($allowed_networks as $net) {
			if (isset($comboarray[$net])) {
                $combo.=$comboarray[$net];
            }
        }
		*/
		$auth = $this->session->get('auth');
		$report = new Invest();
		$report_get_sources = $report->getSourcesUserInvest($auth);
		


		$combo = '';
		foreach ($report_get_sources as $source) {
			$combo .= "<option value='$source[id]'>$source[id]-$source[sourceName]</option>";
		}
		
        $this->view->setVar("combo", $combo);
		
		
		$this->fillFilter();		
    }

	public function fillFilter() {
		$report = new Invest();
		
		
		//countries
        $res = $report->getCountries();
		$countriesCombo = '';
		if(!empty($res)) {
			foreach ($res as $country) {
				if (isset($country['country'])) {
					$countriesCombo .= "<option value='" . $country['country'] . "'>" . $country['country'] . "</option>";
				}
			}
			$this->view->setVar("countriesCombo", $countriesCombo);
		}
		
		//sources
		$auth = $this->session->get('auth');
		$res2 = $report->getSources($auth);
		$sourcesCombo = '';
		if(!empty($res2)) {
			foreach ($res2 as $source) {
				if (isset($source['sourceName'])) {
					$sourcesCombo .= "<option value='" . $source['id'] . "'>" . $source['sourceName'] . "-" . $source['id'] . "</option>";
				}
			}
			$this->view->setVar("sourcesCombo", $sourcesCombo);
		}
		
        /*
		//domains
		$res3 = $report->getDomains();
		$domainsCombo = '';
		if(!empty($res3)) {
			foreach ($res3 as $domain) {
					$domainsCombo .= "<option value='" . $domain['id'] . "'>" . $domain['domain'] . "</option>";
			}
			$this->view->setVar("domainsCombo", $domainsCombo);
		}
		
		//campaigns
		$res4 = $report->getCampaigns();
		$campaignsCombo = '';
		if(!empty($res4)) {
			foreach ($res4 as $campaign) {
					$campaignsCombo .= "<option value='" . $campaign['njump'] . "'>" . $campaign['lpName'] . "</option>";
			}
			$this->view->setVar("campaignsCombo", $campaignsCombo);
		}
        */
	}
	
    public function reportAction() {
		
		error_reporting(0);

        $sdate = $this->request->getPost('sdate');
        $edate = $this->request->getPost('edate');
        $source = $this->request->getPost('sources');


		//mail('martim.barone@mobipium.com','TESTEMULTI',implode('__',$source));

        $report = new Invest();
        $res = $report->get_report($sdate, $edate, $source);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, array('subid', 'country', 'investment', 'impressions', 'clicks_source', 'clicks_mobistein', 'conversions', 'revenue', 'ctr', 'cpc', 'roi', 'cr', 'margin', 'source', 'date'));
        foreach ($res as $row) {
            fputcsv($output, $row);
        }
    }

    public function uploadAction() {
		
        // if($this->request->getPost('tdate')!=null and $this->request->getPost('source')!=null and $this->request->getPost('submit')!=null){
        $tdate = $this->request->getPost('tdate');
        $source = $this->request->getPost('source');
        $meta = Invest::findFirst('source = ' . $source);
        $rate = 1;
        if ($meta->currency != 'USD') {
            $currency = new Invest(); //taxa da currency a multiplicar
            $rate = $currency->get_rate($meta->currency);
        }
        $flocation = $this->upload_file();
        $prearray = $this->parse_csv($flocation, $tdate, $meta);
        $finalarray = $this->order_source_array($prearray, 'subid');
        $ultimatearray = $this->get_report_info($tdate, $source, $meta->country);
	   
        //print_r($finalarray);
        //o index array não esta com subid_Country print_r($finalarray);
        //esta ok print_r($ultimatearray);

        $this->mix_array($finalarray, $ultimatearray, $rate, $source, $tdate, $meta);
        //  }
    }

    private function upload_file() {

        if (isset($_FILES["file"])) {

            //if there was an error uploading the file
            if ($_FILES["file"]["error"] > 0) {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
            } else {

                //Store file in directory "upload" with the name of "uploaded_file.txt"
                $storagename = "uploaded_file.txt";
                move_uploaded_file($_FILES["file"]["tmp_name"], "/home/whatsapp/public_html/mobisteinreport.com/files/invest/" . $storagename);
                // echo "Stored in: " . "/home/whatsapp/public_html/mobisteinreport.com/files/invest/" . $_FILES["file"]["name"] . "<br />";
                return "/home/whatsapp/public_html/mobisteinreport.com/files/invest/" . $storagename;
            }
        } else {
            return 0;
        }
    }

    private function parse_csv($fpath, $tdate, $meta) {

        $row = 1;
        $source_array = array();


        $row = 1;

        if (($handle = fopen($fpath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($meta->line_skip >= $row) {
                    $row++;
                    continue;
                }

                $num = count($data);

                //echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;

                if ($num < 4) {
                    continue;
                }
                for ($c = 0; $c < $num; $c++) {
                    //echo $data[$c] . "->c=".$c."<br />\n";

                    if ($c == $meta->subid_pos) {

                        $ids = $this->fielder_subid($meta, $data[$c]);

                        if ($ids != 0) {
                            $source_array[$row]['subid'] = $ids[0];
                            $source_array[$row]['country'] = $ids[1];
                        } else {
							
                            unset($source_array[$row]);
                            continue;
                        }
                    }
                    if ($c == $meta->click_pos) {

                        $source_array[$row]['click_s'] = $data[$c];
                    }

                    if ($c == $meta->imp_pos) {
                        $source_array[$row]['imp'] = $data[$c];
                    }
                    if ($c == $meta->inv_pos) {
                        $source_array[$row]['inv'] = str_replace(",", ".", $data[$c]);
                    }
                    if ($c == $meta->country_position and $meta->country == 2) {

                        $ncountry = explode('_', $data[$c]);
                        if (strlen($ncountry[0]) == 2) {
                            $source_array[$row]['country'] = $ncountry[0];
                        } else {
                            unset($source_array[$row]);
                            continue;
                        }
                    }
                }
            }
			$fsource = array();
			
			foreach($source_array as $line){
				if($line['imp']!=0){
					$fsource[]=$line;
				}
			}


            fclose($handle);
           /* echo '<pre>';
              print_r($source_array);
              echo '<pre>';
              exit(); */
            return $fsource;
        }
    }

    private function fielder_subid($meta, $data) {
        error_reporting(0);

        $subid_block = '';
        if ($meta->sep1 != "" and $meta->sep2 != "") {
            $subid_block = $this->get_string_between($data, $meta->sep1, $meta->sep2);
        } elseif ($meta->sep1 != "" and $meta->sep2 == "") {
            $subid_block = explode($meta->sep1, $data);

            $subid_block = trim(urldecode($subid_block[1]));
        } else {
            $subid_block = $data;
        }

        if ($meta->encoded == 1) {
            $subid_block = trim(base64_decode($subid_block));
        }

        $subid_arr = explode('_', $subid_block);
		
        if (!is_numeric($subid_arr[$meta->explode_start + 1]) or ! is_numeric($subid_arr[$meta->explode_start + 2])) {
            return 0;
        }
        $exRef = $meta->explode_start;
        $country = 'xx';


        if ($meta->country == 1) {

            $country = $subid_arr[$exRef];
            $exRef = $exRef + 1;
            $subid_block = $country . '_' . $subid_arr[$exRef] . '_' . $subid_arr[$exRef + 1] . '_' . $subid_arr[$exRef + 2];
        } elseif($meta->country == 2) {
            $subid_block = $subid_arr[$exRef] . '_' . $subid_arr[$exRef + 1] . '_' . $subid_arr[$exRef + 2];
        }else{
			$country = $subid_arr[$exRef];
			$subid_block = $subid_arr[$exRef+1] . '_' . $subid_arr[$exRef + 2] . '_' . $subid_arr[$exRef + 3];
		}

	
        return array($subid_block, $country);
    }

    private function order_source_array($source_array, $field) {

        //print_r($source_array);

        $final_array = array();
        foreach ($source_array as $row) {
            $final_array[($row[$field] . strtoupper($row['country']))] = $row;
        }

        //print_r($final_array);
        //echo "LOOOOL2";

        //print_r($final_array);

        /* echo '<pre>';
          print_r($final_array);
          echo '<pre>'; */
        return $final_array;
    }

    private function get_string_between($string, $start, $end) {
        $string = " " . $string;
        $ini = strpos($string, $start);
        if ($ini == 0)
            return "";
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    private function get_report_info($date, $source, $metacountry) {
        $mob_array = array();
        $invest = new Invest();
        /*if ($date == date('Y-m-d')) {

            $click = $this->order_source_array($invest->get_clicks_today($source, $metacountry), 'sub_id');
            $conversion = $this->order_source_array($invest->get_conversions_today($source, $metacountry), 'sub_id');
            $mob_array = $this->build_daily_array($click, $conversion);
        } else {

            $mob_array = $this->order_source_array($invest->get_clicks_date($source, $date, $metacountry), 'sub_id');
        }*/
		 $mob_array = $this->order_source_array($invest->get_clicks_dateNexus($source, $date, $metacountry), 'sub_id');

        return $mob_array;
    }

    private function build_daily_array($click, $conversion) {

        $final_arr = array();
        foreach ($click as $row) {
            $final_arr[$row['sub_id']]['clicks_m'] = $row['clicks_m'];
            $final_arr[$row['sub_id']]['conversions'] = 0;
            $final_arr[$row['sub_id']]['revenue'] = 0;

            if (isset($conversion[$row['sub_id']])) {
                $final_arr[$row['sub_id']]['conversions'] = $conversion[$row['sub_id']]['conversions'];
                $final_arr[$row['sub_id']]['revenue'] = $conversion[$row['sub_id']]['revenue'];
            }
        }
        return $final_arr;
    }

    private function mix_array($source_array, $mob_array, $rate, $source, $date, $meta) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        //print_r($mob_array);

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
               
        $final_arr = array();
		$final_arr_main = array();
		
		$auth = $this->session->get('auth');
		$report = new Invest();
		$report_get_sources = $report->getSourcesUserInvest($auth);
        
		$sources_maisstream = array();
		foreach ($report_get_sources as $sourceID) {
			$sources_maisstream[] = $sourceID['id'];
		}

        //print_r($mob_array);

		//$sources_maisstream = array('219','224','232','325','226', '373', '381', '343','399', '401', '402', '406', '407', '412');

		$insertRequest = 'INSERT INTO I__investReport  (subid,country,investment,impressions,clicks_source,clicks_mobistein,conversions,revenue,ctr,cpc,roi,cr,margin,source,insert_date,insertTimestamp) VALUES ';
        

        foreach ($source_array as $row) {
            $invdollar = $row['inv'] * $rate;
            
			$final_arr[($row['subid'] . $row['country'])]['subid'] = $row['subid'];
            
			$final_arr[($row['subid'] . $row['country'])]['country'] = $row['country'];
            $final_arr[($row['subid'] . $row['country'])]['inv'] = $invdollar;
            $final_arr[($row['subid'] . $row['country'])]['impressions'] = $row['imp'];
            $final_arr[($row['subid'] . $row['country'])]['clicks_source'] = $row['click_s'];
            $final_arr[($row['subid'] . $row['country'])]['clicks_mobistein'] = 0;
            $final_arr[($row['subid'] . $row['country'])]['conversions'] = 0;
            $final_arr[($row['subid'] . $row['country'])]['revenue'] = 0;

            if (isset($mob_array[($row['subid'] . strtoupper($row['country']))])) {
                $final_arr[($row['subid'] . $row['country'])]['clicks_mobistein'] = $mob_array[($row['subid'] . strtoupper($row['country']))]['clicks_m'];
                $final_arr[($row['subid'] . $row['country'])]['conversions'] = $mob_array[($row['subid'] . strtoupper($row['country']))]['conversions'];
                $final_arr[($row['subid'] . $row['country'])]['revenue'] = $mob_array[($row['subid'] . strtoupper($row['country']))]['revenue'];

                unset($mob_array[($row['subid'] . strtoupper($row['country']))]);
            }
            $final_arr[($row['subid'] . $row['country'])]['ctr'] = ($row['imp'] > 0) ? (number_format($row['click_s'] / $row['imp'], 2)) : 0;
            $final_arr[($row['subid'] . $row['country'])]['cpc'] = ($row['click_s'] > 0) ? ($invdollar / $row['click_s']) /** 100*/ : 0;
            $final_arr[($row['subid'] . $row['country'])]['roi'] = ($invdollar > 0) ? number_format((($final_arr[($row['subid']  . $row['country'])]['revenue'] - $invdollar) / $invdollar) * 100, 2) : 0;
            $final_arr[($row['subid'] . $row['country'])]['cr'] = ($final_arr[($row['subid'] . $row['country'])]['clicks_mobistein'] > 0) ? number_format($final_arr[($row['subid'] . $row['country'])]['conversions'] / $final_arr[($row['subid']  . $row['country'])]['clicks_mobistein'], 2) * 100 : 0;
            $final_arr[($row['subid'] . $row['country'])]['margin'] = $final_arr[($row['subid'] . $row['country'])]['revenue'] - $invdollar;

            $subinsert = $row['subid'];
	
            if ($meta->country == 1) {
                $s = explode('_', $row['subid']);
                $subinsert = $s[1] . '_' . $s[2] . '_' . $s[3];
            }
			
			array_push($final_arr_main, $final_arr[($row['subid'] . $row['country'])]);
				
			if(!in_array( $source , $sources_maisstream)) {
				
				fputcsv($output, array('subid', 'country', 'investment', 'impressions', 'clicks_source', 'clicks_mobistein', 'conversions', 'revenue', 'ctr', 'cpc', 'roi', 'cr', 'margin', 'source', 'date'));
				
				$insertRequest.='("' . $subinsert . '","' . $row['country'] . '","' . $invdollar . '","' . $row['imp'] . '","' . $row['click_s'] . '","' . $final_arr[($row['subid'] . $row['country'])]['clicks_mobistein'] . '","' . $final_arr[($row['subid'] . $row['country'])]['conversions'] . '","' . $final_arr[($row['subid'] . $row['country'])]['revenue'] . '","' . $final_arr[($row['subid'] . $row['country'])]['ctr'] . '","' . $final_arr[($row['subid'] . $row['country'])]['cpc'] . '","' . $final_arr[($row['subid'] . $row['country'])]['roi'] . '","' . $final_arr[($row['subid'] . $row['country'])]['cr'] . '","' . $final_arr[($row['subid'] . $row['country'])]['margin'] . '","' . $source . '","' . $date . '","' . date('Y-m-d H:i:s') . '"),';
				fputcsv($output, array($subinsert, $row['country'], $invdollar, $row['imp'], $row['click_s'], $final_arr[($row['subid'] . $row['country'])]['clicks_mobistein'], $final_arr[($row['subid'] . $row['country'])]['conversions'], $final_arr[($row['subid'] . $row['country'])]['revenue'], $final_arr[($row['subid'] . $row['country'])]['ctr'], $final_arr[($row['subid'] . $row['country'])]['cpc'], $final_arr[($row['subid'] . $row['country'])]['roi'], $final_arr[($row['subid'] . $row['country'])]['cr'], $final_arr[($row['subid'] . $row['country'])]['margin'], $source, $date));
			}
        }

        foreach ($mob_array as $row) {
            $invdollar = 0;
            
            $final_arr[($row['sub_id'] . $row['country'])]['subid'] = $row['sub_id'];
            $final_arr[($row['sub_id'] . $row['country'])]['country'] = strtoupper($row['country']);
            $final_arr[($row['sub_id'] . $row['country'])]['inv'] = 0;
            $final_arr[($row['sub_id'] . $row['country'])]['impressions'] = 0;
            $final_arr[($row['sub_id'] . $row['country'])]['clicks_source'] = 0;
            
            $final_arr[($row['sub_id'] . $row['country'])]['clicks_mobistein'] = $mob_array[($row['sub_id'] . strtoupper($row['country']))]['clicks_m'];
            $final_arr[($row['sub_id'] . $row['country'])]['conversions'] = $mob_array[($row['sub_id'] . strtoupper($row['country']))]['conversions'];
            $final_arr[($row['sub_id'] . $row['country'])]['revenue'] = $mob_array[($row['sub_id'] . strtoupper($row['country']))]['revenue'];

            $final_arr[($row['sub_id'] . $row['country'])]['ctr'] = ($row['imp'] > 0) ? (number_format($row['click_s'] / $row['imp'], 2)) : 0;
            $final_arr[($row['sub_id'] . $row['country'])]['cpc'] = ($row['click_s'] > 0) ? ($invdollar / $row['click_s']) /** 100*/ : 0;
            $final_arr[($row['sub_id'] . $row['country'])]['roi'] = ($invdollar > 0) ? number_format((($final_arr[($row['subid']  . $row['country'])]['revenue'] - $invdollar) / $invdollar) * 100, 2) : 0;
            $final_arr[($row['sub_id'] . $row['country'])]['cr'] = ($final_arr[($row['sub_id'] . $row['country'])]['clicks_mobistein'] > 0) ? number_format($final_arr[($row['sub_id'] . $row['country'])]['conversions'] / $final_arr[($row['sub_id']  . $row['country'])]['clicks_mobistein'], 2) * 100 : 0;
            $final_arr[($row['sub_id'] . $row['country'])]['margin'] = $final_arr[($row['sub_id'] . $row['country'])]['revenue'] - $invdollar;

            $subinsert = $row['sub_id'];
    
            if ($meta->country == 1) {
                $s = explode('_', $row['sub_id']);
                $subinsert = $s[1] . '_' . $s[2] . '_' . $s[3];
            }
            
            array_push($final_arr_main, $final_arr[($row['sub_id'] . $row['country'])]);

            if(!in_array( $source , $sources_maisstream)) {
                
                 $insertRequest.='("' . $subinsert . '","' . $row['country'] . '","' . $invdollar . '","0","0","' . $final_arr[($row['sub_id'] . $row['country'])]['clicks_mobistein'] . '","' . $final_arr[($row['sub_id'] . $row['country'])]['conversions'] . '","' . $final_arr[($row['sub_id'] . $row['country'])]['revenue'] . '","' . $final_arr[($row['sub_id'] . $row['country'])]['ctr'] . '","' . $final_arr[($row['sub_id'] . $row['country'])]['cpc'] . '","' . $final_arr[($row['sub_id'] . $row['country'])]['roi'] . '","' . $final_arr[($row['sub_id'] . $row['country'])]['cr'] . '","' . $final_arr[($row['sub_id'] . $row['country'])]['margin'] . '","' . $source . '","' . $date . '","' . date('Y-m-d H:i:s') . '"),';
                
                fputcsv($output, array($subinsert, $row['country'], $invdollar, 0, 0, $final_arr[($row['sub_id'] . $row['country'])]['clicks_mobistein'], $final_arr[($row['sub_id'] . $row['country'])]['conversions'], $final_arr[($row['sub_id'] . $row['country'])]['revenue'], $final_arr[($row['sub_id'] . $row['country'])]['ctr'], $final_arr[($row['sub_id'] . $row['country'])]['cpc'], $final_arr[($row['sub_id'] . $row['country'])]['roi'], $final_arr[($row['sub_id'] . $row['country'])]['cr'], $final_arr[($row['sub_id'] . $row['country'])]['margin'], $source, $date));
            }
        }

		if(in_array( $source , $sources_maisstream)) {
		  
			fputcsv($output, array('subid', 'country', 'investment', 'impressions', 'clicks_source', 'clicks_mobistein', 'conversions', 'revenue', 'ctr', 'cpc', 'roi', 'cr', 'margin', 'source', 'platform', 'gender', 'title', 'description', 'url_cloudinary', 'date'));
			
			$array_subid = array();
			
			foreach ($final_arr_main as $element) {
				$sub_id = '"' . $element['subid'] . '"';
				array_push($array_subid, $sub_id);
			}
			
			$sub_ids = implode(",", $array_subid);
			
            //print_r($sub_ids);

			$inv = new Invest();
			$res = $inv->getInfoMainstream($sub_ids);
			
			$insertRequest = 'INSERT INTO I__investReport  (subid,country,investment,impressions,clicks_source,clicks_mobistein,conversions,revenue,ctr,cpc,roi,cr,margin,source, platform, os, gender, title, description, url_cloudinary,insert_date,insertTimestamp) VALUES ';
			
            //print_r($res);
            //print_r($final_arr_main);

			foreach ($res as $row) {
				foreach ($final_arr_main as $key => $value) {			
                    if($value['subid'] == $row['sub_id'] && $value['country'] == $row['country']) { //
						
                        $final_arr_main[$key]['title'] = $row['title'];
						$final_arr_main[$key]['description'] = $row['description'];
						$final_arr_main[$key]['gender'] = $row['genre'];
						$final_arr_main[$key]['url_cloudinary'] = $row['url_cloudinary'];
						$final_arr_main[$key]['platform'] = $row['platform'];
						$final_arr_main[$key]['os'] = $row['os'];
					}
				}
			}

			//print_r($res);
			
			foreach ($final_arr_main as $key => $row) {			
			
                $insertRequest.='("' . $row['subid'] . '","' . $row['country'] . '","' . $row['inv'] . '","' . $row['impressions'] . '","' . $row['clicks_source'] . '","' . $row['clicks_mobistein'] . '","' . $row['conversions'] . '","' . $row['revenue'] . '","' . $row['ctr'] . '","' . $row['cpc'] . '","' . $row['roi'] . '","' . $row['cr'] . '","' . $row['margin'] . '","' . $source . '",' . (($row['platform'] == "") ? 'NULL' : '"' . $row['platform'] . '"') . ',' . (($row['os'] == "") ? 'NULL' : '"' .$row['os'] . '"') . ','. (($row['gender'] == "") ? 'NULL' : '"' .$row['gender'] . '"') . ',' . (($row['title'] == "") ? 'NULL' : '"' .$row['title'] . '"') . ',' . (($row['description'] == "") ? 'NULL' : '"' .$row['description'] . '"') . ',' . (($row['url_cloudinary'] == "") ? 'NULL' : '"' .$row['url_cloudinary'] . '"') . ',"' . $date . '","' . date('Y-m-d H:i:s') . '"),';
				
				fputcsv($output, array($row['subid'], $row['country'], $row['inv'], $row['impressions'], $row['clicks_source'], $row['clicks_mobistein'], $row['conversions'], $row['revenue'], $row['ctr'], $row['cpc'], $row['roi'] , $row['cr'], $row['margin'], $source, $row['platform'], $row['gender'], $row['title'],$row['description'] , $row['url_cloudinary'] ,  $date));
			}
		}

		//echo $insertRequest;
		
        $sql_req = rtrim($insertRequest, ',');
		//mail('mfcbarone@gmail.com','testinvest',$sql_req);
        $inv = new Invest();
        $inv->insert_report($sql_req, $date, $source);
		
        return $final_arr;	
	
	}
	

	public function setfilterAction() {
		
		$data_array = array(
			'sdate' => $this->request->getPost('sdate'),
			'edate' => $this->request->getPost('edate'),
			'source' => $this->request->getPost('source'),
			'country' => $this->request->getPost('country'),
			//'campaign' => $this->request->getPost('campaign'),
			//'domain' => $this->request->getPost('domain'),
			'agregation' => $this->request->getPost('agregation'),
			'currentLimit' => $this->request->getPost('currentLimit'),
			'col' => $this->request->getPost('col'),
			'order' => $this->request->getPost('order'),
			'search' => $this->request->getPost('search'),
			'os' => $this->request->getPost('os'),
			'platform' => $this->request->getPost('platform'),
            'gender' => $this->request->getPost('gender')
		);
		
		$auth = $this->session->get('auth');
		if(($auth['userlevel'] == 2 && $auth['utype'] == 2) || ($auth['userlevel'] == 1) ) {
			
		} else {
			return;
		}
			
		//res[0] -> table, res[1] -> total pages
		$report = new Invest();
		$res = $report->getFilterContent($data_array);
		
		$tableHead = "";
		$tableTable = "";
		$tableFoot = "";
		$totalPages = $res[1];
		$tableFootValues = array();
        $trRowTable = 0;
		
		if (!empty($res[0]) && $data_array['agregation'] == '') {
			
			$tableHead = "<tr>
				<td><div col='1' class='headsort'>Group</div></td>
				<td><div col='2' class='headsort'>Ad</div></td>
				<td><div col='' class='headsort'>Source</div></td>
				<td><div col='' class='headNOTsort'>AD Name</div></td>
				<td><div col='12' class='headsort'>Country</div></td>
				<td><div col='21' class='headsort'>Gender</div></td>
				<td>Banner</td>
				<td><div col='6' class='headsort'>Impressions</div></td>
				<td><div col='10' class='headsort'>Clicks Source</div></td>
				<td><div col='5' class='headsort'>Revenue</div></td>
				<td><div col='4' class='headsort'>Investment</div></td>
				<td><div col='17' class='headsort'>Margin</div></td>
				<td><div col='18' class='headsort'>ROI</div></td>
				<td><div col='7' class='headsort'>CR</div></td>
				<td><div col='8' class='headsort'>CTR</div></td>
				<td><div col='9' class='headsort'>CPC</div></td>
			</tr>";
			
			foreach($res[0] as $line) {
				
				//print_r($line);
				
				$numberformat = array(
					'revenue' => number_format($line['revenue'], 1, '.', ''),
					'investment' => number_format($line['investment'], 1, '.', ''),
					'margin' => number_format($line['margin'], 1, '.', ''),
					'roi' => number_format($line['roi'], 1, '.', ''),
					'cr' => number_format($line['cr'], 1, '.', ''),
					'ctr' => number_format($line['ctr'] * 100, 1, '.', ''),
					'cpc' => number_format($line['cpc'], 3, '.', '')
				);
				
				$adname = '';
				if($line['url_cloudinary'] != "") {
					$adname = $line['groupz'] . "_" . $line['ad'];
						
					/*
					switch ($line['source']) {
						case "219":
								$adname = $line['groupz'] . "_" . $line['ad'];
								break;
						case "224":
								$adname = $line['groupz'] . "_" . $line['ad'];
								
								break;
						case "343":
								$adname = $line['source'] . "_" . $line['groupz'] . "_" . $line['ad'];
								break;
						case "399":
								$adname = $line['groupz'] . "_" . $line['ad'];
								break;
						case "401":
								$adname = $line['groupz'] . "_" . $line['ad'];
								break;
						case "402":
								$adname = $line['groupz'] . "_" . $line['ad'];
								break;
						case "406":
								$adname = $line['groupz'] . "_" . $line['ad'];
								break;
						case "407":
								$adname = $line['groupz'] . "_" . $line['ad'];
								break;
						default: 
								$adname = '';
								break;	
					}
					*/
				}
				
				
				$banner = ($line['url_cloudinary'] == "") ? $line['banner'] : $line['url_cloudinary'];
				
				$platf = '';
				switch($line['platform']) {
					case 0:
						$platf = 'Desktop';
						break;
					case 1:
						$platf = 'Mobile';
						break;
					case 2:
						$platf = 'Instagram';
						break;
					default: 
						$platf = '';
						break;
				}
				
				$os = '';
				switch($line['os']) {
					case 1:
						$os = 'Android';
						break;
					case 2:
						$os = 'IOS';
						break;
					default: 
						$os = '';
						break;
				}
				
				$gend = '';
				switch($line['gender']) {
					case '1':
						$gend = 'F';
						break;
					case '0':
						$gend = 'M';
						break;
					default: 
						$gend = '';
						break;
				}
				
				//print_r($line);
				
				$tableTable .= "<tr trNumber = $trRowTable>
								<td>$line[groupz]</td>
								<td>$line[ad]</td>
								<td>$line[source]</td>
								<td>$adname</td>
								<td>$line[country]</td>
								<td>$gend</td>
								<td><img class='imgTable' os='$os' p='$platf' t='$line[title]' d='$line[description]' data-toggle='modal' data-target='#modaldetails' src='$banner'></td>
								<td>$line[impressions]</td>
								<td>$line[clicks_source]</td>
								<td>$numberformat[revenue]</td>
								<td>$numberformat[investment]</td>
								<td>$numberformat[margin]</td>
								<td>$numberformat[roi]%</td>
								<td>$numberformat[cr]%</td>
								<td>$numberformat[ctr]%</td>
								<td>$numberformat[cpc]</td>									
							</tr>";
				
				/*
				(isset($tableFootValues['investment'])) ?  ($tableFootValues['investment'] += $line['investment']) : ($tableFootValues['investment'] = $line['investment']);
				(isset($tableFootValues['revenue'])) ?  ($tableFootValues['revenue'] += $line['revenue']) : ($tableFootValues['revenue'] = $line['revenue']);
				(isset($tableFootValues['impressions'])) ?  ($tableFootValues['impressions'] += $line['impressions']) : ($tableFootValues['impressions'] = $line['impressions']);
				(isset($tableFootValues['cr'])) ?  ($tableFootValues['cr'] += $line['cr']) : ($tableFootValues['cr'] = $line['cr']);
				(isset($tableFootValues['ctr'])) ?  ($tableFootValues['ctr'] += $line['ctr']) : ($tableFootValues['ctr'] = $line['ctr']);
				(isset($tableFootValues['cpc'])) ?  ($tableFootValues['cpc'] += $line['cpc']) : ($tableFootValues['cpc'] = $line['cpc']);
				(isset($tableFootValues['clicks_source'])) ?  ($tableFootValues['clicks_source'] += $line['clicks_source']) : ($tableFootValues['clicks_source'] = $line['clicks_source']);
				(isset($tableFootValues['conversions'])) ?  ($tableFootValues['conversions'] += $line['conversions']) : ($tableFootValues['conversions'] = $line['conversions']);
				//(isset($tableFootValues['margin'])) ?  ($tableFootValues['margin'] += $line['margin']) : ($tableFootValues['margin'] = $line['margin']);
				//(isset($tableFootValues['roi'])) ?  ($tableFootValues['roi'] += $line['roi']) : ($tableFootValues['roi'] = $line['roi']);
				*/
				
				$trRowTable++;
			}
			
			/*
			$cpcFoot = ($tableFootValues['clicks_source'] != 0) ? (number_format($tableFootValues['investment'] / $tableFootValues['clicks_source'], 3, '.', '')) : 0;
			$ctrFoot = ($tableFootValues['impressions'] != 0) ? (number_format(($tableFootValues['clicks_source'] / $tableFootValues['impressions']) * 100, 2, '.', '')) : 0;
			$crFoot = ($tableFootValues['clicks_source'] != 0) ? (number_format(($tableFootValues['conversions'] / $tableFootValues['clicks_source']) * 100, 2, '.', '')) : 0;
			$margin = number_format($tableFootValues['revenue'] - $tableFootValues['investment'], 2, '.', '');
			$roi = ($tableFootValues['investment'] != 0) ? (number_format(($margin / $tableFootValues['investment']) * 100, 2, '.', '')) : 0;
			$revenueFoot = number_format($tableFootValues['revenue'], 2, '.', '');
			$investmentFoot = number_format($tableFootValues['investment'], 2, '.', '');
			$tableFoot = "<tr>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>$tableFootValues[impressions]</td>
							<td>$tableFootValues[clicks_source]</td>
							<td>$revenueFoot</td>
							<td>$investmentFoot</td>
							<td>$margin</td>
							<td>$roi%</td>
							<td>$crFoot%</td>
							<td>$ctrFoot%</td>
							<td>$cpcFoot</td>	
						</tr>";
			*/
			
			
			$arr = array(
				'tableHead' => $tableHead,
				'tableBody' => $tableTable,
				'tableFoot' => $tableFoot,
				'totalPages' => $totalPages
			);
			
			echo json_encode($arr);
			
		} else if (empty($res[0])) {
			$arr = array(
				'tableHead' => "<tr>no resuts</tr>",
				'tableBody' => "<tr>no resuts</tr>",
				'tableFoot' => "<tr>no resuts</tr>",
				'totalPages' => "0",
			);
			
			echo json_encode($arr);
			

		} else if(!empty($res[0]) && $data_array['agregation'] != '') {
			$array_group = array(
				'1' => "<td><div col='12' class='headsort'>Country</div></td>",
				'2' => "<td><div col='16' class='headsort'>Source</div></td>",
				'3' => "<td><div col='13' class='headsort'>Domain</div></td>",
				'4' => "<td><div col='14' class='headsort'>Njump</div></td>",
				'5' => "<td><div col='15' class='headsort'>Day</div></td>",
				'6' => "<td><div col='20' class='headsort'>Platform</div></td>",
				'7' => "<td><div col='21' class='headsort'>Gender</div></td>"
			);
			
			$agregationHead = '';
			$agregationFoot = '';
			$agreagtionBody = '';
			foreach($data_array['agregation'] as $line) {
				$agregationHead .= $array_group[$line];	
				$agregationFoot .= '<td>-</td>';
			}
			
			$tableHead = "<tr>"
				. $agregationHead .				
				"<td><div col='6' class='headsort'>Impressions</div></td>
				<td><div col='10' class='headsort'>Clicks Source</div></td>
				<td><div col='5' class='headsort'>Revenue</div></td>
				<td><div col='4' class='headsort'>Investment</div></td>
				<td><div col='17' class='headsort'>Margin</div></td>
				<td><div col='18' class='headsort'>ROI</div></td>
				<td><div col='7' class='headsort'>CR</div></td>
				<td><div col='8' class='headsort'>CTR</div></td>
				<td><div col='9' class='headsort'>CPC</div></td>
			</tr>";
			
			foreach($res[0] as $line) {
				
				$infoArray = $this->tableInfo($line['domain'], $line['njump']);
				
                $gend = '';
                switch($line['gender']) {
                    case '1':
                        $gend = 'F';
                        break;
                    case '0':
                        $gend = 'M';
                        break;
                    default: 
                        $gend = '';
                        break;
                }

                $platf = '';
                switch($line['platform']) {
                    case 0:
                        $platf = 'Desktop';
                        break;
                    case 1:
                        $platf = 'Mobile';
                        break;
                    case 2:
                        $platf = 'Instagram';
                        break;
                    default: 
                        $platf = '';
                        break;
                }
            
				$array_group = array(
					'1' => "<td>$line[country]</td>",
					'2' => "<td>$line[source]</td>",
					'3' => "<td>" . $infoArray[0] . "</td>",
					'4' => "<td>" . $infoArray[1] . "</td>",
					'5' => "<td>$line[insertdate]</td>",
					'6' => "<td>$platf</td>",
					'7' => "<td>$gend</td>"
				);

				$agreagtionBody = '';
				foreach($data_array['agregation'] as $agre) {
					$agreagtionBody .= $array_group[$agre];
				}
				
				$numberformat = array(
					'revenue' => number_format($line['revenue'], 1, '.', ''),
					'investment' => number_format($line['investment'], 1, '.', ''),
					'margin' => number_format($line['margin'], 1, '.', ''),
					'roi' => number_format($line['roi'], 1, '.', ''),
					'cr' => number_format($line['cr'], 1, '.', ''),
					'ctr' => number_format($line['ctr'] * 100, 1, '.', ''),
					'cpc' => number_format($line['cpc'], 3, '.', '')
				);
				
				$tableTable .= "<tr trNumber = $trRowTable>"
								. $agreagtionBody .
								"<td>$line[impressions]</td>
								<td>$line[clicks_source]</td>
								<td>$numberformat[revenue]</td>
								<td>$numberformat[investment]</td>
								<td>$numberformat[margin]</td>
								<td>$numberformat[roi]%</td>
								<td>$numberformat[cr]%</td>
								<td>$numberformat[ctr]%</td>
								<td>$numberformat[cpc]</td>					
							</tr>";
				
				/*
				(isset($tableFootValues['investment'])) ?  ($tableFootValues['investment'] += $line['investment']) : ($tableFootValues['investment'] = $line['investment']);
				(isset($tableFootValues['revenue'])) ?  ($tableFootValues['revenue'] += $line['revenue']) : ($tableFootValues['revenue'] = $line['revenue']);
				(isset($tableFootValues['impressions'])) ?  ($tableFootValues['impressions'] += $line['impressions']) : ($tableFootValues['impressions'] = $line['impressions']);
				(isset($tableFootValues['cr'])) ?  ($tableFootValues['cr'] += $line['cr']) : ($tableFootValues['cr'] = $line['cr']);
				(isset($tableFootValues['ctr'])) ?  ($tableFootValues['ctr'] += $line['ctr']) : ($tableFootValues['ctr'] = $line['ctr']);
				(isset($tableFootValues['cpc'])) ?  ($tableFootValues['cpc'] += $line['cpc']) : ($tableFootValues['cpc'] = $line['cpc']);
				(isset($tableFootValues['clicks_source'])) ?  ($tableFootValues['clicks_source'] += $line['clicks_source']) : ($tableFootValues['clicks_source'] = $line['clicks_source']);
				(isset($tableFootValues['conversions'])) ?  ($tableFootValues['conversions'] += $line['conversions']) : ($tableFootValues['conversions'] = $line['conversions']);
				//(isset($tableFootValues['margin'])) ?  ($tableFootValues['margin'] += $line['margin']) : ($tableFootValues['margin'] = $line['margin']);
				//(isset($tableFootValues['roi'])) ?  ($tableFootValues['roi'] += $line['roi']) : ($tableFootValues['roi'] = $line['roi']);
				*/
				
				$trRowTable++;
			}
			
			/*
			$cpcFoot = ($tableFootValues['clicks_source'] != 0) ? (number_format($tableFootValues['investment'] / $tableFootValues['clicks_source'], 3, '.', '')) : 0;
			$ctrFoot = ($tableFootValues['impressions'] != 0) ? (number_format(($tableFootValues['clicks_source'] / $tableFootValues['impressions']) * 100, 2, '.', '')) : 0;
			$crFoot = ($tableFootValues['clicks_source'] != 0) ? (number_format(($tableFootValues['conversions'] / $tableFootValues['clicks_source']) * 100, 2, '.', '')) : 0;
			$margin = number_format($tableFootValues['revenue'] - $tableFootValues['investment'], 2, '.', '');
			$roi = ($tableFootValues['investment'] != 0) ? (number_format(($margin / $tableFootValues['investment']) * 100, 2, '.', '')) : 0;
			$revenueFoot = number_format($tableFootValues['revenue'], 2, '.', '');
			$investmentFoot = number_format($tableFootValues['investment'], 2, '.', '');
			$tableFoot = "<tr>"
							. $agregationFoot .
							"<td>$tableFootValues[impressions]</td>
							<td>$tableFootValues[clicks_source]</td>
							<td>$revenueFoot</td>
							<td>$investmentFoot</td>
							<td>$margin</td>
							<td>$roi%</td>
							<td>$crFoot%</td>
							<td>$ctrFoot%</td>
							<td>$cpcFoot</td>					
						</tr>";
			*/
			
			$arr = array(
				'tableHead' => $tableHead,
				'tableBody' => $tableTable,
				'tableFoot' => $tableFoot,
				'totalPages' => $totalPages
			);
			
			echo json_encode($arr);
		}
	}
	
	public function totalsRowAction() {
		$data_array = array(
			'sdate' => $this->request->getPost('sdate'),
			'edate' => $this->request->getPost('edate'),
			'source' => $this->request->getPost('source'),
			'country' => $this->request->getPost('country'),
			//'campaign' => $this->request->getPost('campaign'),
			//'domain' => $this->request->getPost('domain'),
			'agregation' => $this->request->getPost('agregation'),
			'currentLimit' => $this->request->getPost('currentLimit'),
			'col' => $this->request->getPost('col'),
			'order' => $this->request->getPost('order'),
			'search' => $this->request->getPost('search'),
            'gender' => $this->request->getPost('gender')
		);
		
		//res[0] -> table, res[1] -> total pages
		$report = new Invest();
		$res = $report->getTotals($data_array);
		
		
		if (!empty($res[0]) && $data_array['agregation'] == '') { 
		
			$cpcFoot = ($res[0]['clicks_source'] != 0) ? (number_format($res[0]['investment'] / $res[0]['clicks_source'], 3, '.', '')) : 0;
			$ctrFoot = ($res[0]['impressions'] != 0) ? (number_format(($res[0]['clicks_source'] / $res[0]['impressions']) * 100, 2, '.', '')) : 0;
			$crFoot = ($res[0]['clicks_source'] != 0) ? (number_format(($res[0]['conversions'] / $res[0]['clicks_source']) * 100, 2, '.', '')) : 0;
			$margin = number_format($res[0]['revenue'] - $res[0]['investment'], 2, '.', '');
			$roi = ($res[0]['investment'] != 0) ? (number_format(($margin / $res[0]['investment']) * 100, 2, '.', '')) : 0;
			$revenueFoot = number_format($res[0]['revenue'], 2, '.', '');
			$investmentFoot = number_format($res[0]['investment'], 2, '.', '');
			$impreFoot = $res[0]['impressions'];
			$clicksFoot = $res[0]['clicks_source'];
			$tableFoot = "<tr>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td>$impreFoot</td>
							<td>$clicksFoot</td>
							<td>$revenueFoot</td>
							<td>$investmentFoot</td>
							<td>$margin</td>
							<td>$roi%</td>
							<td>$crFoot%</td>
							<td>$ctrFoot%</td>
							<td>$cpcFoot</td>	
						</tr>";
						
			$arr = array('tfoot' => $tableFoot);
			echo json_encode($arr);
			
		} else if(!empty($res[0]) && $data_array['agregation'] != '') {
			
			$agregationFoot = '';
			foreach($data_array['agregation'] as $line) {
				$agregationFoot .= '<td>-</td>';
			}
		
			$cpcFoot = ($res[0]['clicks_source'] != 0) ? (number_format($res[0]['investment'] / $res[0]['clicks_source'], 3, '.', '')) : 0;
			$ctrFoot = ($res[0]['impressions'] != 0) ? (number_format(($res[0]['clicks_source'] / $res[0]['impressions']) * 100, 2, '.', '')) : 0;
			$crFoot = ($res[0]['clicks_source'] != 0) ? (number_format(($res[0]['conversions'] / $res[0]['clicks_source']) * 100, 2, '.', '')) : 0;
			$margin = number_format($res[0]['revenue'] - $res[0]['investment'], 2, '.', '');
			$roi = ($res[0]['investment'] != 0) ? (number_format(($margin / $res[0]['investment']) * 100, 2, '.', '')) : 0;
			$revenueFoot = number_format($res[0]['revenue'], 2, '.', '');
			$investmentFoot = number_format($res[0]['investment'], 2, '.', '');
			$impreFoot = $res[0]['impressions'];
			$clicksFoot = $res[0]['clicks_source'];
			$tableFoot = "<tr>"
							. $agregationFoot .
							"<td>$impreFoot</td>
							<td>$clicksFoot</td>
							<td>$revenueFoot</td>
							<td>$investmentFoot</td>
							<td>$margin</td>
							<td>$roi%</td>
							<td>$crFoot%</td>
							<td>$ctrFoot%</td>
							<td>$cpcFoot</td>					
						</tr>";
			
			$arr = array('tfoot' => $tableFoot);
			echo json_encode($arr);
		}
	
	}

	public function campaignsAction() {
		$country = $this->request->getPost('country');
		
		$report = new Invest();
		$res4 = $report->getCampaignsByCountry($country);
		$campaignsCombo = '';
		foreach ($res4 as $campaign) {
                $campaignsCombo .= "<option value='" . $campaign['njump'] . "'>" . $campaign['lpName'] . "</option>";
        }
		echo $campaignsCombo;
	}
	
	public function tableInfo($domainKey, $njumpKey) {
		$domainValue = '';
		$njumpValue = '';
		
		$report = new Invest();
		
		//domains
		$res3 = $report->getDomains();
		$domainsArray = array();
		if(!empty($res3)) {
			foreach ($res3 as $domain) {
				if($domainKey == $domain['id']) { 
					$domainValue = $domain['domain'];
					break;
				}
			}
		}
		
		//campaigns
		$res4 = $report->getCampaigns();
		$campaignsArray = array();
		if(!empty($res4)) {
			foreach ($res4 as $campaign) {
				if($njumpKey == $campaign['njump']) { 
					$njumpValue = $campaign['lpName'];
					break;
				}
			}
		}
		
		return array($domainValue, $njumpValue);
		
	}
	
	public function getReportExcelAction() {
		
	   $data_array = array(
			'sdate' => $this->request->getPost('sdate'),
			'edate' => $this->request->getPost('edate'),
			'source' => $this->request->getPost('source'),
			'country' => $this->request->getPost('country'),
			//'campaign' => $this->request->getPost('campaign'),
			//'domain' => $this->request->getPost('domain'),
			'agregation' => $this->request->getPost('agregation'),
			'col' => $this->request->getPost('col'),
			'order' => $this->request->getPost('order'),
			'search' => $this->request->getPost('search'),
			'os' => $this->request->getPost('os'),
			'platform' => $this->request->getPost('platform'),
			'excel' => 1
		);
		
/*
		$auth = $this->session->get('auth');
		if(($auth['userlevel'] == 2 && $auth['utype'] == 2) || ($auth['userlevel'] == 1) ) {
			
		} else {
			return;
		}
	*/
	
		$report = new Invest();
		$res = $report->getFilterContent($data_array);
		
		$columns = array();
		$content = array();
		
		//agregation
		if($data_array['agregation'] != '') {
			$array_group = array(
					'1' => 'country' ,
					'2' => 'source' ,
					'3' => 'domain' ,
					'4' => 'njump' ,
					'5' => 'insertdate'
				);

			foreach($data_array['agregation'] as $agre) {
				array_push($columns, $array_group[$agre]);
			}
								
			foreach($res[0] as $element) {
				$array_object = array();
				foreach($data_array['agregation'] as $agre) {
					array_push( $array_object , $element[$array_group[$agre]]);
				}
				array_push( $array_object , $element['impressions'], $element['clicks_source'], $element['revenue'], $element['investment'], $element['margin'], $element['roi'], $element['cr'], $element['ctr'], $element['cpc']);
				array_push($content, $array_object);
			}
			
			array_push($columns,  "impressions",  "clicks_source",  "revenue", "investment", "margin",  "roi","cr", "ctr", "cpc");

		//!agregation
		} else {
			$columns = array("group", "ad",  "impressions",  "clicks_source",  "revenue", "investment", "margin",  "roi","cr", "ctr", "cpc", "insertdate");
			
			foreach($res[0] as $element) {
				$array_object = array();
				
				array_push( $array_object , $element['groupz'], $element['ad'], $element['impressions'], $element['clicks_source'], $element['revenue'], $element['investment'], $element['margin'],$element['roi'],  $element['cr'], $element['ctr'], $element['cpc'], $element['insertdate']);
				
				array_push($content, $array_object);
			}
		}

		$this->sendExcel($content, $columns);
	}
	
	private function sendExcel($data, $columns) {
		$title = "Investment Report";
		ini_set("memory_limit", "2048M");
	
		
        try {
            $temp = tmpfile();
            $exColumns = "sep=;\n";
            for ($i = 0; $i < sizeof($columns); $i++) {
                $exColumns .= $columns[$i] . ';';
            }
            fwrite($temp, $exColumns . "\n");


            for ($j = 0; $j < sizeof($data); $j++) {
                $resultRow = '';
                foreach ($data[$j] as $k => $v) {
                    $resultRow .= $data[$j][$k] . ";";
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
