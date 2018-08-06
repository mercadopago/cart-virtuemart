<?php
/**
 * MercadoPago Integration Library
 * Access MercadoPago for payments integration
 *
 * @author hcasatti
 * @package VirtueMart
 * @subpackage payment
 * @link https://www.mercadopago.com
 * @copyright Copyright © 2016 MercadoPago.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 */

 defined('_JEXEC') or die('Restricted access');

$GLOBALS["LIB_LOCATION"] = dirname(__FILE__);

class MP {

    const version = "0.5.2";

    private $client_id;
    private $client_secret;
    private $ll_access_token;
    private $access_data;
    private $sandbox = FALSE;

    function __construct() {
        $i = func_num_args();

        if ($i > 2 || $i < 1) {
            throw new MercadoPagoException("Invalid arguments. Use CLIENT_ID and CLIENT SECRET, or ACCESS_TOKEN");
        }

        if ($i == 1) {
            $this->ll_access_token = func_get_arg(0);
        }

        if ($i == 2) {
            $this->client_id = func_get_arg(0);
            $this->client_secret = func_get_arg(1);
        }
    }

    public function sandbox_mode($enable = NULL) {
        if (!is_null($enable)) {
            $this->sandbox = $enable === TRUE;
        }

        return $this->sandbox;
    }

    /**
     * Get Access Token for API use
     */
    public function get_access_token() {
        if (isset ($this->ll_access_token) && !is_null($this->ll_access_token)) {
            return $this->ll_access_token;
        }

        $app_client_values = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'client_credentials'
        );

        $access_data = MPRestClient::post(array(
            "uri" => "/oauth/token",
            "data" => $app_client_values,
            "headers" => array(
                "content-type" => "application/x-www-form-urlencoded"
            )
        ));

        if ($access_data["status"] != 200) {
            throw new MercadoPagoException ($access_data['response']['message'], $access_data['status']);
        }

        $this->access_data = $access_data['response'];

