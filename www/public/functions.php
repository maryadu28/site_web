<?php
function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

function getlang($controller) {
	$lang = $controller->params()->fromRoute('lang', 0);
	
	if(0 === $lang) {
		if(strpos($_SERVER['REQUEST_URI'], '/fr/')!==false) {
			return getlangid('fr');
		}
		if(strpos($_SERVER['REQUEST_URI'], '/en/')!==false) {
			return getlangid('en');
		}
		return getlangid(_DEFAULT_LANG_CODE_);
	} else {
		return getlangid($lang);
	}
	return getlangid($lang);
}

function getlangurl($id_lang) {
	switch($id_lang) {
		case 1:
			return 'fr';
		break;
		case 2:
			return 'en';
		break;
		default:
			d('La langue id ' . $id_lang . 'n\'est pas définie');
		break;
	}
}

function getlangid($lang) {
	switch($lang) {
		case 'fr':
			return 1;
		break;
		case 'en':
			return 2;
		break;
		default:
			return 2;
		break;
	}
}

function _compress($buffer) {

    if(_ENV_ == 'DEV') {
		return $buffer;	
	}
	
	$search = array(
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
    );

    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );

    $buffer = preg_replace($search, $replace, $buffer);

    return $buffer;
}
function getws() {
	require(_ZF_ROOT_DIR_.'/public/nusoap/nusoap.php');
	//$url = "http://www.esales-ws.fr/b2b_dev.php?wsdl";
	$url = _WS_URL_;
	$client = new nusoap_client($url, true) or die('erreur de connexion: '.$client->getError());
	
	return $client;
}
function is_ajax() {
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return true;
	} else {
		return false;
	}
}
function end_php() {
	$start_php = unserialize(_START_PHP_);
	$end_php = new DateTime(date('Y-m-d H:i:s'));
	$diff = $start_php->diff($end_php); 
	d($diff->format("%H:%I:%S")); }
function d($var, $exit=1, $full=0) {
    
	
		echo '<pre>';
			print_r($var);
		echo '</pre>';
	
		exit();
	
}
function p($var) {
	d($var); }
function s($tableGateway, $query) {
	d($tableGateway->getSql()->getSqlStringForSqlObject($query), 1); }
