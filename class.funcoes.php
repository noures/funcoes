<?php 

class fnSP {
	
	function clearStr($var){
		$var = trim($var);
		$var = str_replace(" ","",$var);
		$var = preg_replace( "/\r|\n/", "", $var);
		$var = preg_replace('/[ \t]+/', ' ', preg_replace('/\s*$^\s*/m', "\n", $var));
		return $var;
	}
	
	/**
     * Valida Arquivo URL
     *     
     */	
	function chkExFile($url) {
		$temp = $this->clearStr($url);
		$temp = get_headers($temp, 1);
    	if(strpos($temp[0],'200') !== false){
			return true;
		} else {
			return false;		
		}
		
	}

	
	/**
     * Limpa Inteiro
     *     
     */	
	function location($url) {
		header('Location: '.$url);	
	}
	
	/**
     * Limpa Inteiro
     *     
     */	
	function p_nome($str) {
		$temp = explode(" ", $str);
		return $temp[0];
	}
	
	/**
     * Limpa Inteiro
     *     
     */
	
	public function getNow() {
		return date('Y-m-d H:i:s');	
	}
	
	/**
     * Limpa Inteiro
     *     
     */
	
	public function cls_int( $int ) {
		return preg_replace("/[^0-9]/", "", $int);	
	}
	
	/**
     * Gera Slug
     *     
     */
	
	public function getSlug($text){	  
	  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	  $text = preg_replace('~[^-\w]+~', '', $text);
	  $text = trim($text, '-');
	  $text = preg_replace('~-+~', '-', $text);
	  $text = strtolower($text);
	  if (empty($text)) {
		return 'n-a';
	  }
	  return $text;
	}
	
	/**
     * Pega IP
     *     
     */

	public function getIp(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){ 
			$ip = $_SERVER['HTTP_CLIENT_IP']; 
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
		}
		else{ 
			$ip = $_SERVER['REMOTE_ADDR']; 
		} 
		return $ip; 
	}
	
	/**
     * Arredondamento por casa decimal
     *     
     */
	
	public function getCeil($number, $significance = 1) {	
        return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
	}
	
	
	/**
     * Retorno float
     *     
     */	
	
	public function getFloat($get_valor) {
		$source = array('.', ',');
		$replace = array('', '.');
		$valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
		return $valor; //retorna o valor formatado para gravar no banco
	}

	/**
     * Cria mascara para campos
     *     
     */
	
	public function getMask($val, $mask){
		 $maskared = '';
		 $k = 0;
		 for($i = 0; $i<=strlen($mask)-1; $i++)	 {
		   if($mask[$i] == '#')	 {
			  if(isset($val[$k]))	$maskared .= $val[$k++];
		   } else {
			   if(isset($mask[$i])) $maskared .= $mask[$i];
		   }
		 }
		 return $maskared;
	}
	
	/**
     * Retorna o mes literal pelo numero
     *     
     */
	
	public function getMes($mes){
		switch ($mes){
		case 1: $mes = "JANEIRO"; break;
		case 2: $mes = "FEVEREIRO"; break;
		case 3: $mes = "MARÇO"; break;
		case 4: $mes = "ABRIL"; break;
		case 5: $mes = "MAIO"; break;
		case 6: $mes = "JUNHO"; break;
		case 7: $mes = "JULHO"; break;
		case 8: $mes = "AGOSTO"; break;
		case 9: $mes = "SETEMBRO"; break;
		case 10: $mes = "OUTUBRO"; break;
		case 11: $mes = "NOVEMBRO"; break;
		case 12: $mes = "DEZEMBRO"; break;
		}
		return $mes;
	}
	
	/**
     * Retorna o dia da semana literal pelo numero
     *     
     */
	
	public function getDia($dia){
		switch ($dia){
	 	case 0: $dia = "Domingo"; break;
		case 1: $dia = "Segunda-feira"; break;
		case 2: $dia = "Terça-feira"; break;
		case 3: $dia = "Quarta-feira"; break;
		case 4: $dia = "Quinta-feira"; break;
		case 5: $dia = "Sexta-feira"; break;
		case 6: $dia = "Sábado"; break;		
		}
		return $dia;
	}
	
	/**
     * Remove diretorio e arquivos de forma recursiva
     *     
     */
	
	public function delDir($dir) { 
	   if (is_dir($dir)) { 
		 $objects = scandir($dir); 
		 foreach ($objects as $object) { 
		   if ($object != "." && $object != "..") { 
			 if (is_dir($dir."/".$object))
			   $this->delDir($dir."/".$object);
			 else
			   unlink($dir."/".$object); 
		   } 
		 }
		 rmdir($dir); 
	   } 
	}
	
	/**
     * Remove diretorio e arquivos de forma recursiva
     *     
     */
	public function zipData($source, $destination) {
		if (extension_loaded('zip') === true) {
			if (file_exists($source) === true) {
				$zip = new ZipArchive();
				if ($zip->open($destination, ZIPARCHIVE::CREATE) === true) {
					$source = realpath($source);
					if (is_dir($source) === true) {
						$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
						foreach ($files as $file) {
							$file = realpath($file);
							if (is_dir($file) === true) {
								$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
							} else if (is_file($file) === true) {
								$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
							}
						}
					} else if (is_file($source) === true) {
						$zip->addFromString(basename($source), file_get_contents($source));
					}
				}
				return $zip->close();
			}
		}
		return false;
	}
	
	public function dbfilter($data){ 
        if( !is_array( $data ) ){
            $data = trim( htmlentities( $data ) );
			$data = trim( $data );
        	$data = $this->link->real_escape_string( $data );
        } else {
            //Self call function to sanitize array data
            $data = array_map( array( 'DB', 'filter' ), $data );
        }
    	return $data;
    }
	
}

