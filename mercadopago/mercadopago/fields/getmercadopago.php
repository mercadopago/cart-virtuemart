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

class JFormFieldGetMercadoPago extends JFormField {

	protected function getInput() {

		vmJsApi::css('mercadopago', 'plugins/vmpayment/mercadopago/mercadopago/assets/css/');
		vmJsApi::addJScript( '/plugins/vmpayment/mercadopago/mercadopago/assets/js/administrator.js');

		$banner = MercadoPagoHelper::getBannerAdmin($_REQUEST['cid'][0]);

		$html = '<img src="' . $banner . '" />';
		$html .= '<input type="hidden" name="mercadopago_sponsor_id" value="VALUE_SPONSOR_ID" />';
		$html .= '<input type="hidden" name="mercadopago_site_id" value="VALUE_SITE_ID" />';
		$html .= '<br/>Module Version: <strong>2.2.0</strong>';

		return $html;
	}

}
