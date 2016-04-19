<?php

defined('JPATH_BASE') or die();

class JFormFieldGetMercadoPagoCredentialsCountry extends JFormField {

	protected function getInput() {

    $html = "";
    $html .= '<a href="https://www.mercadopago.com/mla/herramientas/aplicaciones" target="_blank">Argentina</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mlb/ferramentas/aplicacoes" target="_blank">Brazil</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mlc/herramientas/aplicaciones" target="_blank">Chile</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mco/herramientas/aplicaciones" target="_blank">Colombia</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mlm/herramientas/aplicaciones" target="_blank">México</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mlv/herramientas/aplicaciones" target="_blank">Venezuela</a> ';

		return $html;
	}

}
