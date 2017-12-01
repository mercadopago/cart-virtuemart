# VirtueMart - Mercado Pago Module (v3.0.x)

* [Funcionalidades](#features)
* [Requisitos](#requirements)
* [Versões disponíveis](#available_versions)
* [Instalação](#installation)
* [Configuração de Checkout Padrão](#configuration_std)
* [Cartão de crédito - Checkout Transparente](#configuration_custom)
* [Boleto - Checkout Transparente](#configuration_custom_ticket)
* [Social](#social)

<a name="features"> </a>
##Funcionalidades##

Opções de pagamentos para sua loja online:
Oferecemos dois métodos de pagamento que facilitam a aceitação dos pagamentos de qualquer pessoa, em qualquer lugar.

** Checkout personalizado **
Ofereça um checkout totalmente personalizado para aumentar a experiência da sua loja, o cliente não sai do seu ambiente.

* Integração simples - nenhuma codificação necessária, a menos que você queira.
* Controle total da experiência de compra.
* Pagamento com um clique após a primeira compra.
* Pagamentos com boletos
* Melhora a taxa de conversão.

* Disponível para Argentina, Brasil, Colômbia, México, Peru, Uruguai e Venezuela *

** Checkout básico **

Perfeito para quem busca uma instalação rápida.

* Fácil integração ao site - nenhuma codificação necessária.
* Controle limitado da experiência de compra - exiba a janela de pagamento como redirecionamento, modal ou iframe.
* Pagamento com um clique após a primeira compra.
* Pagamento com boletos, cartões e saldo em conta do Mercado Pago.
* Aceita cupons de descontos do Mercado Pago.

* Disponível para Argentina, Brasil, Chile, Colômbia, México, Peru, Uruguai e Venezuela *

<a name="requirements"> </a>
## Requisitos ##

Basicamente, os requisitos deste plugin são os mesmos que você precisa executar Virtuemart e Joomla. Sua máquina deve ter:

** Plataformas **
* <a href="https://www.joomla.org/download.html"> Joomla </a> 2.5 ou superior;
* <a href="https://virtuemart.net/downloads/"> VirtueMart </a> 3.0.x ou superior;

** Servidor Web Host **

* <a href="http://php.net/"> PHP </a> 5.3 ou superior com suporte CURL;
* <a href="http://www.mysql.com/"> MySQL </a> versão 5.5;
* <a href="https://httpd.apache.org/"> Apache 2.x </a>.

** certificado SSL **

Se você estiver usando Custom Checkout, é um requisito que você tenha um certificado SSL e o formulário de pagamento a ser fornecido em uma página HTTPS.

Durante os testes em modo sandbox, você pode operar por HTTP, mas para homologação, você precisará adquirir o certificado caso não o tenha.

<a name="available_versions"> </a>
## Versões disponíveis ##
<table>
  <thead>
    <tr>
      <th> Versão do Plugin </ th>
      <th> Status </ th>
      <th> VirtueMart Versões compatíveis </ th>
    </tr>
  <thead>
  <tbody>
    <tr>
      <td> v2.0.3 </ td>
      <td> Estável (versão atual) </ td>
      <td> VirtueMart v3.0.x </ td>
    </tr>
  </tbody>
</table>

<a name="installation"> </a>
##Instalação##

1. Baixe o módulo zip
2. Vá para **Extensões> Gerenciador de extensão **
3. Em **Carregar arquivo de pacote> Arquivo de pacote** selecione o **cart-virtuemart.zip** e clique em **Carregar e instalar **

## Vídeo de configuração no Youtube ##
## https://youtu.be/SmdRmGK-3_I

<a name="configuration_std"> </a>
##Configuração do checkout básico##

1. Vá para **VirtueMart> Métodos de pagamento** e clique em **Novo**

2. Complete os campos:
  - **Nome do pagamento** set **Mercado Pago**
  - **Sef Alias** set **mercadopago**
  - **Método de pagamento** selecione **Mercado Pago**
  - **Published** set to **true**
3. Clique em **Salvar**

4. Vá para **Configuração** guia <br/>
  Em primeiro lugar, você precisa configurar as credenciais do seu cliente. Para fazê-lo, preencha o **Client_id** e **Client_secret** na seção Configuração de Credenciais.

![Installation Instructions](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/credentials.png) <br />

  Você pode obter seu **Client_id** e **Client_secret**, de acordo com seu país, nos seguintes links:

    * Argentina: https://www.mercadopago.com/mla/account/credentials
    * Brazil: https://www.mercadopago.com/mlb/account/credentials
    * Colombia: https://www.mercadopago.com/mco/account/credentials
    * Mexico: https://www.mercadopago.com/mlm/account/credentials
    * Venezuela: https://www.mercadopago.com/mlv/account/credentials
    * Uruguay: https://www.mercadopago.com/mlu/account/credentials

5. Configurações do checkout básico. <br />

  ![Instruções de instalação] (https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/checkout_settings.png) <br/>

  **Tipo Checkout**: Como seus clientes irão interagir com o Mercado Pago para pagar suas ordens; <br />
  **Redirecionamento automático**: se configurado, a plataforma retornará à sua loja quando o pagamento for aprovado. <br />
  **Número máximo de parcelas**: as parcelas máximas permitidas para seus clientes; <br/>
  **Excluir métodos de pagamento**: Selecione os métodos de pagamento que você deseja não trabalhar com o Mercado Pago. <br />
  **iFrame Width**: A largura, em pixels, do iFrame (usado apenas com o Método de Integração iFrame); <br />
  **iFrame Height**: A altura, em pixels, do iFrame (usado apenas com iFrame Integration
  
  6. Confiuraça de notificaço de pagamento - IPN. <br />

  ![Installation Instructions](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/ipn_settings.png) <br />

  * **Escolha o status para pagamento aprovado**: Define o status do pedido quando o pagamento é aprovado.
  * **Escolha o status para pagamento pendente**: Define o status do pedido quando o pagamento é pendente.
  * **Escolha o status para pagamento em processamento**: Define o status do pedido quando o pagamento esta em processamento.
  * **Escolha o status para pagamento mediação**: Define o status do pedido quando o pagamento esta em mediação.
  * **Escolha o status para pagamento devolvido**: Define o status do pedido quando o pagamento é devolvido.
  * **Escolha o status para pagamento em chargeback**: Define o status do pedido quando o pagamento não reconhecido pelo usuário - chargeback.
  * **Escolha o status para pagamento cancelado**: Define o status do pedido quando o pagamento é cancelado.
  * **Escolha o status para pagamento rejeitado**: Define o status do pedido quando o pagamento é rejeitado.

7. Outras configurações. <br/>

  ![Instruçes de instalação](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/other_settings.png) <br />

  **Categoria da loja**: Defina a categoria da loja;<br />
  **Log**: Habilite/Desabilite logs.<br />
  **Logo**: Selecione o logo. Você precisa adicionar na pasta /images/stories/virtuemart/payment <br />
  
  <a name="configuration_custom"></a>
##Cartão de crédito - Checkout transparente configuração##

  1. Ir até **VirtueMart > Métodos de pagamento** e clique **Novo**

  2. Complete the fields:
    - **Nome do pagamento** set **Cartão de crédito- Mercado Pago**
    - **Sef Alias** set **mercadopago**
    - **Método de pagamento** select **Mercado Pago**
    - **Publicado** set to **true**

  3. Clique **Salvar**

  4. Clique na tab **Configurações**

  5. Em **Produto Mercado Pago** selecione **Carto de crédito - Checkout Transparente**

  6. Agora configure com as suas credenciais. Para fazer isso, informe seu **access_token** na sessão de credenciais.

    ![Instruções de configuração](https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/credentials_custom.png) <br />

    Para obter suas credenciais **Public Key** e **Access Token**, acesse os links a seguir de acordo com o seu país:

    * Argentina: https://www.mercadopago.com/mla/account/credentials
    * Brasil: https://www.mercadopago.com/mlb/account/credentials
    * Colombia: https://www.mercadopago.com/mco/account/credentials
    * Mexico: https://www.mercadopago.com/mlm/account/credentials
    * Venezuela: https://www.mercadopago.com/mlv/account/credentials
    * Uruguai: https://www.mercadopago.com/mlu/account/credentials
    
<a name="social"></a>
##Social##

Você pode participar do nosso grupo no facebook e assistir nossos vídeos no youtube
<ul>
  <li><a href="https://www.facebook.com/groups/modulos.mercadopago/?ref=ts&fref=ts" target="_blank">FACEBOOK</a></li>
  <li><a href="https://www.youtube.com/playlist?list=PLl8LGzRu2_sXxChIJm1e0xY6dU3Dj_tNi" target="_blank">YOUTUBE</a></li>
</ul>    
