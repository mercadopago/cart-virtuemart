<?php

defined('_JEXEC') or die();

$params_mercadopago_custom = json_encode($viewData['params_mercadopago_custom']);
$amount_cart = $viewData['amount'];

?>

<script>
var mercadopago_custom_public_key = '<?php echo $viewData['public_key']; ?>';
var mercadopago_custom_site_id = '<?php echo $viewData['site_id']; ?>';
var params_mercadopago_custom = JSON.parse('<?php echo $params_mercadopago_custom; ?>');
</script>

<img src="<?php echo MercadoPagoHelper::getBannerCustom($viewData['site_id']);?>" id="mercadopago-banner-custom">

<div id="mercadopago-main">
  <div id="mercadopago-form" >

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
        "installments" => vmText::_('VMPAYMENT_MERCADOPAGO_FORM_INSTALLMENTS')
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
    <!-- <div id="mercadopago-form" > -->
    <div class="mp-box-inputs mp-line mp-paymentMethodsSelector" style="display:none;">
      <label for="paymentMethodIdSelector"><?php echo $form_labels['form']['payment_method']; ?> <em>*</em></label>
      <select id="paymentMethodIdSelector" name="mercadopago_custom[paymentMethodIdSelector]" data-checkout="paymentMethodIdSelector">
      </select>
    </div>

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
        <input type="text" id="securityCode" data-checkout="securityCode" name="mercadopago_custom[securityCode]" autocomplete="off"/>

        <span class="mp-error" id="mp-error-224" data-main="#securityCode"> <?php echo $form_labels['error']['224']; ?> </span>
        <span class="mp-error" id="mp-error-E302" data-main="#securityCode"> <?php echo $form_labels['error']['E302']; ?> </span>
      </div>
    </div>

    <div class="mp-box-inputs mp-col-100 mp-doc">
      <div class="mp-box-inputs mp-col-45 mp-docType">
        <label for="docType"><?php echo $form_labels['form']['document_type']; ?> <em>*</em></label>
        <select id="docType" data-checkout="docType" name="mercadopago_custom[docType]"></select>

        <span class="mp-error" id="mp-error-212" data-main="#docType"> <?php echo $form_labels['error']['212']; ?> </span>
        <span class="mp-error" id="mp-error-322" data-main="#docType"> <?php echo $form_labels['error']['322']; ?> </span>
      </div>

      <div class="mp-box-inputs mp-col-55 mp-docNumber">
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

    <div class="mp-box-inputs mp-col-100">
      <label for="installments"><?php echo $form_labels['form']['installments']; ?> <em>*</em></label>
      <select id="installments" data-checkout="installments" name="mercadopago_custom[installments]"></select>
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
      <input type="text" id="tempIssuer" name="mercadopago_custom[tempIssuer]"/>
      <input type="text" id="tempInstallments" name="mercadopago_custom[tempInstallments]"/>
    </div>

  </div>
</div>


<script>

var config_mp = {
  debug: false,
  add_truncated_card: true,
  site_id: mercadopago_custom_site_id,
  public_key: mercadopago_custom_public_key,
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
  selectors:{
    tempIssuer: "#tempIssuer",
    tempInstallments: "#tempInstallments",

    cardId: "#cardId",

    cardNumber: "#cardNumber",
    cardExpirationMonth: "#cardExpirationMonth",
    cardExpirationYear: "#cardExpirationYear",
    cardholderName: "#cardholderName",
    securityCode: "#securityCode",
    docType: "#docType",
    docNumber: "#docNumber",
    issuer: "#issuer",
    installments: "#installments",

    paymentMethodId: "#paymentMethodId",
    paymentMethodIdSelector: "#paymentMethodIdSelector",
    amount: "#amount",
    token: "#token",
    cardTruncated: "#cardTruncated",
    site_id: "#site_id",

    box_loading: "#mp-box-loading",
    submit: "#submit",
    form: '#mercadopago-form',
    utilities_fields: "#mercadopago-utilities"
  },
  text: {
    choose: "Choose"
  },
  paths:{
    loading: '<?php echo JURI::root() . 'plugins/vmpayment/mercadopago/mercadopago/assets/images/loading.gif'; ?>'
  }
}

Initialize();


