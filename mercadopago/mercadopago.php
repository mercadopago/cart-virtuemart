<?php

defined('_JEXEC') or die('Restricted access');

if (!class_exists('vmPSPlugin')) {
	require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
}

if (!class_exists('MP')) {
	require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'mercadopago'.DS.'mercadopago'.DS.'lib'.DS.'mercadopago.php');
}

if (!class_exists('MercadoPagoHelper')) {
	require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'mercadopago'.DS.'mercadopago'.DS.'helpers'.DS.'mercadopago.php');
}

if (!class_exists('VirtueMartCart')) {
	require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
}

class plgVmPaymentMercadoPago extends vmPSPlugin {

	private $mercadopago_payment_status;

	function __construct(&$subject, $config) {


		parent::__construct($subject, $config);

		$this->_loggable = TRUE;
		$this->tableFields = array_keys($this->getTableSQLFields());
		$this->_tablepkey = 'id';
		$this->_tableId = 'id';

		$this->mercadopago_payment_status = array(
			'pending',
			'approved',
			'in_process',
			'in_mediation',
			'rejected',
			'cancelled',
			'refunded',
			'charged_back'
		);

		$varsToPush = array(
			'mercadopago_site_id' => array('', 'char'),
			'mercadopago_sponsor_id' => array('', 'char'),

			'mercadopago_client_id' => array('', 'char'),
			'mercadopago_client_secret' => array('', 'char'),
			'mercadopago_type_checkout' => array('', 'char'),
			'mercadopago_auto_redirect' => array('', 'char'),
			'mercadopago_max_installments' => array('', 'char'),
			'mercadopago_exclude_payment_methods' => array('', 'char'),
			'mercadopago_width_iframe' => array('', 'char'),
			'mercadopago_height_iframe' => array('', 'char'),
			'mercadopago_sandbox' => array('', 'char'),

			'mercadopago_ipn_approved' => array('', 'char'),
			'mercadopago_ipn_pending' => array('', 'char'),
			'mercadopago_ipn_in_process' => array('', 'char'),
			'mercadopago_ipn_in_mediation' => array('', 'char'),
			'mercadopago_ipn_refunded' => array('', 'char'),
			'mercadopago_ipn_charged_back' => array('', 'char'),
			'mercadopago_ipn_cancelled' => array('', 'char'),
			'mercadopago_ipn_rejected' => array('', 'char'),


			'mercadopago_log' => array('', 'char'),
			'mercadopago_category' => array('', 'char'),
			'payment_logos' => array('', 'char')

		);

		$this->setConfigParameterable($this->_configTableFieldName, $varsToPush);
	}

	public function getVmPluginCreateTableSQL() {
		return $this->createTableSQL('Mercado Pago Table');
	}

	public function getTableSQLFields() {
		return array(
			'id'                            => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT',
			'virtuemart_order_id' 					=> 'int(1) UNSIGNED',
			'order_number'                  => 'char(64)',
			'virtuemart_paymentmethod_id'   => 'mediumint(1) UNSIGNED',
			'payment_name'                  => 'varchar(5000)',
			'payment_order_total'           => 'decimal(15,5) NOT NULL',
			'payment_currency'              => 'smallint(1)',
			'cost_per_transaction'          => 'decimal(10,2)',
			'cost_percent_total'            => 'decimal(10,2)',
			'tax_id'                        => 'smallint(1)',
			'reference'                     => 'char(32)'
		);
	}


	/**
	 * plgVmOnCheckAutomaticSelectedPayment
	 * Checks how many plugins are available. If only one, the user will not have the choice. Enter edit_xxx page
	 * The plugin must check first if it is the correct type
	 *
	 * @author Valerie Isaksen
	 * @param VirtueMartCart cart: the cart object
	 * @return null if no plugin was found, 0 if more then one plugin was found,  virtuemart_xxx_id if only one plugin is found
	 *
	 */

	function plgVmOnCheckAutomaticSelectedPayment (VirtueMartCart $cart, array $cart_prices = array(), &$paymentCounter) {
		return $this->onCheckAutomaticSelected ($cart, $cart_prices, $paymentCounter);
	}


	/**
	* plgVmDisplayListFEPayment
	* This event is fired to display the pluginmethods in the cart (edit shipment/payment) for exampel
	*
	* @param object $cart Cart object
	* @param integer $selected ID of the method selected
	* @return boolean True on succes, false on failures, null when this plugin was not selected.
	* On errors, JError::raiseWarning (or JError::raiseError) must be used to set a message.
	*
	* @author Valerie Isaksen
	* @author Max Milbers
	*/
	public function plgVmDisplayListFEPayment(VirtueMartCart $cart, $selected = 0, &$htmlIn) {
		$this->displayListFE($cart, $selected, $htmlIn);
		return true;
	}



