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

class JFormFieldGetMercadoPagoCredentialsCountry extends JFormField {

	protected function getInput() {

    $html = "";
    $html .= '<a href="https://www.mercadopago.com/mla/account/credentials?type=basic" target="_blank" class="mp-admin-checkout-basic">Argentina</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mlb/account/credentials?type=basic" target="_blank" class="mp-admin-checkout-basic">Brazil</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mlc/account/credentials?type=basic" target="_blank" class="mp-admin-checkout-basic">Chile</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mco/account/credentials?type=basic" target="_blank" class="mp-admin-checkout-basic">Colombia</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mlm/account/credentials?type=basic" target="_blank" class="mp-admin-checkout-basic">México</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mpe/account/credentials?type=basic" target="_blank" class="mp-admin-checkout-basic">Peru</a> | ';
		$html .= '<a href="https://www.mercadopago.com/mlu/account/credentials?type=basic" target="_blank" class="mp-admin-checkout-basic">Uruguay</a> | ';
    $html .= '<a href="https://www.mercadopago.com/mlv/account/credentials?type=basic" target="_blank" class="mp-admin-checkout-basic">Venezuela</a>';



		return $html;
	}

}
