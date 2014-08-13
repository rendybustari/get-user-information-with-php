<?php

class rendy{

	function get_data_user(){
    	
	    	$ip = $this->get_client_ip();
	    	
	    	$getbrowser = $this->getbrowser();

			$geo_data = $this->geo_location_pi($ip);

			/*info browser*/
			$browser = $getbrowser['userAgent'];
			$version = $getbrowser['version'];
			$paltform = $getbrowser['platform'];
			/*=====================================*/

			/*info location user*/
			$country_code = $geo_data['countryCode'];
			$country_name = $geo_data['countryName'];
			$region_name = $geo_data['regionName'];
			$city = $geo_data['cityName'];
			$zip_postal_code = $geo_data['zipCode'];
			$latitude = $geo_data['latitude'];
			$longitude = $geo_data['longitude'];
			$timezone = $geo_data['timeZone'];
			/*============================================*/


		
	    	echo "ip : ". $ip;
	    	echo "<br />";
	    	echo "<br />";
	    	echo "browser : ". $browser;
	    	echo "<br />";
	    	echo "<br />";
	    	echo "version : ". $version;
	    	echo "<br />";
	    	echo "<br />";
	    	echo "platform : ". $paltform;
	    	echo "<br />";
	    	echo "<br />";
	    	echo "country_code : ". $country_code;
	    	echo "<br />";
	    	echo "<br />";
	    	echo "country_name : ". $country_name;
	    	echo "<br />";
	    	echo "<br />";
	    	echo "region_name : ". $region_name;
	    	echo "<br />";
	    	echo "<br />";
	    	echo "city : ". $city;
	    	echo "<br />";
	    	echo "<br />";
	    	echo "zip_postal_code : ". $zip_postal_code;
	    	echo "<br />";
	    	echo "<br />";
	    	echo "latitude : ". $latitude;
	    	echo "<br />";
	    	echo "<br />";
	    	echo "longitude : ". $longitude;
	    	echo "<br />";
	    	echo "<br />";
	    	echo "timezone : ". $timezone;


	    		    	
    }

    function geo_location_pi($ip = null) {
    	
        $d = @file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=93fcf8bd196a2bbce9490e12b6c917452223187c40803c702e6d9edc606b8cd0&ip=$ip&format=xml");
        //$d = simplexml_load_file($d);
        //Use backup server if cannot make a connection
        if (!$d) {
            $backup = @file_get_contents("http://backup.ipinfodb.com/ip_query.php?ip=$ip&output=xml");
            $result = new SimpleXMLElement($backup);
            if (!$backup)
                return false; // Failed to open connection
        } else {

            try{
				$response = new SimpleXMLElement($d);

				foreach($response as $field=>$value){
					$result[(string)$field] = (string)$value;
				}

			}
			catch(Exception $e){
				$errors[] = $e->getMessage();
				return false;
			}
    	}

    	return $result;
	}

	function get_client_ip() {
	    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}

	function getbrowser(){
	
		    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
		    $bname = 'Unknown';
		    $platform = 'Unknown';
		    $version= "";

		    //First get the platform?
		    if (preg_match('/linux/i', $u_agent)) {
		        $platform = 'linux';
		    }
		    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		        $platform = 'mac';
		    }
		    elseif (preg_match('/windows|win32/i', $u_agent)) {
		        $platform = 'windows';
		    }
		    
		    // Next get the name of the useragent yes seperately and for good reason
		    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
		    { 
		        $bname = 'Internet Explorer'; 
		        $ub = "MSIE"; 
		    } 
		    elseif(preg_match('/Firefox/i',$u_agent)) 
		    { 
		        $bname = 'Mozilla Firefox'; 
		        $ub = "Firefox"; 
		    } 
		    elseif(preg_match('/Chrome/i',$u_agent)) 
		    { 
		        $bname = 'Google Chrome'; 
		        $ub = "Chrome"; 
		    } 
		    elseif(preg_match('/Safari/i',$u_agent)) 
		    { 
		        $bname = 'Apple Safari'; 
		        $ub = "Safari"; 
		    } 
		    elseif(preg_match('/Opera/i',$u_agent)) 
		    { 
		        $bname = 'Opera'; 
		        $ub = "Opera"; 
		    } 
		    elseif(preg_match('/Netscape/i',$u_agent)) 
		    { 
		        $bname = 'Netscape'; 
		        $ub = "Netscape"; 
		    } 
		    
		    // finally get the correct version number
		    $known = array('Version', $ub, 'other');
		    $pattern = '#(?<browser>' . join('|', $known) .
		    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		    if (!preg_match_all($pattern, $u_agent, $matches)) {
		        // we have no matching number just continue
		    }
		    
		    // see how many we have
		    $i = count($matches['browser']);
		    if ($i != 1) {
		        //we will have two since we are not using 'other' argument yet
		        //see if version is before or after the name
		        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
		            $version= $matches['version'][0];
		        }
		        else {
		            $version= $matches['version'][1];
		        }
		    }
		    else {
		        $version= $matches['version'][0];
		    }
		    
		    // check if we have a number
		    if ($version==null || $version=="") {$version="?";}
		    
		    return array(
		        'userAgent' => $u_agent,
		        'name'      => $bname,
		        'version'   => $version,
		        'platform'  => $platform,
		        'pattern'    => $pattern
		    );
		} 
	

}

$obj = new rendy();

$a = $obj->get_data_user();


?>
