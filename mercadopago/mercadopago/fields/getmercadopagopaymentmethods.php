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

defined('JPATH_BASE') or die();

if (!class_exists('MP')) {
	require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'mercadopago'.DS.'mercadopago'.DS.'lib'.DS.'mercadopago.php');
}

if (!class_exists('MercadoPagoHelper')) {
	require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'mercadopago'.DS.'mercadopago'.DS.'helpers'.DS.'mercadopago.php');
}

JFormHelper::loadFieldClass('list');
jimport('joomla.form.formfield');

class JFormFieldGetMercadoPagoPaymentMethods extends JFormFieldList {

	protected $type = 'MercadoPagoPaymentMethods';

	protected function getOptions() {

    $config = MercadoPagoHelper::getParamsMP($_REQUEST['cid'][0]);
    $payment_methods = array("response" => array());
    $options = array();

    if(isset($config['mercadopago_client_id']) && isset($config['mercadopago_client_secret']) && $config['mercadopago_client_id'] != "" && $config['mercadopago_client_secret'] != "" ){

      $mercadopago = new MP($config['mercadopago_client_id'], $config['mercadopago_client_secret']);

      //get info user
      $request = array(
          "uri" => "/users/me",
          "params" => array(
              "access_token" => $mercadopago->get_access_token()
          )
      );

      $users = MPRestClient::get($request);

      //get payment methods
      $request = array(
          "uri" => "/sites/" . $users['response']['site_id'] . "/payment_methods"
      );

      $payment_methods = MPRestClient::get($request);
    }

    foreach ($payment_methods['response'] as $payment_method) {
      $options[] = JHtml::_('select.option', $payment_method['id'], $payment_method['name']);
    }

		return $options;
	}

}
