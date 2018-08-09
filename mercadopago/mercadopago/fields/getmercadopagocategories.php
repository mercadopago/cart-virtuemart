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
