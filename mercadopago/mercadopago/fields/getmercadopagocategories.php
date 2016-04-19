<?php

defined('JPATH_BASE') or die();

if (!class_exists('MP')) {
	require(VMPATH_ROOT . DS.'plugins'.DS.'vmpayment'.DS.'mercadopago'.DS.'mercadopago'.DS.'lib'.DS.'mercadopago.php');
}

JFormHelper::loadFieldClass('list');
jimport('joomla.form.formfield');

class JFormFieldGetMercadoPagoCategories extends JFormFieldList {

	protected function getOptions() {

    $request = array(
        "uri" => "/item_categories"
    );

    $categories = MPRestClient::get($request);

    foreach ($categories['response'] as $category) {
      $options[] = JHtml::_('select.option', $category['id'], $category['description']);
    }

		return $options;
	}

}
