 <?php //Template Name: Payment from URL 
 if(!session_id()) {
session_start();
}
get_header();?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<?
$today = date('d-m-Y H:i:s');
$optionsPlugin = get_option('option_name');
file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", "\n\n" . $today . "___" ." START NEW PAGE GET\n" . var_export($_GET, true), FILE_APPEND);
file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", "\n\n" . $today . "___" ."  POST\n" . var_export($_POST, true), FILE_APPEND);
$result=array();
function Connect1C($optionsPlugin){
	if (!function_exists('is_soap_fault')){
      print 'Не настроен web сервер. Не найден модуль php-soap.';
      return false;
    }
    try {
		$ClientOneC = new SoapClient($optionsPlugin['rapfu_hst'],
                               array('login'          => $optionsPlugin['rapfu_lgn'],
                                     'password'       => $optionsPlugin['rapfu_psw'],
                                     'soap_version'   => SOAP_1_2,
                                     'cache_wsdl'     => WSDL_CACHE_NONE, //WSDL_CACHE_MEMORY, //, WSDL_CACHE_NONE, WSDL_CACHE_DISK or WSDL_CACHE_BOTH
                                     'exceptions'     => true,
                                     'trace'          => 1));
    }catch(SoapFault $e) {
      trigger_error('Ошибка подключения или внутренняя ошибка сервера. Не удалось связаться с базой 1С.', E_ERROR);
      //var_dump($e);
    }
    //echo 'Раз<br>';
    if (is_soap_fault($ClientOneC)){
		trigger_error('Ошибка подключения или внутренняя ошибка сервера. Не удалось связаться с базой 1С.', E_ERROR);
		return false;
    }
    return $ClientOneC;
	}

function GetData($idc, $txt){
	unset($_SESSION["OFFER_PAY_FROM_URL"]);
	if (is_object($idc)){
			$p['Params'] =$txt;
		try {
			$reto = $idc->GetOfferInfo($p);
		} catch (SoapFault $e){
			//echo "<span style='color:red;'>ERROR!</span> </br>";
			//var_dump($e);
			file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", $today . "___" ."line 42 get data return\n" . var_export($e, true), FILE_APPEND);
		}
	}
	else{
		  if($ids==NULL){
				//	echo 'Не удалось подключиться к 1С<br>';
				file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", $today . "___" ."\n\nОтвет от SendOfferPaymentData:\n" . var_export($ids, true), FILE_APPEND);
		  }
	  }
	return $reto;
  }
function PutData($idc, $txt){
	  if (is_object($idc)){
		 $p['Params'] =$txt;
		try {
		 $reto = $idc->SendOfferPaymentData($p);
		} catch (SoapFault $e) {
                      echo "<span style='color:red;'>ERROR!</span> </br>";
				file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", $today . "___" ."\n\nОтвет от SendOfferPaymentData:\n" . var_export($ids, true), FILE_APPEND);
		} 	
	  }
	  else{
		  if($ids==NULL){
			echo 'Не удалось подключиться к 1С<br>';
			//var_dump($idc);
		  }
	  }
	return $reto;
  }