function sanitize($string, $force_lowercase = true, $anal = false) {
	$string = str_replace('ç', 'c', $string);
	$string = str_replace('<br>', '', $string);
	$clean = str_to_noaccent($string);
	$strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "â€”", "â€“", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($clean)));
    $clean = str_replace('-', " ", $clean);
	$clean = str_replace('  ', " ", $clean);
	$clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
	
	$clean = str_replace('-jpg', ".jpg", $clean);
	$clean = str_replace('-jpeg', ".jpeg", $clean);
	$clean = str_replace('-gif', ".gif", $clean);
	$clean = str_replace('-png', ".png", $clean);
	$clean = str_replace('-bmp', ".bmp", $clean);
	
    return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
}
function sanitizesearch($string, $force_lowercase = true, $anal = false) {
    $clean = str_to_noaccent($string);
    $strip = array("~", "`", "!", "@@", "#", "$", "%", "^", "&", "*", "(", ")", "=", "+", "[", "{", "]",
        "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
        "â€”", "â€“", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($clean)));
    $clean = str_replace('-', " ", $clean);
    $clean = str_replace('  ', " ", $clean);
    $clean = preg_replace('/\s+/', "-", $clean);//modification "_" par "-"
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;

    return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
}
function sanitizepost($var, $type='encode') {
	switch($type) {
		case 'encode':
			$var = str_replace('&eacute;', '@eacute', $var); 
			$var = str_replace('&egrave;', '@egrave', $var); 
			$var = str_replace('&ecirc;', '@ecirc', $var); 
			$var = str_replace('&icirc;', '@icirc', $var); 
			$var = str_replace('&acirc;', '@acirc', $var); 
			$var = str_replace('&agrave;', '@agrave', $var); 
			$var = str_replace('&Oslash;', '@space', $var); 
			$var = str_replace('&deg;', '@deg', $var); 
			
			$var = str_replace('  ', ' ', $var); 
			$var = str_replace(' - ', '-', $var); 
			$var = str_replace(' ', '@space', $var); 
			$var = str_replace("'", '@cote', $var);		
			$var = str_replace('/', '@slash', $var);
			$var = str_replace('_', '@underscore', $var);
			$var = str_replace('-', '@hyphen', $var);
			$var = str_replace('.', '@point', $var);
			$var = str_replace(',', '@comma', $var);
			$var = str_replace('*', '@star', $var);
			$var = str_replace('+', '@more', $var);
		break;
		case 'decode':
			$var = str_replace('@eacute', '&eacute;', $var); 
			$var = str_replace('@egrave', '&egrave;', $var); 
			$var = str_replace('@ecirc', '&ecirc;', $var); 
			$var = str_replace('@icirc', '&icirc;', $var); 
			$var = str_replace('@acirc', '&acirc;', $var); 
			$var = str_replace('@agrave', '&agrave;', $var); 
			$var = str_replace('@deg', '&deg;', $var); 
			
			$var = str_replace('  ', ' ', $var); 
			$var = str_replace(' ', '-', $var); 
			$var = str_replace('@space', ' ', $var); 
			$var = str_replace('@cote', "'", $var);		
			$var = str_replace('@slash', '/', $var);		
			$var = str_replace('@underscore', '_', $var);	
			$var = str_replace('@hyphen', '-', $var);	
			$var = str_replace('@point', '.', $var);	
			$var = str_replace('@comma', ',', $var);
			$var = str_replace('@star', '*', $var);
			$var = str_replace('@more', '+', $var);
		break;
	}
	return $var;
}
function str_to_noaccent($str)
{
    $url = $str;
    $url = preg_replace('#&Ccedil;#', 'C', $url);
    $url = preg_replace('#&ccedil;#', 'c', $url);
    $url = preg_replace('#&egrave;|&eacute;|&ecirc;|&euml;#', 'e', $url);
    $url = preg_replace('#&Egrave;|&Eacute;|&Ecirc;|&Euml;#', 'E', $url);
    $url = preg_replace('#&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;#', 'a', $url);
    //$url = preg_replace('#@|&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;#', 'A', $url);
    $url = preg_replace('#&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;#', 'A', $url);
    $url = preg_replace('#&igrave;|&iacute;|&icirc;|&iuml;#', 'i', $url);
    $url = preg_replace('#&Igrave;|&Iacute;|&Icirc;|&Iuml;#', 'I', $url);
    $url = preg_replace('#&otilde;|&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;#', 'o', $url);
    $url = preg_replace('#&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;#', 'O', $url);
    $url = preg_replace('#&ugrave;|&uacute;|&ucirc;|&uuml;#', 'u', $url);
    $url = preg_replace('#&Ugrave;|&Uacute;|&Ucirc;|&Uuml;#', 'U', $url);
    $url = preg_replace('#&yacute;|&yuml;#', 'y', $url);
    $url = preg_replace('#&Yacute;#', 'Y', $url);
	
	$caracteres = array(
			'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
			'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', '€' => 'e',
			'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
			'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
			'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
			'Œ' => 'oe', 'œ' => 'oe',
			'$' => 's', 'Š' => 's');
	 
	$url = strtr($url, $caracteres);
	$url = preg_replace('#[^A-Za-z0-9]+#', ' ', $url);

    return ($url);
}
function truncate($text, $length = 100, $options = array())
{
	$default = array(
		'ending' => '...', 'exact' => false
	);
	$options = array_merge($default, $options);
	extract($options);

	$text = strip_tags($text);
	
	if (mb_strlen($text) <= $length) {
		return $text;
	} else {
		$truncate = mb_substr($text, 0, $length - mb_strlen($ending));
	}

	if (!$exact) {
		$spacepos = mb_strrpos($truncate, ' ');
		if (isset($spacepos)) {
			$truncate_tmp = mb_substr($truncate, 0, $spacepos);
		}
		if(!empty($truncate_tmp)) {
			$truncate = $truncate_tmp;
		}
	}
	$truncate = str_replace('.', '. ', $truncate);
	$truncate = str_replace('!', '! ', $truncate);
	$truncate = str_replace('?', '? ', $truncate);
	$truncate = str_replace(';', '; ', $truncate);
	
	
	$truncate .= $ending;
	return $truncate;
}
function newdate($in, $month="1", $lang="1")
{
	$date1 = substr($in, 0, 4); //Annee
	$date2 = substr($in, 5, 2); //Mois
	$date3 = substr($in, 8, 2); //Jour
	$date4 = date("l", mktime(0, 0, 0, $date2, $date3, $date1)); //Nom du jour

	if($lang==1) {
		if($date2=="01"){ $date2 = "Janvier"; }
		if($date2=="02"){ $date2 = "F&eacute;vrier"; }
		if($date2=="03"){ $date2 = "Mars"; }
		if($date2=="04"){ $date2 = "Avril"; }
		if($date2=="05"){ $date2 = "Mai"; }
		if($date2=="06"){ $date2 = "Juin"; }
		if($date2=="07"){ $date2 = "Juillet"; }
		if($date2=="08"){ $date2 = "Ao&ucirc;t"; }
		if($date2=="09"){ $date2 = "Septembre"; }
		if($date2=="10"){ $date2 = "Octobre"; }
		if($date2=="11"){ $date2 = "Novembre"; }
		if($date2=="12"){ $date2 = "D&eacute;cembre"; }
		if($date4=="Monday"){ $date4 = "lundi"; }
		if($date4=="Tuesday"){ $date4 = "mardi"; }
		if($date4=="Wednesday"){ $date4 = "mercredi"; }
		if($date4=="Thursday"){ $date4 = "jeudi"; }
		if($date4=="Friday"){ $date4 = "vendredi"; }
		if($date4=="Saturday"){ $date4 = "samedi"; }
		if($date4=="Sunday"){ $date4 = "dimanche"; }
	} else {
		if($date2=="01"){ $date2 = "January"; }
		if($date2=="02"){ $date2 = "February"; }
		if($date2=="03"){ $date2 = "March"; }
		if($date2=="04"){ $date2 = "April"; }
		if($date2=="05"){ $date2 = "May"; }
		if($date2=="06"){ $date2 = "June"; }
		if($date2=="07"){ $date2 = "July"; }
		if($date2=="08"){ $date2 = "August"; }
		if($date2=="09"){ $date2 = "September"; }
		if($date2=="10"){ $date2 = "October"; }
		if($date2=="11"){ $date2 = "November"; }
		if($date2=="12"){ $date2 = "December"; }
		if($date4=="Monday"){ $date4 = "monday"; }
		if($date4=="Tuesday"){ $date4 = "tuesday"; }
		if($date4=="Wednesday"){ $date4 = "wednesday"; }
		if($date4=="Thursday"){ $date4 = "thursday"; }
		if($date4=="Friday"){ $date4 = "friday"; }
		if($date4=="Saturday"){ $date4 = "saturday"; }
		if($date4=="Sunday"){ $date4 = "sunday"; }
	}
	
	if($month==1) {
		$newdate = $date2." ".$date1;
	} else {
		$newdate = $date4." ".$date3." ".$date2." ".$date1;
	}

	return $newdate;
}
function gmail($subject='', $content='', $dest='') {
	$msg = '
		<html>
		<body>
		<img src="'._URL_.'/img/logo-urbanact.png" alt="Urbanact"><br>
		<br><br>';
	$msg .= '<xmp style="text-align: left;">';
	$msg .= print_r($content, TRUE);
	$msg .= '</xmp><br><br>
					Cordialement,<br>
					<br>
					L\'&eacute;quipe de Urbanact.com<br>
					<br>
					<hr>';
	$msg .= '</body>
		</html>';
	
	if(empty($dest)) {
		$dest = _MAIL_ADMIN_;
	}
	
	$subject = strip_tags($subject);
	
	$html = new \Zend\Mime\Part($msg);
	$html->type = "text/html";

	$body = new \Zend\Mime\Message();
	$body->setParts(array($html));

	$message = new \Zend\Mail\Message();
	$message->setEncoding('UTF-8');
	$message->addFrom('contact@urbanact.com', 'Urbanact');
	$message->addTo($dest);
	$message->setSubject('Tandem - '.$subject);
	$message->setBody($body);

	$transport = new \Zend\Mail\Transport\Sendmail();
	$transport->send($message);
	
}
function getIp() {
	$ip = getenv('HTTP_CLIENT_IP')?:
		getenv('HTTP_X_FORWARDED_FOR')?:
		getenv('HTTP_X_FORWARDED')?:
		getenv('HTTP_FORWARDED_FOR')?:
		getenv('HTTP_FORWARDED')?:
		getenv('REMOTE_ADDR');
	return $ip;
}
function clean_string($string) {
  $s = trim($string);
  $s = iconv("UTF-8", "UTF-8//IGNORE", $s); // drop all non utf-8 characters

  // this is some bad utf-8 byte sequence that makes mysql complain - control and formatting i think
  $s = preg_replace('/(?>[\x00-\x1F]|\xC2[\x80-\x9F]|\xE2[\x80-\x8F]{2}|\xE2\x80[\xA4-\xA8]|\xE2\x81[\x9F-\xAF])/', ' ', $s);

  $s = preg_replace('/\s+/', ' ', $s); // reduce all multiple whitespace to a single space

  return $s;
}

