# VirtueMart – Módulo de Mercado Pago (v3.0.x)
* [Funcionalidades](#Funcionalidades)
* [Requerimientos](#Requerimientos)
* [Versiones disponibles](#Versiones disponibles)
* [Instalación](#Instalación)
* [Configuración de checkout básico](#configuración_basico)
* [Tarjeta de crédito – Checkout personalizado](#configuración_custom)
* [Ticket – Checkout personalizado](#configuración_custom_ticket)
* [Social](#social)
&lt;a name=&quot;Funcionalidades&quot;&gt;&lt;/a&gt;
##Funcionalidades##
Opciones de Checkout ideales para tu negocio:
Ofrecemos dos tipos de checkout que hacen fácil aceptar pagos de forma
segura de cualquier persona, en cualquier lugar.
**Checkout Personalizado**
Ofrece un checkout completamente customizable a la experiencia de tu
marketplace con una API de payments fácil de usar.
* Fácil integración — sin necesidad de modificar el código, salvo que
quieras.
* Completo control de la experiencia de compra.
* Tarjetas guardadas del cliente para una experiencia más rápida.
* Aceotar tickets además de las tarjetas.
* Mejorara el índice de conversión.
*Disponible para Argentina, Brazil, Colombia, Mexico, Peru, Uruguay y
Venezuela*
**Checkout básico**
Ideal para merchants que quieren salir de manera rápida y fácil.
* Fácil integración web — Sin necesidad de desarrollar código.
* Control limitado de la experiencia de compra — Mostrar la ventana del
checkout como redirect, modal o iframe.
* Guarda tarjetas del comprador para un checkout más rápido.
* Acepta tickets, transferencia bancaria y dinero en cuenta además de
tarjetas de crédito.
* Acepta cupones de descuento de MercadoPago.
*Disponible para Argentina, Brazil, Chile, Colombia, Mexico, Peru, Uruguay
y Venezuela*
&lt;a name=&quot;Requerimientos&quot;&gt;&lt;/a&gt;
##Requerimientos##
Basicamente, los requerimientos de este plugin son los mismos que se
necesitan para utilizer Virtuemart y Joomla. Tu máquina debería tener:
**Plataformas**
* &lt;a href=&quot;https://www.joomla.org/download.html&quot;&gt;Joomla&lt;/a&gt; 2.5 o mayor;
* &lt;a href=&quot;https://virtuemart.net/downloads/&quot;&gt;VirtueMart&lt;/a&gt; 3.0.x o mayor;
**Web Server Host**

* &lt;a href=&quot;http://php.net/&quot;&gt;PHP&lt;/a&gt; 5.3 o mayor con soporte CURL;
* &lt;a href=&quot;http://www.mysql.com/&quot;&gt;MySQL&lt;/a&gt; version 5.5;
* &lt;a href=&quot;https://httpd.apache.org/&quot;&gt;Apache 2.x&lt;/a&gt;.
**Certificado SSL**
Si estás utilizando Checkout básico, es un requerimiento que tengas un
certificado SSL y el formulario del pago proveído bajo una página HTTPS.
Durante los testeos en modo Sandbox, puedes operar sobre HTTP, pero para la
homologaión necesitarás adquirir este certificado en caso de que no lo
tengan.
&lt;a name=&quot;Versiones disponibles&quot;&gt;&lt;/a&gt;
##Versiones disponibles##
&lt;table&gt;
&lt;thead&gt;
&lt;tr&gt;
&lt;th&gt;Version del plugin&lt;/th&gt;
&lt;th&gt;Status&lt;/th&gt;
&lt;th&gt;Versiones compatibles de Virtuemart&lt;/th&gt;
&lt;/tr&gt;
&lt;thead&gt;
&lt;tbody&gt;
&lt;tr&gt;
&lt;td&gt;v2.0.3&lt;/td&gt;
&lt;td&gt;Estable (Versión actual)&lt;/td&gt;
&lt;td&gt;VirtueMart v3.0.x&lt;/td&gt;
&lt;/tr&gt;
&lt;/tbody&gt;
&lt;/table&gt;
&lt;a name=&quot;Instalación&quot;&gt;&lt;/a&gt;
##Instalación##
1. Descargar el zip del módulo
2. Ir a **Extensions &gt; Extension Manager**
3. En **Upload Package File &gt; Package File** seleccionar **cart-
virtuemart.zip** y clickear en **Upload &amp; Installation**
&lt;a name=&quot;configuración_básico&quot;&gt;&lt;/a&gt;
##Configuración de checkout básico##
1. Ir a **VirtueMart &gt; Payment Methods** and click **New**
2. Completar el formulario:
- **Payment set** setear **Mercado Pago**
- **Sef Alias** setear **mercadopago**
- **Payment Method** seleccionar **Mercado Pago**
- **Published** setear **true**
3. Click en **Save**
4. Ir a **Configuration** &lt;br/&gt;
Primero que nada, necesitas configurar las credenciales de tu cliente.
Para hacerlo, complete tu **Client_id** y **Client_secret** en la sección
de la configuración de las credenciales.
![Instrucciones de instalación](https://raw.github.com/mercadopago/cart-
virtuemart/master/README.img/credentials.png) &lt;br /&gt;

Puedes obtener tu **Client_id** y **Client_secret**, dependiendo e tu
país en los siguientes links:
* Argentina: https://www.mercadopago.com/mla/herramientas/aplicaciones
* Brazil: https://www.mercadopago.com/mlb/ferramentas/aplicacoes
* Chile: https://www.mercadopago.com/mlc/herramientas/aplicaciones
* Colombia: https://www.mercadopago.com/mco/herramientas/aplicaciones
* Mexico: https://www.mercadopago.com/mlm/herramientas/aplicaciones
* Peru: https://www.mercadopago.com/mpe/account/credentials?type=basic
* Venezuela: https://www.mercadopago.com/mlv/herramientas/aplicaciones
* Uruguay: https://www.mercadopago.com/mlu/herramientas/aplicaciones
5. Configuraciones del Checkout. &lt;br/&gt;
![Instrucciones de instalación](https://raw.github.com/mercadopago/cart-
virtuemart/master/README.img/checkout_settings.png) &lt;br /&gt;
**Typo de Checkout**: Como tus clients van a interactuar con MercadoPaog
para pagar sus ordenes.;&lt;br /&gt;
**Auto Redirect**: Si está seteado, la plataforma volverá a tu tienda
cuando el pago sea aprobado.&lt;br /&gt;
**Maxima cantidad de cuotas**: La mayor cantidad de cuotas permitidas
para tus clientes;&lt;br /&gt;
**Excluir métodos de pago**: Seleccionar los métodos de pago que no
quieres trabajar con MercadoPago.&lt;br /&gt;
**Ancho del iFrame**: El ancho, en pixeles, del iFrame (sólo para
integraciones que utilizan el iframe);&lt;br /&gt;
**Altura del iFrame**: La altura, en pixeles, del iFrame (sólo para
integraciones que utilizan el iframe);&lt;br /&gt;
**Mercado Pago Sandbox**: Testea tus pagos en el ambiente de sandbox de
Mercado Pago;&lt;br/&gt;
6. Configuraciones de IPN. &lt;br/&gt;
![Instrucciones para la
instalación](https://raw.github.com/mercadopago/cart-
virtuemart/master/README.img/ipn_settings.png) &lt;br /&gt;
* **Elige el status para ordenes aprobadas**: Setea el status de la orden
cuando los pagos son aprobados.
* **Elige el status para ordenes pendientes**: Setea el status de la
orden cuando los pagos son pendientes.
* **Elige el status para ordenes están en proceso**: Setea el status de
la orden cuando los pagos están en proceso.
* **Elige el status para ordenes están en mediación**: Setea el status de
la orden cuando los pagos están en mediación.
* **Elige el status para ordenes son devueltas**: Setea el status de la
orden cuando los pagos son devueltos.
* **Elige el status para ordenes tienen contracargos**: Setea el status
de la orden cuando los pagos son contracargos.
* **Elige el status para ordenes canceladas**: Setea el status de la
orden cuando los pagos son cancelados.
* **Elige el status para ordenes rechazadas**: Setea el status de la
orden cuando los pagos son rechazados.
7. Otras configuraciones. &lt;br/&gt;
![Instruciones para la
instalación](https://raw.github.com/mercadopago/cart-
virtuemart/master/README.img/other_settings.png) &lt;br /&gt;

**Categoría de la tienda**: Define la categoría de la tienda. ;&lt;br /&gt;
**Log**: Habilitar/Deshabilitar logs.&lt;br /&gt;
**Logo**: Seleccionar el logo. Debes agregar el archivo en la carpeta
/images/stories/virtuemart/payment &lt;br /&gt;
&lt;a name=&quot;configuración_custom&quot;&gt;&lt;/a&gt;
##Tarjeta de crédito – Configuración de checkout personalizado ##
1. Ir a **VirtueMart &gt; Payment Methods** y hacer click en **New**
2. Completar los campos:
- **Nombre de pago** setear **Tarjeta de crédito - Mercado Pago**
- **Sef Alias** setear **mercadopago**
- **Método de pago** seleccionar **Mercado Pago**
- **Publicar** setear **true**
3. Click en **Save**
4. Ir a **Configuración**
5. En **Producto Mercado Pago** seleccionar **Tarjeta de crédito -
Checkout personalizado**
6. Configura tus credenciales. Para hacerlo, complete tu **access_token**
en la sección de configuración de las credenciales.
![Instrucciones para la
instalación](https://raw.github.com/mercadopago/cart-
virtuemart/master/README.img/credentials_custom.png) &lt;br /&gt;
Puedes obtener tu **Public Key** y **Access Token**, dependiendo de tu
país, en:
* Argentina: https://www.mercadopago.com/mla/account/credentials
* Brazil: https://www.mercadopago.com/mlb/account/credentials
* Colombia: https://www.mercadopago.com/mco/account/credentials
* Mexico: https://www.mercadopago.com/mlm/account/credentials
* Venezuela: https://www.mercadopago.com/mlv/account/credentials
* Uruguay: https://www.mercadopago.com/mlu/account/credentials
7. Configuración del checkout. &lt;br/&gt;
![Instruciones para la
instalación](https://raw.github.com/mercadopago/cart-
virtuemart/master/README.img/checkout_settings_custom.png) &lt;br /&gt;
**Statement Descriptor**: Setea la etiqueta que el cliente verá en su
facture.;&lt;br /&gt;
**Binary**: Cuando se define como true, el pago solo puede ser aprobado
o rechazado. Sino el status de in_process es agregado.&lt;br /&gt;
8. Configuración IPN. &lt;br/&gt;
![Instrucciones para la
instalación](https://raw.github.com/mercadopago/cart-
virtuemart/master/README.img/ipn_settings.png) &lt;br /&gt;
* **Elige el status para ordenes aprobadas**: Setea el status de la orden
cuando los pagos son aprobados.
* **Elige el status para ordenes pendientes**: Setea el status de la
orden cuando los pagos son pendientes.

* **Elige el status para ordenes están en proceso**: Setea el status de
la orden cuando los pagos están en proceso.
* **Elige el status para ordenes están en mediación**: Setea el status de
la orden cuando los pagos están en mediación.
* **Elige el status para ordenes son devueltas**: Setea el status de la
orden cuando los pagos son devueltos.
* **Elige el status para ordenes tienen contracargos**: Setea el status
de la orden cuando los pagos son contracargos.
* **Elige el status para ordenes canceladas**: Setea el status de la
orden cuando los pagos son cancelados.
* **Elige el status para ordenes rechazadas**: Setea el status de la
orden cuando los pagos son rechazados.
&lt;a name=&quot;configuración_custom_ticket&quot;&gt;&lt;/a&gt;
## Ticket – Checkout personalizado##
1. Ir a **VirtueMart &gt; Payment Methods** y hacer click en **New**
2. Completar los campos:
- **Nombre de pago** setear **Ticket - Mercado Pago**
- **Sef Alias** setear **mercadopago**
- **Método de pago** seleccionar **Mercado Pago**
- **Publicar** setear **true**
3. Click en **Save**
4. Ir a **Configuración** tab
5. En **Mercado Pago Product** seleccionar **Ticket - Checkout Custom**
6. Ahora configure tus credenciales. Para hacerlo, complete
**public_key** y **access_token** en la sección de configuración de
credenciales.
![Instrucciones para la
instalación](https://raw.github.com/mercadopago/cart-
virtuemart/master/README.img/credentials_custom_ticket.png) &lt;br /&gt;
Puedes obtener tu **Public Key** y **Access Token**, dependiendo de tu
país, en:
* Argentina: https://www.mercadopago.com/mla/account/credentials
* Brazil: https://www.mercadopago.com/mlb/account/credentials
* Colombia: https://www.mercadopago.com/mco/account/credentials
* Mexico: https://www.mercadopago.com/mlm/account/credentials
* Venezuela: https://www.mercadopago.com/mlv/account/credentials
* Uruguay: https://www.mercadopago.com/mlu/account/credentials

7. Configuración IPN. &lt;br/&gt;
![Instrucciones para la
instalación](https://raw.github.com/mercadopago/cart-
virtuemart/master/README.img/ipn_settings.png) &lt;br /&gt;
* **Elige el status para ordenes aprobadas**: Setea el status de la
orden cuando los pagos son aprobados.
* **Elige el status para ordenes pendientes**: Setea el status de la
orden cuando los pagos son pendientes.
* **Elige el status para ordenes están en proceso**: Setea el status de
la orden cuando los pagos están en proceso.

* **Elige el status para ordenes están en mediación**: Setea el status de
la orden cuando los pagos están en mediación.
* **Elige el status para ordenes son devueltas**: Setea el status de la
orden cuando los pagos son devueltos.
* **Elige el status para ordenes tienen contracargos**: Setea el status
de la orden cuando los pagos son contracargos.
* **Elige el status para ordenes canceladas**: Setea el status de la
orden cuando los pagos son cancelados.
* **Elige el status para ordenes rechazadas**: Setea el status de la
orden cuando los pagos son rechazados.
&lt;a name=&quot;social&quot;&gt;&lt;/a&gt;
##social##
Sigue nuestro grupo de facebook y mira nuestros videos
&lt;ul&gt;
&lt;li&gt;&lt;a
href=&quot;https://www.facebook.com/groups/modulos.mercadopago/?ref=ts&amp;fref=ts&quot;
target=&quot;_blank&quot;&gt;FACEBOOK&lt;/a&gt;&lt;/li&gt;
&lt;li&gt;&lt;a
href=&quot;https://www.youtube.com/playlist?list=PLl8LGzRu2_sXxChIJm1e0xY6dU3Dj_
tNi&quot; target=&quot;_blank&quot;&gt;YOUTUBE&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
