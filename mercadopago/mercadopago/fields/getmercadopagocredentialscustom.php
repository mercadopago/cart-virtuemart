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

class JFormFieldGetMercadoPagoCredentialsCustom extends JFormField {

	protected function getInput() {

    $html = '<div class="mp-admin-checkout-custom">';
	    $html .= '<a href="https://www.mercadopago.com/mla/account/credentials" target="_blank">Argentina</a> | ';
	    $html .= '<a href="https://www.mercadopago.com/mlb/account/credentials" target="_blank">Brazil</a> | ';
	    // $html .= '<a href="https://www.mercadopago.com/mlc/herramientas/aplicaciones" target="_blank">Chile</a> | ';
	    $html .= '<a href="https://www.mercadopago.com/mco/account/credentials" target="_blank">Colombia</a> | ';
	    $html .= '<a href="https://www.mercadopago.com/mlm/account/credentials" target="_blank">México</a> | ';
		$html .= '<a href="https://www.mercadopago.com/mlv/account/credentials" target="_blank">Venezuela</a> | ';
		$html .= '<a href="https://www.mercadopago.com/mlu/account/credentials" target="_blank">Uruguai</a> ';
		$html .= '</div>';

		return $html;
	}

}