function recursiveRebuildResArray($res,  &$arRes, $key, $index){
	//echo "key = " . $key . "; index = " . $index . "</br>";
	if(is_array($res['#value']))
	{
		foreach($res['#value'] as $itemRes)
		{
			$k =$itemRes['name']['#value'];
			if($itemRes['Value']['#type'] != 'jv8:Array')
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
?>
<div class="pay_from_url mkd-content">
	<div class="mkd-content-inner">
<?
if(!empty($_REQUEST['abid'])){
	/* $url = parse_url($url, PHP_URL_QUERY); */
	$idc = Connect1C($optionsPlugin);
	file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", $today . "___" ."IDC return\n" . var_export($idc, true), FILE_APPEND);
	$num = htmlspecialchars($_REQUEST['abid']);
	$t = '{"#type": "jv8:Structure","#value": [{"name": {"#type": "jxs:string","#value": "OfferID"},
			"Value": {"#type": "jxs:string","#value": "'. $num .'"}}]}';
	$ret1c = GetData($idc, $t);
  $_SESSION["OFFER_PAY_FROM_URL"]['abid'] = $num;
	//var_dump($ret1c);
	$aa=$ret1c->return; 
	$dataResult = json_decode($aa, 1);
	$StatusResult = $dataResult->Status; //получим значение параметра Status, который был сформирован при ответе веб-сервиса 1С
	$MessageResult = $dataResult->Message; //получим значение параметра Message, который был сформирован при ответе веб-сервиса 1С
		
	file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", $today . "___" ."StatusResult return\n" . var_export($StatusResult, true), FILE_APPEND);
	file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", $today . "___" ."MessageResult return\n" . var_export($MessageResult, true), FILE_APPEND);
	$arRes  = recursiveRebuildResArray($dataResult, $result, false, false);
	$_SESSION["OFFER_PAY_FROM_URL"]['offers'][$arRes['MainOffers']['UID'][0]]['Offer']= $arRes['MainOffers']['Offer'][0];
	$_SESSION["OFFER_PAY_FROM_URL"]['offers'][$arRes['MainOffers']['UID'][0]]['Number'] = $arRes['MainOffers']['Number'][0];
	$_SESSION["OFFER_PAY_FROM_URL"]['offers'][$arRes['MainOffers']['UID'][0]]['Price'] = $arRes['MainOffers']['Price'][0];
	$_SESSION["OFFER_PAY_FROM_URL"]['offers'][$arRes['MainOffers']['UID'][0]]['OldPrice'] = $arRes['MainOffers']['OldPrice'][0];
	foreach($arRes['AdditionalOffers']['UID'] as $kkey=>$kvalue){
		$_SESSION["OFFER_PAY_FROM_URL"]['offers'][$kvalue]['Offer'] = $arRes['AdditionalOffers']['Offer'][$kkey];
		$_SESSION["OFFER_PAY_FROM_URL"]['offers'][$kvalue]['Number'] = $arRes['AdditionalOffers']['Number'][$kkey];
		$_SESSION["OFFER_PAY_FROM_URL"]['offers'][$kvalue]['Price'] = $arRes['AdditionalOffers']['Price'][$kkey];
		$_SESSION["OFFER_PAY_FROM_URL"]['offers'][$kvalue]['OldPrice'] = $arRes['AdditionalOffers']['OldPrice'][$kkey];
	}
	file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", $today . "___" ."line 131 get data return\n" . var_export($arRes, true), FILE_APPEND);
	file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", $today . "___" ."line 150 set data session\n" . var_export($_SESSION["OFFER_PAY_FROM_URL"], true), FILE_APPEND);
	if(!empty($arRes['Error'])){?>
		
		<div class="mkd-title mkd-standard-type mkd-content-center-alignment mkd-animation-no" style="color:#ffffff;;background-color:#4564fd;;height:60px;" data-height="60">
			<div class="mkd-title-image"></div>
			<div class="mkd-title-holder" style="height:60px;">
				<div class="mkd-container clearfix">
					<div class="mkd-container-inner">
						<div class="mkd-title-subtitle-holder" style="">
							<div class="mkd-title-subtitle-holder-inner">
								<h1 style="color:#ffffff;">
									<span><?=$arRes['Error'];?></span>
								</h1>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
<?}
else{?>
	<div class="mkd-title mkd-standard-type mkd-content-center-alignment mkd-animation-no" style="color:#ffffff;;background-color:#4564fd;;height:60px;" data-height="60">
		<div class="mkd-title-image"></div>
		<div class="mkd-title-holder" style="height:60px;">
			<div class="mkd-container clearfix">
				<div class="mkd-container-inner">
					<div class="mkd-title-subtitle-holder" style="">
						<div class="mkd-title-subtitle-holder-inner">
							<h1 style="color:#ffffff;">
								<span><?=$arRes['Name'];?></span>
							</h1>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="pay_from_url mkd-container">
		<div class="mkd-container-inner clearfix">
			<div  class="product type-product post-23180 status-publish first instock product_cat-abonementy has-post-thumbnail downloadable virtual purchasable product-type-simple woovr-active">
				<div class="mkd-single-product-content">
					<div class="mkd-single-product-summary">
						<div class="summary entry-summary">	
							<form class="cart" action="" method="post" enctype="multipart/form-data">
								<div id="yith_wapo_groups_container" class="yith_wapo_groups_container enable-collapse-feature" style="">					
									<div class="yith_wapo_group_total dopoffers">	
													<div id="product-id-<?=$num;?>" class="dopoffers__offer main">
														<div style="min-width:70%;">
															<div class=" offer__title">
																<h1 itemprop="name" class="mkd-single-product-title1 product-name-<?=$arRes['MainOffers']['UID'][0];?>"><?=$arRes['MainOffers']['Offer'][0];?></h1>
																<p><?= nl2br($arRes['Description'], true);?></p>
															</div>
														</div>
														<div class="yith_wapo_group_product_old_price_total" style="width:15%">
															<span class="price amount">
																<span class="product-price-<?=$arRes['MainOffers']['UID'][0];?>"><?=$arRes['MainOffers']['Price'][0];?></span>&nbsp;<span class="rur">р</span>
															</span>												
														<?if($arRes['MainOffers']['OldPrice'][0]>0 &&  $arRes['MainOffers']['OldPrice'][0] != $arRes['MainOffers']['Price'][0]){?>
															<span class="price old amount">
																<span class="product-old-price-<?=$arRes['MainOffers']['UID'][0];?>"><?=$arRes['MainOffers']['OldPrice'][0];?></span>&nbsp;<span class="rur">р</span>
															</span>
															<?}?>
														</div>													
														<div style="min-width:15%;">
															<input class="product-id dop-check" id="<?=$arRes['MainOffers']['UID'][0];?>" data-main="Y" type="checkbox" name="extOffer" value="1" checked>
														</div>			
													</div>	
										<?if(!empty($arRes['AdditionalOffers']['Offer'])):?>
										<h2>Также вы можете приобрести:</h2>
											<?foreach($arRes['AdditionalOffers']['Offer'] as $indOffer=>$optionsPluginOffer):?>
													<div class="dopoffers__offer">
														<div style="min-width:70%;" class="product-name-<?=$arRes['AdditionalOffers']['UID'][$indOffer];?>"><?=$optionsPluginOffer;?></div>
														<div class="yith_wapo_group_product_old_price_total" style="width:15%">
															<span class="price amount"><span class="product-price-<?=$arRes['AdditionalOffers']['UID'][$indOffer];?>"><?=$arRes['AdditionalOffers']['Price'][$indOffer];?></span>&nbsp;<span class="rur">р</span></span>
															<?if($arRes['AdditionalOffers']['OldPrice'][$indOffer]>0 && $arRes['AdditionalOffers']['OldPrice'][$indOffer]!==$arRes['AdditionalOffers']['Price'][$indOffer]){?>
																<span class="price old amount">
																	<span class="product-old-price-<?=$arRes['AdditionalOffers']['UID'][$indOffer];?>"><?=$arRes['AdditionalOffers']['OldPrice'][$indOffer];?></span>&nbsp;<span class="rur">р</span>
																</span>
															<?}?>
														</div>													
													
														<div style="min-width:15%;">
															<input class="product-id dop-check" id="<?=$arRes['AdditionalOffers']['UID'][$indOffer];?>" type="checkbox" name="extOffer" value="0">
														</div>			
													</div>																
											<?endforeach;?>
										<?endif;?>
									</div>
									<div class="yith_wapo_group_product_price_total">
										<span class=" " id="itogo">Итого к оплате: <span  id="total"><?=$arRes['MainOffers']['Price'][0];?></span>&nbsp;<span class="rur">р</span></span>
									</div>
									<div class=""><a href="#" class="flag_pay" id="go_pay" >Оплатить</a></div>
								</div>
							</form>

							<script>
							function calcprice(){
									var totalprice=0;
									$('form input:checked').each(function() {
										var prod_id = $(this).attr('id');
										var cur_dop_price = $('.product-price-' + prod_id)[0].innerText;
										totalprice = +totalprice + +cur_dop_price;
									});
									if(totalprice==0){
									$("a.flag_pay").css("display", "none");
									}
									else{
									$("a.flag_pay").css("display", "block");
									}
									return totalprice;
							}
								var cart = [];
								$('#go_pay').click(function(){
									godata();
								})
								$('.dop-check').click(function(){
									//var cur_price = $(".current-price")[0].innerText;
								//	totalprice = +cur_price;
										$('#total').html(calcprice());
								});
							//	calcprice();
								function buildElement(tagName, props) {
								var element = document.createElement(tagName);
								for (var propName in props) element[propName] = props[propName];
								return element;
								}
								
								function godata(){
									var form = buildElement('form', {
										method: 'post',
										action: 'https://reasun.ru/oplata-po-ssylke/',
									});
								
									form.appendChild(
										buildElement('input', {
										type: 'hidden',
										name: 'offerid1c',
										value: '<?=$num;?>',
										}))
									form.appendChild(
										buildElement('input', {
										type: 'hidden',
										name: 'mode',
										value: 'gopay',
										}))
						/* 			form.appendChild(
										buildElement('input', {
										type: 'hidden',
										name: 'offers[0][UID]',
										value: '<?=$arRes['MainOffers']['UID'][0];?>',
										}))
									form.appendChild(
										buildElement('input', {
										type: 'hidden',
										name: 'offers[0][name]',
										value: '<?=$arRes['MainOffers']['Offer'][0];?>',
										}))
									form.appendChild(
										buildElement('input', {
										type: 'hidden',
										name: 'offers[0][flag_main_offer]',
										value: 'Y',
										}))
									form.appendChild(
										buildElement('input', {
										type: 'hidden',
										name: 'offers[0][price]',
										value: '<?=$arRes['MainOffers']['Price'][0];?>',
										})) */
									$('form.cart input:checked').each(function(index) {
										if(index>0){
										}
										//console.log($(this));
										var prod_id = $(this).attr('id');
										if($(this).attr('data-main') =="Y")
										var mainflag = "Y";
									else
										var mainflag = "N";
										//console.log("prod_id: " + prod_id);
										var cur_dop_price = $('.product-price-' + prod_id)[0].innerText;
										var cur_dop_name = $('.product-name-' + prod_id)[0].innerText;
									//	console.log(index, prod_id,cur_dop_name,  cur_dop_price);				
										form.appendChild(
											buildElement('input', {
											type: 'hidden',
											name: 'offers[' + (index+1) + '][UID]',
											value: prod_id,
											}))
										form.appendChild(
											buildElement('input', {
											type: 'hidden',
											name: 'offers[' + (index+1) + '][name]',
											value: cur_dop_name,
											}))
										form.appendChild(
											buildElement('input', {
											type: 'hidden',
											name: 'offers[' + (index+1) + '][flag_main_offer]',
											value: mainflag,
											}))
										form.appendChild(
											buildElement('input', {
											type: 'hidden',
											name: 'offers[' + (index+1) + '][price]',
											value: cur_dop_price,
											}))
										// Output: input_1, input_3
									});
									form.style.display = 'none';
									document.body.appendChild(form);
									//console.log(form);
									form.submit();
								}
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?}
}
?>
<?if(!empty($_REQUEST['mode']) && $_REQUEST['mode']=='gopay'){
		$vars = array();		
		$vars['userName'] = $optionsPlugin['rapfu_payment_lgn'];
		$vars['password'] =  $optionsPlugin['rapfu_payment_psw'];
		$vars['orderNumber'] =time();/* ID заказа в магазине */
		$_SESSION["OFFER_PAY_FROM_URL"]['order_id_site'] = $vars['orderNumber'];
		$vars['amount'] = 0;/* Сумма заказа в копейках */
		$_SESSION["OFFER_PAY_FROM_URL"]['amount'] = $vars['amount'];
		/* Корзина для чека (необязательно) */
		$cart = [];
		$dbArInsert = [];
		file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", "\n" . $today . "___" ."GOPAY Mode GOPAY\n" . var_export($_POST, true), FILE_APPEND);
		file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", "\n" . $today . "___" ."GOPAY Data in SESSION\n" . var_export($_SESSION["OFFER_PAY_FROM_URL"], true), FILE_APPEND);

		foreach($_POST['offers'] as $ind=>$offerPost){
			$uidpost = $offerPost['UID'];
/* 			if($offerPost['price'] != $_SESSION["OFFER_PAY_FROM_URL"][$uidpost]['Price']){
				echo "<h2><span style='color:red;' data-post-price='" . $offerPost['price'] . "' data-price-sess='".$_SESSION["OFFER_PAY_FROM_URL"][$uidpost]['Price'] . "'>Внимание! На Вашем компьютере обнаружено потенциально опасное ПО!</span></h2>";
				die();
			} */
			$vars['amount'] = $vars['amount'] + $_SESSION["OFFER_PAY_FROM_URL"]['offers'][$uidpost]['Price']*100;
			$dbArInsert[] = array(
				'offer_id_from_one_c' => $_POST['offerid1c'],
				'order_id_site' =>''.$vars['orderNumber'],
				'order_id_pay' => '',
				'order_pay_status' => false,
				'cart_item_name' => $offerPost['name'],
				'cart_item_main_offer_flag' => $offerPost['flag_main_offer'],
				'cart_item_uid' => $offerPost['UID'],
				'cart_item_price' => $_SESSION["OFFER_PAY_FROM_URL"]['offers'][$uidpost]['Price']*100,
				'cart_item_quantity' =>1,
			); 
			$cart[] = array(
					'positionId' => $ind,
					'name' => $offerPost['name'],
					'quantity' => array(
						'value' => 1,    
						'measure' => 'шт'
					),
					'itemAmount' => ($_SESSION["OFFER_PAY_FROM_URL"]['offers'][$offerPost['UID']]['Price'] * 100),
					'itemCode' => $offerPost['UID'],
					'tax' => array(
						'taxType' => 0,
						'taxSum' => 0
					),
					'itemPrice' => ($_SESSION["OFFER_PAY_FROM_URL"]['offers'][$offerPost['UID']]['Price'] * 100),
				);
		}
		$_SESSION["OFFER_PAY_FROM_URL"]['amount']  = $vars['amount'];
		$vars['orderBundle'] = json_encode(array('cartItems' => array('items' => $cart)), JSON_UNESCAPED_UNICODE);		
		/* URL куда клиент вернется в случае успешной оплаты */
		$vars['returnUrl'] = 'https://reasun.ru/oplata-po-ssylke/?callback=sb';
		/* URL куда клиент вернется в случае ошибки */
		$vars['failUrl'] = 'https://reasun.ru/oplata-po-ssylke/?err=1';
		/* Описание заказа, не более 24 символов, запрещены % + \r \n */
		$vars['description'] = 'Заказ №' . $vars['orderNumber'] . ' на https://reasun.ru/oplata-po-ssylke/';
		var_dump($vars);
		$ch = curl_init($optionsPlugin['rapfu_payment_hst'] . 'register.do?' . http_build_query($vars));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$res = curl_exec($ch);
		$res = json_decode($res, JSON_OBJECT_AS_ARRAY);
		curl_close($ch);
	file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", " GOPAY Res:\n" . var_export($res, true), FILE_APPEND);
	if (empty($res['orderId']))
	{
		/* Возникла ошибка: */
		//file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", "Пустой orderId. Ошибка подключения на стороне платежной системы. $res:\n" . var_export($res, true), FILE_APPEND);
		echo "Ошибка подключения на стороне платежной системы</br>";
		echo $res['errorMessage'];						
	}
	else
	{
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		global $wpdb;
		$table = $wpdb->get_blog_prefix() . 'rapfu_orders';
		$charset = "DEFAULT CHARACTER SET " . $wpdb->charset . " COLLATE " . $wpdb->collate;
		if($res['formUrl'])
			{
				foreach($dbArInsert as $lineOffer){
					$lineOffer['order_id_pay'] = ''. $res['orderId'];
					$wpdb->insert($table, $lineOffer);
				}
				/* Перенаправление клиента на страницу оплаты */
				header('Location: ' . $res['formUrl'], true);		
			}
	}
}?>
<?//file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", "Возврат после оплаты:\n", FILE_APPEND);?>
<?if(isset($_REQUEST['callback']) && $_REQUEST['callback'] == 'sb' ){
	// Показываем страницу  с оформленным заказом.
	if(isset($_REQUEST['orderId'])) $orderid = $_REQUEST['orderId'];
	elseif($_REQUEST['mdOrder']) $orderid = $_REQUEST['mdOrder'];
			$checklistoffer="";
			global $wpdb;
			$table = $wpdb->get_blog_prefix() . 'rapfu_orders';
			$charset = "DEFAULT CHARACTER SET " . $wpdb->charset . " COLLATE " . $wpdb->collate;
			if(isset($_REQUEST['orderId'])){
			$arCurPayOffer = $wpdb->get_results("SELECT * FROM ". $table . " WHERE order_id_pay = '" . $orderid . "'", ARRAY_A);
			$payStatus=[
			'approved'=>  'Операция удержания (холдирования) суммы',
			'declinedByTimeout' => 'Операция отклонения заказа по истечении его времени жизни',
			'deposited' => 'Оплата прошла успешно',
			'reversed' => 'Оплата отменена',
			'refunded' => 'Операция возврата'
			];
			$status=true;
				foreach ( $arCurPayOffer as $offer ) {
					$checklistoffer .= "<p>" . $offer['cart_item_name'] . " - " . $offer['cart_item_price']/100 . "&nbsp;руб. </p>";
					$status = ($status && $offer['order_pay_status']);
					$oper = $offer['operation'];
				}?>
			<div class="mkd-title mkd-standard-type mkd-content-center-alignment mkd-animation-no" style="color:#ffffff;;background-color:#4564fd;;height:60px;" data-height="60">
				<div class="mkd-title-image"></div>
				<div class="mkd-title-holder" style="height:60px;">
					<div class="mkd-container clearfix">
						<div class="mkd-container-inner">
							<div class="mkd-title-subtitle-holder" style="">
								<div class="mkd-title-subtitle-holder-inner">
									<h1 style="color:#ffffff;">Спасибо, что остаётесь с нами!</h1>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="pay_from_url mkd-container">
				<div class="mkd-container-inner clearfix">
					<h2>Ваш заказ принят.</h2>
					<?$payStatusText = !empty($payStatus[$oper]) ? $payStatus[$oper] : 'Нет данных об оплате.'; 
					echo  "<p>Статус оплаты: " . $payStatusText . "</p>";?> 
					<h3>Состав заказа:</h3>
					<?=$checklistoffer;?>		
				</div>
			</div>
			<?
				file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", "\n" . $today . "___" ."\nCALLBACK  Данные для 1с из БД:\n" . var_export($arCurPayOffer, true), FILE_APPEND);
				file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", "\n" . $today . "___" 
				."\nCALLBACK  status: " . 	var_export($status, true)
				. "" . var_export($arCurPayOffer, true), FILE_APPEND);
			if(!$status)
			{?>
		<style>
			.loading {
				width: 60px;
				height: 34px;
				/* position: absolute; */
				/* top: 50%; */
				/* left: 50%; */
				margin: 25px auto;
				transform: rotate(45deg);
				display: block;
				position: relative;
			}
			.loading div {
			width: 8px;
			height: 8px;
			background: #215ca7;
			border-radius: 100%;
			float: left;
			margin-bottom: 12px;
			animation: scaleDot 1.5s cubic-bezier(0, -0.12, 0.68, 1.42) infinite;
			}
			.loading div:not(:nth-child(3n)) {
			margin-right: 12px;
			}
			.loading div:nth-child(1) {
			animation-delay: 0;
			}
			.loading div:nth-child(2), .loading div:nth-child(4) {
			animation-delay: 0.1s;
			}
			.loading div:nth-child(3),
			.loading div:nth-child(6),
			.loading div:nth-child(9) {
			animation-delay: 0.2s;
			}
			.loading div:nth-child(4),
			.loading div:nth-child(7),
			.loading div animation-delay: .3s,
			.loading div:nth-child(8),
			.loading div animation-delay: .4s {
			animation-delay: 0.5s;
			}
			@-moz-keyframes scaleDot {
			40% {
				transform: scale(1.3) translate(-2px, -2px);
			}
			80% {
				transform: scale(1);
			}
			100% {
				transform: scale(1);
			}
			}
			@-webkit-keyframes scaleDot {
			40% {
				transform: scale(1.3) translate(-2px, -2px);
			}
			80% {
				transform: scale(1);
			}
			100% {
				transform: scale(1);
			}
			}
			@-o-keyframes scaleDot {
			40% {
				transform: scale(1.3) translate(-2px, -2px);
			}
			80% {
				transform: scale(1);
			}
			100% {
				transform: scale(1);
			}
			}
			@keyframes scaleDot {
			40% {
				transform: scale(1.3) translate(-2px, -2px);
			}
			80% {
				transform: scale(1);
			}
			100% {
				transform: scale(1);
			}
			}
		</style>
		<div class="mkd-container-inner clearfix">
				<div class="loading">
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
				</div><p>Проверяем оплату заказа. Через некоторое время страница обновится.</p>
				</div>
				<?
				/* Перенаправление клиента на страницу оплаты */
			//	header('refresh:10;');		
			}
			}?>
			<?if(isset($_GET['mdOrder']) && isset($_GET['operation'])){
				/*
			Успех:
			*/
			$operationText=[
			'approved'=>  'операция удержания (холдирования) суммы',
			'declinedByTimeout' => 'операция отклонения заказа по истечении его времени жизни',
			'deposited' => 'операция завершения',
			'reversed' => 'операция отмены',
			'refunded' => 'операция возврата'
			];
			/* Тут нужно сохранить ID платежа в своей БД - $res['orderId'] */
			
			if(!isset($_GET['operation']) || $_GET['operation']!='deposited') $status = 0;
			else $status = $_GET['status'];
	// $mess = $operationText[$_GET['operation']];
		$mess = htmlspecialchars($_GET['operation']);
			$wpdb->update( $table, [ 'order_pay_status' => $status, 'operation'=>$mess ], [ 'order_id_pay' => $orderid, 'order_id_site' => $_GET['orderNumber'] ]);
			
			file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", "\n" . $today . "___" ."\nCALLBACK  Lsat query\n" . $wpdb->last_query, FILE_APPEND);
			
			$arCurPayOffer = $wpdb->get_results("SELECT * FROM ". $table . " WHERE order_id_pay = '" . $orderid . "'", ARRAY_A);
			file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", "\n" . $today . "___" ."\nCALLBACK  Данные для 1с из БД:\n" . var_export($arCurPayOffer, true), FILE_APPEND);

			$dataDopOfferBegin = '{"name": {"#type": "jxs:string","#value": "PaidOffers"},"Value": {"#type": "jv8:Array","#value": [';
			$dataDopOfferEnd = ']}}';
			$dataAllOfferEnd = ']}';
			$delim="";
			$AddOfferId = false;
			foreach ( $arCurPayOffer as $offer ) {
				if(!$AddOfferId){
					//$orderIdInSite = $offer['order_id_site'];
					$dataMainOffer = '{"#type": "jv8:Structure","#value": [{"name": {"#type": "jxs:string","#value": "OfferID"},
					"Value": {"#type": "jxs:string","#value": "' . $offer['offer_id_from_one_c'] .'"}},';
					$AddOfferId = true;
				} 
				$dataDopOffer .= $delim . '{"#type": "jxs:string","#value": "' . $offer['cart_item_uid'] .'"}';
				$delim =",";				
			}
			$dataFor1C = '' . $dataMainOffer . $dataDopOfferBegin . $dataDopOffer . $dataDopOfferEnd . $dataAllOfferEnd;
			
			unset ($AddOfferId,$dataMainOffer,$dataDopOfferBegin,$dataDopOffer,$dataDopOfferEnd,$dataAllOfferEnd);
			file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", "\n" . $today . "___" ."\nДанные для 1с об оплате:\n" . var_export($dataFor1C, true), FILE_APPEND);
			$idc = Connect1C($optionsPlugin);
			$ret1c = PutData($idc, $dataFor1C);
				$aa=$ret1c->return; 
			$dataResult = json_decode($aa, 1);				
			file_put_contents(ABSPATH."/wp-content/logsfileoplata.txt", "\n" . $today . "___" ."\nВернулись данные из 1с:\n" . var_export($dataResult, true), FILE_APPEND);?>
<?		}
}?>
		</div><!-- close div.content_inner -->
</div>
<?get_footer();