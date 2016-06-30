<?php

defined('JPATH_BASE') or die();

class JFormFieldGetMercadoPagoCredentialsCustom extends JFormField {

	protected function getInput() {

    $html = '<div class="mp-admin-checkout-custom">';
	    $html .= '<a href="https://www.mercadopago.com/mla/account/credentials" target="_blank">Argentina</a> | ';
	    $html .= '<a href="https://www.mercadopago.com/mlb/account/credentials" target="_blank">Brazil</a> | ';
	    // $html .= '<a href="https://www.mercadopago.com/mlc/herramientas/aplicaciones" target="_blank">Chile</a> | ';
	    $html .= '<a href="https://www.mercadopago.com/mco/account/credentials" target="_blank">Colombia</a> | ';
	    $html .= '<a href="https://www.mercadopago.com/mlm/account/credentials" target="_blank">México</a> | ';
	    $html .= '<a href="https://www.mercadopago.com/mlv/account/credentials" target="_blank">Venezuela</a> ';
		$html .= '</div>';

		return $html;
	}

}
