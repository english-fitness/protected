<?php
class Common
{
	/**
     * @param $text
     * @param int $chars
     * @return string
     */
    public static function truncate($text, $chars = 25)
    {
        $chars = intval($chars);
        if(strlen($text) < $chars){
        	return $text;
        }else{
	        $text = $text . " ";
	        $text = substr($text, 0, $chars);
	        $text = substr($text, 0, strrpos($text, ' '));
	        $text = $text . "...";
	        return $text;
        }
    }

    public static function formatCartCode($in)
    {
        return  substr($in,0,4)."-".substr($in,4,4)."-".substr($in,8,4);
    }
    /**
     * Allow html tags in content, description
     */
    public static function allowHtmlTags()
    {
    	$allowableTags = '<div><p><a><br><span><b><strong><i><em><u><table><tbody><tr><td><img><ul><ol><li>';
    	return $allowableTags;
    }
    
    /**
     * Parse fullname to lastname, firstname
     * Example Tam Le Minh: lastname = Le Minh, firstname = Tam
     * Example Le Minh Tam: lastname = Le Minh, firstname = Tam
     */
    public static function parseName($fullname, $format='first/last')
    {
    	$parts = explode(" ", $fullname);
    	$firtname = ""; $lastname = "";
    	if(count($parts)>1){
    		if($format=='first/last'){
	    		$firtname = $parts[0];
	    		unset($parts[0]);//Remove firstname
	    		$lastname = implode(" ", $parts);//Lastname
    		}elseif($format=='last/first'){
    			$firtname = $parts[count($parts)-1];
	    		unset($parts[count($parts)-1]);//Remove lastname
	    		$lastname = implode(" ", $parts);//Firstname
    		}
    	}elseif(count($parts)==1){
    		$firtname = $parts[0];//Firstname
    		$lastname = $parts[0];//Lastname
    	}
    	echo $fullname.'->'.$lastname.':'.$firtname;
    	return array(
    		'firstname'=>$firtname,
    	 	'lastname'=>$lastname,
    	);
    }
    
    //Convert filter date in search/list, convert to yyyy-mm-dd
    public static function convertDateFilter($strDate, $format='dd/mm/yyyy')
    {
    	$dateFilter = "";
    	if(trim($strDate)!=""){
    		$d = explode('/', $strDate);
    		for($i=count($d)-1; $i>=0; $i--){
    			$dateFilter .= $d[$i].'-';
    		}
    		if($dateFilter!="") $dateFilter = substr($dateFilter, 0, -1);
    	}
    	return $dateFilter;
    }
    
    /* upload profile picture */
    public function uploadProfilePicture($name,$dir)
    {
        if(isset($_FILES[$name]) &&  $_FILES[$name]['tmp_name']!="")
        {

            $file = $_FILES[$name];
            $imageType = array('image/gif','image/jpeg','image/jpg','image/png');
            $imageInfo =getimagesize($file['tmp_name']);
            if(array_search($imageInfo['mime'],$imageType)){
                $saveFileName = time().$file['name'];
                move_uploaded_file($file['tmp_name'], $dir."/".$saveFileName);
                return $saveFileName;
            }
        }
        return false;
    }
    
    //Create password Crypt
    public static function passwordCrypt($passwordSave)
    {
        $salt = "";
        for ($i = 0; $i < 16; $i++) {
            $salt .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", mt_rand(0, 63), 1);
        }
        // sha256
        $salt = '$5$'.$salt.'$';
        return crypt($passwordSave, $salt);
    }
    
    //Display date options
    public static function numberOptions($max,$min=1)
    {
        $options = array(""=>"---");
        for($i=$min;$i<=$max;$i++){
        	$key = ($i<10)? '0'.$i: $i;
        	$options[$key] = $key; 
        }
        return $options;
    }
    
    //Load ajax image javascript
    public static function loadImageJavascript($htmlTag,$image = null, $optionHtml = null)
    {
        if($image){
            $img = '<img '.$optionHtml.' src="'.$image.'" alt="'.$image.'"/>';
        }
        return "<script type='text/javascript'> $('".$htmlTag."').html('".$img."')</script>";
    }

    /* Load content javascript*/

    public static function loadContent($htmlTag,$content)
    {
        return "<script type='text/javascript'> $('".$htmlTag."').html('".$content."')</script>";
    }
    
    //Window location js url
    public static function windowLocationJs($url)
    {
        return "<script type='text/javascript'> window.location ='".$url."';</script>";
    }

    /* format date */
    public static function formatDate($date)
    {
        return date("d/m/Y",strtotime($date));
    }
    
    /* format datatime */
    public static  function  formatDatetime($datetime){
        return date("H:i, d/m/Y",strtotime($datetime));
    }
    
    /* format duration */
    public static function formatDuration($planStart,$duration)
    {
        $start = date("H:i",strtotime($planStart));
        $end = date("H:i",strtotime($planStart)+$duration*60);
        return $start." - ".$end;
    }
	
    /**
	 * Validate phone number(mobile or telephone)
	 */
	public static function validatePhoneNumber($phoneStr)
	{
		//Valid format 9, 10, 11 số
		$length = strlen($phoneStr);
		if(!in_array($length, array(9,10,11))){
			return false;//Not valid format
		}
		if(preg_match("/[0-9]{".$length."}$/", $phoneStr)) {
	  		return true;
		}
		return false;
	}
	
	/**
	 * Check validate str is date with format yyyy-mm-dd
	 */
	public static function validateDateFormat($strDate, $format='yyyy-mm-dd')
	{
		if (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $strDate)){
			return true;
		}
		return false;
	}

    public static function formatScore($score,$totalScore)
    {
        return ($score*(10/$totalScore));
    }

    public static  function vnStrFilter ($str){

        $unicode = array(

            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

            'd'=>'đ',

            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

            'i'=>'í|ì|ỉ|ĩ|ị',

            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',

            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'D'=>'Đ',

            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',

            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

        );

        foreach($unicode as $nonUnicode=>$uni){

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);

        }

        return $str;

    }
}
?>