	/**
	* Check if the payment conditions are fulfilled for this payment method
	* @author: Valerie Isaksen
	* @param $cart_prices: cart prices
	* @param $payment
	* @return true: if the conditions are fulfilled, false otherwise
	*/

	//função responsável por aparecer o metodo de pagamento na listagem de selecao
	protected function checkConditions($cart, $method, $cart_prices) {

		//$this->convert ($method);

		$address = (($cart->ST == 0) ? $cart->BT : $cart->ST);

		$amount = $cart_prices['salesPrice'];

		$countries = array();
		if (!empty($method->countries)) {
			if (!is_array ($method->countries)) {
				$countries[0] = $method->countries;
			} else {
				$countries = $method->countries;
			}
		}
		// probably did not gave his BT:ST address
		if (!is_array ($address)) {
			$address = array();
			$address['virtuemart_country_id'] = 0;
		}

		if (!isset($address['virtuemart_country_id'])) {
			$address['virtuemart_country_id'] = 0;
		}

		if (in_array ($address['virtuemart_country_id'], $countries) || count ($countries) == 0) {
			return TRUE;
		}

		return FALSE;
	}

	// Função responsável por selecionar o meio de pagamento no seletor de
	// pagamentos no checkout
	public function plgVmOnSelectedCalculatePricePayment(VirtueMartCart $cart, array &$cart_prices, &$cart_prices_name) {
		return $this->onSelectedCalculatePrice($cart, $cart_prices, $cart_prices_name);
	}

	function setVariablesMP($payment_method){

		$sponsor_id = "";

		if($payment_method->mercadopago_sponsor_id == "VALUE_SPONSOR_ID"){
			$mercadopago = new MP($payment_method->mercadopago_client_id, $payment_method->mercadopago_client_secret);

			//get info user
			$request = array(
				"uri" => "/users/me",
				"params" => array(
					"access_token" => $mercadopago->get_access_token()
				)
			);

			$user = MPRestClient::get($request);

			//check is a test
			if ($user['status'] == 200 && !in_array("test_user", $user['response']['tags'])) {
				switch ($user['response']['site_id']) {
					case 'MLA':
						$sponsor_id = 210948971;
						break;
					case 'MLB':
						$sponsor_id = 210947592;
						break;
					case 'MLC':
						$sponsor_id = 210948392;
						break;
					case 'MCO':
						$sponsor_id = 210946379;
						break;
					case 'MLM':
						$sponsor_id = 210949235;
						break;
					case 'MPE':
						$sponsor_id = 217174514;
						break;
					case 'MLV':
						$sponsor_id = 210946191;
						break;
					default:
					$sponsor_id = "";
					break;
				}
			}

			$db = JFactory::getDBO();
			$query = "SELECT `payment_params` FROM `#__virtuemart_paymentmethods` WHERE `virtuemart_paymentmethod_id`= " . $payment_method->virtuemart_paymentmethod_id;
			$db->setQuery($query);
			$params = $db->loadResult();

			//replace value in params
			$params = str_replace("VALUE_SPONSOR_ID", $sponsor_id, $params);
			$params = str_replace("VALUE_SITE_ID", $user['response']['site_id'], $params);
			$query = "UPDATE `#__virtuemart_paymentmethods` SET payment_params = '".$params."' WHERE `virtuemart_paymentmethod_id`= " . $payment_method->virtuemart_paymentmethod_id;

			$this->logInfo('Query to execute: ' . $query, 'debug');

			$db->setQuery($query);
			$resultado = $db->execute();

			if(!$resultado){
				$this->logInfo('Erro in save sponsor_id and site_id... ', 'debug');
			}

			//set value in object
			$payment_method->mercadopago_site_id = $user['response']['site_id'];
			$payment_method->mercadopago_sponsor_id = $sponsor_id;
		}

		return $payment_method;

	}

