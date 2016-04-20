# VirtueMart - Mercado Pago Module (v3.0.x)

* [Features](#features)
* [Available versions](#available_versions)
* [Installation](#installation)
* [Configuration](#configuration)

<a name="features"></a>
##Features##
**Standard checkout**

This feature allows merchants to have a standard checkout. It includes features like
customizations of title, description, category, and external reference, integrations via
iframe, modal, and redirection, with configurable auto-returning, max installments and
payment method exclusion setup, and sandbox/debug options.

*Available for Argentina, Brazil, Chile, Colombia, Mexico and Venezuela*

<a name="available_versions"></a>
##Available versions##
<table>
  <thead>
    <tr>
      <th>Plugin Version</th>
      <th>Status</th>
      <th>VirtueMart Compatible Versions</th>
    </tr>
  <thead>
  <tbody>
    <tr>
      <td>v1.0.0</td>
      <td>Stable (Current version)</td>
      <td>VirtueMart v3.0.x</td>
    </tr>
  </tbody>
</table>

<a name="installation"></a>
##Installation##

1. Download the zip module
2. Go to **Extensions > Extension Manager**
3. In **Upload Package File > Package File** select the **cart-virtuemart.zip** and click **Upload & Installation**

<a name="configuration"></a>
##Configuration##

1. Go to **VirtueMart > Payment Methods** and click **New**

2. Complete the fields:
  - **Payment Name** set **Mercado Pago**
  - **Sef Alias** set **mercadopago**
  - **Payment Method** select **Mercado Pago**
  - **Published** set to **true**
3. Click in **Save**

4. Go to **Configuration** tab <br/>
  First of all, you need to configure your client credentials. To make it, fill your **Client_id**, **Client_secret** in Credentials Configuration section.

  ![Installation Instructions](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/credentials.png) <br />

  You can obtain your **Client_id** and **Client_secret**, accordingly to your country, in the following links:

  * Argentina: https://www.mercadopago.com/mla/herramientas/aplicaciones
  * Brazil: https://www.mercadopago.com/mlb/ferramentas/aplicacoes
  * Chile: https://www.mercadopago.com/mlc/herramientas/aplicaciones
  * Colombia: https://www.mercadopago.com/mco/herramientas/aplicaciones
  * Mexico: https://www.mercadopago.com/mlm/herramientas/aplicaciones
  * Venezuela: https://www.mercadopago.com/mlv/herramientas/aplicaciones

5. Checkout settings. <br/>

  ![Installation Instructions](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/checkout_settings.png) <br />

  **Type Checkout**: How your customers will interact with Mercado Pago to pay their orders;<br />
  **Auto Redirect**: If set, the platform will return to your store when the payment is approved.<br />
  **Maximum Number of Installments**: The maximum installments allowed for your customers;<br />
	**Exclude Payment Methods**: Select the payment methods that you want to not work with Mercado Pago.<br />
  **iFrame Width**: The width, in pixels, of the iFrame (used only with iFrame Integration Method);<br />
  **iFrame Height**: The height, in pixels, of the iFrame (used only with iFrame Integration Method);<br />
  **Mercado Pago Sandbox**: Test your payments in Mercado Pago sandbox environment;<br/>

6. IPN settings. <br/>

  ![Installation Instructions](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/ipn_settings.png) <br />

  * **Choose the status of approved orders**: Sets up the order status when payments are approved.
  * **Choose the status when payment is pending**: Sets up the order status when payments are pending.
  * **Choose the status when payment is process**: Sets up the order status when payments are in process.
  * **Choose the status when client open a mediation**: Sets up the order status when client opens a mediation.
  * **Choose the status of refunded orders**: Sets up the order status when payments are refunded.
  * **Choose the status when payment was chargeback**: Sets up the order status when payments are chargeback.
  * **Choose the status when payment was canceled**: Sets up the order status when payments are canceled.
  * **Choose the status when payment was reject**: Sets up the order status when payments are rejected.

7. Other settings. <br/>

  ![Installation Instructions](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/other_settings.png) <br />

  **Store Category**: Sets up the category of the store;<br />
  **Log**: Enables/disables logs.<br />
  **Logo**: Select the logo. You must add the file in the folder /images/stories/virtuemart/payment <br />