class CodCupom { 
    
    private static $algo = CRYPT_EXT_DES;     
    private static $cost = '$103';  
    
    public static function unique_salt() {
        return substr(sha1(mt_rand()),0,22);
    } 
    
    public static function hash($password) { 
		for ($i = 0; $i < 99; $i++) {
			$password = sha1(md5($password));
		}
        $codigo = crypt($password,
                    self::$algo .
                    self::$cost .
                    '' . self::unique_salt()); 
	 	return strtoupper(str_replace("1$","",$codigo));
    }  
     
}

class pwSP { 
    
    private static $algo = '$7f';     
    private static $cost = '$103';  
    
    public static function unique_salt() {
        return substr(sha1(mt_rand()),0,22);
    } 
    
    public static function hash($password) { 
		for ($i = 0; $i < 99; $i++) {
			$password = sha1(md5($password));
		}
        return crypt($password,
                    self::$algo .
                    self::$cost .
                    '$' . self::unique_salt()); 
    }  
    
    public static function check_password($hash, $password) { 
		for ($i = 0; $i < 99; $i++) {
			$password = sha1(md5($password));
		}
        $full_salt = substr($hash, 0, 29); 
        $new_hash = crypt($password, $full_salt); 
        return ($hash == $new_hash); 
    }
	
 
}


function recursive_array_search($needle,$haystack) {
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
            return $current_key;
        }
    }
    return false;
}


 function encryptString($clearText,$pemPath){
    $keyFile=fopen($pemPath,"r");
    $publicKey=fread($keyFile,8192);
    fclose($keyFile);
    openssl_get_publickey($publicKey);
    openssl_public_encrypt($clearText,$cryptText,$publicKey);
    return(base64_encode($cryptText));
  }

/**
 * Returns an encrypted & utf8-encoded
 */
function encrypt($pure_string, $encryption_key) { 
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return $encrypted_string;
}
               
/**
 * Returns decrypted original string
 */
