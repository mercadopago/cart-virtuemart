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

$payment = $viewData["payment"]['response'];
?>

<p><?php echo vmText::_('VMPAYMENT_MERCADOPAGO_PAYMENT_ID'); ?>: <?php echo $payment['id']; ?></p>

<p><?php echo vmText::_('VMPAYMENT_MERCADOPAGO_GENERATE_TICKET'); ?>: <a href="<?php echo $payment['transaction_details']['external_resource_url']; ?>" target="_blank"><?php echo vmText::_('VMPAYMENT_MERCADOPAGO_GENERATE'); ?> </a></p>

<p>
  <img src="https://secure.mlstatic.com/components/resources/mp/desktop/css/assets/desktop-logo-mercadopago.png" style="width: 100px;"/>
</p>
