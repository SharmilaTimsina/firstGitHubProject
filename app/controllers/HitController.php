<?php

/**
 * HitController
 *
 * Hit Insertion and presentation
 */
class HitController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Hit');
        parent::initialize();
    }

    public function indexAction()
    {
      
    }
   /**
     * This action inserts click information in the report table
     *
     */
    public function clickAction()
    {
        //if ($this->request->isPost()) {
            //mail('mfcbarone@gmail.com','testreferal',$_SERVER['HTTP_REFERER']);
            $carrier = $this->request->get('carrier');
            $country = $this->request->get('country');
            $aff = $this->request->get('aff');
            
           // $filter = $this->filterClick($country, $aff, $carrier);
            //if($filter != false){
            
                    date_default_timezone_set ( $this->setTimeZone($aff) );

                    $conditions = "carrier = :carrier: AND country = :country: AND display_date = :display_date:";

                    //Parameters whose keys are the same as placeholders
                    $parameters = array(
                    "carrier" => $carrier,//$filter[1],
                    "country" => $this->_countryConvert($country),//$filter[0],
                    "display_date"=> date('Y-m-d')    
                    );

                    //Perform the query
                    $repshell = new Hit();
                    $repshell->setAffiliate($aff);
                    $rep = $repshell->findFirst(array(
                    $conditions,
                    "bind" => $parameters
                    ));

                    if ($rep != false) {

                        $rep->updateClick(); 
                        $rep->save();

                    }else{

                        $rep = new Hit();
                        $rep->setAffiliate($aff);
                        $rep->clicks = 1;
                        $rep->conversions = 0;
                        $rep->revenue = 0;
                        $rep->insert_date = date('Y-m-d H:i:s');
                        $rep->display_date = date('Y-m-d');
                        $rep->carrier = $carrier;
                        $rep->country = $this->_countryConvert($country);
                        $rep->country_code = $country;
                        $rep->c_rate = '0.00';
                        $rep->save();
               
                }
            
            //}
            $response = new \Phalcon\Http\Response();
            $response->setRawHeader("HTTP/1.1 200 OK");
    }
    
    
    public function conversionAction()
    {
       // if ($this->request->isPost()) {
           
            $carrier = $this->request->get('carrier');
            $country = $this->request->get('country');
            $cpa     = $this->request->get('cpa');
            $aff = $this->request->get('aff');
            
            //$filter = $this->filterClick($country, $aff, $carrier);
            //if($filter != false){
            
                    date_default_timezone_set ( $this->setTimeZone($aff) );
                    $conditions = "carrier = :carrier: AND country = :country: AND display_date = :display_date:";

                    //Parameters whose keys are the same as placeholders
                    $parameters = array(
                    "carrier"      => $carrier,//$filter[1],
                    "country"      => $this->_countryConvert($country),//$filter[0],    
                    "display_date" => date('Y-m-d')        
                    );

                    //Perform the query
                    $repshell = new Hit();
                    $repshell->setAffiliate($aff);
                    $rep = $repshell->findFirst(array(
                    $conditions,
                    "bind" => $parameters
                    ));

                    if ($rep != false) {

                        $rep->updateConversion($cpa); 
                        $rep->save();

                        $response = new \Phalcon\Http\Response();
                        $response->setRawHeader("HTTP/1.1 200 OK");

                    }
        //}
        $response = new \Phalcon\Http\Response();
        $response->setRawHeader("HTTP/1.1 200 OK");
    }
    private function filterClick($country_code, $aff, $carrier){
        
            $conditions = "country_code = :country_code: AND fk_affid = :fk_affid:";

            //Parameters whose keys are the same as placeholders
            $parameters = array(
            "country_code" => $country_code,
            "fk_affid"     => $aff,            
            );
            
            //Perform the query
            $filter_object = new Filters();
            $filter = $filter_object->findFirst(array(
            $conditions,
            "bind" => $parameters
            ));
            
            if($filter != false){
                
                $opArray = explode(',',$filter->operators);
                $ccarrier = $this->carrierConvert($carrier);
                if(in_array($ccarrier, $opArray)){
                    return array($filter->country,$ccarrier);
                }
                return false;
            
                }
             return false;  
            }
        
        
    private function setTimeZone($aff){
        $timezone = 'Europe/Lisbon';
        switch ($aff) {
            case 107:
                $timezone = 'Europe/Madrid';
            break;
            case 110:
                $timezone = 'America/Buenos_Aires';
            break;
            case 112:
                $timezone = 'America/Buenos_Aires';
            break;
            case 113:
                $timezone = 'Europe/Lisbon';
            break;
            case 115:
                $timezone = 'America/Buenos_Aires';
            break;
            case 117:
                $timezone = 'America/Buenos_Aires';
            break;
            case 119:
                $timezone = 'Europe/Istanbul';
            break;
            
        }
        return $timezone;
    }
    
    private function _countryConvert($iso){
        
        $countries= array(
                    'AF'=>'Afghanistan',
                    'AL'=>'Albania',
                    'DZ'=>'Algeria',
                    'AS'=>'American Samoa',
                    'AD'=>'Andorra',
                    'AO'=>'Angola',
                    'AI'=>'Anguilla',
                    'AQ'=>'Antarctica',
                    'AG'=>'Antigua And Barbuda',
                    'AR'=>'Argentina',
                    'AM'=>'Armenia',
                    'AW'=>'Aruba',
                    'AU'=>'Australia',
                    'AT'=>'Austria',
                    'AZ'=>'Azerbaijan',
                    'BS'=>'Bahamas',
                    'BH'=>'Bahrain',
                    'BD'=>'Bangladesh',
                    'BB'=>'Barbados',
                    'BY'=>'Belarus',
                    'BE'=>'Belgium',
                    'BZ'=>'Belize',
                    'BJ'=>'Benin',
                    'BM'=>'Bermuda',
                    'BT'=>'Bhutan',
                    'BO'=>'Bolivia',
                    'BA'=>'Bosnia And Herzegovina',
                    'BW'=>'Botswana',
                    'BV'=>'Bouvet Island',
                    'BR'=>'Brazil',
                    'IO'=>'British Indian Ocean Territory',
                    'BN'=>'Brunei',
                    'BG'=>'Bulgaria',
                    'BF'=>'Burkina Faso',
                    'BI'=>'Burundi',
                    'KH'=>'Cambodia',
                    'CM'=>'Cameroon',
                    'CA'=>'Canada',
                    'CV'=>'Cape Verde',
                    'KY'=>'Cayman Islands',
                    'CF'=>'Central African Republic',
                    'TD'=>'Chad',
                    'CL'=>'Chile',
                    'CN'=>'China',
                    'CX'=>'Christmas Island',
                    'CC'=>'Cocos (Keeling) Islands',
                    'CO'=>'Columbia',
                    'KM'=>'Comoros',
                    'CG'=>'Congo',
                    'CK'=>'Cook Islands',
                    'CR'=>'Costa Rica',
                    'CI'=>'Cote D\'Ivorie (Ivory Coast)',
                    'HR'=>'Croatia (Hrvatska)',
                    'CU'=>'Cuba',
                    'CY'=>'Cyprus',
                    'CZ'=>'Czech Republic',
                    'CD'=>'Democratic Republic Of Congo (Zaire)',
                    'DK'=>'Denmark',
                    'DJ'=>'Djibouti',
                    'DM'=>'Dominica',
                    'DO'=>'Dominican Republic',
                    'TP'=>'East Timor',
                    'EC'=>'Ecuador',
                    'EG'=>'Egypt',
                    'SV'=>'El Salvador',
                    'GQ'=>'Equatorial Guinea',
                    'ER'=>'Eritrea',
                    'EE'=>'Estonia',
                    'ET'=>'Ethiopia',
                    'FK'=>'Falkland Islands (Malvinas)',
                    'FO'=>'Faroe Islands',
                    'FJ'=>'Fiji',
                    'FI'=>'Finland',
                    'FR'=>'France',
                    'FX'=>'France, Metropolitan',
                    'GF'=>'French Guinea',
                    'PF'=>'French Polynesia',
                    'TF'=>'French Southern Territories',
                    'GA'=>'Gabon',
                    'GM'=>'Gambia',
                    'GE'=>'Georgia',
                    'DE'=>'Germany',
                    'GH'=>'Ghana',
                    'GI'=>'Gibraltar',
                    'GR'=>'Greece',
                    'GL'=>'Greenland',
                    'GD'=>'Grenada',
                    'GP'=>'Guadeloupe',
                    'GU'=>'Guam',
                    'GT'=>'Guatemala',
                    'GN'=>'Guinea',
                    'GW'=>'Guinea-Bissau',
                    'GY'=>'Guyana',
                    'HT'=>'Haiti',
                    'HM'=>'Heard And McDonald Islands',
                    'HN'=>'Honduras',
                    'HK'=>'Hong Kong',
                    'HU'=>'Hungary',
                    'IS'=>'Iceland',
                    'IN'=>'India',
                    'ID'=>'Indonesia',
                    'IR'=>'Iran',
                    'IQ'=>'Iraq',
                    'IE'=>'Ireland',
                    'IL'=>'Israel',
                    'IT'=>'Italy',
                    'JM'=>'Jamaica',
                    'JP'=>'Japan',
                    'JO'=>'Jordan',
                    'KZ'=>'Kazakhstan',
                    'KE'=>'Kenya',
                    'KI'=>'Kiribati',
                    'KW'=>'Kuwait',
                    'KG'=>'Kyrgyzstan',
                    'LA'=>'Laos',
                    'LV'=>'Latvia',
                    'LB'=>'Lebanon',
                    'LS'=>'Lesotho',
                    'LR'=>'Liberia',
                    'LY'=>'Libya',
                    'LI'=>'Liechtenstein',
                    'LT'=>'Lithuania',
                    'LU'=>'Luxembourg',
                    'MO'=>'Macau',
                    'MK'=>'Macedonia',
                    'MG'=>'Madagascar',
                    'MW'=>'Malawi',
                    'MY'=>'Malaysia',
                    'MV'=>'Maldives',
                    'ML'=>'Mali',
                    'MT'=>'Malta',
                    'MH'=>'Marshall Islands',
                    'MQ'=>'Martinique',
                    'MR'=>'Mauritania',
                    'MU'=>'Mauritius',
                    'YT'=>'Mayotte',
                    'MX'=>'Mexico',
                    'FM'=>'Micronesia',
                    'MD'=>'Moldova',
                    'MC'=>'Monaco',
                    'MN'=>'Mongolia',
                    'MS'=>'Montserrat',
                    'MA'=>'Morocco',
                    'MZ'=>'Mozambique',
                    'MM'=>'Myanmar (Burma)',
                    'NA'=>'Namibia',
                    'NR'=>'Nauru',
                    'NP'=>'Nepal',
                    'NL'=>'Netherlands',
                    'AN'=>'Netherlands Antilles',
                    'NC'=>'New Caledonia',
                    'NZ'=>'New Zealand',
                    'NI'=>'Nicaragua',
                    'NE'=>'Niger',
                    'NG'=>'Nigeria',
                    'NU'=>'Niue',
                    'NF'=>'Norfolk Island',
                    'KP'=>'North Korea',
                    'MP'=>'Northern Mariana Islands',
                    'NO'=>'Norway',
                    'OM'=>'Oman',
                    'PK'=>'Pakistan',
                    'PW'=>'Palau',
                    'PA'=>'Panama',
                    'PG'=>'Papua New Guinea',
                    'PY'=>'Paraguay',
                    'PE'=>'Peru',
                    'PH'=>'Philippines',
                    'PN'=>'Pitcairn',
                    'PL'=>'Poland',
                    'PT'=>'Portugal',
                    'PR'=>'Puerto Rico',
                    'QA'=>'Qatar',
                    'RE'=>'Reunion',
                    'RO'=>'Romania',
                    'RU'=>'Russia',
                    'RW'=>'Rwanda',
                    'SH'=>'Saint Helena',
                    'KN'=>'Saint Kitts And Nevis',
                    'LC'=>'Saint Lucia',
                    'PM'=>'Saint Pierre And Miquelon',
                    'VC'=>'Saint Vincent And The Grenadines',
                    'SM'=>'San Marino',
                    'ST'=>'Sao Tome And Principe',
                    'SA'=>'Saudi Arabia',
                    'SN'=>'Senegal',
                    'SC'=>'Seychelles',
                    'SL'=>'Sierra Leone',
                    'SG'=>'Singapore',
                    'SK'=>'Slovak Republic',
                    'SI'=>'Slovenia',
                    'SB'=>'Solomon Islands',
                    'SO'=>'Somalia',
                    'ZA'=>'South Africa',
                    'GS'=>'South Georgia And South Sandwich Islands',
                    'KR'=>'South Korea',
                    'ES'=>'Spain',
                    'LK'=>'Sri Lanka',
                    'SD'=>'Sudan',
                    'SR'=>'Suriname',
                    'SJ'=>'Svalbard And Jan Mayen',
                    'SZ'=>'Swaziland',
                    'SE'=>'Sweden',
                    'CH'=>'Switzerland',
                    'SY'=>'Syria',
                    'TW'=>'Taiwan',
                    'TJ'=>'Tajikistan',
                    'TZ'=>'Tanzania',
                    'TH'=>'Thailand',
                    'TG'=>'Togo',
                    'TK'=>'Tokelau',
                    'TO'=>'Tonga',
                    'TT'=>'Trinidad And Tobago',
                    'TN'=>'Tunisia',
                    'TR'=>'Turkey',
                    'TM'=>'Turkmenistan',
                    'TC'=>'Turks And Caicos Islands',
                    'TV'=>'Tuvalu',
                    'UG'=>'Uganda',
                    'UA'=>'Ukraine',
                    'AE'=>'United Arab Emirates',
                    'GB'=>'United Kingdom',
                    'US'=>'United States',
                    'UM'=>'United States Minor Outlying Islands',
                    'UY'=>'Uruguay',
                    'UZ'=>'Uzbekistan',
                    'VU'=>'Vanuatu',
                    'VA'=>'Vatican City (Holy See)',
                    'VE'=>'Venezuela',
                    'VN'=>'Vietnam',
                    'VG'=>'Virgin Islands (British)',
                    'VI'=>'Virgin Islands (US)',
                    'WF'=>'Wallis And Futuna Islands',
                    'EH'=>'Western Sahara',
                    'WS'=>'Western Samoa',
                    'YE'=>'Yemen',
                    'YU'=>'Yugoslavia',
                    'ZM'=>'Zambia',
                    'ZW'=>'Zimbabwe'
                    );
       return $countries[strtoupper ( $iso )];
    }
        
      
    
    
}