function preferredLanguage() {
    // Parse the Accept-Language according to:
    // http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
	if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$HTTP_ACCEPT_LANGUAGE = 'fr-fr,en-us;q=0.7,en;q=0.3';
	} else {
		$HTTP_ACCEPT_LANGUAGE = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	}
    preg_match_all(
        '/([a-z]{1,8})' .       // M1 - First part of language e.g en
        '(-[a-z]{1,8})*\s*' .   // M2 -other parts of language e.g -us
        // Optional quality factor M3 ;q=, M4 - Quality Factor
        '(;\s*q\s*=\s*((1(\.0{0,3}))|(0(\.[0-9]{0,3}))))?/i',
        $HTTP_ACCEPT_LANGUAGE,
        $langParse);

    $langs = $langParse[1]; // M1 - First part of language
    $quals = $langParse[4]; // M4 - Quality Factor

    $numLanguages = count($langs);
    $langArr = array();

    for ($num = 0; $num < $numLanguages; $num++)
    {
        $newLang = strtoupper($langs[$num]);
        $newQual = isset($quals[$num]) ?
            (empty($quals[$num]) ? 1.0 : floatval($quals[$num])) : 0.0;

        // Choose whether to upgrade or set the quality factor for the
        // primary language.
        $langArr[$newLang] = (isset($langArr[$newLang])) ?
            max($langArr[$newLang], $newQual) : $newQual;
    }

    // sort list based on value
    // langArr will now be an array like: array('EN' => 1, 'ES' => 0.5)
    arsort($langArr, SORT_NUMERIC);

    // The languages the client accepts in order of preference.
    $acceptedLanguages = array_keys($langArr);

    // Set the most preferred language that we have a translation for.
    foreach ($acceptedLanguages as $preferredLanguage)
    {
        return strtolower($preferredLanguage) . "_" . strtoupper($preferredLanguage);
    }
}

