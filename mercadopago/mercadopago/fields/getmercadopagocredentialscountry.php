<?php

defined('JPATH_BASE') or die();

class JFormFieldGetMercadoPagoCredentialsCountry extends JFormField {

	protected function getInput() {

    $html = "";
    $html .= '<a href="https://www.mercadopago.com/mla/account/credentials?type=basic" target="_blank">Argentina</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mlb/account/credentials?type=basic" target="_blank">Brazil</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mlc/account/credentials?type=basic" target="_blank">Chile</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mco/account/credentials?type=basic" target="_blank">Colombia</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mlm/account/credentials?type=basic" target="_blank">México</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mpe/account/credentials?type=basic" target="_blank">Peru</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mlv/account/credentials?type=basic" target="_blank">Venezuela</a> ';

		return $html;
	}

}