        return $this->access_data['access_token'];
    }

    /**
     * Get information Merchant Order
     * @param id
     * @return array(json)
    */
    public function get_merchant_order($id) {
      $uri_prefix = $this->sandbox ? "/sandbox" : "";
      $request = array(
          "uri" => $uri_prefix."/merchant_orders/{$id}",
          "params" => array(
              "access_token" => $this->get_access_token()
          )
      );
        $merchant_order = MPRestClient::get($request);
        return $merchant_order;
    }

    /**
     * Get information for specific authorized payment
     * @param id
     * @return array(json)
    */
    public function get_authorized_payment($id) {
        $request = array(
            "uri" => "/authorized_payments/{$id}",
            "params" => array(
                "access_token" => $this->get_access_token()
            )
        );

        $authorized_payment_info = MPRestClient::get($request);
        return $authorized_payment_info;
    }

    /**
     * Cancel preapproval payment
     * @param int $id
     * @return array(json)
     */
    public function cancel_preapproval_payment($id) {
        $request = array(
            "uri" => "/preapproval/{$id}",
            "params" => array(
                "access_token" => $this->get_access_token()
            ),
            "data" => array(
                "status" => "cancelled"
            )
        );

        $response = MPRestClient::put($request);
        return $response;
    }

    /**
     * Create a checkout preference
     * @param array $preference
     * @return array(json)
     */
    public function create_preference($preference) {
        $request = array(
            "uri" => "/checkout/preferences",
            "params" => array(
                "access_token" => $this->get_access_token()
            ),
            "headers" => array(
                "user-agent" => "platform:desktop,type:virtuemart,so:" . MP_MODULE_VERSION
            ),
            "data" => $preference
        );

        $preference_result = MPRestClient::post($request);
        return $preference_result;
    }

    /**
     * Update a checkout preference
     * @param string $id
     * @param array $preference
     * @return array(json)
     */
    public function update_preference($id, $preference) {
        $request = array(
            "uri" => "/checkout/preferences/{$id}",
            "params" => array(
                "access_token" => $this->get_access_token()
            ),
            "data" => $preference
        );

        $preference_result = MPRestClient::put($request);
        return $preference_result;
    }

    /**
     * Get a checkout preference
     * @param string $id
     * @return array(json)
     */
    public function get_preference($id) {
        $request = array(
            "uri" => "/checkout/preferences/{$id}",
            "params" => array(
                "access_token" => $this->get_access_token()
            )
        );

        $preference_result = MPRestClient::get($request);
        return $preference_result;
    }


    /* APIs v1 */
    /**
     * Create a payment v1
     * @param array $preference
     * @return array(json)
     */

    public function create_payment($preference) {
        $request = array(
            "uri" => "/v1/payments",
            "params" => array(
                "access_token" => $this->get_access_token()
            ),
            "headers" => array(
                "X-Tracking-Id" => "platform:v1-whitelabel,type:virtuemart,so:" . MP_MODULE_VERSION
            ),
            "data" => $preference
        );

        $payment = MPRestClient::post($request);
        return $payment;
    }

    public function get_paymentV1($id) {
        $request = array(
            "uri" => "/v1/payments/{$id}",
            "params" => array(
                "access_token" => $this->get_access_token()
            )
        );

        $payment = MPRestClient::get($request);
        return $payment;
    }

    public function get_payment_methods_v1() {
        $request = array(
            "uri" => "/v1/payment_methods",
            "params" => array(
                "access_token" => $this->get_access_token()
            )
        );

        $payment = MPRestClient::get($request);
        return $payment;
    }

    public function get_or_create_customer($payer_email){
      $customer = $this->search_customer($payer_email);
      if($customer['status'] == 200 && $customer['response']['paging']['total'] > 0){
        $customer = $customer['response']['results'][0];
      }else{
        $customer = $this->create_customer($payer_email)['response'];
      }
      return $customer;
    }

    public function create_customer($email) {
        $request = array(
            "uri" => "/v1/customers",
            "params" => array(
                "access_token" => $this->get_access_token()
            ),
            "data" => array(
              "email" => $email
            )
        );
        $customer = MPRestClient::post($request);
        return $customer;
    }

    public function search_customer($email) {
        $request = array(
            "uri" => "/v1/customers/search",
            "params" => array(
                "access_token" => $this->get_access_token(),
                "email" => $email
            )
        );
        $customer = MPRestClient::get($request);
        return $customer;
    }

    public function create_card_in_customer($customer_id, $token, $payment_method_id = null, $issuer_id = null) {
        $request = array(
            "uri" => "/v1/customers/" . $customer_id . "/cards",
            "params" => array(
                "access_token" => $this->get_access_token()
            ),
            "data" => array(
              "token" => $token,
              "issuer_id" => $issuer_id,
              "payment_method_id" => $payment_method_id
            )
        );
        $card = MPRestClient::post($request);
        return $card;
    }

    public function get_all_customer_cards($customer_id, $token) {
        $request = array(
            "uri" => "/v1/customers/" . $customer_id . "/cards",
            "params" => array(
                "access_token" => $this->get_access_token()
            )
        );
        $cards = MPRestClient::get($request);
        return $cards;
    }


    /**
     * Create a preapproval payment
     * @param array $preapproval_payment
     * @return array(json)
     */
    public function create_preapproval_payment($preapproval_payment) {
        $request = array(
            "uri" => "/preapproval",
            "params" => array(
                "access_token" => $this->get_access_token()
            ),
            "data" => $preapproval_payment
        );

        $preapproval_payment_result = MPRestClient::post($request);
        return $preapproval_payment_result;
    }

    /**
     * Get a preapproval payment
     * @param string $id
     * @return array(json)
     */
    public function get_preapproval_payment($id) {
        $request = array(
            "uri" => "/preapproval/{$id}",
            "params" => array(
                "access_token" => $this->get_access_token()
            )
        );

        $preapproval_payment_result = MPRestClient::get($request);
        return $preapproval_payment_result;
    }

    /**
     * Update a preapproval payment
     * @param string $preapproval_payment, $id
     * @return array(json)
     */

    public function update_preapproval_payment($id, $preapproval_payment) {
        $request = array(
            "uri" => "/preapproval/{$id}",
            "params" => array(
                "access_token" => $this->get_access_token()
            ),
            "data" => $preapproval_payment
        );

        $preapproval_payment_result = MPRestClient::put($request);
        return $preapproval_payment_result;
    }

    /* Generic resource call methods */

    /**
    * Generic resource get
    * @param request
    * @param params (deprecated)
    * @param authenticate = true (deprecated)
    */
    public function get($request, $params = null, $authenticate = true) {
        if (is_string ($request)) {
            $request = array(
                "uri" => $request,
                "params" => $params,
                "authenticate" => $authenticate
            );
        }

        $request["params"] = isset ($request["params"]) && is_array ($request["params"]) ? $request["params"] : array();

        if (!isset ($request["authenticate"]) || $request["authenticate"] !== false) {
            $request["params"]["access_token"] = $this->get_access_token();
        }

        $result = MPRestClient::get($request);
        return $result;
    }

    /**
    * Generic resource post
    * @param request
    * @param data (deprecated)
    * @param params (deprecated)
    */
    public function post($request, $data = null, $params = null) {
        if (is_string ($request)) {
            $request = array(
                "uri" => $request,
                "data" => $data,
                "params" => $params
            );
        }

        $request["params"] = isset ($request["params"]) && is_array ($request["params"]) ? $request["params"] : array();

        if (!isset ($request["authenticate"]) || $request["authenticate"] !== false) {
            $request["params"]["access_token"] = $this->get_access_token();
        }

        $result = MPRestClient::post($request);
        return $result;
    }

    /**
    * Generic resource put
    * @param request
    * @param data (deprecated)
    * @param params (deprecated)
    */
    public function put($request, $data = null, $params = null) {
        if (is_string ($request)) {
            $request = array(
                "uri" => $request,
                "data" => $data,
                "params" => $params
            );
        }

        $request["params"] = isset ($request["params"]) && is_array ($request["params"]) ? $request["params"] : array();

        if (!isset ($request["authenticate"]) || $request["authenticate"] !== false) {
            $request["params"]["access_token"] = $this->get_access_token();
        }

        $result = MPRestClient::put($request);
        return $result;
    }

    /**
    * Generic resource delete
    * @param request
    * @param data (deprecated)
    * @param params (deprecated)
    */
    public function delete($request, $params = null) {
        if (is_string ($request)) {
            $request = array(
                "uri" => $request,
                "params" => $params
            );
        }

        $request["params"] = isset ($request["params"]) && is_array ($request["params"]) ? $request["params"] : array();

        if (!isset ($request["authenticate"]) || $request["authenticate"] !== false) {
            $request["params"]["access_token"] = $this->get_access_token();
        }

        $result = MPRestClient::delete($request);
        return $result;
    }

    /* **************************************************************************************** */

}