//specific actions to populate data in the form
if(params_mercadopago_custom != null){

  document.querySelector(config_mp.selectors.cardNumber).value = params_mercadopago_custom.cardTruncated;
  document.querySelector(config_mp.selectors.cardExpirationMonth).value = params_mercadopago_custom.cardExpirationMonth;
  document.querySelector(config_mp.selectors.cardExpirationYear).value = params_mercadopago_custom.cardExpirationYear;
  document.querySelector(config_mp.selectors.cardholderName).value = params_mercadopago_custom.cardholderName;
  document.querySelector(config_mp.selectors.securityCode).value = params_mercadopago_custom.securityCode;
  document.querySelector(config_mp.selectors.docType).value = params_mercadopago_custom.docType;
  document.querySelector(config_mp.selectors.docNumber).value = params_mercadopago_custom.docNumber;

  //replica
  document.querySelector(config_mp.selectors.cardTruncated).value = params_mercadopago_custom.cardTruncated;

  //hidden inputs
  document.querySelector(config_mp.selectors.paymentMethodId).value = params_mercadopago_custom.paymentMethodId;
  document.querySelector(config_mp.selectors.token).value = params_mercadopago_custom.token;

  document.querySelector(config_mp.selectors.tempInstallments).value = params_mercadopago_custom.installments;
  document.querySelector(config_mp.selectors.tempIssuer).value = params_mercadopago_custom.issuer;

  //action to get installments
  guessingPaymentMethod({type: "keyup"});

  //set timeout and select
  setTimeout(function() {
    document.querySelector(config_mp.selectors.installments).value = params_mercadopago_custom.installments;
    document.querySelector(config_mp.selectors.issuer).value = params_mercadopago_custom.issuer;
  }, 1000);
}

/*
*
*
* payment methods
*
*/

function getBin() {
  var cardSelector = document.querySelector(config_mp.selectors.cardId);
  if (cardSelector && cardSelector[cardSelector.options.selectedIndex].value != "-1") {
    return cardSelector[cardSelector.options.selectedIndex].getAttribute('first_six_digits');
  }
  var ccNumber = document.querySelector(config_mp.selectors.cardNumber);
  return ccNumber.value.replace(/[ .-]/g, '').slice(0, 6);
}

function clearOptions() {
  var bin = getBin();
  if (bin.length == 0) {
    hideIssuer();

    var selectorInstallments = document.querySelector(config_mp.selectors.installments),
    fragment = document.createDocumentFragment(),
    option = new Option(config_mp.text.choose + "...", '-1');

    selectorInstallments.options.length = 0;
    fragment.appendChild(option);
    selectorInstallments.appendChild(fragment);
    selectorInstallments.setAttribute('disabled', 'disabled');
  }
}

function guessingPaymentMethod(event) {

  var bin = getBin(),
  amount = document.querySelector(config_mp.selectors.amount).value;
  if (event.type == "keyup") {
    if (bin.length == 6) {
      Mercadopago.getPaymentMethod({
        "bin": bin
      }, setPaymentMethodInfo);
    }
  } else {
    setTimeout(function() {
      if (bin.length >= 6) {
        Mercadopago.getPaymentMethod({
          "bin": bin
        }, setPaymentMethodInfo);
      }
    }, 100);
  }
};

function setPaymentMethodInfo(status, response) {
  if (status == 200) {

    if(config_mp.site_id != "MLM"){
      //guessing
      document.querySelector(config_mp.selectors.paymentMethodId).value = response[0].id;
      document.querySelector(config_mp.selectors.cardNumber).style.background = "url(" + response[0].secure_thumbnail + ") 98% 50% no-repeat #fff";
    }



    // check if the security code (ex: Tarshop) is required
    var cardConfiguration = response[0].settings,
    bin = getBin(),
    amount = document.querySelector(config_mp.selectors.amount).value;

    for (var index = 0; index < cardConfiguration.length; index++) {
      if (bin.match(cardConfiguration[index].bin.pattern) != null && cardConfiguration[index].security_code.length == 0) {
        /*
        * In this case you do not need the Security code. You can hide the input.
        */
      } else {
        /*
        * In this case you NEED the Security code. You MUST show the input.
        */
      }
    }

    Mercadopago.getInstallments({
      "bin": bin,
      "amount": amount
    }, setInstallmentInfo);

    // check if the issuer is necessary to pay
    var issuerMandatory = false,
    additionalInfo = response[0].additional_info_needed;

    for (var i = 0; i < additionalInfo.length; i++) {
      if (additionalInfo[i] == "issuer_id") {
        issuerMandatory = true;
      }
    };
    if (issuerMandatory) {
      Mercadopago.getIssuers(response[0].id, showCardIssuers);
      addEventCheckout(document.querySelector(config_mp.selectors.issuer), 'change', setInstallmentsByIssuerId);
    } else {
      hideIssuer();
    }
  }
};