function _ltrim($var)
{
	while(substr($var,0,1)==' ') {
		$var = substr($var, 1, strlen($var));
	}
	while(substr($var,strlen($var)-1,1)==' ') {
		$var = substr($var, 0, strlen($var)-1);
	}
	return $var;
}

function getDatesBetween($start, $end, $format='Y-m-d')
{
    if($start > $end)
    {
        return false;
    }    
    
    $sdate    = strtotime("$start +0 day");
    $edate    = strtotime($end);
    
    $dates = array();
    
    for($i = $sdate; $i <= $edate; $i += strtotime('+1 day', 0))
    {
        $dates[] = date($format, $i);
    }
    
    return $dates;
}

function datatable($values, $fields, $url="", $rep="", $alternative_url="") {
	
	$loader = isset($_POST['loader']) ? $_POST['loader'] : "";
	
	if(is_ajax() && $loader=='') {
		
		/* MAJ 2020-09-29 */
		if(is_object($fields)) {
			$e = $fields;
			$fields = array();
			foreach($e->fields as $name => $options) {
				array_push($fields, $name);
			}
			$url = isset($e->_name) ? $e->_name : '';
			$rep = isset($e->_directory) ? $e->_directory : '';
			$alternative_url = (isset($e->_alternative_url) && !empty($e->_alternative_url)) ? $e->_alternative_url : $alternative_url;
		}
		
		$datas = array();
		foreach($values as $data) {
			$rows = array();
			$link_before = '';
			$link_after = '';
			$active = 1;
			$active_before = '';
			$active_after = '';
			if($alternative_url!='') {
				$alternative_url_new = $alternative_url;
				foreach($fields as $field) {
					$alternative_url_new = str_replace('{' . $field. '}', $data->$field, $alternative_url_new);
				}
				if(strpos($alternative_url, $e->_name . '/edit/')===false) {
					$link_before = '<a href="' . $alternative_url_new . '">';
					$link_after = '</a>';
				}
			}
			if(property_exists($data, 'active') && $data->active==0) {
				$active_before = '<span class="text-danger"><i>';
				$active_after = '</i></span>';
				$active = 0;
			}
			$nb=-1;
			foreach($fields as $field) {
				if(strpos($field,'id_')===false) {
					$nb++;
					if($nb==1) {
						if(property_exists($data, 'active')) {
							if($data->active==1) {
								$btn_active = '<td><button title="Disable" data-id="'.$data->id.'" class="btn btn-outline-secondary btn-sm activeButton"><i class="fa fa-power-off" aria-hidden="true"></i> </button></td>';
							} else {
								$btn_active = '<td><button title="Enable" data-id="'.$data->id.'" class="btn btn-outline-secondary btn-sm activeButton"><i class="fa fa-power-off" aria-hidden="true"></i> </button></td>';
							}				
						} else {
							$btn_active = '';
						}
						
						if(strpos($alternative_url, _URL_ADMIN_ . '/edit/')!==false) {
							$edit = '<a href="' . _URL_ADMIN_ . '/' . $alternative_url_new .'" title="Modifier" class="btn btn-outline-secondary btn-sm edit">';
						} else {
							$edit = '<a href="' . _URL_ADMIN_ . '/' . $url . '/edit/'.$data->id.'" title="Modifier" class="btn btn-outline-secondary btn-sm edit">';
						}

						$rows[] = '<table class="admin_actions_buttons"><tr>'.$btn_active.'<td>'.$edit . '
										<i class="fa fa-edit" aria-hidden="true"></i>
									</a></td><td>
									<a href="' . _URL_ADMIN_ . '/' . $url . '/delete/'.$data->id.'" title="Supprimer" class="btn btn-outline-secondary btn-sm delete">
										<i class="fas fa-trash" aria-hidden="true"></i>
									</a></td></tr></table>';
					}
					$img = $data->$field;
					$extension = substr($img, strlen($img)-3, 3);
					if($active == 1 &&
					   ($extension=='jpg' 
					   || $extension=='epg'
					   || $extension=='png'
					   || $extension=='gif')
					  ) {
						$rows[] = $link_before.'<img src="' . _IMG_  . $rep . '/' . $data->$field . '" class="avatar-xs">'.$link_after;
					} else {
						if($nb==0) { 
							$rows[] = truncate($data->$field, 40);
						} else {
							if(empty($link_before) && substr($data->$field,0,4)=='http') {
								$link_before = '<a href="' . $data->$field . '" target="_blank">';
								$link_after = '</a>';
							}
							$rows[] = $link_before.$active_before.truncate($data->$field, 40).$active_after.$link_after;
						}
					}
				}
			}
			
			$datas[] = $rows;
		}
		echo json_encode($datas);
		exit();
	}
}