	function plgVmConfirmedOrder($cart, $order) {
		if (!($payment_method = $this->getVmPluginMethod($cart->virtuemart_paymentmethod_id))) {
			return NULL;
		}

		if (!$this->selectedThisElement($payment_method->payment_element)) {
			return FALSE;
		}

		// //add order
		// $this->getPaymentCurrency($method);
		// $paymentCurrency = CurrencyDisplay::getInstance($method->payment_currency);
		// $total_payment_currency = round($paymentCurrency->convertCurrencyTo($method->payment_currency, $order['details']['BT']->order_total, FALSE), 2);
		//
		// $dbValues['payment_name'] = $this->renderPluginName($method);
		// $dbValues['order_number'] = $order['details']['BT']->order_number;
		// $dbValues['virtuemart_paymentmethod_id'] = $order['details']['BT']->virtuemart_paymentmethod_id;
		// $dbValues['cost_per_transaction'] = (!empty($method->cost_per_transaction) ? $method->cost_per_transaction : 0);
		// $dbValues['cost_percent_total'] = (!empty($method->cost_percent_total) ? $method->cost_percent_total : 0);
		// $dbValues['payment_currency'] = 'BRL';
		// $dbValues['payment_order_total'] = $total_payment_currency;
		// $dbValues['reference'] = $order['details']['BT']->virtuemart_order_id;
		//
		// // storing data order into plugin data table
		// $this->storePSPluginInternalData($dbValues);

		$this->_debug = $payment_method->mercadopago_log == "true" ? TRUE : FALSE;

		$this->logInfo('--------------------------------------', 'debug');
		$this->logInfo('Create Preference: ' . date("Y-m-d H:i:s"), 'debug');
		$this->logInfo('Order Data: ' . json_encode($order['details']), 'debug');

		$payment_method = $this->setVariablesMP($payment_method);

		$mercadopago = new MP($payment_method->mercadopago_client_id, $payment_method->mercadopago_client_secret);

		$mercadopago->sandbox_mode($payment_method->mercadopago_sandbox == "true"? true : null);

		$preference = array();

		$preference["external_reference"] = $order['details']['BT']->virtuemart_order_id;

		$preference["items"] = array();

		foreach ($cart->products as $product) {
			$preference["items"][] = array(
				"title" => $product->product_name,
				"description" => substr($product->product_desc, 0, 200),
				"quantity" => $product->quantity,
				"picture_url" => JURI::root() . $product->file_url,
				"category_id" => $payment_method->mercadopago_category,
				//não pegar o valor do produto e sim o valor de venda dele
				//"unit_price" => (float) $product->prices['product_price']
				"unit_price" => (float) $product->prices['salesPrice']

			);
		}

		// case exist bill discount in store
		if($cart->cartPrices['billDiscountAmount'] < 0){
			$preference["items"][] = array(
				"description" => "Bill Discount Amount",
				"quantity" => 1,
				"unit_price" => (float) $cart->cartPrices['billDiscountAmount']
			);
		}

		$preference["payer"]["name"] = $order['details']['BT']->first_name;
		$preference["payer"]["surname"] = $order['details']['BT']->last_name;
		$preference["payer"]["email"] = $order['details']['BT']->email;

		//Valida se existe telefone disponível
		$phone = $order['details']['BT']->phone_1;
		if($phone == null){
			$phone = $order['details']['BT']->phone_2;
			if($phone == null && isset($order['details']['ST'])){
				$phone = $order['details']['ST']->phone_1;
				if($phone == null){
					$phone = $order['details']['ST']->phone_2;
				}
			}
		}

		//$preference["payer"]["phone"]["area_code"] = "";
		$preference["payer"]["phone"]["number"] = $phone;

		$preference["payer"]["address"]["zip_code"] = $order['details']['BT']->zip;
		$preference["payer"]["address"]["street_name"] = $order['details']['BT']->address_1 . " - " .  $order['details']['BT']->address_2 . " - " . $order['details']['BT']-> city;
		//$preference["payer"]["address"]["street_number"] = "";

		// Shipments
		//$preference["shipments"]["mode"] = "custom";

		// busca o valor de venda e não o valor real da entrega
		// $preference["shipments"]["cost"] = $cart->cartPrices['shipmentValue'];
		$preference["shipments"]["cost"] = $cart->cartPrices['salesPriceShipment'];

		//caso não existe ST usa o BT
		$shipments;

		if(isset($order['details']['ST'])){
			$shipments = $order['details']['ST'];
		}else{
			$shipments = $order['details']['BT'];
		}

		$preference["shipments"]["receiver_address"]["zip_code"] = $shipments->zip;
		$preference["shipments"]["receiver_address"]["street_name"] = $shipments->address_1 . " " .  $shipments->address_2 . " - " . $shipments-> city;
		// $preference["shipments"]["receiver_address"]["street_number"] = "";
		// $preference["shipments"]["receiver_address"]["floor"] = "";
		// $preference["shipments"]["receiver_address"]["apartment"] = "";


		$preference["payment_methods"]['installments'] = (int) $payment_method->mercadopago_max_installments;

		// exclude payment methods
		if(isset($config['mercadopago_exclude_payment_methods']) && $payment_method->mercadopago_exclude_payment_methods != ""){
			$preference["payment_methods"]['excluded_payment_methods'] = array();
			$payment_methods = str_replace("[", "", $payment_method->mercadopago_exclude_payment_methods);
			$payment_methods = str_replace("]", "", $payment_methods);
			$payment_methods = explode ( "," , $payment_methods);

			foreach ($payment_methods as $pm) {
				$preference["payment_methods"]["excluded_payment_methods"][] = array(
					"id" => $pm
				);
			}
		}

		$preference["back_urls"]["success"] = JURI::root() . "index.php?option=com_virtuemart&view=pluginresponse&task=pluginresponsereceived&payment_method_id=" . $cart->virtuemart_paymentmethod_id;
		$preference["back_urls"]["pending"] = JURI::root() . "index.php?option=com_virtuemart&view=pluginresponse&task=pluginresponsereceived&payment_method_id=" . $cart->virtuemart_paymentmethod_id;
		$preference["back_urls"]["failure"] = JURI::root() . "index.php?option=com_virtuemart&view=pluginresponse&task=pluginresponsereceived&payment_method_id=" . $cart->virtuemart_paymentmethod_id;

		$preference['auto_return'] = "";

		$preference["notification_url"] = JURI::root() . "index.php?option=com_virtuemart&view=pluginresponse&task=pluginnotification&payment_method_id=" . $cart->virtuemart_paymentmethod_id;

		//caso não seja auto return, limpa o carrinho agora
		if($payment_method->mercadopago_auto_redirect == "true"){
			$preference['auto_return'] = "approved";
		}else{
			//reset cart
			$cart = VirtueMartCart::getCart();
			$cart->emptyCart();
		}



		// add sponsor_id
		if($payment_method->mercadopago_sponsor_id != ""){
			$preference["sponsor_id"] = (int) $payment_method->mercadopago_sponsor_id;
		}

		$this->logInfo('Json Preference: ' . json_encode($preference), 'debug');
		$checkout_preference = $mercadopago->create_preference($preference);
		$this->logInfo('Preference Result: ' . json_encode($checkout_preference), 'debug');

		//init point (sandbox?)
		$init_point = $payment_method->mercadopago_sandbox == "false" ? $checkout_preference['response']['init_point']: $checkout_preference['response']['sandbox_init_point'];

		$html = $this->renderByLayout('mercadopago_checkout',
		array(
			"init_point" => $init_point,
			"banner" => MercadoPagoHelper::getBanner($payment_method->mercadopago_site_id),
			"payment_method" => $payment_method
		)
	);

	JRequest::setVar('html', $html);
	return true;
}


function plgVmOnPaymentResponseReceived(&$html){

	if (!($payment_method = $this->getVmPluginMethod($_REQUEST['payment_method_id']))) {
		return NULL;
	}

	$this->_debug = $payment_method->mercadopago_log == "true" ? TRUE : FALSE;

	$mercadopago = new MP($payment_method->mercadopago_client_id, $payment_method->mercadopago_client_secret);
	$mercadopago->sandbox_mode($payment_method->mercadopago_sandbox == "true" ? true : null);
	$merchant_order = $mercadopago->get_merchant_order($_REQUEST['merchant_order_id']);

	if($merchant_order['status'] != 200){
		return NULL;
	}

	$payment = $merchant_order['response']['payments'][0];

	if(count($merchant_order['response']['payments']) > 1){
			$payment = MercadoPagoHelper::overOnePaymentsIPN($merchant_order['response']);
	}

	$html = $this->renderByLayout('mercadopago_finish_checkout',
		array(
			"payment" => $payment
		)
	);

	//reset cart
	$cart = VirtueMartCart::getCart();
	$cart->emptyCart();

	JRequest::setVar('html', $html);
	return TRUE;
}

// url notification
// index.php?option=com_virtuemart&view=pluginresponse&task=pluginnotification&pm=mercadopago
function plgVmOnPaymentNotification(){
	$status_http = "HTTP/1.1 200 OK";

	if (!($payment_method = $this->getVmPluginMethod($_REQUEST['payment_method_id']))) {
		return NULL;
	}

	$this->_debug = $payment_method->mercadopago_log == "true" ? TRUE : FALSE;

	$this->logInfo('--------------------------------------', 'debug');
	$this->logInfo('Notification: ' . date("Y-m-d H:i:s"), 'debug');
	$this->logInfo('Request data: ' . json_encode($_REQUEST), 'debug');

	if(isset($_REQUEST['topic']) && isset($_REQUEST['id']) && !is_null($_REQUEST['id'])){
		if($_REQUEST['topic'] == "merchant_order"){
			//$config = $this->_getConfigMP();
			$mercadopago = new MP($payment_method->mercadopago_client_id, $payment_method->mercadopago_client_secret);
			$mercadopago->sandbox_mode($payment_method->mercadopago_sandbox == "true" ? true : null);

			$merchant_order = $mercadopago->get_merchant_order($_REQUEST['id']);
			$this->logInfo('Merchant Order Result: ' . json_encode($merchant_order), 'debug');

			if($merchant_order['status'] == 200){
				if($merchant_order['response']['status'] == "closed"){

					$payment = $merchant_order['response']['payments'][0];
					$payment_status = $payment['status'];


					//two cards
					if(count($merchant_order['response']['payments']) > 1){
							$payment = MercadoPagoHelper::overOnePaymentsIPN($merchant_order['response']);
							$payment_status = $payment['status'];
					}

					if(!in_array($payment_status, $this->mercadopago_payment_status)){
						$this->logInfo('Payment status no mapped: ' . $payment_status, 'debug');
						$payment_status = 'pending';
					}

					$orderModel=VmModel::getModel('orders');
					$order = $orderModel->getOrder($merchant_order['response']['external_reference']);

					//set comments
					$comment = vmText::_('VMPAYMENT_MERCADOPAGO_AUMATIC_NOTIFICATION') . "\n";
					$comment .= vmText::_('VMPAYMENT_MERCADOPAGO_PAYMENT_ID') . ": " . $payment['id'] . "\n";
					$comment .= vmText::_('VMPAYMENT_MERCADOPAGO_PAYMENT_STATUS') . ": " .vmText::_('VMPAYMENT_MERCADOPAGO_PAYMENT_STATUS_' . strtoupper($payment_status)) ;

					//set status in order and comments
					$params_ipn = "mercadopago_ipn_" . $payment_status;

					//verifica se a notificação não é repitida
					if($payment_method->$params_ipn != $order['details']['BT']->order_status){

						$order['order_status'] = $payment_method->$params_ipn;
						$order['customer_notified'] = 1;
						$order['comments'] =  $comment;

						$order_status = $orderModel->updateStatusForOneOrder ($order['details']['BT']->virtuemart_order_id, $order, TRUE);
						if(!$order_status){
							//if dont saved
							echo "We could not save the order: " . $order_status;
							$this->logInfo("We could not save the order: " . $order_status, 'debug');
							$status_http = "HTTP/1.1 500 Internal Server Error";

						} //end order status

						$this->logInfo("Order status save: " . $payment_method->$params_ipn, 'debug');
						$this->logInfo("Comments " . $comment, 'debug');
					}

					echo $comment;
				}

			}else{
				$status_http = "HTTP/1.1 404 Not Found";
			} //end if status 200
		} //end if merchant_order
	}

	$this->logInfo("Http Status: " . $status_http, 'debug');
	header($status_http);
	exit;

}


/**
* Create the table for this plugin if it does not yet exist.
* This functions checks if the called plugin is active one.
* When yes it is calling the standard method to create the tables
*
* @author Valérie Isaksen
*
*/

function plgVmOnStoreInstallPaymentPluginTable ($jplugin_id) {
	return $this->onStoreInstallPluginTable ($jplugin_id);
}

/**
* Fired in payment method when click save into
* payment method info view
* @param String $name
* @param Integer $id
* @param String $table
* @return bool
*/

function plgVmDeclarePluginParamsPayment($name, $id, &$data) {
	return $this->declarePluginParams('payment', $name, $id, $data);
}

/**
*
* Função responsavel por mostrar os valores salvos dentro dos inputs
* na tela de configuração
*/

function plgVmDeclarePluginParamsPaymentVM3( &$data) {
	return $this->declarePluginParams('payment', $data);
}

/**
*
* Função acionada no botão save
*
*/
function plgVmSetOnTablePluginParamsPayment($name, $id, &$table) {
	return $this->setOnTablePluginParams($name, $id, $table);
}

}