function decrypt($encrypted_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    return $decrypted_string;
}


  function _RedeCard_CodVer($n_filiacao,$total,$ip) {
		  $data = getdate();
		  $segundosAgora = $data['seconds'];
		  /*
		  esta é uma tabelinha de codificação da própria redecard, onde eles
		  embaralham os segundos.
		  NÃO ALTERAR!
		  */
		  $_secCodificado = array(11,17,21,31,56,34,42,3,18,13,
		  12,18,22,32,57,35,43,4,19,14,9,20,23,33,58,36,44,5,24,
		  15,62,25,34,59,37,45,6,25,16,27,63,26,35,60,38,46,7,26,
		  17,28,14,36,2,39,47,8,29,22,55,33);
		 
		  $segundosAgora = $_secCodificado[ $segundosAgora ];

		  $pad = '';
		  if ($segundosAgora < 10) {
				  $pad = "0";
		  } else {
				  $pad = "";
		  }
		  $tamIP = strlen($ip);
		  $total = intval($total);
		  $numfil = intval($n_filiacao);
		  $i5 = $total + $segundosAgora;
		  $i6 = $segundosAgora + $tamIP;
		  $i7 = $segundosAgora * $numfil;
		  $i8 = strlen($i7);
		  return "$i7$i5$i6-$i8$pad$segundosAgora";
  }


 function ImageResize($width, $height, $img_name) {
      
	  $_FILES['logo_image'] = readfile($img_name);
	  /* Get original file size */
	  list($w, $h) = getimagesize($_FILES['logo_image']['tmp_name']);


	  /*$ratio = $w / $h;
	  $size = $width;

	  $width = $height = min($size, max($w, $h));

	  if ($ratio < 1) {
		  $width = $height * $ratio;
	  } else {
		  $height = $width / $ratio;
	  }*/

	  /* Calculate new image size */
	  $ratio = max($width/$w, $height/$h);
	  $h = ceil($height / $ratio);
	  $x = ($w - $width / $ratio) / 2;
	  $w = ceil($width / $ratio);
	  /* set new file name */
	  $path = $img_name;


	  /* Save image */
	  if($_FILES['logo_image']['type']=='image/jpeg')
	  {
		  /* Get binary data from image */
		  $imgString = file_get_contents($_FILES['logo_image']['tmp_name']);
		  /* create image from string */
		  $image = imagecreatefromstring($imgString);
		  $tmp = imagecreatetruecolor($width, $height);
		  imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);
		  imagejpeg($tmp, $path, 100);
	  }
	  else if($_FILES['logo_image']['type']=='image/png')
	  {
		  $image = imagecreatefrompng($_FILES['logo_image']['tmp_name']);
		  $tmp = imagecreatetruecolor($width,$height);
		  imagealphablending($tmp, false);
		  imagesavealpha($tmp, true);
		  imagecopyresampled($tmp, $image,0,0,$x,0,$width,$height,$w, $h);
		  imagepng($tmp, $path, 0);
	  }
	  else if($_FILES['logo_image']['type']=='image/gif')
	  {
		  $image = imagecreatefromgif($_FILES['logo_image']['tmp_name']);

		  $tmp = imagecreatetruecolor($width,$height);
		  $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
		  imagefill($tmp, 0, 0, $transparent);
		  imagealphablending($tmp, true); 

		  imagecopyresampled($tmp, $image,0,0,0,0,$width,$height,$w, $h);
		  imagegif($tmp, $path);
	  }
	  else
	  {
		  return false;
	  }

	  return true;
	  imagedestroy($image);
	  imagedestroy($tmp);
}

function geraSenha($tamanho = 8, $maiusculas = false, $numeros = true, $simbolos = false){
	$lmin = 'abcdefghijklmnopqrstuvwxyz';
	$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$num = '1234567890';
	$simb = '!@#$%*-';
	$retorno = '';
	$caracteres = '';	
	$caracteres .= $lmin;
	if ($maiusculas) $caracteres .= $lmai;
	if ($numeros) $caracteres .= $num;
	if ($simbolos) $caracteres .= $simb;	
	$len = strlen($caracteres);
	for ($n = 1; $n <= $tamanho; $n++) {
		$rand = mt_rand(1, $len);
		$retorno .= $caracteres[$rand-1];
	}
	return $retorno;
}



/******************************************************************************* devolve o dia da semana  **************************************************/

function diasemana($data) {
	$ano =  substr("$data", 0, 4);
	$mes =  substr("$data", 5, -3);
	$dia =  substr("$data", 8, 9);

	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );

	switch($diasemana) {
		case"0": $diasemana = "Domingo";       break;
		case"1": $diasemana = "Segunda-Feira"; break;
		case"2": $diasemana = "Terça-Feira";   break;
		case"3": $diasemana = "Quarta-Feira";  break;
		case"4": $diasemana = "Quinta-Feira";  break;
		case"5": $diasemana = "Sexta-Feira";   break;
		case"6": $diasemana = "Sábado";        break;
	}

	echo "$diasemana";
}