function input_format($number, $feed = 'put') {
	return $number;
	switch($feed) {
		case 'put':
			return number_format($number, 2, ',', ' ');
		break;
		case 'get':
			$number = str_replace(',', '.', $number);
			$number = str_replace(' ', '', $number);
			return $number;
		break;
		default:
			return $number;
		break;
	}
}

function _array($object) {
	$tab = array();
	foreach($object as $row) {
		$tab[] = $row;
	}
	return $tab;
}

function clean_news($news) {
	if(strpos($news, 'Dispositif')!==false){
		$pos = strpos($news, 'Dispositif');
		$news_tmp = substr($news, $pos, strlen($news));
		if(strpos($news_tmp, '</p>')!==false) {
			$pos = strpos($news_tmp, '</p>') + 4;
			$news = substr($news_tmp, $pos, strlen($news));
		}
	}
	
	return $news;
}

//Default upload function for module fileInput
function upload($targetDir) {
    $preview = $config = $errors = [];
    
    if (!file_exists($targetDir)) {
        @mkdir($targetDir);
    }
	
    $fileBlob = 'img';                      // the parameter name that stores the file blob
	
    if (isset($_FILES[$fileBlob]) && isset($_POST['uploadToken'])) {
        $token = $_POST['uploadToken'];          // gets the upload token
        if ($token!='GJSEVR') {            // your access validation routine (not included)
            return [
                'error' => 'Access not allowed'  // return access control error
            ];
        }
        $file = $_FILES[$fileBlob]['tmp_name'][0];  // the path for the uploaded file chunk 
		
        $fileName = md5(rand()).'.jpg'; //$_POST['fileName'];          // you receive the file name as a separate post data
        $fileSize = 10000; //$_POST['fileSize'];          // you receive the file size as a separate post data
        $fileId = rand(10000,99999); //$_POST['fileId'];              // you receive the file identifier as a separate post data
        $index =  0; //$_POST['chunkIndex'];          // the current file chunk index
        $totalChunks = 0; //$_POST['chunkCount'];     // the total number of chunks for this file
        $targetFile = $targetDir.'/'.$fileName;  // your target file path
        if ($totalChunks > 1) {                  // create chunk files only if chunks are greater than 1
            $targetFile .= '_' . str_pad($index, 4, '0', STR_PAD_LEFT); 
        } 
        $thumbnail = 'unknown.jpg';
		
		if(move_uploaded_file($file, $targetFile)) {
			//On sauvegarde la photo originale
			$pos = strrpos($targetFile, '/', 0);
			$directory = substr($targetFile,0,$pos).'/'.'.orig';
			//Si le répertoire .orig n'existe pas on le créé
			if(!is_dir($directory)) {
				mkdir($directory, 0777);
			}
			
			$original = substr($targetFile,0,$pos).'/'.'.orig'.substr($targetFile,$pos,strlen($targetFile));
			//Si la photo originale n'existe pas on la créé
			if(!file_exists($original)) {
				copy($targetFile, $original);
			}
            // get list of all chunks uploaded so far to server
            $chunks = glob("{$targetDir}/{$fileName}_*"); 
            // check uploaded chunks so far (do not combine files if only one chunk received)
            $allChunksUploaded = $totalChunks > 1 && count($chunks) == $totalChunks;
            if ($allChunksUploaded) {           // all chunks were uploaded
                $outFile = $targetDir.'/'.$fileName;
                // combines all file chunks to one file
                combineChunks($chunks, $outFile);
            } 
            // if you wish to generate a thumbnail image for the file
            $targetUrl = resize($fileName, $targetDir, '/small', '600', $type = 'w');
			// separate link for the full blown image file
            $zoomUrl = _IMG_ . 'uploads/' . $fileName;
            return [
                'chunkIndex' => $index,         // the chunk index processed
                'initialPreview' => $targetUrl, // the thumbnail preview data (e.g. image)
                'initialPreviewConfig' => [
                    [
                        'type' => 'image',      // check previewTypes (set it to 'other' if you want no content preview)
                        'caption' => $fileName, // caption
                        'key' => $fileId,       // keys for deleting/reorganizing preview
                        'fileId' => $fileId,    // file identifier
                        'size' => $fileSize,    // file size
                        'zoomData' => $zoomUrl, // separate larger zoom data
                    ]
                ],
                'append' => true
            ];
        } else {
			
			$upload_error = $_FILES[$fileBlob]["error"][0];
			
			switch($upload_error) {
				case 1:
					//UPLOAD_ERR_INI_SIZE = Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
					$error = 'Votre fichier est trop volumineux. Veuillez sélectionner une photo de moins de '.ini_get("upload_max_filesize");
				break;
				case 2:
					//UPLOAD_ERR_FORM_SIZE = Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
					$error = 'Votre fichier est trop volumineux pour ce formulaire. Veuillez sélectionner une photo de moins de '.ini_get("upload_max_filesize");
				break;
				case 3:
					//UPLOAD_ERR_PARTIAL = Value: 3; The uploaded file was only partially uploaded.
					$error = 'Une erreur est survenue, votre fichier n\'a pas été copié en entier. Veuillez réessayer';
				break;
				case 4:
					//UPLOAD_ERR_NO_FILE = Value: 4; No file was uploaded.
					$error = 'Attention, aucun fichier séléctionné.';
				break;
				case 6:
					//UPLOAD_ERR_NO_TMP_DIR = Value: 6; Missing a temporary folder. Introduced in PHP 5.0.3.
					$error = 'Attention, le répertoire temporaire est manquant. Veuilelz réessayer plus tard.';
				break;
				case 7:
					//UPLOAD_ERR_CANT_WRITE = Value: 7; Failed to write file to disk. Introduced in PHP 5.1.0.
					$error = 'Attention, impossible actuellement d\'écrire sur le serveur, veuillez réessayer plus tard.';
				break;
				case 8:
					//UPLOAD_ERR_EXTENSION = Value: 8; A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help.
					$error = 'Attention, le format de votre fichier est inconnu.';
				break;
				default:
					$error = 'E' . $upload_error . ' : Une erreur est survenue';
				break;
			}

			return [
                'error' => $error
            ];
        }
    }
    return [
        'error' => 'No file found'
    ];
}

