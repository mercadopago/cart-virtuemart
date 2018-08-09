<?php
/**
* Mercado Pago plugin
*
* @author Developers Mercado Pago <modulos@mercadopago.com>
* @version 2.2.0
* @package VirtueMart
* @subpackage payment
* @link https://www.mercadopago.com
* @copyright Copyright © 2016 MercadoPago.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

defined('_JEXEC') or die('Restricted access');
define("MP_MODULE_VERSION", _getVersionMPModule());
define("VIRTUEMART_VERSION", _getVersionVirtueMart());

if (!class_exists('vmPSPlugin')) {
	require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
}

if (!class_exists('MP')) {
	require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'mercadopago'.DS.'mercadopago'.DS.'lib'.DS.'mercadopago.php');
}

if (!class_exists('MercadoPagoHelper')) {
	require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'mercadopago'.DS.'mercadopago'.DS.'helpers'.DS.'mercadopago.php');
}

function _getVersionMPModule() {
	$version = "not_defined";
	if(method_exists(JFactory,'getXML')){
		$xml = JPATH_SITE .'/plugins/vmpayment/mercadopago/mercadopago.xml';
		$xml = JFactory::getXML($xml);
		$version = (string) $xml->version;
	}
	return $version;
}

function _getVersionVirtueMart(){
	$version = "not_defined";
	if(method_exists(JFactory,'getXML')){
		$xml = JPATH_SITE .'/modules/mod_virtuemart_cart/mod_virtuemart_cart.xml';
		$xml = JFactory::getXML($xml);
		$version = (string) $xml->version;
	}
	return $version;
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

			'mercadopago_product_checkout' => array('', 'char'),

			'mercadopago_client_id' => array('', 'char'),
			'mercadopago_client_secret' => array('', 'char'),
			'mercadopago_type_checkout' => array('', 'char'),
			'mercadopago_auto_redirect' => array('', 'char'),
			'mercadopago_two_cards' => array('', 'char'),
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
			'payment_logos' => array('', 'char'),

			//checkout custom
			'mercadopago_access_token' => array('', 'char'),
			'mercadopago_public_key' => array('', 'char'),
			'mercadopago_binary_mode' => array('', 'char'),
			'mercadopago_statement_descriptor' => array('', 'char'),
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

		$htmla = array();
		$pm;

		if ($this->getPluginMethods($cart->vendorId) === 0) {
			return false;
		}

		JHTML::script("https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js");
		vmJsApi::css('custom_checkout_mercadopago', 'plugins/vmpayment/mercadopago/mercadopago/assets/css');
		vmJsApi::css('custom_checkout_ticket_mercadopago', 'plugins/vmpayment/mercadopago/mercadopago/assets/css');

		foreach ($this->methods as $payment_method) {
			//open checkout
			$this->_openCheckout($payment_method);

			if ($this->checkConditions($cart, $payment_method, $cart->cartPrices)) {

				//set variable mercado pago
				$payment_method = $this->setVariablesMP($payment_method);
				$this->_debug = $payment_method->mercadopago_log == "true" ? TRUE : FALSE;

				$cartPrices=$cart->cartPrices;

				$cost_method = true;

				if (isset($payment_method->cost_method)) {
					$cost_method = $payment_method->cost_method;
				}

				$methodSalesPrice = $this->setCartPrices($cart, $cartPrices, $payment_method);

				//add radio buton to select in checkout
				$html = $this->getPluginHtml($payment_method, $selected, $methodSalesPrice);

				//show html when selected
				if ($this->checkConditions($cart, $selected, $cart->cartPrices) && $selected == $payment_method->virtuemart_paymentmethod_id) {

					if($payment_method->mercadopago_product_checkout == "custom_credit_card"){

						$payer_email = $cart->BT['email'];

						if($payer_email == ""){
							$payer_email = $cart->ST['email'];
						}

						$mercadopago = new MP($payment_method->mercadopago_access_token);
						$customer = $mercadopago->get_or_create_customer($payer_email);

						$this->logInfo("Email: {$payer_email} API Customer result:" . json_encode($customer), 'debug');

						$params = array(
							"virtuemart_paymentmethod_id" => $selected,
							"public_key" => $payment_method->mercadopago_public_key,
							"site_id" => $payment_method->mercadopago_site_id,
							"params_mercadopago_custom" => vRequest::getVar('mercadopago_custom'),
							"amount" => $cart->cartPrices['billTotal'],
							"customer" => $customer
						);

						$html .= $this->renderByLayout('mercadopago_checkout_custom', $params);
					}elseif ($payment_method->mercadopago_product_checkout == "custom_ticket") {
						$mercadopago = new MP($payment_method->mercadopago_access_token);
						$list_payment_methods = $mercadopago->get_payment_methods_v1();
						$payment_methods = array();
						foreach ($list_payment_methods['response'] as $key => $pm) {
							if($pm['payment_type_id'] != "credit_card" &&
							$pm['payment_type_id'] != "debit_card" &&
							$pm['payment_type_id'] != "prepaid_card")
							{
								$payment_methods[] = $pm;
							}

						}
						$params = array(
							"virtuemart_paymentmethod_id" => $selected,
							"site_id" => $payment_method->mercadopago_site_id,
							"params_mercadopago_custom_ticket" => vRequest::getVar('mercadopago_custom_ticket'),
							"list_payment_methods" => $payment_methods
						);
						$html .= $this->renderByLayout('mercadopago_checkout_custom_ticket', $params);
					}elseif($payment_method->mercadopago_product_checkout == "basic_checkout") {
						$params = array(
							"virtuemart_paymentmethod_id" => $selected,
							"site_id" => $payment_method->mercadopago_site_id
						);
						$html .= $this->renderByLayout('mercadopago_checkout_standard', $params);
					}


				}
				//create array to add all payment methods active mercado pago
				$htmla[] = $html;
			}
		}




		//add array mercado pago in list payment methods
		$htmlIn[] = $htmla;

		return true;
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
		$site_id = "";
		$mercadopago;

		if($payment_method->mercadopago_product_checkout == "custom_credit_card" || $payment_method->mercadopago_product_checkout == "custom_ticket"){
			$mercadopago = new MP($payment_method->mercadopago_access_token);
		}else{
			$mercadopago = new MP($payment_method->mercadopago_client_id, $payment_method->mercadopago_client_secret);
		}

		if($payment_method->mercadopago_sponsor_id == "VALUE_SPONSOR_ID"){

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
					case 'MLU':
					$sponsor_id = 246379702;
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

		//add order
		VmConfig::loadJLang('com_virtuemart',true);
		VmConfig::loadJLang('com_virtuemart_orders', TRUE);

    if (!class_exists ('VirtueMartModelOrders')) {
        require(VMPATH_ADMIN . DS . 'models' . DS . 'orders.php');
    }

    $this->getPaymentCurrency($payment_method);

    $dbValues['payment_name'] = $this->renderPluginName ($payment_method);
    $dbValues['order_number'] = $cart->order_number;
    $dbValues['virtuemart_paymentmethod_id'] = $cart->virtuemart_paymentmethod_id;
    $dbValues['cost_per_transaction'] = $payment_method->cost_per_transaction;
    $dbValues['cost_percent_total'] = $payment_method->cost_percent_total;
    $dbValues['payment_currency'] = $payment_method->payment_currency;
    $dbValues['payment_order_total'] = $cart->cartPrices['billTotal'];
    $dbValues['tax_id'] = $payment_method->tax_id;
    $this->storePSPluginInternalData ($dbValues);

		$this->_debug = $payment_method->mercadopago_log == "true" ? TRUE : FALSE;
		$payment_method = $this->setVariablesMP($payment_method);

		//checkout type product
		if($payment_method->mercadopago_product_checkout == "custom_credit_card"){
			return $this->postCreditCardPaymentV1($cart, $order, $payment_method);
		}elseif ($payment_method->mercadopago_product_checkout == "custom_ticket") {
			return $this->postTicketPaymentV1($cart, $order, $payment_method);
		}

		return $this->postPreferenceCheckout($cart, $order, $payment_method);
	}

	function postPreferenceCheckout($cart, $order, $payment_method){

		$this->logInfo('--------------------------------------', 'debug');
		$this->logInfo('Create Preference: ' . date("Y-m-d H:i:s"), 'debug');
		$this->logInfo('Order Data: ' . json_encode($order['details']), 'debug');

		$mercadopago = new MP($payment_method->mercadopago_client_id, $payment_method->mercadopago_client_secret);

		$mercadopago->sandbox_mode($payment_method->mercadopago_sandbox == "true"? true : null);

		$preference = array();

		$preference["external_reference"] = $order['details']['BT']->virtuemart_order_id;

		$preference["items"] = $this->getItemsFromCart($cart, $payment_method);

		$preference["payer"]["name"] = $order['details']['BT']->first_name;
		$preference["payer"]["surname"] = $order['details']['BT']->last_name;
		$preference["payer"]["email"] = $order['details']['BT']->email;


		$phone = $this->getPhoneFromOrder($order);

		//$preference["payer"]["phone"]["area_code"] = "";
		$preference["payer"]["phone"]["number"] = $phone;

		$preference["payer"]["address"]["zip_code"] = $order['details']['BT']->zip;
		$preference["payer"]["address"]["street_name"] = $order['details']['BT']->address_1 . " - " .  $order['details']['BT']->address_2 . " - " . $order['details']['BT']-> city;
		//$preference["payer"]["address"]["street_number"] = "";

		// Shipments
		//$preference["shipments"]["mode"] = "custom";

		// busca o valor de venda e não o valor real da entrega
		// $preference["shipments"]["cost"] = $cart->cartPrices['shipmentValue'];
		// $preference["shipments"]["cost"] = (float) $cart->cartPrices['salesPriceShipment'];

		//caso não existe ST usa o BT
		$shipments = $this->getShipmentsFromOrder($order);

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

		$preference["notification_url"] = JURI::root() . "index.php?option=com_virtuemart&view=pluginresponse&task=pluginnotification&mercadopago_type_integration=basic&payment_method_id=" . $cart->virtuemart_paymentmethod_id;

		//caso não seja auto return, limpa o carrinho agora
		if($payment_method->mercadopago_auto_redirect == "true"){
			$preference['auto_return'] = "approved";
		}else{
			//reset cart
			$this->emptyCart();
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

		$params = array(
			"init_point" => $init_point,
			"banner" => MercadoPagoHelper::getBanner($payment_method->mercadopago_site_id),
			"payment_method" => $payment_method
		);

		$this->_closeCheckout($payment_method);
		$html = $this->renderByLayout('mercadopago_checkout_standard_flow', $params);
		JRequest::setVar('html', $html);
		return true;
	}

	function postCreditCardPaymentV1($cart, $order, $payment_method){

		// DELETE
		// require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'mercadopago'.DS.'mercadopago'.DS.'lib'.DS.'test.php');

		$this->logInfo('--------------------------------------', 'debug');
		$this->logInfo('Post Payment V1', 'debug');

		$mercadopago = new MP($payment_method->mercadopago_access_token);
		$params = vRequest::getVar('mercadopago_custom');

		$this->logInfo('PARAMS: ' . json_encode($params), 'debug');

		if($params['paymentMethodId'] == ""){
			$params['paymentMethodId'] = $params['paymentMethodSelector'];
		}

		$payment = array();

		$payment = $this->makePreferenceV1($cart, $order, $payment_method);

		$payment['token'] = $params['token'];

		$payment['payment_method_id'] = $params['paymentMethodId'];

		$payment['statement_descriptor'] = $payment_method->mercadopago_statement_descriptor;

		$payment['installments'] = (int) $params['installments'];
		$tempInstallments = (int) $params['tempInstallments'];
		//case user confirm purchase before mercadopagojs populate selector
		if($payment['installments'] < 0 && $payment['installments'] != "" && $tempInstallments > -1){
			$payment['installments'] = $tempInstallments;
			$this->logInfo('added installment from the temporary params', 'debug');
		}

		$issuer = $params['issuer'];
		if(isset($issuer) && $issuer != "" && $issuer > 1){
			$payment['issuer_id'] = $issuer;
		}

		if($payment_method->mercadopago_binary_mode == "true"){
			$payment['binary_mode'] = true;
		}

		//case user confirm purchase before mercadopagojs populate selector
		$tempIssuer = (int) $params['tempIssuer'];
		if($issuer < 0 && $tempIssuer > -1){
			$payment['issuer_id'] = $tempIssuer;
			$this->logInfo('added issuer_id from the temporary params', 'debug');
		}

		// Flow: for Customer & Cards
		$payer_email = $order['details']['BT']->email;
		$customer = $mercadopago->get_or_create_customer($payer_email);

		if($params['CustomerAndCard'] == 'true'){
			$payment['payer']['id'] = $customer['id'];
		}

		$payment['metadata']['token'] = $params['token'];;
		$payment['metadata']['customer_id'] = $customer['id'];
		// End Flow: for Customer & Cards

		$this->logInfo('Params POST v1 Custom: ' . json_encode($payment), 'debug');


		$payment_result = $mercadopago->create_payment($payment);

		$this->logInfo('Result POST v1 Custom: ' . json_encode($payment_result), 'debug');

		if($payment_result['status'] == 200 || $payment_result['status'] == 201 ){
			$this->_closeCheckout($payment_method, $payment_result['response']['id']);

			// empty car
			$this->emptyCart();

			$params = array(
				"payment" => $payment_result
			);

			$html = $this->renderByLayout('mercadopago_response_custom', $params);
			JRequest::setVar('html', $html);
			return true;

		}else{
			$this->_closeCheckout($payment_method);
			return $this->processErrorV1($payment_result);
		}

	}

	function postTicketPaymentV1($cart, $order, $payment_method){

		$this->_debug = $payment_method->mercadopago_log == "true" ? TRUE : FALSE;

		// DELETE
		// require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'mercadopago'.DS.'mercadopago'.DS.'lib'.DS.'test.php');

		$this->logInfo('--------------------------------------', 'debug');
		$this->logInfo('Post Payment Ticket V1', 'debug');

		$params = vRequest::getVar('mercadopago_custom_ticket');

		$this->logInfo('PARAMS: ' . json_encode($params), 'debug');

		$payment = array();
		$payment = $this->makePreferenceV1($cart, $order, $payment_method);

		$payment['payment_method_id'] = $params['payment_method_id'];

		$this->logInfo('Params POST v1 Custom Ticket: ' . json_encode($payment), 'debug');

		$mercadopago = new MP($payment_method->mercadopago_access_token);
		$payment_result = $mercadopago->create_payment($payment);

		$this->logInfo('Result POST v1 Custom Ticket: ' . json_encode($payment_result), 'debug');

		if($payment_result['status'] == 200 || $payment_result['status'] == 201 ){
			$this->_closeCheckout($payment_method, $payment_result['response']['id']);

			// empty car
			$this->emptyCart();

			$params = array(
				"payment" => $payment_result
			);

			$html = $this->renderByLayout('mercadopago_response_custom_ticket', $params);
			JRequest::setVar('html', $html);
			return true;

		}else{
			$this->_closeCheckout($payment_method);
			return $this->processErrorV1($payment_result);
		}
	}

	function makePreferenceV1($cart, $order, $payment_method){
		$vendorModel = VmModel::getModel('vendor');
		$this->vendor = $vendorModel->getVendor();

		$payment = array();

		$payment['description'] = $this->vendor->vendor_store_name . " - " . $order['details']['BT']->virtuemart_order_id;

		$payment['transaction_amount'] = (float) number_format($cart->cartPrices['billTotal'], 2, '.', '');

		$payment['external_reference'] = $order['details']['BT']->virtuemart_order_id;

		$notification_url = JURI::root() . "index.php?option=com_virtuemart&view=pluginresponse&task=pluginnotification&mercadopago_type_integration=custom&payment_method_id=" . $cart->virtuemart_paymentmethod_id;

		if(strpos($notification_url, "localhost") === false){
			$payment['notification_url'] = $notification_url;
		}

		//payer email
		$payment['payer']['email'] = $order['details']['BT']->email;


		// Additional Info
		// Items Info
		$payment['additional_info']['items'] = $this->getItemsFromCart($cart, $payment_method);


		//Valida se existe telefone disponível
		$phone = $this->getPhoneFromOrder($order);

		// Payer Info
		$payment['additional_info']['payer']['first_name'] = $order['details']['BT']->first_name;
		$payment['additional_info']['payer']['last_name'] = $order['details']['BT']->last_name;
		// $payment['additional_info']['payer']['registration_date'] = "2015-06-02T12:58:41.425-04:00";
		// $payment['additional_info']['payer']['phone']['area_code'] = "11";
		$payment['additional_info']['payer']['phone']['number'] = $phone;
		$payment['additional_info']['payer']['address']['street_name'] = $order['details']['BT']->address_1 . " - " .  $order['details']['BT']->address_2 . " - " . $order['details']['BT']->city;
		$payment['additional_info']['payer']['address']['zip_code'] = $order['details']['BT']->zip;
		// $payment['additional_info']['payer']['address']['street_number'] = (int) 123;

		$shipments = $this->getShipmentsFromOrder($order);

		// Shipments Info
		$payment['additional_info']['shipments']['receiver_address']['zip_code'] = $shipments->zip;
		$payment['additional_info']['shipments']['receiver_address']['street_name'] = $shipments->address_1 . " " .  $shipments->address_2 . " - " . $shipments->city;
		// $payment['additional_info']['shipments']['receiver_address']['street_number'] = (int) 123;
		// $payment['additional_info']['shipments']['receiver_address']['floor'] = (int) "";
		// $payment['additional_info']['shipments']['receiver_address']['apartment'] = "";

		// add sponsor_id
		if($payment_method->mercadopago_sponsor_id != ""){
			$payment["sponsor_id"] = (int) $payment_method->mercadopago_sponsor_id;
		}

		return $payment;
	}

	function getItemsFromCart($cart, $payment_method){
		$total_sum = 0;
		$total_order = (float) ($cart->cartPrices['billTotal'] - $cart->cartPrices['salesPriceShipment']);
		$items = array();

		foreach ($cart->products as $product) {
			$items[] = array(
				"title" => $product->product_name,
				"description" => substr($product->product_desc, 0, 200),
				"quantity" => $product->quantity,
				"picture_url" => JURI::root() . $product->file_url,
				"category_id" => $payment_method->mercadopago_category,
				//não pegar o valor do produto e sim o valor de venda dele
				//"unit_price" => (float) $product->prices['product_price']
				// "unit_price" => (float) number_format($product->prices['salesPrice'], 2)
				"unit_price" => (float) $product->prices['salesPrice']
			);

			$total_sum += (float) ($product->prices['salesPrice'] *  $product->quantity);
		}

		$items[] = array(
			"title" => $items[0]['title'] . " - Shipment",
			"description" => $items[0]['description'] . " - Shipment",
			"quantity" => 1,
			"unit_price" => (float) $cart->cartPrices['salesPriceShipment']
		);

		if($total_sum != $total_order){
			// check diff
			$tax_or_disccount = $total_order - $total_sum;

			$items[] = array(
				"title" => $items[0]['title'],
				"description" => $items[0]['description'],
				"quantity" => 1,
				"unit_price" => (float) $tax_or_disccount
			);

			$this->logInfo("Total: {$total_sum} Total Order: {$total_order} Amount tax or discount (diff): {$tax_or_disccount}", 'debug');
		}


		return $items;
	}

	function getShipmentsFromOrder($order){
		$shipments;

		if(isset($order['details']['ST'])){
			$shipments = $order['details']['ST'];
		}else{
			$shipments = $order['details']['BT'];
		}

		return $shipments;
	}

	function getPhoneFromOrder($order){
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

		return $phone;
	}

	function processErrorV1($response){
		$message = "";
		foreach ($response['response']['cause'] as $causes) {
			$string_translate = 'VMPAYMENT_MERCADOPAGO_POST_PAYMENT_ERROR_' . $causes['code'];
			$message = vmText::_($string_translate);

			//case not exist translate for code
			if($string_translate == $message){
				$message = $causes['description'];
			}
		}

		$this->logInfo('Error message: ' . $message, 'debug');
		$this->redirectOnMsg($message, 'Error');
		return false;
	}

	function redirectOnMsg($message, $type){
		// Warning
		// Message
		// Notice
		// Error
		$app = JFactory::getApplication();
		$app->enqueueMessage($message, $type);
		$app->redirect(JRoute::_('index.php?option=com_virtuemart&view=cart&Itemid=' . vRequest::getInt('Itemid'), false));
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

		$params = array(
			"payment" => $payment
		);

		$html = $this->renderByLayout('mercadopago_response_standard', $params);

		//reset cart
		$this->emptyCart();

		JRequest::setVar('html', $html);
		return TRUE;
	}

	// url notification
	// index.php?option=com_virtuemart&view=pluginresponse&task=pluginnotification&pm=mercadopago
	function plgVmOnPaymentNotification(){

		if (!($payment_method = $this->getVmPluginMethod($_REQUEST['payment_method_id']))) {
			return NULL;
		}

		$this->_debug = $payment_method->mercadopago_log == "true" ? TRUE : FALSE;

		$this->logInfo('--------------------------------------', 'debug');
		$this->logInfo('Notification: ' . date("Y-m-d H:i:s"), 'debug');
		$this->logInfo('Request data: ' . json_encode($_REQUEST), 'debug');


		if($_REQUEST['mercadopago_type_integration'] == 'custom'){
			$status_http = $this->paymentNotificationV1($payment_method);
		}elseif ($_REQUEST['mercadopago_type_integration'] == 'basic') {
			$status_http = $this->paymentNotificationBasic($payment_method);
		}

		$this->logInfo("Http Status: " . $status_http, 'debug');
		header($status_http);
		exit;
	}

	function paymentNotificationBasic($payment_method){
		$status_http = "HTTP/1.1 200 OK";

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

						$external_reference = $merchant_order['response']['external_reference'];

						//update order
						$status_http = $this->updateStatusOrder($payment_method, $external_reference, $payment_status, $payment);
					}

				}else{
					$status_http = "HTTP/1.1 404 Not Found";
				} //end if status 200
			} //end if merchant_order
		}

		return $status_http;

	}

	function paymentNotificationV1($payment_method){
		$status_http = "HTTP/1.1 200 OK";

		if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'payment' && isset($_REQUEST['data_id'])){
			$payment_id = $_REQUEST['data_id'];

			$mercadopago = new MP($payment_method->mercadopago_access_token);
			$payment = $mercadopago->get_paymentV1($payment_id);

			$this->logInfo("Get Payment Result: " . json_encode($payment), 'debug');

			if($payment['status'] == 200){

				$payment = $payment['response'];

				//flow to create card in customer
				if($payment_method->mercadopago_product_checkout == 'custom_credit_card' && $payment['status'] == 'approved'){
					$customer_id = $payment['metadata']['customer_id'];
					$token = $payment['metadata']['token'];
					$payment_method_id = $payment['payment_method_id'];
					$issuer_id = (int) $payment['issuer_id'];

					//create card
					$card = $mercadopago->create_card_in_customer($customer_id, $token, $payment_method_id, $issuer_id);
					$this->logInfo("Card created: " . json_encode($card), 'debug');
				}

				//update order
				$status_http = $this->updateStatusOrder($payment_method, $payment['external_reference'], $payment['status'], $payment);

			}else{
				$status_http = "HTTP/1.1 404 Not Found";
			} //end if status 200
		}

		return $status_http;
	}


	function updateStatusOrder($payment_method, $order_id, $status, $payment){
		$status_http = "HTTP/1.1 200 OK";

		$orderModel=VmModel::getModel('orders');
		$order = $orderModel->getOrder($order_id);

		//set comments
		$comment = vmText::_('VMPAYMENT_MERCADOPAGO_AUMATIC_NOTIFICATION') . "\n";
		$comment .= vmText::_('VMPAYMENT_MERCADOPAGO_PAYMENT_ID') . ": " . $payment['id'] . "\n";
		$comment .= vmText::_('VMPAYMENT_MERCADOPAGO_PAYMENT_STATUS') . ": " .vmText::_('VMPAYMENT_MERCADOPAGO_PAYMENT_STATUS_' . strtoupper($status)) ;

		//case ticket
		if($payment_method->mercadopago_product_checkout == 'custom_ticket' && $status == 'pending'){
			$comment .= "\n" . vmText::_('VMPAYMENT_MERCADOPAGO_GENERATE_TICKET') . ': <a href="'.$payment['transaction_details']['external_resource_url'].'" target="_blank">' . vmText::_('VMPAYMENT_MERCADOPAGO_GENERATE') . '</a>';
		}

		//set status in order and comments
		$params_ipn = "mercadopago_ipn_" . $status;


		$update_status = true;
		$update_comment = true;

		//analyse all status
		foreach ($order['history'] as $history) {
			if($payment_method->$params_ipn == $history->order_status_code){
				$update_status = false;
			}

			if(strip_tags($comment) == strip_tags($history->comments)){
				$update_comment = false;
			}
		}

		if($update_status){
			$order['order_status'] = $payment_method->$params_ipn;
		}

		if($update_comment){
			$order['comments'] =  $comment;
		}

		$order['customer_notified'] = 1;

		//case status or comments not exist, update order!
		if($update_status || $update_comment){
			$order_status = $orderModel->updateStatusForOneOrder ($order['details']['BT']->virtuemart_order_id, $order, TRUE);
			if(!$order_status){
				//if dont saved
				echo "We could not save the order: " . $order_status;
				$this->logInfo("We could not save the order: " . $order_status, 'debug');
				$status_http = "HTTP/1.1 500 Internal Server Error";

			} //end order status
		}

		echo $comment;

		return $status_http;
	}


	function emptyCart(){
		if (!class_exists('VirtueMartCart')){
			require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		}

		$cart = VirtueMartCart::getCart();
		$cart->emptyCart();
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

	/**
	* This event is fired after the payment method has been selected. It can be used to store
	* additional payment info in the cart.
	*
	* @author Max Milbers
	* @author Valérie isaksen
	*
	* @param VirtueMartCart $cart: the actual cart
	* @return null if the payment was not selected, true if the data is valid, error message if the data is not vlaid
	*
	*/
	public function plgVmOnSelectCheckPayment (VirtueMartCart $cart, &$msg) {

		return $this->OnSelectCheck ($cart);
	}

	/**
	* This method is fired when showing the order details in the frontend.
	* It displays the method-specific data.
	*
	* @param integer $order_id The order ID
	* @return mixed Null for methods that aren't active, text (HTML) otherwise
	* @author Max Milbers
	* @author Valerie Isaksen
	*/
	public function plgVmOnShowOrderFEPayment ($virtuemart_order_id, $virtuemart_paymentmethod_id, &$payment_name) {

		$this->onShowOrderFE ($virtuemart_order_id, $virtuemart_paymentmethod_id, $payment_name);
	}

	/**
	* This method is fired when showing when priting an Order
	* It displays the the payment method-specific data.
	*
	* @param integer $_virtuemart_order_id The order ID
	* @param integer $method_id  method used for this order
	* @return mixed Null when for payment methods that were not selected, text (HTML) otherwise
	* @author Valerie Isaksen
	*/
	function plgVmonShowOrderPrintPayment ($order_number, $method_id) {

		return $this->onShowOrderPrint ($order_number, $method_id);
	}

	/**
	*
	* Função acionada no botão save que retorna os dados salvados no banco de dados
	*
	*/
	function plgVmSetOnTablePluginPayment(&$data, &$table){

		$this->_twoCards($data);
		$this->_actionAnalytics($data);

	}

	function _twoCards($data){
		if(	isset($data['mercadopago_client_id']) &&
		isset($data['mercadopago_client_secret']) &&
		$data['mercadopago_client_id'] != "" &&
		$data['mercadopago_client_secret'] != "" &&
		$data['mercadopago_product_checkout'] == "basic_checkout"){

			//init mercado pago
			$mercadopago = new MP($data['mercadopago_client_id'], $data['mercadopago_client_secret']);

			$two_cards = $data['mercadopago_two_cards'] == 'true' ? 'active' : 'inactive';

			//get info user
			$request = array(
				"uri" => "/account/settings",
				"params" => array(
					"access_token" => $mercadopago->get_access_token()
				),
				"data" => array(
					"two_cards" => $two_cards
				),
				"headers" => array(
					"content-type" => "application/json"
				)
			);
			$account_settings = MPRestClient::put($request);

			if($account_settings['status'] == 200){
				return true;
			}
		}
	}

	function _actionAnalytics($data){
		$type = "none";

		switch ($data['mercadopago_product_checkout']) {
			case 'basic_checkout':
			$type = "checkout_basic";
			break;
			case 'custom_credit_card':
			$type = "checkout_custom_credit_card";
			break;
			case 'custom_ticket':
			$type = "checkout_custom_ticket";
			break;
			default:
			$type = "none";
			break;
		}

		if ($type != "none"){

			$version = VIRTUEMART_VERSION;
			$mercadopago;
			$credentials = false;
			$two_cards = "";

			if($type == "checkout_basic"){
				if($data['mercadopago_client_id'] != "" && $data['mercadopago_client_secret'] != ""){
					$mercadopago = new MP($data['mercadopago_client_id'], $data['mercadopago_client_secret']);
					$two_cards = $data['mercadopago_two_cards'] == 'true' ? 'active' : 'inactive';
					$credentials = true;
				}
			}else{
				if($data['mercadopago_access_token'] != ""){
					$mercadopago = new MP($data['mercadopago_access_token']);
					$credentials = true;
				}
			}


			if($credentials){
				$status_module = $data['published'] == 1 ? "true" : "false";

				//get info user
				$request = array(
					"uri" => "/modules/tracking/settings",
					"params" => array(
						"access_token" => $mercadopago->get_access_token()
					),
					"data" => array(
						"platform" => "VirtueMart",
						"platform_version" => $version,
						"module_version" => MP_MODULE_VERSION,
						"code_version" => phpversion()
					),
					"headers" => array(
						"content-type" => "application/json"
					)
				);

				$request['data'][$type] = $status_module;

				if($two_cards != ''){
					$request['data']['two_cards'] = $two_cards;
				}

				$account_settings = MPRestClient::post($request);

				if($account_settings['status'] == 200){
					return true;
				}
			} //end valid credentials
		} //end type none
	} //end function

	function _getClientId($at){
		$t = explode ( "-" , $at);
		if(count($t) > 0){
			return $t[1];
		}
		return "";
	}

	function _openCheckout($payment_method){
		$settings = $this->_getActionAndValue($payment_method);

		JHTML::script("https://secure.mlstatic.com/modules/javascript/analytics.js");

		if( $settings['token'] != ""){
			vmJsApi::addJScript('mpopen', "
			var MA = ModuleAnalytics;
			MA." . $settings['func'] . "('" . $settings['token'] . "');
			MA.setPlatform('VirtueMart');
			MA.setPlatformVersion('" . VIRTUEMART_VERSION . "');
			MA.setModuleVersion('" . MP_MODULE_VERSION . "');
			MA.post();
			");
		}
	}

	function _closeCheckout($payment_method, $payment_id = ""){
		$settings = $this->_getActionAndValue($payment_method);
		JHTML::script("https://secure.mlstatic.com/modules/javascript/analytics.js");
		vmJsApi::addJScript('mpclosed', "
		var MA = ModuleAnalytics;
		MA." . $settings['func'] . "('" . $settings['token'] . "');
		MA.setPaymentType('" . $settings['type'] . "');
		MA.setCheckoutType('" . $settings['checkout'] . "');
		MA.setPaymentId('" . $payment_id . "');
		MA.put();
		");
	}


	function _getActionAndValue($payment_method){
		$settings = array();

		if($payment_method->mercadopago_product_checkout == "custom_credit_card"){
			$settings["token"] = $payment_method->mercadopago_public_key;
			$settings["func"] = "setPublicKey";
			$settings['type'] = "credit_card";
			$settings['checkout'] = "custom";
		}elseif ($payment_method->mercadopago_product_checkout == "custom_ticket") {
			$settings["token"] = $this->_getClientId($payment_method->mercadopago_access_token);
			$settings["func"] = "setToken";
			$settings['type'] = "ticket";
			$settings['checkout'] = "custom";
		}elseif($payment_method->mercadopago_product_checkout == "basic_checkout") {
			$settings["token"] = $payment_method->mercadopago_client_id;
			$settings["func"] = "setToken";
			$settings['type'] = "basic";
			$settings['checkout'] = "basic";
		}

		return $settings;
	}

}
