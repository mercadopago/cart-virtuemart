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

if (!class_exists('MP')) {
  require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'mercadopago'.DS.'mercadopago'.DS.'lib'.DS.'mercadopago.php');
}

class MercadoPagoHelper{

  static function getBanner($site_id){
    $banners = array(
      "MLA" => "http://imgmp.mlstatic.com/org-img/banners/ar/medios/online/468X60.jpg",
      "MLB" => "http://imgmp.mlstatic.com/org-img/MLB/MP/BANNERS/tipo2_468X60.jpg",
      "MCO" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png",
      "MLC" => "https://secure.mlstatic.com/developers/site/cloud/banners/cl/468x60.gif",
      "MLV" => "https://imgmp.mlstatic.com/org-img/banners/ve/medios/468X60.jpg",
      // TODO: update the banner "MPE" => "",
      "MPE" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png",
      "MLM" => "http://imgmp.mlstatic.com/org-img/banners/mx/medios/MLM_468X60.JPG",
      "MPE" => "http://imgmp.mlstatic.com/org-img/banners/mx/medios/MLM_468X60.JPG",
      "MLU" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png",
      "DEFAULT" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png"
    );

    return $banners[strtoupper($site_id)];
  }

  static function getBannerCustom($site_id){
    $banners = array(
      "MLA" => "http://imgmp.mlstatic.com/org-img/banners/ar/medios/online/468X60.jpg",
      "MLB" => "http://imgmp.mlstatic.com/org-img/MLB/MP/BANNERS/tipo2_468X60.jpg",
      "MCO" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png",
      "MLM" => "http://imgmp.mlstatic.com/org-img/banners/mx/medios/MLM_468X60.JPG",
      "MLC" => "https://secure.mlstatic.com/developers/site/cloud/banners/cl/468x60.gif",
      "MLV" => "https://imgmp.mlstatic.com/org-img/banners/ve/medios/468X60.jpg",
      "MPE" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png",
      "MLU" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png",
      "DEFAULT" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png"
    );

    return $banners[strtoupper($site_id)];
  }

  static function getBannerCustomTicket($site_id){
    $banners = array(
      "MLA" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png",
      "MLB" => "http://imgmp.mlstatic.com/org-img/MLB/MP/BANNERS/2014/230x60.png",
      "MCO" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png",
      "MLM" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png",
      "MLC" => "https://secure.mlstatic.com/developers/site/cloud/banners/cl/468x60.gif",
      "MLV" => "https://imgmp.mlstatic.com/org-img/banners/ve/medios/468X60.jpg",
      "MPE" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png",
      "MLU" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png",
      "DEFAULT" => "https://a248.e.akamai.net/secure.mlstatic.com/components/resources/mp/css/assets/desktop-logo-mercadopago.png"
    );

    return $banners[strtoupper($site_id)];
  }


  static function getBannerAdmin($virtuemart_paymentmethod_id){

    $payment_method = MercadoPagoHelper::getParamsMP($virtuemart_paymentmethod_id);

    if($payment_method['mercadopago_site_id'] == "VALUE_SITE_ID"){

      if($payment_method['mercadopago_client_id'] != "" && $payment_method['mercadopago_client_secret'] !=""){
        $mercadopago = new MP($payment_method['mercadopago_client_id'], $payment_method['mercadopago_client_secret']);

        //get info user
        $request = array(
          "uri" => "/users/me",
          "params" => array(
            "access_token" => $mercadopago->get_access_token()
          )
        );

        $user = MPRestClient::get($request);

        $payment_method['mercadopago_site_id'] = $user['response']['site_id'];

      }else{
        $payment_method['mercadopago_site_id'] = "default";
      }
    }

    return MercadoPagoHelper::getBanner($payment_method['mercadopago_site_id']);
  }

  static function getParamsMP($virtuemart_paymentmethod_id){
    $paramsData = array();

    $db = JFactory::getDBO();
    $db->setQuery('SELECT `payment_params` FROM `#__virtuemart_paymentmethods` WHERE `virtuemart_paymentmethod_id`= ' . $virtuemart_paymentmethod_id);
    $data = explode('|', $db->loadResult());

    foreach ($data as $param) {
      if (!empty($param)){
        $array_temp = explode('=', $param);
        $paramsData[$array_temp[0]] = str_replace('"', '',$array_temp[1]);
      }
    }

    return $paramsData;
  }

  static function overOnePaymentsIPN($merchant_order){

    $total_amount = $merchant_order['total_amount'];
    $total_paid_approved = 0;
    $payment_return = array(
      "status" => "pending",
      "id" => ""
    );

    foreach($merchant_order['payments'] as $payment){

      //apenas soma quando for aprovado para mudar o status do pedido
      if($payment['status'] == "approved"){
        $total_paid_approved += $payment['total_paid_amount'];
      }

      //caso seja aprovado, authorized ou pendente adiciona os ids para mostrar na tela
      if($payment['status'] == "approved" || $payment['status'] == "authorized" || $payment['status'] == "pending"){
        $separator = "";
        if($payment_return['id'] != ""){
          $separator = " | ";
        }

        $payment_return['id'] .= $separator . $payment['id'];
      }
    }

    if($total_paid_approved >= $total_amount){
      $payment_return['status'] = "approved";
    }

    return $payment_return;

  }

  /**
	 * Get shop country from shop currency
	 *
	 * Get shop country on 2 letter code format from configured currency for logs
	 * api country initials
	 *
	 * @return country_initials string
	 */
	public static function getShopCountry()
	{
		$curr_country_map = array(
			'BRL' => 'BR',
			'ARS' => 'AR',
			'CLP' => 'CL',
			'COP' => 'CO',
			'MXN' => 'MX',
			'UYU' => 'UY',
			'UYI' => 'UY',
			'VEF' => 'VE',
			'PEN' => 'PE'
		);
		$db = JFactory::getDBO();
		$query = "SELECT curr.`currency_code_3` ccode FROM `#__virtuemart_vendors` AS vend
							JOIN `#__virtuemart_currencies` AS curr
							ON vend.`vendor_currency` = curr.`virtuemart_currency_id`
							LIMIT 1";
		$db->setQuery($query);
		$currency = $db->loadObject();
    $currency = json_decode(json_encode($currency), true);
		return array_key_exists($currency['ccode'], $curr_country_map) ? $curr_country_map[$currency['ccode']] : "";
	}

	/**
	 * Get super user email
	 *
	 * Get administrator email from db in orther to send it to Logs API.
	 *
	 * @return admin_email string
	 */
	public static function getAdminEmail()
	{
		$db = JFactory::getDBO();
		$query = "SELECT u.email
							FROM `#__user_usergroup_map` ugm
							JOIN `#__usergroups` ug ON ug.`id` = ugm.`group_id`
							JOIN `#__users` u ON u.`id` = ugm.`user_id`
							WHERE ug.`title` = 'Super Users'
							LIMIT 1";
		$db->setQuery($query);
		$user = $db->loadObject();
    $user = json_decode(json_encode($user), true);
		return array_key_exists('email', $user) ? $user['email'] : "";
	}

}