// combine all chunks
// no exception handling included here - you may wish to incorporate that
function combineChunks($chunks, $targetFile) {
    // open target file handle
    $handle = fopen($targetFile, 'a+');
    
    foreach ($chunks as $file) {
        fwrite($handle, file_get_contents($file));
    }
    
    // you may need to do some checks to see if file 
    // is matching the original (e.g. by comparing file size)
    
    // after all are done delete the chunks
    foreach ($chunks as $file) {
        @unlink($file);
    }
    
    // close the file handle
    fclose($handle);
}
 
// generate and fetch thumbnail for the file
function resize($img_big, $dirSrc, $dirDest, $Hauteur_Miniatures, $type = 'h')
{
	//URL Absolue
    $urlAbs = _IMG_ABS_;

    $tnH = $Hauteur_Miniatures;
    $t_rename = 0;
    $th_quality = 1;

    if (substr($dirSrc, 0, 1) == "/") {
        $dirSrc = $dirSrc; //substr($dirSrc, 1, strlen($dirSrc));
    } else {
        $dirSrc = '/' . $dirSrc;
    }

    if (substr($img_big, 0, 1) == "/") {
        $img_big = $dirSrc . $img_big;
    } else {
        $img_big = $dirSrc . '/' . $img_big;
    }

    if (substr($dirDest, 0, 1) == "/") {
        $dirDest = $dirSrc . $dirDest;
    } else {
        $dirDest = $dirSrc . '/' . $dirDest;
    }

    if (substr($dirDest, strlen($dirDest) - 1, 1) == "/") {
        $dirDest = substr($dirDest, 0, strlen($dirDest) - 1);
    }
    
    if(!is_dir($dirDest)) {
        mkdir($dirDest, 0777);
    }
	
    $dirDest = $dirDest . '/';

	$size = @getimagesize($img_big);
	$src = '';
    switch ($size[2]) {
        case 1:
            if (imagetypes() & IMG_GIF)
                $src = imagecreatefromgif($img_big);
            break;
        case 2:
            if (imagetypes() & IMG_JPG)
                $src = imagecreatefromjpeg($img_big);
            break;
        case 3:
            if (imagetypes() & IMG_PNG)
                $src = imagecreatefrompng($img_big);
            break;
        default :
            if (preg_match("/\.wbmp$/", $img_big) && (imagetypes() & IMG_WBMP)) {
                $src = imagecreatefromwbmp($img_big);
                $size[0] = imagesx($src);
                $size[1] = imagesy($src);
                if (!isset($format))
                    $format = 4;
            }
    }

    if (!$src) {
        $thumbs[$img_big] = "Format NON SUPPORTE !";
        return false;
    } else {
        if ($type == 'h') {
            $destW = ($size[0] * $tnH) / $size[1];
            $destH = $tnH;
        } else {
            $destW = $tnH;
            $destH = ($size[1] * $tnH) / $size[0];
        }
        if ($th_quality == 1) {
            $dest = imagecreatetruecolor($destW, $destH);
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $destW, $destH, $size[0], $size[1]);
        } else {
            $dest = imagecreatetruecolor($destW, $destH);
            imagecopyresized($dest, $src, 0, 0, 0, 0, $destW, $destH, $size[0], $size[1]);
        }
        $tn_name = $img_big;

        $tn_name = preg_replace("/\.(gif|jpe|jpg|jpeg|png|wbmp)$/i", "", $tn_name);
        $tn_name = preg_replace("/.*\/([^\/]+)$/i", "$dirDest\\1", $tn_name);

        if (isset($format))
            $type = $format;
        else
            $type = $size[2];

        switch ($type) {
            case 1 :
                if (imagetypes() & IMG_GIF) {
                    imagegif($dest, $tn_name . ".gif");
                    $thumbs[$img_big] = "$tn_name.gif";
                }
                break;
            case 2:
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($dest, $tn_name . ".jpg");
                    $thumbs[$img_big] = "$tn_name.jpg";
                }
                break;
            case 3:
                if (imagetypes() & IMG_PNG) {
                    imagepng($dest, $tn_name . ".png");
                    $thumbs[$img_big] = "$tn_name.png";
                }
                break;
            default:
                if (imagetypes() & IMG_WBMP) {
                    imagewbmp($dest, $tn_name . ".wbmp");
                    $thumbs[$img_big] = "$tn_name.wbmp";
                }
        }

        if (!($thumbs[$img_big])) {
            $thumbs[$img_big] = "Format NON SUPPORTE !";
        }
		//On copie la miniature vers l'image de base
		copy($thumbs[$img_big], $img_big);
		
		// FIN CREATION
        return $thumbs;
    }
}

