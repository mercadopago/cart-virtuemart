# VirtueMart - Mercado Pago Module (v3.0.x)

* [Funcionalidades](#features)
* [Requisitos](#requirements)
* [Versões disponíveis](#available_versions)
* [Instalação](#installation)
* [Configuração de Checkout Padrão](#configuration_std)
* [Cartão de crédito - Custom Checkout Configurationn](#configuration_custom)
* [Ticket - Custom Checkout Configuration](#configuration_custom_ticket)
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
2. Vá para ** Extensões> Gerenciador de extensão **
3. Em ** Carregar arquivo de pacote> Arquivo de pacote ** selecione o ** cart-virtuemart.zip ** e clique em ** Carregar e instalar **

## Vídeo de configuração no Youtube ##
## https://youtu.be/SmdRmGK-3_I

<a name="configuration_std"> </a>
## Configuração do checkout básico ##

1. Vá para ** VirtueMart> Métodos de pagamento ** e clique em ** Novo **

2. Complete os campos:
  - ** Nome do pagamento ** set ** Mercado Pago **
  - ** Sef Alias ​​** set ** mercadopago **
  - ** Método de pagamento ** selecione ** Mercado Pago **
  - ** Published ** set to ** true **
3. Clique em ** Salvar **

4. Vá para ** Configuração ** guia <br/>
  Em primeiro lugar, você precisa configurar as credenciais do seu cliente. Para fazê-lo, preencha o ** Client_id ** e ** Client_secret ** na seção Configuração de Credenciais.

  ! [Instruções de instalação] (https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/credentials.png) <br />

  Você pode obter seu ** Client_id ** e ** Client_secret **, de acordo com seu país, nos seguintes links:

  * Argentina: https://www.mercadopago.com/mla/herramientas/aplicaciones
  * Brasil: https://www.mercadopago.com/mlb/ferramentas/aplicacoes
  * Chile: https://www.mercadopago.com/mlc/herramientas/aplicaciones
  * Colômbia: https://www.mercadopago.com/mco/herramientas/aplicaciones
  * México: https://www.mercadopago.com/mlm/herramientas/aplicaciones
  * Peru: https://www.mercadopago.com/mpe/account/credentials?type=basic
  * Venezuela: https://www.mercadopago.com/mlv/herramientas/aplicaciones
  * Uruguai: https://www.mercadopago.com/mlu/herramientas/aplicaciones

5. Configurações do checkout básico. <br/>

  ! [Instruções de instalação] (https://raw.github.com/mercadopago/cart-virtuemart/master/README.img/checkout_settings.png) <br />

  ** Tipo Checkout **: Como seus clientes irão interagir com o Mercado Pago para pagar suas ordens; <br />
  ** Redirecionamento automático **: se configurado, a plataforma retornará à sua loja quando o pagamento for aprovado. <br />
  ** Número máximo de parcelas **: as parcelas máximas permitidas para seus clientes; <br />
  ** Excluir métodos de pagamento **: Selecione os métodos de pagamento que você deseja não trabalhar com o Mercado Pago. <br />
  ** iFrame Width **: A largura, em pixels, do iFrame (usado apenas com o Método de Integração iFrame); <br />
  ** iFrame Height **: A altura, em pixels, do iFrame (usado apenas com iFrame Integration