/**
 * MercadoPago cURL RestClient
 */
class MPRestClient {
    static $check_loop = 0;
    const API_BASE_URL = "https://api.mercadopago.com";
    
    /**
     *Product Id 
     */
    const PRODUCT_ID = "BC32CJBU643001OI39C0";

    private static function build_request($request) {
        if (!extension_loaded ("curl")) {
            throw new MercadoPagoException("cURL extension not found. You need to enable cURL in your php.ini or another configuration you have.");
        }

        if (!isset($request["method"])) {
            throw new MercadoPagoException("No HTTP METHOD specified");
        }

        if (!isset($request["uri"])) {
            throw new MercadoPagoException("No URI specified");
        }

        // Set headers
        $headers = array("accept: application/json");

        //set x_product_id
        if($request["method"] == 'POST'){
            $header_opt[] = "x-product-id: " . self::PRODUCT_ID;
        }

        $json_content = true;
        $form_content = false;
        $default_content_type = true;

        if (isset($request["headers"]) && is_array($request["headers"])) {
            foreach ($request["headers"] as $h => $v) {
                $h = strtolower($h);
                $v = strtolower($v);

                if ($h == "content-type") {
                    $default_content_type = false;
                    $json_content = $v == "application/json";
                    $form_content = $v == "application/x-www-form-urlencoded";
                }

                array_push ($headers, $h.": ".$v);
            }
        }
        if ($default_content_type) {
            array_push($headers, "content-type: application/json");
        }

        // Build $connect
        $connect = curl_init();

        curl_setopt($connect, CURLOPT_USERAGENT, "platform:desktop,type:virtuemart,so:" . MP_MODULE_VERSION);
        curl_setopt($connect, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($connect, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($connect, CURLOPT_CAINFO, $GLOBALS["LIB_LOCATION"] . "/cacert.pem");
        curl_setopt($connect, CURLOPT_CUSTOMREQUEST, $request["method"]);
        curl_setopt($connect, CURLOPT_HTTPHEADER, $headers);

        // Set parameters and url
        if (isset ($request["params"]) && is_array($request["params"]) && count($request["params"]) > 0) {
            $request["uri"] .= (strpos($request["uri"], "?") === false) ? "?" : "&";
            $request["uri"] .= self::build_query($request["params"]);
        }
        curl_setopt($connect, CURLOPT_URL, self::API_BASE_URL . $request["uri"]);

        // Set data
        if (isset($request["data"])) {
            if ($json_content) {
                if (gettype($request["data"]) == "string") {
                    json_decode($request["data"], true);
                } else {
                    $request["data"] = json_encode($request["data"]);
                }

                if(function_exists('json_last_error')) {
                    $json_error = json_last_error();
                    if ($json_error != JSON_ERROR_NONE) {
                        throw new MercadoPagoException("JSON Error [{$json_error}] - Data: ".$request["data"]);
                    }
                }
            } else if ($form_content) {
                $request["data"] = self::build_query($request["data"]);
            }

            curl_setopt($connect, CURLOPT_POSTFIELDS, $request["data"]);
        }

        return $connect;
    }

    private static function exec($request) {
    // private static function exec($method, $uri, $data, $content_type) {

        $connect = self::build_request($request);

        $api_result = curl_exec($connect);
        $api_http_code = curl_getinfo($connect, CURLINFO_HTTP_CODE);

        $response = array(
            "status" => $api_http_code,
            "response" => json_decode($api_result, true)
        );

        if ($response != null && $response['status'] >= 400 && self::$check_loop == 0) {
          try {
            self::$check_loop = 1;
            $message = null;
            $payload = null;
            $endpoint = null;
            $errors = array();

            if (isset($response['response'])) {
              if (isset($response['response']['message'])) {
                $message = $response['response']['message'];
              }
              if (isset ($response['response']['cause'])) {
                  if (isset ($response['response']['cause']['code']) && isset ($response['response']['cause']['description'])) {
                      $message .= " - ".$response['response']['cause']['code'].': '.$response['response']['cause']['description'];
                  } else if (is_array ($response['response']['cause'])) {
                      foreach ($response['response']['cause'] as $cause) {
                          $message .= " - ".$cause['code'].': '.$cause['description'];
                      }
                  }
              }
            }
            if ($request != null) {
              if (isset($request["uri"]) && $request["uri"] != null) {
                $endpoint = $request["uri"];
              }

              if (isset($request["data"]) && $request["data"] != null) {
                $payload = json_encode($request["data"]);
              }
            }

            $errors[] = array(
              "endpoint" => $endpoint,
              "message" => $message,
              "payloads" => $payload
            );


            $log_response = self::sendErrorLog($request["status"], $errors);

            if($log_response["status"] > 400) {
              throw new MercadoPagoException ("error to call API LOGS".$log_response["response"],
                                              $log_response["status"]);
            }
          } catch (Exception $e) {
            throw new MercadoPagoException ("error to call API LOGS".$e, 500);
          }
        }
        self::$check_loop = 0;
        curl_close($connect);

        return $response;
    }

    private static function build_query($params) {
        if (function_exists("http_build_query")) {
            return http_build_query($params, "", "&");
        } else {
            foreach ($params as $name => $value) {
                $elements[] = "{$name}=" . urlencode($value);
            }

            return implode("&", $elements);
        }
    }

    public static function get($request) {
        $request["method"] = "GET";

        return self::exec($request);
    }

    public static function post($request) {
        $request["method"] = "POST";

        return self::exec($request);
    }

    public static function put($request) {
        $request["method"] = "PUT";

        return self::exec($request);
    }

    public static function delete($request) {
        $request["method"] = "DELETE";

        return self::exec($request);
    }

    /**
    * Send error log to LogApi
    * @param code
    * @param errors
    */
    public static function sendErrorLog($code, $errors) {

      $data = array(
        "code" => $code,
        "module" => "VirtueMart",
        "module_version" => MP_MODULE_VERSION,
        "url_store" => $_SERVER['HTTP_HOST'],
        "email_admin" => MercadoPagoHelper::getAdminEmail(),
        "country_initial" => MercadoPagoHelper::getShopCountry(),
        "server_version" => php_uname('s'),
        "code_lang" => "PHP" . phpversion(),
        "errors" => $errors
      );

      $request = array(
        "uri" => "/modules/log",
        "data" => $data
      );

      $result_response = MPRestClient::post($request);
      return $result_response;
    }

}

class MercadoPagoException extends Exception {
    public function __construct($message, $code = 500, Exception $previous = null) {
        // Default code 500
        parent::__construct($message, $code, $previous);
    }
}