function crop($target="", $img_width="", $img_height="", $x="", $y="", $w="", $h="", $img_natural_width="", $img_natural_height="") {
	
	if(empty($w)) {
		echo json_encode('error');
		exit();
	}
	
	$targ_w = 600; //Taille en pixel de la cible
	
	//Bug IE9 qui ne retourne pas de $img_natural_width
	if(empty($img_natural_width)) {
		$img_natural_width = $img_width;
		$img_natural_height = $img_height;
	}
	
	//Si l'image était redimensionnée, on met à jour les valeurs pour l'image d'origine
	if($img_natural_width!=$img_width && $img_width!=0 && $img_height!=0) {
		$x = ($x*$img_natural_width)/$img_width;
		$y = ($y*$img_natural_height)/$img_height;
		$w = ($w*$img_natural_width)/$img_width;
		$h = ($h*$img_natural_height)/$img_height;
	}
	
	//Taille nécessaire de l'image pour obtenir une vignette de 600px
	$img_natural_width_new = ($targ_w*$img_natural_width)/$w;
	$img_natural_height_new = ($img_natural_width_new*$img_natural_height)/$img_natural_width;
	$targ_h = ($targ_w*$h)/$w;
	
	$jpeg_quality = 90;
	
	$src = _IMG_ABS_ . $target;
	$img_r = @imagecreatefromjpeg($src);
	if(!$img_r) {
		echo json_encode('error');
	} else {
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

		imagecopyresampled($dst_r, //Lien vers la ressource cible de l'image.
						   $img_r, //Lien vers la ressource source de l'image.
						   0, //X : coordonnées du point de destination.
						   0, //Y : coordonnées du point de destination.
						   $x, //X : coordonnées du point source.
						   $y, //Y : coordonnées du point source.
						   $img_natural_width_new, //Largeur de la destination.
						   $img_natural_height_new, //Hauteur de la destination.
						   $img_natural_width, //Largeur de la source.
						   $img_natural_height //Hauteur de la source.
						  );
		
		imagejpeg($dst_r, $src, $jpeg_quality);
		echo json_encode('success');
	}
}

