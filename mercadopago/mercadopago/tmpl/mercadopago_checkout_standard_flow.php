<?php

defined('_JEXEC') or die('Restricted access');

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

$init_point = $viewData["init_point"];
$payment_method = $viewData["payment_method"];
$banner = $viewData["banner"];

?>

<script type="text/javascript" src="https://www.mercadopago.com/org-img/jsapi/mptools/buttons/render.js"></script>

<img src="<?php echo $banner; ?>" /> <br /><br />

<?php
$html = "";
switch ($payment_method->mercadopago_type_checkout) {
  case 'redirect':

    $html .= '<script type="text/javascript">';
    $html .= '	$MPC.openCheckout ({';
    $html .= '		url: "'. $init_point . '",';
    $html .= '		mode: "redirect",';
    $html .= '		onreturn: function(data) {';
    $html .= '		}';
    $html .= '});';
    $html .= '</script>';

    break;

  case 'lightbox':

    $html .= '<a href="'. $init_point .'" name="MP-Checkout" class="blue-M-Rn" mp-mode="modal">Pagar</a>';
    $html .= '<script type="text/javascript">';
    $html .= '	$MPC.openCheckout ({';
    $html .= '		url: "'. $init_point . '",';
    $html .= '		mode: "modal",';
    $html .= '		onreturn: function(data) {';
    $html .= '		}';
    $html .= '});';
    $html .= '</script>';

    break;

  case 'iframe':
  default:
    $html .= '<iframe src="' . $init_point . '" name="MP-Checkout" width="'.$payment_method->mercadopago_width_iframe.'" height="'.$payment_method->mercadopago_height_iframe.'" frameborder="0"></iframe>';
    break;
}

echo $html;
