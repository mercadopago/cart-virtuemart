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



$params_mercadopago_custom = json_encode($viewData['params_mercadopago_custom']);
$amount_cart = $viewData['amount'];
$customer = $viewData['customer'];

?>

<img src="<?php echo MercadoPagoHelper::getBannerCustom($viewData['site_id']);?>" id="mercadopago-banner-custom">

<div id="mercadopago-main">

  <?php
  $form_labels = array(
    "form" => array(
      "payment_method" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_PAYMENT_METHOD'),
      "credit_card_number" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_CREDIT_CARD_NUMBER'),
      "expiration_month" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_EXPIRATION_MONTH'),
      "expiration_year" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_EXPIRATION_YEAR'),
      "year" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_YEAR'),
      "month" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_MONTH'),
      "card_holder_name" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_CARD_HOLDER_NAME'),
      "security_code" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_SECURITY_CODE'),
      "document_type" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_DOCUMENT_TYPE'),
      "document_number" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_DOCUMENT_NUMBER'),
      "issuer" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ISSUER'),
      "installments" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_INSTALLMENTS'),

      "your_card" => "Your Card",
      "other_cards" => "Other Cards",
      "other_card" => "Other Card",
      "ended_in" => "ended in"
    ),
    "error" => array(
      //card number
      "205" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_205'),
      "E301" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_E301'),
      //expiration date
      "208" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_208'),
      "209" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_209'),
      "325" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_325'),
      "326" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_326'),
      //card holder name
      "221" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_221'),
      "316" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_316'),
      //security code
      "224" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_224'),
      "E302" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_E302'),
      //doc type
      "212" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_212'),
      "322" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_322'),
      //doc number
      "214" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_214'),
      "324" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_324'),
      //doc sub type
      "213" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_213'),
      "323" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_323'),
      //issuer
      "220" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_ERROR_220')
      )
    );
    ?>

    <div id="mercadopago-form-customer-and-card">

      <div class="mp-box-inputs mp-line">
        <label for="paymentMethodIdSelector"><?php echo $form_labels['form']['payment_method']; ?> <em>*</em></label>

        <select id="paymentMethodSelector" name="mercadopago_custom[paymentMethodSelector]" data-checkout='cardId'>
          <optgroup label="<?php echo $form_labels['form']['your_card']; ?>" id="payment-methods-for-customer-and-cards">
            <?php foreach ($customer["cards"] as $card) { ?>

              <option value="<?php echo $card["id"]; ?>"
                first_six_digits="<?php echo $card["first_six_digits"]; ?>"
                last_four_digits="<?php echo $card["last_four_digits"]; ?>"
                security_code_length="<?php echo $card["security_code"]["length"]; ?>"
                type_checkout="customer_and_card"
                payment_method_id="<?php echo $card["payment_method"]["id"]; ?>"
                >
                <?php echo ucfirst($card["payment_method"]["name"]); ?> <?php echo $form_labels['form']['ended_in']; ?> <?php echo $card["last_four_digits"]; ?>
              </option>
              <?php } ?>
            </optgroup>

            <optgroup label="<?php echo $form_labels['form']['other_cards']; ?>" id="payment-methods-list-other-cards">
              <option value="-1"><?php echo $form_labels['form']['other_card']; ?></option>
            </optgroup>

          </select>
        </div>

        <div class="mp-box-inputs mp-line" id="mp-securityCode-customer-and-card">
          <div class="mp-box-inputs mp-col-45">
            <label for="customer-and-card-securityCode"><?php echo $form_labels['form']['security_code']; ?> <em>*</em></label>
            <input type="text" id="customer-and-card-securityCode" data-checkout="securityCode" autocomplete="off" maxlength="4"/>

            <span class="mp-error" id="mp-error-224" data-main="#customer-and-card-securityCode"> <?php echo $form_labels['error']['224']; ?> </span>
            <span class="mp-error" id="mp-error-E302" data-main="#customer-and-card-securityCode"> <?php echo $form_labels['error']['E302']; ?> </span>
            <span class="mp-error" id="mp-error-E203" data-main="#customer-and-card-securityCode"> <?php echo $form_labels['error']['E203']; ?> </span>
          </div>
        </div>

      </div> <!--  end mercadopago-form-osc -->

      <div id="mercadopago-form">
        <div class="mp-box-inputs mp-col-100">
          <label for="cardNumber"><?php echo $form_labels['form']['credit_card_number']; ?> <em>*</em></label>
          <input type="text" id="cardNumber" data-checkout="cardNumber" autocomplete="off"/>
          <span class="mp-error" id="mp-error-205" data-main="#cardNumber"> <?php echo $form_labels['error']['205']; ?> </span>
          <span class="mp-error" id="mp-error-E301" data-main="#cardNumber"> <?php echo $form_labels['error']['E301']; ?> </span>
        </div>

        <div class="mp-box-inputs mp-line">
          <div class="mp-box-inputs mp-col-45">
            <label for="cardExpirationMonth"><?php echo $form_labels['form']['expiration_month']; ?> <em>*</em></label>
            <select id="cardExpirationMonth" data-checkout="cardExpirationMonth" name="mercadopago_custom[cardExpirationMonth]">
              <option value="-1"> <?php echo $form_labels['form']['month']; ?> </option>
              <?php for ($x=1; $x<=12; $x++): ?>
                <option value="<?php echo $x; ?>"> <?php echo $x; ?></option>
              <?php endfor; ?>
            </select>
          </div>

          <div class="mp-box-inputs mp-col-10">
            <div id="mp-separete-date">
              /
            </div>
          </div>

          <div class="mp-box-inputs mp-col-45">
            <label for="cardExpirationYear"><?php echo $form_labels['form']['expiration_year']; ?> <em>*</em></label>
            <select  id="cardExpirationYear" data-checkout="cardExpirationYear" name="mercadopago_custom[cardExpirationYear]">
              <option value="-1"> <?php echo $form_labels['form']['year']; ?> </option>
              <?php for ($x=date("Y"); $x<= date("Y") + 10; $x++): ?>
                <option value="<?php echo $x; ?>"> <?php echo $x; ?> </option>
              <?php endfor; ?>
            </select>
          </div>

          <span class="mp-error" id="mp-error-208" data-main="#cardExpirationMonth"> <?php echo $form_labels['error']['208']; ?> </span>
          <span class="mp-error" id="mp-error-209" data-main="#cardExpirationYear"> </span>
          <span class="mp-error" id="mp-error-325" data-main="#cardExpirationMonth"> <?php echo $form_labels['error']['325']; ?> </span>
          <span class="mp-error" id="mp-error-326" data-main="#cardExpirationYear"> </span>

        </div>

        <div class="mp-box-inputs mp-col-100">
          <label for="cardholderName"><?php echo $form_labels['form']['card_holder_name']; ?> <em>*</em></label>
          <input type="text" id="cardholderName" name="mercadopago_custom[cardholderName]" data-checkout="cardholderName" autocomplete="off"/>

          <span class="mp-error" id="mp-error-221" data-main="#cardholderName"> <?php echo $form_labels['error']['221']; ?> </span>
          <span class="mp-error" id="mp-error-316" data-main="#cardholderName"> <?php echo $form_labels['error']['316']; ?> </span>
        </div>

        <div class="mp-box-inputs mp-line">
          <div class="mp-box-inputs mp-col-45">
            <label for="securityCode"><?php echo $form_labels['form']['security_code']; ?> <em>*</em></label>
            <input type="text" id="securityCode" data-checkout="securityCode" autocomplete="off" maxlength="4"/>

            <span class="mp-error" id="mp-error-224" data-main="#securityCode"> <?php echo $form_labels['error']['224']; ?> </span>
            <span class="mp-error" id="mp-error-E302" data-main="#securityCode"> <?php echo $form_labels['error']['E302']; ?> </span>
          </div>
        </div>

        <div class="mp-box-inputs mp-col-100 mp-doc">
          <div class="mp-box-inputs mp-col-35 mp-docType">
            <label for="docType"><?php echo $form_labels['form']['document_type']; ?> <em>*</em></label>
            <select id="docType" data-checkout="docType" name="mercadopago_custom[docType]"></select>

            <span class="mp-error" id="mp-error-212" data-main="#docType"> <?php echo $form_labels['error']['212']; ?> </span>
            <span class="mp-error" id="mp-error-322" data-main="#docType"> <?php echo $form_labels['error']['322']; ?> </span>
          </div>

          <div class="mp-box-inputs mp-col-65 mp-docNumber">
            <label for="docNumber"><?php echo $form_labels['form']['document_number']; ?> <em>*</em></label>
            <input type="text" id="docNumber" data-checkout="docNumber" name="mercadopago_custom[docNumber]" autocomplete="off"/>

            <span class="mp-error" id="mp-error-214" data-main="#docNumber"> <?php echo $form_labels['error']['214']; ?> </span>
            <span class="mp-error" id="mp-error-324" data-main="#docNumber"> <?php echo $form_labels['error']['324']; ?> </span>
          </div>
        </div>

        <div class="mp-box-inputs mp-col-100 mp-issuer">
          <label for="issuer"><?php echo $form_labels['form']['issuer']; ?> <em>*</em></label>
          <select id="issuer" data-checkout="issuer" name="mercadopago_custom[issuer]"></select>

          <span class="mp-error" id="mp-error-220" data-main="#issuer"> <?php echo $form_labels['error']['220']; ?> </span>
        </div>

      </div>  <!-- end #mercadopago-form -->

      <div class="mp-box-inputs mp-box-installments">
        <div class="mp-box-inputs mp-col-100">
          <label for="installments"><?php echo $form_labels['form']['installments']; ?> <em>*</em></label>
          <select id="installments" data-checkout="installments" name="mercadopago_custom[installments]"></select>
        </div>
      </div>


      <div class="mp-box-inputs mp-line">

        <!-- NOT DELETE LOADING-->
        <div class="mp-box-inputs mp-col-25">
          <div id="mp-box-loading">
          </div>
        </div>

      </div>

      <div class="mp-box-inputs mp-col-100" id="mercadopago-utilities">
        <input type="text" id="site_id"  name="mercadopago_custom[site_id]"/>
        <input type="text" id="amount" value="<?php echo $amount_cart; ?>" name="mercadopago_custom[amount]"/>
        <input type="text" id="paymentMethodId" name="mercadopago_custom[paymentMethodId]"/>
        <input type="text" id="token" name="mercadopago_custom[token]"/>
        <input type="text" id="cardTruncated" name="mercadopago_custom[cardTruncated]"/>
        <input type="text" id="CustomerAndCard" name="mercadopago_custom[CustomerAndCard]"/>
        <input type="text" id="tempIssuer" name="mercadopago_custom[tempIssuer]"/>
        <input type="text" id="tempInstallments" name="mercadopago_custom[tempInstallments]"/>
      </div>

    </div>

    <script>

    // MPv1
    // Handlers Form Mercado Pago v1
    (function (){

      var MPv1 = {
        debug: true,
        add_truncated_card: true,
        site_id: '',
        public_key: '',
        customer_and_card: {
          default: true,
          status: true
        },
        create_token_on: {
          event: true, //if true create token on event, if false create on click and ignore others events. eg: paste or keyup
          keyup: false,
          paste: true,
        },
        inputs_to_create_token: [
          "cardNumber",
          "cardExpirationMonth",
          "cardExpirationYear",
          "cardholderName",
          "securityCode",
          "docType",
          "docNumber"
        ],

        inputs_to_create_token_customer_and_card: [
          "paymentMethodSelector",
          "securityCode"
        ],

        selectors:{

          paymentMethodSelector: "#paymentMethodSelector",
          pmCustomerAndCards: "#payment-methods-for-customer-and-cards",
          pmListOtherCards: "#payment-methods-list-other-cards",
          mpSecurityCodeCustomerAndCard: "#mp-securityCode-customer-and-card",

          cardNumber: "#cardNumber",
          cardExpirationMonth: "#cardExpirationMonth",
          cardExpirationYear: "#cardExpirationYear",
          cardholderName: "#cardholderName",
          securityCode: "#securityCode",
          docType: "#docType",
          docNumber: "#docNumber",
          issuer: "#issuer",
          installments: "#installments",

          mpDoc: ".mp-doc",
          mpIssuer: ".mp-issuer",
          mpDocType: ".mp-docType",
          mpDocNumber: ".mp-docNumber",
          // mpPaymentMethodSelector: ".mp-paymentMethodsSelector",

          paymentMethodId: "#paymentMethodId",
          amount: "#amount",
          token: "#token",
          cardTruncated: "#cardTruncated",
          site_id: "#site_id",
          CustomerAndCard: '#CustomerAndCard',

          box_loading: "#mp-box-loading",
          submit: "#submit",
          form: '#mercadopago-form',
          formCustomerAndCard: '#mercadopago-form-customer-and-card',
          utilities_fields: "#mercadopago-utilities"
        },
        text: {
          choose: "Choose",
          other_bank: "Other Bank"
        },
        paths:{
          loading: "images/loading.gif"
        }
      }

      MPv1.getBin = function () {
        var cardSelector = document.querySelector(MPv1.selectors.paymentMethodSelector);
        if (cardSelector && cardSelector[cardSelector.options.selectedIndex].value != "-1") {
          return cardSelector[cardSelector.options.selectedIndex].getAttribute('first_six_digits');
        }

        var ccNumber = document.querySelector(MPv1.selectors.cardNumber);
        return ccNumber.value.replace(/[ .-]/g, '').slice(0, 6);
      }

      MPv1.clearOptions = function () {
        var bin = MPv1.getBin();

        if (bin.length == 0) {
          MPv1.hideIssuer();

          var selectorInstallments = document.querySelector(MPv1.selectors.installments),
          fragment = document.createDocumentFragment(),
          option = new Option(MPv1.text.choose + "...", '-1');

          selectorInstallments.options.length = 0;
          fragment.appendChild(option);
          selectorInstallments.appendChild(fragment);
          selectorInstallments.setAttribute('disabled', 'disabled');
        }
      }

      MPv1.guessingPaymentMethod = function (event) {

        var bin = MPv1.getBin();
        var amount = MPv1.getAmount();

        if (event.type == "keyup") {
          if (bin != null && bin.length == 6 ) {
            Mercadopago.getPaymentMethod({
              "bin": bin
            }, MPv1.setPaymentMethodInfo);
          }
        } else {
          setTimeout(function() {
            if (bin.length >= 6) {
              Mercadopago.getPaymentMethod({
                "bin": bin
              }, MPv1.setPaymentMethodInfo);
            }
          }, 100);
        }
      };

      MPv1.setPaymentMethodInfo = function (status, response) {

        if (status == 200) {

          if(MPv1.site_id != "MLM"){
            //guessing
            document.querySelector(MPv1.selectors.paymentMethodId).value = response[0].id;

            if(MPv1.customer_and_card.status){
              document.querySelector(MPv1.selectors.paymentMethodSelector).style.background = "url(" + response[0].secure_thumbnail + ") 95% 50% no-repeat #fff";
            }else{
              document.querySelector(MPv1.selectors.cardNumber).style.background = "url(" + response[0].secure_thumbnail + ") 98% 50% no-repeat #fff";
            }

          }

          // check if the security code (ex: Tarshop) is required
          var cardConfiguration = response[0].settings;
          var bin = MPv1.getBin();
          var amount = MPv1.getAmount();

          Mercadopago.getInstallments({
            "bin": bin,
            "amount": amount
          }, MPv1.setInstallmentInfo);

          // check if the issuer is necessary to pay
          var issuerMandatory = false,
          additionalInfo = response[0].additional_info_needed;

          for (var i = 0; i < additionalInfo.length; i++) {
            if (additionalInfo[i] == "issuer_id") {
              issuerMandatory = true;
            }
          };
          if (issuerMandatory && MPv1.site_id != "MLM") {
            var payment_method_id = response[0].id;
            MPv1.getIssuersPaymentMethod(payment_method_id);
          } else {
            MPv1.hideIssuer();
          }
        }
      }


      MPv1.changePaymetMethodSelector = function (){
        var payment_method_id = document.querySelector(MPv1.selectors.paymentMethodSelector).value;
        MPv1.getIssuersPaymentMethod(payment_method_id);

      }


      /*
      *
      *
      * Issuers
      *
      */

      MPv1.getIssuersPaymentMethod = function (payment_method_id){
        var amount = MPv1.getAmount();

        //flow: MLM mercadopagocard
        if(payment_method_id == 'mercadopagocard'){
          Mercadopago.getInstallments({
            "payment_method_id": payment_method_id,
            "amount": amount
          }, MPv1.setInstallmentInfo);
        }

        Mercadopago.getIssuers(payment_method_id, MPv1.showCardIssuers);
        MPv1.addListenerEvent(document.querySelector(MPv1.selectors.issuer), 'change', MPv1.setInstallmentsByIssuerId);
      }


      MPv1.showCardIssuers = function (status, issuers) {

        //if the API does not return any bank
        if(issuers.length > 0){
          var issuersSelector = document.querySelector(MPv1.selectors.issuer),
          fragment = document.createDocumentFragment();

          issuersSelector.options.length = 0;
          var option = new Option(MPv1.text.choose + "...", '-1');
          fragment.appendChild(option);

          for (var i = 0; i < issuers.length; i++) {
            if (issuers[i].name != "default") {
              option = new Option(issuers[i].name, issuers[i].id);
            } else {
              option = new Option("Otro", issuers[i].id);
            }
            fragment.appendChild(option);
          }
          issuersSelector.appendChild(fragment);
          issuersSelector.removeAttribute('disabled');
          //document.querySelector(MPv1.selectors.issuer).removeAttribute('style');
        }else{
          MPv1.hideIssuer();
        }
      }

      MPv1.setInstallmentsByIssuerId = function (status, response) {
        var issuerId = document.querySelector(MPv1.selectors.issuer).value;
        var amount = MPv1.getAmount();

        if (issuerId === '-1') {
          return;
        }

        var params_installments = {
          "bin": MPv1.getBin(),
          "amount": amount,
          "issuer_id": issuerId
        }

        if(MPv1.site_id == "MLM"){
          params_installments = {
            "payment_method_id": document.querySelector(MPv1.selectors.paymentMethodSelector).value,
            "amount": amount,
            "issuer_id": issuerId
          }
        }

        Mercadopago.getInstallments(params_installments, MPv1.setInstallmentInfo);
      }

      MPv1.hideIssuer = function (){
        var $issuer = document.querySelector(MPv1.selectors.issuer);
        var opt = document.createElement('option');
        opt.value = "-1";
        opt.innerHTML = MPv1.text.other_bank;

        $issuer.innerHTML = "";
        $issuer.appendChild(opt);
        $issuer.setAttribute('disabled', 'disabled');
      }

      /*
      *
      *
      * Installments
      *
      */

      MPv1.setInstallmentInfo = function(status, response) {
        var selectorInstallments = document.querySelector(MPv1.selectors.installments);

        if (response.length > 0) {

          var html_option = '<option value="-1">' + MPv1.text.choose + '...</option>';
          payerCosts = response[0].payer_costs;

          // fragment.appendChild(option);
          for (var i = 0; i < payerCosts.length; i++) {
            html_option += '<option value="'+ payerCosts[i].installments +'">' + (payerCosts[i].recommended_message || payerCosts[i].installments) + '</option>';
          }

          // not take the user's selection if equal
          if(selectorInstallments.innerHTML != html_option){
            selectorInstallments.innerHTML = html_option;
          }

          selectorInstallments.removeAttribute('disabled');
        }
      }


      /*
      *
      *
      * Customer & Cards
      *
      */

      MPv1.cardsHandler = function () {

        var cardSelector = document.querySelector(MPv1.selectors.paymentMethodSelector);
        var type_checkout = cardSelector[cardSelector.options.selectedIndex].getAttribute("type_checkout");
        var amount = MPv1.getAmount();


        if(MPv1.customer_and_card.default){

          if (cardSelector &&
            cardSelector[cardSelector.options.selectedIndex].value != "-1" &&
            type_checkout == "customer_and_card") {

              document.querySelector(MPv1.selectors.paymentMethodId).value = cardSelector[cardSelector.options.selectedIndex].getAttribute('payment_method_id');

              MPv1.clearOptions();

              MPv1.customer_and_card.status = true;

              var _bin = cardSelector[cardSelector.options.selectedIndex].getAttribute("first_six_digits");

              Mercadopago.getPaymentMethod({
                "bin": _bin
              }, MPv1.setPaymentMethodInfo);

            }else{
              document.querySelector(MPv1.selectors.paymentMethodId).value = cardSelector.value != -1 ? cardSelector.value : "";
              MPv1.customer_and_card.status = false;
              MPv1.resetBackgroundCard();
              MPv1.guessingPaymentMethod({type: "keyup"});
            }

            MPv1.setForm();
          }
        }

        /*
        * Payment Methods
        *
        */

        MPv1.getPaymentMethods = function(){
          var fragment = document.createDocumentFragment();
          var paymentMethodsSelector = document.querySelector(MPv1.selectors.paymentMethodSelector)
          var mainPaymentMethodSelector = document.querySelector(MPv1.selectors.paymentMethodSelector)

          //set loading
          mainPaymentMethodSelector.style.background = "url("+MPv1.paths.loading+") 95% 50% no-repeat #fff";

          //if customer and card
          if(MPv1.customer_and_card.status){
            paymentMethodsSelector = document.querySelector(MPv1.selectors.pmListOtherCards)

            //clean payment methods
            paymentMethodsSelector.innerHTML = "";
          }else{
            paymentMethodsSelector.innerHTML = "";
            option = new Option(MPv1.text.choose + "...", '-1');
            fragment.appendChild(option);
          }

          Mercadopago.getAllPaymentMethods(function(code, payment_methods){

            for(var x=0; x < payment_methods.length; x++){
              var pm = payment_methods[x];

              if((pm.payment_type_id == "credit_card" ||
              pm.payment_type_id == "debit_card" ||
              pm.payment_type_id == "prepaid_card") &&
              pm.status == "active"){

                option = new Option(pm.name, pm.id);
                option.setAttribute("type_checkout", "custom");
                fragment.appendChild(option);

              }//end if

            } //end for

            paymentMethodsSelector.appendChild(fragment);
            mainPaymentMethodSelector.style.background = "#fff";
          });
        }

        /*
        *
        * Functions related to Create Tokens
        *
        */


        MPv1.createTokenByEvent = function(){

          var $inputs = MPv1.getForm().querySelectorAll('[data-checkout]');
          var $inputs_to_create_token = MPv1.getInputsToCreateToken();

          // console.log("createTokenByEvent", $inputs_to_create_token);

          for(var x = 0; x < $inputs.length; x++){
            var element = $inputs[x];

            //add events only in the required fields
            if($inputs_to_create_token.indexOf(element.getAttribute("data-checkout")) > -1){

              var event = "focusout";

              if(element.nodeName == "SELECT"){
                event = "change";
              }

              MPv1.addListenerEvent(element, event, MPv1.validateInputsCreateToken);

              //for firefox
              MPv1.addListenerEvent(element, "blur", MPv1.validateInputsCreateToken);

              if(MPv1.create_token_on.keyup){
                MPv1.addListenerEvent(element, "keyup", MPv1.validateInputsCreateToken);
              }

              if(MPv1.create_token_on.paste){
                MPv1.addListenerEvent(element, "paste", MPv1.validateInputsCreateToken);
              }

            }
          }
        }

        MPv1.createTokenBySubmit = function(){
          addListenerEvent(document.querySelector(MPv1.selectors.form), 'submit', MPv1.doPay);
        }

        var doSubmit = false;

        MPv1.doPay = function(event){
          event.preventDefault();
          if(!doSubmit){
            MPv1.createToken();
            return false;
          }
        }


        MPv1.validateInputsCreateToken = function(){
          var valid_to_create_token = true;
          var $inputs = MPv1.getForm().querySelectorAll('[data-checkout]');
          var $inputs_to_create_token = MPv1.getInputsToCreateToken();

          for(var x = 0; x < $inputs.length; x++){
            var element = $inputs[x];

            //check is a input to create token
            if($inputs_to_create_token.indexOf(element.getAttribute("data-checkout")) > -1){
              if(element.value == -1 || element.value == ""){
                valid_to_create_token = false;
              } //end if check values
            } //end if check data-checkout
          }//end for

          if(valid_to_create_token){
            MPv1.createToken();
          }
        }

        MPv1.createToken = function(){
          MPv1.hideErrors();

          //show loading
          document.querySelector(MPv1.selectors.box_loading).style.background = "url("+MPv1.paths.loading+") 0 50% no-repeat #fff";

          //form
          var $form = MPv1.getForm();

          Mercadopago.createToken($form, MPv1.sdkResponseHandler);

          return false;
        }

        MPv1.sdkResponseHandler = function(status, response) {
          //hide loading
          document.querySelector(MPv1.selectors.box_loading).style.background = "";

          if (status != 200 && status != 201) {
            MPv1.showErrors(response);
          }else{
            var token = document.querySelector(MPv1.selectors.token);
            token.value = response.id;

            if(MPv1.add_truncated_card){
              var card = MPv1.truncateCard(response);
              document.querySelector(MPv1.selectors.cardTruncated).value=card;
            }

            if(!MPv1.create_token_on.event){
              doSubmit=true;
              btn = document.querySelector(MPv1.selectors.form);
              btn.submit();
            }
          }
        }

        /*
        *
        *
        * useful functions
        *
        */


        MPv1.resetBackgroundCard = function () {
          document.querySelector(MPv1.selectors.paymentMethodSelector).style.background = "no-repeat #fff";
          document.querySelector(MPv1.selectors.cardNumber).style.background = "no-repeat #fff";
        }


        MPv1.setForm = function () {
          if(MPv1.customer_and_card.status){
            document.querySelector(MPv1.selectors.form).style.display = 'none';
            document.querySelector(MPv1.selectors.mpSecurityCodeCustomerAndCard).removeAttribute('style');
          }else{
            document.querySelector(MPv1.selectors.mpSecurityCodeCustomerAndCard).style.display = 'none';
            document.querySelector(MPv1.selectors.form).removeAttribute('style');
          }

          Mercadopago.clearSession();

          if(MPv1.create_token_on.event){
            MPv1.createTokenByEvent();
            MPv1.validateInputsCreateToken();
          }

          document.querySelector(MPv1.selectors.CustomerAndCard).value = MPv1.customer_and_card.status;
        }

        MPv1.getForm = function(){
          if(MPv1.customer_and_card.status){
            return document.querySelector(MPv1.selectors.formCustomerAndCard);
          }else{
            return document.querySelector(MPv1.selectors.form);
          }
        }

        MPv1.getInputsToCreateToken = function(){
          if(MPv1.customer_and_card.status){
            return MPv1.inputs_to_create_token_customer_and_card;
          }else{
            return MPv1.inputs_to_create_token;
          }
        }

        MPv1.truncateCard = function(response_card_token){
          var first_six_digits;
          var last_four_digits;

          if(MPv1.customer_and_card.status){
            var cardSelector = document.querySelector(MPv1.selectors.paymentMethodSelector);
            first_six_digits = cardSelector[cardSelector.options.selectedIndex].getAttribute("first_six_digits").match(/.{1,4}/g)
            last_four_digits = cardSelector[cardSelector.options.selectedIndex].getAttribute("last_four_digits")
          }else{
            first_six_digits = response_card_token.first_six_digits.match(/.{1,4}/g)
            last_four_digits = response_card_token.last_four_digits
          }

          var card = first_six_digits[0] + " " + first_six_digits[1] + "** **** " + last_four_digits;
          return card;

        }

        MPv1.getAmount = function(){
          return document.querySelector(MPv1.selectors.amount).value;
        }

        /*
        *
        *
        * Show errors
        *
        */

        MPv1.showErrors = function(response){
          var $form = MPv1.getForm();

          for(var x = 0; x < response.cause.length; x++){
            var error = response.cause[x];
            var $span = $form.querySelector('#mp-error-' + error.code);
            var $input = $form.querySelector($span.getAttribute("data-main"));

            $span.style.display = 'inline-block';
            $input.classList.add("mp-error-input");

          }

          return;
        }

        MPv1.hideErrors = function(){

          for(var x = 0; x < document.querySelectorAll('[data-checkout]').length; x++){
            var $field = document.querySelectorAll('[data-checkout]')[x];
            $field.classList.remove("mp-error-input");

          } //end for

          for(var x = 0; x < document.querySelectorAll('.mp-error').length; x++){
            var $span = document.querySelectorAll('.mp-error')[x];
            $span.style.display = 'none';

          }

          return;
        }

        /*
        *
        * Add events to guessing
        *
        */


        MPv1.addListenerEvent = function(el, eventName, handler){
          if (el.addEventListener) {
            el.addEventListener(eventName, handler);
          } else {
            el.attachEvent('on' + eventName, function(){
              handler.call(el);
            });
          }
        };

        MPv1.addListenerEvent(document.querySelector(MPv1.selectors.cardNumber), 'keyup', MPv1.guessingPaymentMethod);
        MPv1.addListenerEvent(document.querySelector(MPv1.selectors.cardNumber), 'keyup', MPv1.clearOptions);
        MPv1.addListenerEvent(document.querySelector(MPv1.selectors.cardNumber), 'change', MPv1.guessingPaymentMethod);


        // MPv1.cardsHandler();




        /*
        *
        *
        * Initialization function
        *
        */

        MPv1.Initialize = function(site_id, public_key){

          //sets
          MPv1.site_id = site_id
          MPv1.public_key = public_key


          Mercadopago.setPublishableKey(MPv1.public_key);

          //flow: customer & cards
          var selectorPmCustomerAndCards = document.querySelector(MPv1.selectors.pmCustomerAndCards);
          if(MPv1.customer_and_card.default && selectorPmCustomerAndCards.childElementCount > 0){
            MPv1.addListenerEvent(document.querySelector(MPv1.selectors.paymentMethodSelector), 'change', MPv1.cardsHandler);
            MPv1.cardsHandler();
          }else{
            //if customer & cards is disabled
            //or customer does not have cards
            MPv1.customer_and_card.status = false;
            document.querySelector(MPv1.selectors.formCustomerAndCard).style.display = 'none';
          }

          if(MPv1.create_token_on.event){
            MPv1.createTokenByEvent();
          }else{
            MPv1.createTokenBySubmit()
          }

          //flow: MLM
          if(MPv1.site_id != "MLM"){
            Mercadopago.getIdentificationTypes();
          }

          if(MPv1.site_id == "MLM"){

            //hide documento for mex
            document.querySelector(MPv1.selectors.mpDoc).style.display = 'none';
            document.querySelector(MPv1.selectors.formCustomerAndCard).removeAttribute('style');

            if(!MPv1.customer_and_card.status){
              document.querySelector(MPv1.selectors.mpSecurityCodeCustomerAndCard).style.display = 'none';
            }


            //removing not used fields for this country
            MPv1.inputs_to_create_token.splice(MPv1.inputs_to_create_token.indexOf("docType"), 1);
            MPv1.inputs_to_create_token.splice(MPv1.inputs_to_create_token.indexOf("docNumber"), 1);

            MPv1.addListenerEvent(document.querySelector(MPv1.selectors.paymentMethodSelector), 'change', MPv1.changePaymetMethodSelector);

            //get payment methods and populate selector
            MPv1.getPaymentMethods();
          }

          //flow: MLB AND MCO
          if(MPv1.site_id == "MLB"){

            document.querySelector(MPv1.selectors.mpDocType).style.display = 'none';
            document.querySelector(MPv1.selectors.mpIssuer).style.display = 'none';
            //ajust css
            document.querySelector(MPv1.selectors.docNumber).classList.remove("mp-col-75");
            document.querySelector(MPv1.selectors.docNumber).classList.add("mp-col-100");

          }else if (MPv1.site_id == "MCO") {
            document.querySelector(MPv1.selectors.mpIssuer).style.display = 'none';
          }

          if(MPv1.debug){
            document.querySelector(MPv1.selectors.utilities_fields).style.display = 'inline-block';
            // console.log(MPv1);
          }

          document.querySelector(MPv1.selectors.site_id).value = MPv1.site_id;

          //set form for basic ou customer & cards
          // MPv1.setForm();

          return;
        }


        this.MPv1 = MPv1;

      }).call();

      </script>




      <!-- customize -->
      <script>

      var mercadopago_site_id = '<?php echo $viewData['site_id']; ?>';
      var mercadopago_public_key = '<?php echo $viewData['public_key']; ?>';
      var params_mercadopago_custom = JSON.parse('<?php echo $params_mercadopago_custom; ?>');
      var domain_store = '<?php echo JURI::root(); ?>';

      //replace function
      MPv1.getPaymentMethods = function(){
        var fragment = document.createDocumentFragment();
        var paymentMethodsSelector = document.querySelector(MPv1.selectors.paymentMethodSelector)
        var mainPaymentMethodSelector = document.querySelector(MPv1.selectors.paymentMethodSelector)

        //set loading
        mainPaymentMethodSelector.style.background = "url("+MPv1.paths.loading+") 95% 50% no-repeat #fff";

        //if customer and card
        if(MPv1.customer_and_card.status){
          paymentMethodsSelector = document.querySelector(MPv1.selectors.pmListOtherCards)

          //clean payment methods
          paymentMethodsSelector.innerHTML = "";
        }else{
          paymentMethodsSelector.innerHTML = "";
          option = new Option(MPv1.text.choose + "...", '-1');
          fragment.appendChild(option);
        }

        Mercadopago.getAllPaymentMethods(function(code, payment_methods){

          for(var x=0; x < payment_methods.length; x++){
            var pm = payment_methods[x];

            if((pm.payment_type_id == "credit_card" ||
            pm.payment_type_id == "debit_card" ||
            pm.payment_type_id == "prepaid_card") &&
            pm.status == "active"){

              option = new Option(pm.name, pm.id);
              option.setAttribute("type_checkout", "custom");
              fragment.appendChild(option);

            }//end if

          } //end for

          paymentMethodsSelector.appendChild(fragment);
          mainPaymentMethodSelector.style.background = "#fff";

          var evt = new CustomEvent('MercadopagoPopulatePaymentMethods', { status: "finish" });
          window.dispatchEvent(evt);
        });
      }

      MPv1.showCardIssuers = function (status, issuers) {

        //if the API does not return any bank
        if(issuers.length > 0){
          var issuersSelector = document.querySelector(MPv1.selectors.issuer),
          fragment = document.createDocumentFragment();

          issuersSelector.options.length = 0;
          var option = new Option(MPv1.text.choose + "...", '-1');
          fragment.appendChild(option);

          for (var i = 0; i < issuers.length; i++) {
            if (issuers[i].name != "default") {
              option = new Option(issuers[i].name, issuers[i].id);
            } else {
              option = new Option("Otro", issuers[i].id);
            }
            fragment.appendChild(option);
          }
          issuersSelector.appendChild(fragment);
          issuersSelector.removeAttribute('disabled');
          //document.querySelector(MPv1.selectors.issuer).removeAttribute('style');
        }else{
          MPv1.hideIssuer();
        }

        var evt = new CustomEvent('MercadopagoPopulateIssuer', { status: "finish" });
        window.dispatchEvent(evt);
      }

      MPv1.setInstallmentInfo = function(status, response) {
        var selectorInstallments = document.querySelector(MPv1.selectors.installments);

        if (response.length > 0) {

          var html_option = '<option value="-1">' + MPv1.text.choose + '...</option>';
          payerCosts = response[0].payer_costs;

          // fragment.appendChild(option);
          for (var i = 0; i < payerCosts.length; i++) {
            html_option += '<option value="'+ payerCosts[i].installments +'">' + (payerCosts[i].recommended_message || payerCosts[i].installments) + '</option>';
          }

          // not take the user's selection if equal
          if(selectorInstallments.innerHTML != html_option){
            selectorInstallments.innerHTML = html_option;
          }

          selectorInstallments.removeAttribute('disabled');

        }

        var evt = new CustomEvent('MercadopagoPopulateInstallment', { status: "finish" });
        window.dispatchEvent(evt);
      }

      MPv1.setForm = function () {
        if(MPv1.customer_and_card.status){
          document.querySelector(MPv1.selectors.form).style.display = 'none';
          document.querySelector(MPv1.selectors.mpSecurityCodeCustomerAndCard).removeAttribute('style');

          //reset CVV
          document.querySelector(MPv1.selectors.cardNumber).value = "";
          document.querySelector(MPv1.selectors.securityCode).value = "";
        }else{
          document.querySelector(MPv1.selectors.mpSecurityCodeCustomerAndCard).style.display = 'none';
          document.querySelector(MPv1.selectors.form).removeAttribute('style');

          //reset info card
          document.querySelector(MPv1.selectors.SecurityCodeCustomerAndCard).value = ""
        }

        document.querySelector(MPv1.selectors.token).value = ""
        Mercadopago.clearSession();

        if(MPv1.create_token_on.event){
          MPv1.createTokenByEvent();
          // MPv1.validateInputsCreateToken();
        }

        document.querySelector(MPv1.selectors.CustomerAndCard).value = MPv1.customer_and_card.status;
      }


      //init selectors custom for virtuemart
      MPv1.debug = false;
      MPv1.selectors.tempIssuer = "#tempIssuer";
      MPv1.selectors.tempInstallments = "#tempInstallments";
      MPv1.selectors.SecurityCodeCustomerAndCard = "#customer-and-card-securityCode";
      MPv1.paths.loading = domain_store + "plugins/vmpayment/mercadopago/mercadopago/assets/images/loading.gif";

      //init MPv1
      MPv1.Initialize(mercadopago_site_id, mercadopago_public_key);

      //specific actions to populate data in the form on reload page
      if(params_mercadopago_custom != null){

        document.querySelector(MPv1.selectors.cardNumber).value = params_mercadopago_custom.cardTruncated;
        document.querySelector(MPv1.selectors.cardExpirationMonth).value = params_mercadopago_custom.cardExpirationMonth;
        document.querySelector(MPv1.selectors.cardExpirationYear).value = params_mercadopago_custom.cardExpirationYear;
        document.querySelector(MPv1.selectors.cardholderName).value = params_mercadopago_custom.cardholderName;
        document.querySelector(MPv1.selectors.securityCode).value = "***";
        document.querySelector(MPv1.selectors.docType).value = params_mercadopago_custom.docType;
        document.querySelector(MPv1.selectors.docNumber).value = params_mercadopago_custom.docNumber;

        //replica
        document.querySelector(MPv1.selectors.cardTruncated).value = params_mercadopago_custom.cardTruncated;

        //customer & card
        MPv1.customer_and_card.status = params_mercadopago_custom.CustomerAndCard == 'true' ? true : false;
        document.querySelector(MPv1.selectors.CustomerAndCard).value = params_mercadopago_custom.CustomerAndCard;
        document.querySelector(MPv1.selectors.paymentMethodSelector).value = params_mercadopago_custom.paymentMethodSelector;
        document.querySelector(MPv1.selectors.SecurityCodeCustomerAndCard).value = "***";

        //reload form
        MPv1.resetBackgroundCard();
        MPv1.setForm();

        //hidden inputs
        document.querySelector(MPv1.selectors.paymentMethodId).value = params_mercadopago_custom.paymentMethodId;
        document.querySelector(MPv1.selectors.token).value = params_mercadopago_custom.token;
        document.querySelector(MPv1.selectors.tempInstallments).value = params_mercadopago_custom.installments;
        document.querySelector(MPv1.selectors.tempIssuer).value = params_mercadopago_custom.issuer;

        // Flow: MLM
        window.addEventListener('MercadopagoPopulatePaymentMethods', function (e) {
          document.querySelector(MPv1.selectors.paymentMethodSelector).value = params_mercadopago_custom.paymentMethodSelector;
          MPv1.changePaymetMethodSelector();
        });


        window.addEventListener('MercadopagoPopulateIssuer', function (e) {
          document.querySelector(MPv1.selectors.issuer).value = params_mercadopago_custom.issuer;
          MPv1.setInstallmentsByIssuerId();
        });

        window.addEventListener('MercadopagoPopulateInstallment', function (e) {
          document.querySelector(MPv1.selectors.installments).value = params_mercadopago_custom.installments;
        });

        //action to get installments
        if(MPv1.site_id != "MLM"){
          MPv1.guessingPaymentMethod({type: "keyup"});
        }
      }

      </script>