function rotate($img) {
	$filename = _IMG_ABS_ . $img;
	$degrees = 270;
	$source = imagecreatefromjpeg($filename);
	$rotate = imagerotate($source, $degrees, 0);
	imagejpeg($rotate, $filename);
}

function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir);
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
           rrmdir($dir. DIRECTORY_SEPARATOR .$object);
         else
           unlink($dir. DIRECTORY_SEPARATOR .$object); 
       } 
     }
     rmdir($dir); 
   } 
 }

function downloadcsv($e, $table, $response){
	
	$namefile = $e->_name.'_'.date('Ymd-his');
	$fp = fopen(_ZF_ROOT_DIR_.'/public'._URL_ADMIN_.'/files/csv/' . $namefile . '.csv', 'w');
	$list = array();
	foreach($e->fields as $name => $options){
		if(strpos($name,'id_')===false) {
			array_push($list, $name);
		}
	}
	fputcsv($fp, $list, ';');
	foreach($table->fetchAll()->toArray() as $fields_tmp) {
		$fields = array();
		foreach($fields_tmp as $field => $value) {
			if(strpos($field,'id_')===false) {
				array_push($fields, $value);
			}
		}
		fputcsv($fp, $fields,';');
	}
	fclose($fp);
	$fileName = _ZF_ROOT_DIR_.'/public'._URL_ADMIN_.'/files/csv/' . $namefile . '.csv';
	if(!is_file($fileName)) {
		return '';
	}
	$fileContents = file_get_contents($fileName);
	$response->setContent($fileContents);
	$headers = $response->getHeaders();
	$headers->clearHeaders()
		->addHeaderLine('Content-Encoding: UTF-8')
		->addHeaderLine('Content-type: text/csv; charset=UTF-8')
		->addHeaderLine('Content-Disposition', 'attachment; filename="' . $namefile . '.csv"')
		->addHeaderLine('Content-Length', strlen($fileContents));
		echo "\xEF\xBB\xBF";
	return $response;
}