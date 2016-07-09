<?php
$payment = $viewData["payment"];

?>
    <p><?php echo vmText::_('VMPAYMENT_MERCADOPAGO_PAYMENT_STATUS_' . strtoupper($payment['status'])); ?></p>

    <?php if($payment['status'] == "rejected" || $payment['status'] == "in_process"){ ?>

      <p><?php echo vmText::_('VMPAYMENT_MERCADOPAGO_PAYMENT_STATUS_' . strtoupper($payment['status_detail'])); ?></p>

    <?php } ?>

    <p><?php echo vmText::_('VMPAYMENT_MERCADOPAGO_PAYMENT_ID'); ?>: <?php echo $payment['id']; ?></p>

<br />
<p>
  <img src="https://secure.mlstatic.com/components/resources/mp/desktop/css/assets/desktop-logo-mercadopago.png" style="width: 100px;"/>
</p>
