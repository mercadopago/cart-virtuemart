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

$list_payment_methods = $viewData['list_payment_methods'];
$id_checked = $viewData['params_mercadopago_custom_ticket']['payment_method_id'];
?>

<br/>

<img src="<?php echo MercadoPagoHelper::getBannerCustomTicket($viewData['site_id']);?>" id="mercadopago-banner-custom-ticket">

<ul id="mercadopago-off-payments">
  <?php
  if(count($list_payment_methods) > 1){
    foreach ($list_payment_methods as $key => $pm) {
      $selected = $pm['id'] == $id_checked ? 'checked="checked"': '';
      ?>
      <li>
        <input type="radio" value="<?php echo $pm['id'];?>" name="mercadopago_custom_ticket[payment_method_id]" <?php echo $selected;?> > <img src="<?php echo $pm['secure_thumbnail'];?>">  (<?php echo $pm['name'];?>)
      </li>
      <?php
    } ?>

    <?php
  }else{ ?>
    <li>
      <!-- <img src="<?php echo $list_payment_methods[0]['secure_thumbnail'];?>"> -->
      <input type="hidden" name="mercadopago_custom_ticket[payment_method_id]" value="<?php echo $list_payment_methods[0]['id'];?>">
    </li>
    <?php
  }
  ?>
</ul>