/******************************************************************************* devolve a url atual  **************************************************/

function location($url) { 
?>
		<script>					
			window.location.href = "<?php echo $url; ?>";
		</script>
<?php
}


/******************************************************************************* valida cnppj  **************************************************/

function validaCNPJ($cnpj)
{
	$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

	// Valida tamanho
	if (strlen($cnpj) != 14)
		return false;

	// Valida primeiro dígito verificador
	for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
	{
		$soma += $cnpj{$i} * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}

	$resto = $soma % 11;

	if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
		return false;

	// Valida segundo dígito verificador
	for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
	{
		$soma += $cnpj{$i} * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}

	$resto = $soma % 11;

	return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
}

/******************************************************************************* valida cpf  **************************************************/

function validaCPF($cpf = null) {
 
    // Verifica se um número foi informado
    if(empty($cpf)) {
        return false;
    }
 
    // Elimina possivel mascara
    $cpf = preg_replace('[^0-9]', '', $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
     
    // Verifica se o numero de digitos informados é igual a 11 
    if (strlen($cpf) != 11) {
        return false;
    }
    // Verifica se nenhuma das sequências invalidas abaixo 
    // foi digitada. Caso afirmativo, retorna falso
    else if ($cpf == '00000000000' || 
        $cpf == '11111111111' || 
        $cpf == '22222222222' || 
        $cpf == '33333333333' || 
        $cpf == '44444444444' || 
        $cpf == '55555555555' || 
        $cpf == '66666666666' || 
        $cpf == '77777777777' || 
        $cpf == '88888888888' || 
        $cpf == '99999999999') {
        return false;
     // Calcula os digitos verificadores para verificar se o
     // CPF é válido
     } else {   
         
        for ($t = 9; $t < 11; $t++) {
             
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
 
        return true;
    }
}

/******************************************************************************* so numero  **************************************************/

function l_int($str) {
    return preg_replace("/[^0-9]/", "", $str);
}

/******************************************************************************* devolve a url atual  **************************************************/

function curPageURL() { 
 $pageURL = 'http';
 if ($_SERVER["SERVER_PORT"] == "443") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

/******************************************************************************* tratador de erros  **************************************************/

function trataErros($errno, $errstr, $errfile, $errline) {	
    $string = "$errno - $errstr em $errfile na linha $errline\n";
    file_put_contents('error.log', $string, FILE_APPEND);
    return TRUE;
}

/******************************************************************************* limitador de texto  **************************************************/

function limit_text( $text, $limit )
{
  // figure out the total length of the string
  if( strlen($text)>$limit )
  {
    # cut the text
    $text = substr( $text,0,$limit );
    # lose any incomplete word at the end
    $text = substr( $text,0,-(strlen(strrchr($text,' '))) );
  }
  // return the processed string
  return $text;
}



/******************************************************************************* gerador de captcha  **************************************************/

function GeraPalavra(){	
	$palavra = substr(str_shuffle("abcdefghkmn23456789"),0,(5)); 
	$_SESSION["palavra"] = $palavra; // atribui para a sessao a palavra gerada	
}




/******************************************************************************* mascara em php  **************************************************/

function formatoReal($valor) {
		$valor = (string)$valor;
		$regra = "/^[0-9]{1,3}([.]([0-9]{3}))*[,]([.]{0})[0-9]{0,2}$/";
		if(preg_match($regra,$valor)) {
			return true;
		} else {
			return false;
		}
	}



/******************************************************************************* geo ip **************************************************/


function geoCheckIP( $ip ){
    //check, if the provided ip is valid
    if( !filter_var( $ip, FILTER_VALIDATE_IP ) )
    {
        throw new InvalidArgumentException("IP is not valid");
    }

    //contact ip-server
    $response=@file_get_contents( 'http://www.netip.de/search?query='.$ip );

    if( empty( $response ) )
    {
        throw new InvalidArgumentException( "Error contacting Geo-IP-Server" );
    }

    //Array containing all regex-patterns necessary to extract ip-geoinfo from page
    $patterns=array();
    $patterns["domain"] = '#Domain: (.*?) #i';
    $patterns["country"] = '#Country: (.*?) #i';
    $patterns["state"] = '#State/Region: (.*?)<br#i';
    $patterns["town"] = '#City: (.*?)<br#i';

    //Array where results will be stored
    $ipInfo=array();

    //check response from ipserver for above patterns
    foreach( $patterns as $key => $pattern )   {
        //store the result in array
        $ipInfo[$key] = preg_match( $pattern, $response, $value ) && !empty( $value[1] ) ? $value[1] : 'not found';
    }
    
    /*I've included the substr function for Country to exclude the abbreviation (UK, US, etc..)
    To use the country abbreviation, simply modify the substr statement to:
    substr($ipInfo["country"], 0, 3)
    */
    $ipdata = $ipInfo["town"]. ", ".$ipInfo["state"].", ".substr($ipInfo["country"], 4);

    return $ipdata;
}


/************************************************************************ DUMP *************************************************************************/
function dump(&$var, $info = FALSE){
    $scope = false;
    $prefix = 'unique';
    $suffix = 'value';
 
    if($scope) $vals = $scope;
    else $vals = $GLOBALS;

    $old = $var;
    $var = $new = $prefix.rand().$suffix; $vname = FALSE;
    foreach($vals as $key => $val) if($val === $new) $vname = $key;
    $var = $old;
    echo "<pre style='margin: 0px 0px 10px 0px; display: block; background: white; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 12px; line-height: 15px;'>";
    if($info != FALSE) echo "<b style='color: red;'>$info:</b><br />";
    do_dump($var, '$'.$vname);
    echo "</pre>";
}

function do_dump(&$var, $var_name = NULL, $indent = NULL, $reference = NULL){
    $do_dump_indent = "<span style='color:#eeeeee;'>|</span> &nbsp;&nbsp; ";
    $reference = $reference.$var_name;
    $keyvar = 'the_do_dump_recursion_protection_scheme'; $keyname = 'referenced_object_name';

    if (is_array($var) && isset($var[$keyvar]))
    {
        $real_var = &$var[$keyvar];
        $real_name = &$var[$keyname];
        $type = ucfirst(gettype($real_var));
        echo "$indent$var_name <span style='color:#a2a2a2'>$type</span> = <span style='color:#e87800;'>&amp;$real_name</span><br />";
    }
    else
    {
        $var = array($keyvar => $var, $keyname => $reference);
        $avar = &$var[$keyvar];
   
        $type = ucfirst(gettype($avar));
        if($type == "String") $type_color = "<span style='color:green'>";
        elseif($type == "Integer") $type_color = "<span style='color:red'>";
        elseif($type == "Double"){ $type_color = "<span style='color:#0099c5'>"; $type = "Float"; }
        elseif($type == "Boolean") $type_color = "<span style='color:#92008d'>";
        elseif($type == "NULL") $type_color = "<span style='color:black'>";
   
        if(is_array($avar))
        {
            $count = count($avar);
            echo "$indent" . ($var_name ? "$var_name => ":"") . "<span style='color:#a2a2a2'>$type ($count)</span><br />$indent(<br />";
            $keys = array_keys($avar);
            foreach($keys as $name)
            {
                $value = &$avar[$name];
                do_dump($value, "['$name']", $indent.$do_dump_indent, $reference);
            }
            echo "$indent)<br />";
        }
        elseif(is_object($avar))
        {
            echo "$indent$var_name <span style='color:#a2a2a2'>$type</span><br />$indent(<br />";
            foreach($avar as $name=>$value) do_dump($value, "$name", $indent.$do_dump_indent, $reference);
            echo "$indent)<br />";
        }
        elseif(is_int($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color$avar</span><br />";
        elseif(is_string($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color\"$avar\"</span><br />";
        elseif(is_float($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color$avar</span><br />";
        elseif(is_bool($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color".($avar == 1 ? "TRUE":"FALSE")."</span><br />";
        elseif(is_null($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> {$type_color}NULL</span><br />";
        else echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $avar<br />";

        $var = $var[$keyvar];
    }
}


if($_SERVER['SERVER_ADDR'] == '192.168.0.110'){

	function money_format($format, $number)
	{
		$regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.
				  '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
		if (setlocale(LC_MONETARY, 0) == 'C') {
			setlocale(LC_MONETARY, '');
		}
		$locale = localeconv();
		preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
		foreach ($matches as $fmatch) {
			$value = floatval($number);
			$flags = array(
				'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
							   $match[1] : ' ',
				'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
				'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
							   $match[0] : '+',
				'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
				'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
			);
			$width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
			$left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
			$right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
			$conversion = $fmatch[5];
	
			$positive = true;
			if ($value < 0) {
				$positive = false;
				$value  *= -1;
			}
			$letter = $positive ? 'p' : 'n';
	
			$prefix = $suffix = $cprefix = $csuffix = $signal = '';
	
			$signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
			switch (true) {
				case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
					$prefix = $signal;
					break;
				case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
					$suffix = $signal;
					break;
				case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
					$cprefix = $signal;
					break;
				case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
					$csuffix = $signal;
					break;
				case $flags['usesignal'] == '(':
				case $locale["{$letter}_sign_posn"] == 0:
					$prefix = '(';
					$suffix = ')';
					break;
			}
			if (!$flags['nosimbol']) {
				$currency = $cprefix .
							($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
							$csuffix;
			} else {
				$currency = '';
			}
			$space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';
	
			$value = number_format($value, $right, $locale['mon_decimal_point'],
					 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
			$value = @explode($locale['mon_decimal_point'], $value);
	
			$n = strlen($prefix) + strlen($currency) + strlen($value[0]);
			if ($left > 0 && $left > $n) {
				$value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
			}
			$value = implode($locale['mon_decimal_point'], $value);
			if ($locale["{$letter}_cs_precedes"]) {
				$value = $prefix . $currency . $space . $value . $suffix;
			} else {
				$value = $prefix . $value . $space . $currency . $suffix;
			}
			if ($width > 0) {
				$value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
						 STR_PAD_RIGHT : STR_PAD_LEFT);
			}
	
			$format = str_replace($fmatch[0], $value, $format);
		}
		return $format;
	}

	}

function money_format($format, $number){
    $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.
              '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
    if (setlocale(LC_MONETARY, 0) == 'C') {
        setlocale(LC_MONETARY, '');
    }
    $locale = localeconv();
    preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
    foreach ($matches as $fmatch) {
        $value = floatval($number);
        $flags = array(
            'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
                           $match[1] : ' ',
            'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
            'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
                           $match[0] : '+',
            'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
            'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
        );
        $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
        $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
        $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
        $conversion = $fmatch[5];

        $positive = true;
        if ($value < 0) {
            $positive = false;
            $value  *= -1;
        }
        $letter = $positive ? 'p' : 'n';

        $prefix = $suffix = $cprefix = $csuffix = $signal = '';

        $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
        switch (true) {
            case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
                $prefix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
                $suffix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
                $cprefix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
                $csuffix = $signal;
                break;
            case $flags['usesignal'] == '(':
            case $locale["{$letter}_sign_posn"] == 0:
                $prefix = '(';
                $suffix = ')';
                break;
        }
        if (!$flags['nosimbol']) {
            $currency = $cprefix .
                        ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
                        $csuffix;
        } else {
            $currency = '';
        }
        $space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';

        $value = number_format($value, $right, $locale['mon_decimal_point'],
                 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
        $value = @explode($locale['mon_decimal_point'], $value);

        $n = strlen($prefix) + strlen($currency) + strlen($value[0]);
        if ($left > 0 && $left > $n) {
            $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
        }
        $value = implode($locale['mon_decimal_point'], $value);
        if ($locale["{$letter}_cs_precedes"]) {
            $value = $prefix . $currency . $space . $value . $suffix;
        } else {
            $value = $prefix . $value . $space . $currency . $suffix;
        }
        if ($width > 0) {
            $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
                     STR_PAD_RIGHT : STR_PAD_LEFT);
        }

        $format = str_replace($fmatch[0], $value, $format);
    }
    return $format;
}

?>