/*
*
*
* Issuers
*
*/

function showCardIssuers(status, issuers) {

  var issuersSelector = document.querySelector(config_mp.selectors.issuer),
  fragment = document.createDocumentFragment();

  issuersSelector.options.length = 0;
  var option = new Option(config_mp.text.choose + "...", '-1');
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
  //document.querySelector(config_mp.selectors.issuer).removeAttribute('style');
};

function setInstallmentsByIssuerId(status, response) {
  var issuerId = document.querySelector(config_mp.selectors.issuer).value,
  amount = document.querySelector(config_mp.selectors.amount).value;

  if (issuerId === '-1') {
    return;
  }

  Mercadopago.getInstallments({
    "bin": getBin(),
    "amount": amount,
    "issuer_id": issuerId
  }, setInstallmentInfo);
};



function hideIssuer(){
  var $issuer = document.querySelector(config_mp.selectors.issuer);
  var opt = document.createElement('option');
  opt.value = "1";
  opt.innerHTML = "Other Bank";

  $issuer.innerHTML = "";
  $issuer.appendChild(opt);
  // $issuer.setAttribute('disabled', 'disabled');
}

/*
*
*
* Installments
*
*/

function setInstallmentInfo(status, response) {
  var selectorInstallments = document.querySelector(config_mp.selectors.installments);

  if (response.length > 0) {

    var html_option = '<option value="-1">' + config_mp.text.choose + '...</option>';
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
};


/*
*
*
* Customer & Cards
*
*/

function cardsHandler() {
  clearOptions();
  var cardSelector = document.querySelector(config_mp.selectors.cardId),
  amount = document.querySelector(config_mp.selectors.amount).value;

  if (cardSelector && cardSelector[cardSelector.options.selectedIndex].value != "-1") {
    var _bin = cardSelector[cardSelector.options.selectedIndex].getAttribute("first_six_digits");
    Mercadopago.getPaymentMethod({
      "bin": _bin
    }, setPaymentMethodInfo);
  }
}


/*
* Payment Methods
*
*/

function getPaymentMethods(){
  var paymentMethodsSelector = document.querySelector(config_mp.selectors.paymentMethodIdSelector)

  //set loading
  paymentMethodsSelector.style.background = "url("+config_mp.paths.loading+") 95% 50% no-repeat #fff";

  Mercadopago.getAllPaymentMethods(function(code, payment_methods){
    fragment = document.createDocumentFragment();
    option = new Option(config_mp.text.choose + "...", '-1');
    fragment.appendChild(option);


    for(var x=0; x < payment_methods.length; x++){
      var pm = payment_methods[x];

      if((pm.payment_type_id == "credit_card" ||
      pm.payment_type_id == "debit_card" ||
      pm.payment_type_id == "prepaid_card") &&
      pm.status == "active"){

        option = new Option(pm.name, pm.id);
        fragment.appendChild(option);

      }//end if

    } //end for

    paymentMethodsSelector.appendChild(fragment);
    paymentMethodsSelector.style.background = "#fff";
  });
}

/*
*
* Functions related to Create Tokens
*
*/


function createTokenByEvent(){
  for(var x = 0; x < document.querySelectorAll('[data-checkout]').length; x++){
    var element = document.querySelectorAll('[data-checkout]')[x];

    //add events only in the required fields
    if(config_mp.inputs_to_create_token.indexOf(element.getAttribute("data-checkout")) > -1){

      var event = "focusout";

      if(element.nodeName == "SELECT"){
        event = "change";
      }

      addEventCheckout(element, event, validateInputsCreateToken);

      if(config_mp.create_token_on.keyup){
        addEventCheckout(element, "keyup", validateInputsCreateToken);
      }

      if(config_mp.create_token_on.paste){
        addEventCheckout(element, "paste", validateInputsCreateToken);
      }

    }
  }
}

function createTokenBySubmit(){
  addEventCheckout(document.querySelector(config_mp.selectors.form), 'submit', doPay);
}

var doSubmit = false;

function doPay(event){
  event.preventDefault();
  if(!doSubmit){
    createToken();
    return false;
  }
};

function validateInputsCreateToken(){
  var valid_to_create_token = true;

  for(var x = 0; x < document.querySelectorAll('[data-checkout]').length; x++){
    var element = document.querySelectorAll('[data-checkout]')[x];

    //check is a input to create token
    if(config_mp.inputs_to_create_token.indexOf(element.getAttribute("data-checkout")) > -1){
      if(element.value == -1 || element.value == ""){
        valid_to_create_token = false;
      } //end if check values
    } //end if check data-checkout
  }//end for

  if(valid_to_create_token){
    createToken();
  }
}

function createToken(){
  hideErrors();

  //show loading
  document.querySelector(config_mp.selectors.box_loading).style.background = "url("+config_mp.paths.loading+") 0 50% no-repeat #fff";

  //form
  var $form = document.querySelector(config_mp.selectors.form);

  Mercadopago.createToken($form, sdkResponseHandler);

  return false;
}

function sdkResponseHandler(status, response) {
  //hide loading
  document.querySelector(config_mp.selectors.box_loading).style.background = "";

  if (status != 200 && status != 201) {
    showErrors(response);
  }else{
    var token = document.querySelector(config_mp.selectors.token);
    token.value = response.id;

    if(config_mp.add_truncated_card){
      var card = truncateCard(response);
      document.querySelector(config_mp.selectors.cardTruncated).value=card;
    }

    if(!config_mp.create_token_on.event){
      doSubmit=true;
      btn = document.querySelector(config_mp.selectors.form);
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


function truncateCard(response_card_token){
  var first_six_digits = response_card_token.first_six_digits.match(/.{1,4}/g)
  var card = first_six_digits[0] + " " + first_six_digits[1] + "** **** " + response_card_token.last_four_digits;
  return card;
}

/*
*
*
* Show errors
*
*/

function showErrors(response){
  for(var x = 0; x < response.cause.length; x++){
    var error = response.cause[x];
    var $span = document.querySelector('#mp-error-' + error.code);
    var $input = document.querySelector($span.getAttribute("data-main"));

    $span.style.display = 'inline-block';
    $input.classList.add("mp-error-input");

  }

  return;
}

function hideErrors(){

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


function addEventCheckout(el, eventName, handler){
  if (el.addEventListener) {
    el.addEventListener(eventName, handler);
  } else {
    el.attachEvent('on' + eventName, function(){
      handler.call(el);
    });
  }
};


addEventCheckout(document.querySelector(config_mp.selectors.cardNumber), 'keyup', guessingPaymentMethod);
addEventCheckout(document.querySelector(config_mp.selectors.cardNumber), 'keyup', clearOptions);
addEventCheckout(document.querySelector(config_mp.selectors.cardNumber), 'change', guessingPaymentMethod);
cardsHandler();


/*
*
*
* Initialization function
*
*/

function Initialize(){

  if(config_mp.create_token_on.event){
    createTokenByEvent();
  }else{
    createTokenBySubmit()
  }

  Mercadopago.setPublishableKey(config_mp.public_key);

  if(config_mp.site_id != "MLM"){
    Mercadopago.getIdentificationTypes();
  }

  if(config_mp.site_id == "MLM"){

    //hide documento for mex
    document.querySelector(".mp-doc").style.display = 'none';
    document.querySelector(".mp-paymentMethodsSelector").removeAttribute('style');

    //removing not used fields for this country
    config_mp.inputs_to_create_token.splice(config_mp.inputs_to_create_token.indexOf("docType"), 1);
    config_mp.inputs_to_create_token.splice(config_mp.inputs_to_create_token.indexOf("docNumber"), 1);

    //get payment methods and populate selector
    getPaymentMethods();
  }

  if(config_mp.site_id == "MLB"){

    document.querySelector(".mp-docType").style.display = 'none';
    document.querySelector(".mp-issuer").style.display = 'none';
    //ajust css
    document.querySelector(".mp-docNumber").classList.remove("mp-col-75");
    document.querySelector(".mp-docNumber").classList.add("mp-col-100");

  }else if (config_mp.site_id == "MCO") {
    document.querySelector(".mp-issuer").style.display = 'none';
  }

  document.querySelector(config_mp.selectors.site_id).value = config_mp.site_id;

  if(config_mp.debug){
    document.querySelector(config_mp.selectors.utilities_fields).style.display = 'inline-block';
    // console.log(config_mp);
  }
}
</script>
