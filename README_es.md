# VirtueMart – Módulo de Mercado Pago (v3.0.x)

* [Funcionalidades](#Funcionalidades)
* [Requerimientos](#Requerimientos)
* [Versiones disponibles](#Versiones disponibles)
* [Instalación](#Instalación)
* [Configuración de checkout básico](#configuración_basico)
* [Tarjeta de crédito – Checkout personalizado](#configuración_custom)
* [Ticket – Checkout personalizado](#configuración_custom_ticket)
* [Social](#social)

<a name="Funcionalidades"></a>
##Funcionalidades##

Opciones de Checkout ideales para tu negocio:
Ofrecemos dos tipos de checkout que hacen fácil aceptar pagos de forma segura de cualquier persona, en cualquier lugar. 

**Checkout Personalizado**

Ofrece un checkout completamente customizable a la experiencia de tu marketplace con una API de payments fácil de usar. 

* Fácil integración — sin necesidad de modificar el código, salvo que quieras.
* Completo control de la experiencia de compra.
* Tarjetas guardadas del cliente para una experiencia más rápida.
* Aceotar tickets además de las tarjetas.
* Mejorara el índice de conversión.

*Disponible para Argentina, Brazil, Colombia, Mexico, Peru, Uruguay y Venezuela*

**Checkout básico**

Ideal para merchants que quieren salir de manera rápida y fácil. 

* Fácil integración web — Sin necesidad de desarrollar código.
* Control limitado de la experiencia de compra — Mostrar la ventana del checkout como redirect, modal o iframe.
* Guarda tarjetas del comprador para un checkout más rápido.
* Acepta tickets, transferencia bancaria y dinero en cuenta además de tarjetas de crédito. 
* Acepta cupones de descuento de MercadoPago. 

*Disponible para Argentina, Brazil, Chile, Colombia, Mexico, Peru, Uruguay y Venezuela*

<a name="Requerimientos"></a>
##Requerimientos##

Basicamente, los requerimientos de este plugin son los mismos que se necesitan para utilizer Virtuemart y Joomla. Tu máquina debería tener: 

**Plataformas**

* <a href="https://www.joomla.org/download.html">Joomla</a> 2.5 o mayor;
* <a href="https://virtuemart.net/downloads/">VirtueMart</a> 3.0.x o mayor;

**Web Server Host**

* <a href="http://php.net/">PHP</a> 5.3 o mayor con soporte CURL;
* <a href="http://www.mysql.com/">MySQL</a> version 5.5;
* <a href="https://httpd.apache.org/">Apache 2.x</a>.

**Certificado SSL**

Si estás utilizando Checkout básico, es un requerimiento que tengas un certificado SSL y el formulario del pago proveído bajo una página HTTPS. 
Durante los testeos en modo Sandbox, puedes operar sobre HTTP, pero para la homologaión necesitarás adquirir este certificado en caso de que no lo tengan. 


<a name="Versiones disponibles"></a>
##Versiones disponibles##
<table>
  <thead>
    <tr>
      <th>Version del plugin</th>
      <th>Status</th>
      <th>Versiones compatibles de Virtuemart</th>
    </tr>
  <thead>
  <tbody>
    <tr>
      <td>v2.0.3</td>
      <td>Estable (Versión actual)</td>
      <td>VirtueMart v3.0.x</td>
    </tr>
  </tbody>
</table>

<a name="Instalación"></a>
##Instalación##

1. Descargar el zip del módulo
2. Ir a **Extensions > Extension Manager**
3. En **Upload Package File > Package File** seleccionar **cart-virtuemart.zip** y clickear en **Upload & Installation**

<a name="configuración_básico"></a>
##Configuración de checkout básico##

1. Ir a **VirtueMart > Payment Methods** and click **New**

2. Completar el formulario:
  - **Payment set** setear **Mercado Pago**
  - **Sef Alias** setear **mercadopago**
  - **Payment Method** seleccionar **Mercado Pago**
  - **Published** setear **true**
3. Click en **Save**

4. Ir a **Configuration** <br/>
  Primero que nada, necesitas configurar las credenciales de tu cliente. Para hacerlo, complete tu **Client_id** y **Client_secret** en la sección de la configuración de las credenciales.

  ![Instrucciones de instalación](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/credentials.png) <br />

  Puedes obtener tu **Client_id** y **Client_secret**, dependiendo e tu país en los siguientes links:

  * Argentina: https://www.mercadopago.com/mla/herramientas/aplicaciones
  * Brazil: https://www.mercadopago.com/mlb/ferramentas/aplicacoes
  * Chile: https://www.mercadopago.com/mlc/herramientas/aplicaciones
  * Colombia: https://www.mercadopago.com/mco/herramientas/aplicaciones
  * Mexico: https://www.mercadopago.com/mlm/herramientas/aplicaciones
  * Peru: https://www.mercadopago.com/mpe/account/credentials?type=basic
  * Venezuela: https://www.mercadopago.com/mlv/herramientas/aplicaciones
  * Uruguay: https://www.mercadopago.com/mlu/herramientas/aplicaciones

5. Configuraciones del Checkout. <br/>

  ![Instrucciones de instalación](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/checkout_settings.png) <br />

  **Typo de Checkout**: Como tus clients van a interactuar con MercadoPaog para pagar sus ordenes.;<br />
  **Auto Redirect**: Si está seteado, la plataforma volverá a tu tienda cuando el pago sea aprobado.<br />
  **Maxima cantidad de cuotas**: La mayor cantidad de cuotas permitidas para tus clientes;<br />
  **Excluir métodos de pago**: Seleccionar los métodos de pago que no quieres trabajar con MercadoPago.<br />
  **Ancho del iFrame**: El ancho, en pixeles, del iFrame (sólo para integraciones que utilizan el iframe);<br />
  **Altura del iFrame**: La altura, en pixeles, del iFrame (sólo para integraciones que utilizan el iframe);<br />
  **Mercado Pago Sandbox**: Testea tus pagos en el ambiente de sandbox de Mercado Pago;<br/>

6. Configuraciones de IPN. <br/>

  ![Instrucciones para la instalación](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/ipn_settings.png) <br />

  * **Elige el status para ordenes aprobadas**: Setea el status de la orden cuando los pagos son aprobados.
  * **Elige el status para ordenes pendientes**: Setea el status de la orden cuando los pagos son pendientes.
  * **Elige el status para ordenes están en proceso**: Setea el status de la orden cuando los pagos están en proceso.
  * **Elige el status para ordenes están en mediación**: Setea el status de la orden cuando los pagos están en mediación.
  * **Elige el status para ordenes son devueltas**: Setea el status de la orden cuando los pagos son devueltos.
  * **Elige el status para ordenes tienen contracargos**: Setea el status de la orden cuando los pagos son contracargos.
   * **Elige el status para ordenes canceladas**: Setea el status de la orden cuando los pagos son cancelados.
   * **Elige el status para ordenes rechazadas**: Setea el status de la orden cuando los pagos son rechazados.

7. Otras configuraciones. <br/>

  ![Instruciones para la instalación](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/other_settings.png) <br />

  **Categoría de la tienda**: Define la categoría de la tienda. ;<br />
  **Log**: Habilitar/Deshabilitar logs.<br />
  **Logo**: Seleccionar el logo. Debes agregar el archivo en la carpeta /images/stories/virtuemart/payment <br />

<a name="configuración_custom"></a>
##Tarjeta de crédito – Configuración de checkout personalizado ##

  1. Ir a **VirtueMart > Payment Methods** y hacer click en **New**

  2. Completar los campos:
    - **Nombre de pago** setear **Tarjeta de crédito - Mercado Pago**
    - **Sef Alias** setear **mercadopago**
    - **Método de pago** seleccionar **Mercado Pago**
    - **Publicar** setear **true**

  3. Click en **Save**

  4. Ir a **Configuración** 

  5. En **Producto Mercado Pago** seleccionar **Tarjeta de crédito - Checkout personalizado**

  6. Configura tus credenciales. Para hacerlo, complete tu **access_token** en la sección de configuración de las credenciales. 

    ![Instrucciones para la instalación](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/credentials_custom.png) <br />

    Puedes obtener tu **Public Key** y **Access Token**, dependiendo de tu país, en:

    * Argentina: https://www.mercadopago.com/mla/account/credentials
    * Brazil: https://www.mercadopago.com/mlb/account/credentials
    * Colombia: https://www.mercadopago.com/mco/account/credentials
    * Mexico: https://www.mercadopago.com/mlm/account/credentials
    * Venezuela: https://www.mercadopago.com/mlv/account/credentials
    * Uruguay: https://www.mercadopago.com/mlu/account/credentials

  7. Configuración del checkout. <br/>

    ![Instruciones para la instalación](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/checkout_settings_custom.png) <br />

    **Statement Descriptor**: Setea la etiqueta que el cliente verá en su facture.;<br />
    **Binary**: Cuando se define como true, el pago solo puede ser aprobado o rechazado. Sino el status de in_process es agregado.<br />

  8. Configuración IPN. <br/>

    ![Instrucciones para la instalación](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/ipn_settings.png) <br />

  * **Elige el status para ordenes aprobadas**: Setea el status de la orden cuando los pagos son aprobados.
  * **Elige el status para ordenes pendientes**: Setea el status de la orden cuando los pagos son pendientes.
  * **Elige el status para ordenes están en proceso**: Setea el status de la orden cuando los pagos están en proceso.
  * **Elige el status para ordenes están en mediación**: Setea el status de la orden cuando los pagos están en mediación.
  * **Elige el status para ordenes son devueltas**: Setea el status de la orden cuando los pagos son devueltos.
  * **Elige el status para ordenes tienen contracargos**: Setea el status de la orden cuando los pagos son contracargos.
   * **Elige el status para ordenes canceladas**: Setea el status de la orden cuando los pagos son cancelados.
   * **Elige el status para ordenes rechazadas**: Setea el status de la orden cuando los pagos son rechazados.

<a name="configuración_custom_ticket"></a>
## Ticket – Checkout personalizado##

  1. Ir a **VirtueMart > Payment Methods** y hacer click en **New**

  2. Completar los campos:
    - **Nombre de pago** setear **Ticket - Mercado Pago**
    - **Sef Alias** setear **mercadopago**
    - **Método de pago** seleccionar **Mercado Pago**
    - **Publicar** setear **true**

  3. Click en **Save**

  4. Ir a **Configuración** tab

  5. En **Mercado Pago Product** seleccionar **Ticket - Checkout Custom**

  6. Ahora configure tus credenciales. Para hacerlo, complete **public_key** y **access_token** en la sección de configuración de credenciales.

    ![Instrucciones para la instalación](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/credentials_custom_ticket.png) <br />

    Puedes obtener tu **Public Key** y **Access Token**, dependiendo de tu país, en:

    * Argentina: https://www.mercadopago.com/mla/account/credentials
    * Brazil: https://www.mercadopago.com/mlb/account/credentials
    * Colombia: https://www.mercadopago.com/mco/account/credentials
    * Mexico: https://www.mercadopago.com/mlm/account/credentials
    * Venezuela: https://www.mercadopago.com/mlv/account/credentials
    * Uruguay: https://www.mercadopago.com/mlu/account/credentials
    

  7. Configuración IPN. <br/>

    ![Instrucciones para la instalación](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/ipn_settings.png) <br />

    * **Elige el status para ordenes aprobadas**: Setea el status de la orden cuando los pagos son aprobados.
  * **Elige el status para ordenes pendientes**: Setea el status de la orden cuando los pagos son pendientes.
  * **Elige el status para ordenes están en proceso**: Setea el status de la orden cuando los pagos están en proceso.
  * **Elige el status para ordenes están en mediación**: Setea el status de la orden cuando los pagos están en mediación.
  * **Elige el status para ordenes son devueltas**: Setea el status de la orden cuando los pagos son devueltos.
  * **Elige el status para ordenes tienen contracargos**: Setea el status de la orden cuando los pagos son contracargos.
  * **Elige el status para ordenes canceladas**: Setea el status de la orden cuando los pagos son cancelados.
  * **Elige el status para ordenes rechazadas**: Setea el status de la orden cuando los pagos son rechazados.


<a name="social"></a>
##social##

Sigue nuestro grupo de facebook y mira nuestros videos
<ul>
  <li><a href="https://www.facebook.com/groups/modulos.mercadopago/?ref=ts&fref=ts" target="_blank">FACEBOOK</a></li>
  <li><a href="https://www.youtube.com/playlist?list=PLl8LGzRu2_sXxChIJm1e0xY6dU3Dj_tNi" target="_blank">YOUTUBE</a></li>
</ul>

