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

<p><?php echo vmText::_('VMPAYMENT_MERCADOPAGO_PAYMENT_STATUS_' . strtoupper($payment['status'])); ?></p>

  <?php if($payment['status'] == "rejected" || $payment['status'] == "in_process"){ ?>

    <p><?php echo vmText::_('VMPAYMENT_MERCADOPAGO_PAYMENT_STATUS_' . strtoupper($payment['status_detail'])); ?></p>

  <?php } ?>

<p><?php echo vmText::_('VMPAYMENT_MERCADOPAGO_PAYMENT_ID'); ?>: <?php echo $payment['id']; ?></p>

<p>
  <img src="https://secure.mlstatic.com/components/resources/mp/desktop/css/assets/desktop-logo-mercadopago.png" style="width: 100px;"/>
</p>

<br />
