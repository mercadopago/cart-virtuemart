jQuery().ready(function ($) {

  var config = {

    custom_credid_card: ".mp-admin-checkout-custom-credid-card",
    custom_ticket: ".mp-admin-checkout-custom-ticket",
    custom: ".mp-admin-checkout-custom",
    basic: ".mp-admin-checkout-basic",

    selector: {
      type_integration: "select[name='params[mercadopago_product_checkout]']"
    }
  }


  $(config.selector.type_integration).change(function () {
    handleTypeIntegration();
  });



  handleTypeIntegration = function(){
    var type_integration = $(config.selector.type_integration).val();
    switch (type_integration) {

      case "basic_checkout":
        $(config.custom_credid_card).parents(".control-group").hide();
        $(config.custom_ticket).parents(".control-group").hide();
        $(config.custom).parents(".control-group").hide();

        $(config.basic).parents(".control-group").show();
      break;
      case "custom_credit_card":
        $(config.basic).parents(".control-group").hide();
        $(config.custom_ticket).parents(".control-group").hide();

        //show config credic card
        $(config.custom).parents(".control-group").show();
        $(config.custom_credid_card).parents(".control-group").show();
      break;
      case "custom_ticket":
        $(config.custom_credid_card).parents(".control-group").hide();
        $(config.basic).parents(".control-group").hide();

        //show config ticket
        $(config.custom).parents(".control-group").show();
        $(config.custom_ticket).parents(".control-group").show();
      break;


    }
  }


  //force init form
  handleTypeIntegration();

});
