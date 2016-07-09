<?php

defined('JPATH_BASE') or die();

class JFormFieldGetMercadoPago extends JFormField {

	protected function getInput() {

		vmJsApi::css('mercadopago', 'plugins/vmpayment/mercadopago/mercadopago/assets/css/');
		vmJsApi::addJScript( '/plugins/vmpayment/mercadopago/mercadopago/assets/js/administrator.js');

		$banner = MercadoPagoHelper::getBannerAdmin($_REQUEST['cid'][0]);

		$html = '<img src="' . $banner . '" />';
		$html .= '<input type="hidden" name="mercadopago_sponsor_id" value="VALUE_SPONSOR_ID" />';
		$html .= '<input type="hidden" name="mercadopago_site_id" value="VALUE_SITE_ID" />';

		return $html;
	}

}
