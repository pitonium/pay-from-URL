<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Reasun_Abonement_Pay_From_URL
 * @subpackage Reasun_Abonement_Pay_From_URL/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?function Connect1C(){
    if (!function_exists('is_soap_fault')){
      print 'Не настроен web сервер. Не найден модуль php-soap.';
      return false;
    }
    try {
      $ClientOneC = new SoapClient('http://80.79.245.93/1cwsprod/ws/onlinestore.1cws?wsdl',
                               array('login'          => 'shopreasun',
                                     'password'       => 'Aa123456',
                                     'soap_version'   => SOAP_1_2,
                                     'cache_wsdl'     => WSDL_CACHE_NONE, //WSDL_CACHE_MEMORY, //, WSDL_CACHE_NONE, WSDL_CACHE_DISK or WSDL_CACHE_BOTH
                                     'exceptions'     => true,
                                     'trace'          => 1));
    }catch(SoapFault $e) {
      trigger_error('Ошибка подключения или внутренняя ошибка сервера. Не удалось связаться с базой 1С.', E_ERROR);
      var_dump($e);
    }
    //echo 'Раз<br>';
    if (is_soap_fault(ClientOneC)){
      trigger_error('Ошибка подключения или внутренняя ошибка сервера. Не удалось связаться с базой 1С.', E_ERROR);
      return false;
    }
    return $ClientOneC;
  }

  function GetData($idc, $txt){
	  if (is_object($idc)){
		 $p['Params'] =$txt;
		try {
		 $reto = $idc->GetOfferInfo($p);
		} catch (SoapFault $e) {
                      echo "<span style='color:red;'>ERROR!</span> </br>";
			//var_dump($e);
		} 	
	  }
	  else{
		echo 'Не удалось подключиться к 1С<br>';
		//var_dump($idc);
	  }
	return $reto;
  }

 
 $result=array();
function recursiveRebuildResArray($res,  &$arRes, $key, $index){
	//echo "key = " . $key . "; index = " . $index . "</br>";
	if(is_array($res['#value']))
	{
		foreach($res['#value'] as $itemRes)
		{
			$k =$itemRes['name']['#value'];
			if($itemRes['Value']['#type'] == 'jxs:string' or $itemRes['Value']['#type'] == 'jxs:decimal' )
			{
				if($key)
					if($index)
						$arRes[$key][$k][] = $itemRes['Value']['#value'];
					else
						$arRes[$key][$k] = $itemRes['Value']['#value'];
				else
					if($index)
						$arRes[$k][] = $itemRes['Value']['#value'];
					else
						$arRes[$k] = $itemRes['Value']['#value'];
			}
			else
			{
				//echo "Type: " . $itemRes['Value']['#type'] . "</br>";
				if($itemRes['Value']['#type'] == 'jv8:Array'){
				//	echo "It`s Array!</br>";
					foreach($itemRes['Value']['#value'] as $ind=>$ittem){
					//	echo "ind=" . $ind . "</br>";
						recursiveRebuildResArray($ittem,  $arRes, $k, true);
				}
				}
				else
					foreach($itemRes['Value']['#value'] as $ittem)
						recursiveRebuildResArray($ittem,  $arRes, $k, false);
			}
		}
	}
	else
	{
		$arRes[$res['name']['#value']] = $itemRes['Value']['#value'];
	}
	return $arRes;
} 

/* $url = parse_url($url, PHP_URL_QUERY); */
  $idc = Connect1C();
  $num = htmlspecialchars($_REQUEST['abid']);
 $t = '{"#type": "jv8:Structure","#value": [{"name": {"#type": "jxs:string","#value": "OfferID"},
			"Value": {"#type": "jxs:string","#value": "'. $num .'"}}]}';
  $ret1c = GetData($idc, $t);
  
  var_dump($ret1c);
  $aa=$ret1c->return; 
  $dataResult = json_decode($aa, 1);
        $StatusResult = $dataResult->Status; //получим значение параметра Status, который был сформирован при ответе веб-сервиса 1С
        $MessageResult = $dataResult->Message; //получим значение параметра Message, который был сформирован при ответе веб-сервиса 1С
		var_dump($StatusResult);
		echo "</br>";
		var_dump($MessageResult);
		echo "</br>";
		
$arRes  = recursiveRebuildResArray($dataResult, $result, false, false);
  echo "<pre>" . var_export($arRes, true) . "</pre>";
