<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Path Helpers
 *
 * @package      CodeIgniter
 * @subpackage   Helpers
 * @category     Helpers
 * @author       Al-amin Jwel
 * @link         www.al-amin.me
 */



//=====================converts a number to word====================== 
//====================================================================
if ( ! function_exists('numtowords'))

{ function numtowords($number) {
   
    $hyphen      = '-';
    $conjunction = '  ';
    $separator   = ' ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'Zero',
        1                   => 'One',
        2                   => 'Two',
        3                   => 'Three',
        4                   => 'Four',
        5                   => 'Five',
        6                   => 'Six',
        7                   => 'Seven',
        8                   => 'Eight',
        9                   => 'Nine',
        10                  => 'Ten',
        11                  => 'Eleven',
        12                  => 'Twelve',
        13                  => 'Thirteen',
        14                  => 'Fourteen',
        15                  => 'Fifteen',
        16                  => 'Sixteen',
        17                  => 'Seventeen',
        18                  => 'Eighteen',
        19                  => 'Nineteen',
        20                  => 'Twenty',
        30                  => 'Thirty',
        40                  => 'Fourty',
        50                  => 'Fifty',
        60                  => 'Sixty',
        70                  => 'Seventy',
        80                  => 'Eighty',
        90                  => 'Ninety',
        100                 => 'Hundred',
        1000                => 'Thousand',
        1000000             => 'Million',
        1000000000          => 'Billion',
        1000000000000       => 'Trillion',
        1000000000000000    => 'Quadrillion',
        1000000000000000000 => 'Quintillion'
    );
   
    if (!is_numeric($number)) {
        return false;
    }
   
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'numtowords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . numtowords(abs($number));
    }
   
    $string = $fraction = null;
   
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
   
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . numtowords($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = numtowords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= numtowords($remainder);
            }
            break;
    }
   
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
   
   return  $string;
  }
}
//=====================converts a number to word====================== 
//====================================================================





//=====================converts a number to phrase====================== 
//======================================================================
if ( ! function_exists('numtophrase'))
{   
  function numtophrase($num) {
   
   if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
      }
    }
    return $num.'th';
  }
}

//=====================converts a number to phrase====================== 
//======================================================================




//=====================converts a phrase to number====================== 
//======================================================================
if ( ! function_exists('phrasetonumber'))
{   
  function phrasetonumber($phrase) {   
   if(strlen($phrase)==4)
   return substr($phrase,0,2);
   else
   return substr($phrase,0,1);
  }
}
//=====================converts a phrase to number====================== 



//======================get data=================================
//===============================================================
if ( ! function_exists('get_data_helper'))
{   
  function get_data_helper($table,$where='',$select='',$join='',$limit='',$start='',$order_by='',$group_by='',$num_rows=1,$single_value=1) 
  {
       $ci = &get_instance();
       $ci->load->model('basic');  
       $results=$ci->basic->get_data($table,$where,$select,$join,$limit,$start,$order_by,$group_by,$num_rows);
     
       if($single_value==1) return $results[0];
       else return $results;

  }
}


/*date Time Formating*/
 if ( ! function_exists('date_time_formating'))
{   
  function date_time_formating($date) 
  {
       return date('d/m/Y h:i:s a',strtotime($date));
  }
 }



/**Date Time formating**/
 if ( ! function_exists('date_formating'))
{   
  function date_formating($date) 
  {
       return date('d/m/Y',strtotime($date));
  }
 }



if ( ! function_exists('convert_to_grid_data'))
{   
  function convert_to_grid_data($total_info,$total_result=10) 
  {
       $result["total"] = $total_result;
		$items = array();
		
		foreach($total_info as $index=>$info){
			if($index!=='extra_index'){
				$info_obj=(object)$info;
				array_push($items, $info_obj);
			}
			
		}
		$result["rows"] = $items;
		return json_encode($result);
  }
}


if ( ! function_exists('calcutate_age'))
{ 
  function calcutate_age($dob)
  {

      $dob = date("Y-m-d",strtotime($dob));

      $dobObject = new DateTime($dob);
      $nowObject = new DateTime();

      $diff = $dobObject->diff($nowObject);

      return $diff->y;

  }
}

/**This function to take the original site url . Because we are using subdomain for same site.So for facebook page like we need to do a universal url**/

if ( ! function_exists('get_current_url_without_subdomain'))
{ 
  function get_current_url_without_subdomain()
  {
  		$CI =& get_instance();
		
		$url=current_url();
		$info = parse_url($url);
		$url_without_subdomain=$CI->config->item('fb_like_doamin').$info['path'];
		return $url_without_subdomain;
  }
}

//======================used for counting a field================
//===============================================================



if ( ! function_exists('random_value_from_array'))
{ 
 	 function random_value_from_array($array, $default=null)
		{
		    $k = mt_rand(0, count($array) - 1);
		    return isset($array[$k])? $array[$k]: $default;
		}
}


if ( ! function_exists('get_domain_only'))
{ 
  function get_domain_only($url) {
    $url=str_replace("www.","",$url);
    $url=str_replace("WWW.","",$url);
    
      if (!preg_match("@^https?://@i", $url) && !preg_match("@^ftps?://@i", $url)) {
          $url = "http://" . $url;
      }
    
    
      $parsed=@parse_url($url);
    
    return $parsed['host'];
    
  }
}


if ( ! function_exists('isImage'))
{ 
  
  function isImage( $url )
    {
      $pos = strrpos( $url, ".");
    if ($pos === false)
      return false;
    $ext = strtolower(trim(substr( $url, $pos)));
    $imgExts = array(".gif", ".jpg", ".jpeg", ".png", ".tiff", ".tif"); // this is far from complete but that's always going to be the case...
    if ( in_array($ext, $imgExts) )
      return true;
      return false;
    }
}








