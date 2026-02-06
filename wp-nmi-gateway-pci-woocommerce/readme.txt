=== Pledged Plugins PCI Gateway for NMI and WooCommerce ===
Contributors: mohsinoffline
Donate link: https://wpgateways.com/support/send-payment/
Tags: nmi, network merchants, payment gateway, woocommerce, pci
Plugin URI: https://bitbucket.org/pledged/wc-nmi-pci-pro
Author URI: https://pledgedplugins.com
Requires at least: 4.4
Tested up to: 6.9
Requires PHP: 5.6
Stable tag: 1.2.10
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

PCI Compliant NMI payment gateway integration for WooCommerce to accept credit cards directly on WordPress e-commerce websites.

== Description ==

[NMI](https://www.nmi.com/) (Network Merchants) provides all the tools and services for merchants to accept almost any kind of payment online making the perfect solution for accepting credit, debit and electronic payments online.

[WooCommerce](https://woocommerce.com/) is one of the oldest and most powerful e-commerce solutions for WordPress. This platform is very widely supported in the WordPress community which makes it easy for even an entry level e-commerce entrepreneur to learn to use and modify.

#### Free version Features
* **Easy Install**: Like all Pledged Plugins add-ons, this plugin installs with one click. After installing, you will have only a few fields to fill out before you are ready to accept credit cards on your store.
* **Secure Credit Card Processing**: Uses [Collect.js](https://secure.nmi.com/merchants/resources/integration/download.php?document=collectjs) tokenization library to send secure payment data directly to NMI so no worries about certifying with PCI-DSS.
* **Refund via Dashboard**: Process full or partial refunds, directly from your WordPress dashboard! No need to search order in your NMI account.
* **Authorize Now, Capture Later**: Optionally choose only to authorize transactions, and capture at a later date.
* **Restrict Card Types**: Optionally choose to restrict certain card types and the plugin will hide its icon and provide a proper error message on checkout.
* **Gateway Receipts**: Optionally choose to send receipts from your NMI merchant account.
* **Logging**: Enable logging so you can debug issues that arise if any.

> #### Pro Version Features
> * Everything that is in the Free version plus...
> * **Shipping Fields:**  Pass shipping address to NMI.
> * **AVS and CVV Responses:**  Store AVS and CVV responses in order notes.
> * **Payment Amounts:**  Display the payment amounts in order notes.
> * **Dynamic Descriptors:**  Pass dynamic descriptor to NMI that will appear on a credit card or bank statement of the customer.
>
> #### Enterprise Version Features
> * Everything that is in the Free and Pro versions plus...
> * **Process Subscriptions:**  Use with  [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/)  extension to **create and manage products with recurring payments**  — payments that will give you residual revenue you can track and count on.
> * **Setup Pre-Orders:**  Use with  [WooCommerce Pre-Orders](https://woocommerce.com/products/woocommerce-pre-orders/)  extension so customers can order products before they’re available by submitting their card details. The card is then automatically charged when the pre-order is available.
> * **3D Secure 2 Card Verification:**  Optionally, enable 3D Secure 2 card verification and make your site Strong Customer Authentication (SCA) compliant.
> * **ACH Payments:**  Fully supports eCheck payments via ACH network.
> * **Pay via Saved Cards:** Enable option to use saved card details on the gateway servers for quicker checkout. No sensitive card data is stored on the website!
> * **One Click Upsells:** Compatible with  [FunnelKit (formerly WooFunnels) One Click Upsells](https://funnelkit.com/woocommerce-one-click-upsells-upstroke/).
>
> [Click here](https://pledgedplugins.com/products/nmi-payment-gateway-woocommerce/) for Pricing details.

#### Requirements
* Active  [NMI](https://www.nmi.com/)  account.
* [**WooCommerce**](https://woocommerce.com/)  version 3.3 or later.
* A valid SSL certificate is required to ensure your customer credit card details are safe and make your site PCI DSS compliant. This plugin does not store the customer credit card numbers or sensitive information on your website.
#### Extend, Contribute, Integrate
Contributors are welcome to send pull requests via [Bitbucket repository](https://bitbucket.org/pledged/wc-nmi-pci-pro).

For custom payment gateway integration with your WordPress website, please [contact us here](https://wpgateways.com/support/custom-payment-gateway-integration/).

#### Disclaimer
This plugin is not affiliated with or supported by NMI, WooCommerce.com or Automattic. All logos and trademarks are the property of their respective owners.

== Installation ==

1. Upload `wp-nmi-gateway-pci-woocommerce` folder/directory to the `/wp-content/plugins/` directory.
2. Activate the plugin (WordPress -> Plugins).
3. Go to the WooCommerce settings page (WordPress -> WooCommerce -> Settings) and select the Payments tab.
4. Under the Payments tab, you will find all the available payment methods. Find the 'NMI' link in the list and click it.
5. On this page you will find all of the configuration options for this payment gateway.
6. Enable the method by using the checkbox.
7. Enter the NMI API keys (Private Key and Public Key).

That's it! You are ready to accept credit cards with your NMI merchant account now connected to WooCommerce.

== Frequently Asked Questions ==

= Is SSL Required to use this plugin? =
A valid SSL certificate is required to ensure your customer credit card details are safe and make your site PCI DSS compliant. This plugin does not store the customer credit card numbers or sensitive information on your website.

== Changelog ==

= 1.2.10 =
* Removed jquery-payment script handle
* Fixed state being passed as NA when the address fields are empty

= 1.2.9 =
* Made compatible with Theme high multi step checkout
* Added backward compatibility for refunds
* Updated "WC tested up to" header to 10.3

= 1.2.8 =
* Made compatible with Avada multi step checkout
* Updated "WC tested up to" header to 10.1

= 1.2.7 =
* Updated "WC tested up to" header to 10.0

= 1.2.6 =
* Added better error handling for card fields validation
* Fixed issues with WC version < 8.4.0
* Fixed gateway log URL
* Updated "WC tested up to" header to 9.9

= 1.2.5 =
* Added filter for capturing on order status change
* Fixed PHP notice on loading translation
* Fixed issue with data erasure
* Updated compatibility info to WordPress 6.8

= 1.2.4 =
* Added card validation error to order notes when collect.js is disabled
* Updated "WC tested up to" header to 9.8

= 1.2.3 =
* Added block checkout styling improvements
* Updated compatibility info to WordPress 6.7
* Updated "WC tested up to" header to 9.5

= 1.2.2 =
* Set order status to Failed only once on unsuccessful capture from Edit Order screen
* Added "Capture authorized transaction on status change" option
* Prevent blocking the billing details area for distraction free checkout
* Updated "WC tested up to" header to 9.4

= 1.2.1 =
* Fixed error message not showing on block checkout
* Fixed issue with collect.js being enqueued everywhere
* Updated compatibility info to WordPress 6.6
* Updated "WC tested up to" header to 9.2

= 1.2.0 =
* Added checkout block payments support
* Updated “WC tested up to” header to 9.0

= 1.1.7 =
* Fixed CSS issue with FunnelKit plugin
* Updated "WC tested up to" header to 8.7
* Updated compatibility info to WordPress 6.5

= 1.1.6 =
* Updated "WC tested up to" header to 8.3
* Updated compatibility info to WordPress 6.4
* Declared incompatibility with cart and checkout blocks

= 1.1.5 =
* Updated "WC tested up to" header to 8.2

= 1.1.4 =
* Updated "WC tested up to" header to 8.0
* Updated compatibility info to WordPress 6.3

= 1.1.3 =
* Made compatible with LaForat theme
* Updated "WC tested up to" header to 7.7

= 1.1.2 =
* Made compatible with WooCommerce HPOS
* Fixed captured payments being voided on cancelling orders
* Updated compatibility info to WordPress 6.2
* Updated "WC tested up to" header to 7.5

= 1.1.1 =
* Made compatible with Avada Checkout
* Made compatible with Woolentor plugin
* Added minor improvements in code base
* Updated "WC tested up to" header to 7.0
* Updated compatibility info to WordPress 6.1
* Fixed PHP notices

= 1.1.0 =
* Capture or void payment if the order is authorized regardless of whether it was changed from on-hold or not
* Saved "authcode" from transaction response to order meta
* Made compatible with CheckoutWC plugin
* Updated "WC tested up to" header to 6.6
* Updated compatibility info to WordPress 6.0
* General code clean up

= 1.0.4 =
* Updated "WC tested up to" header to 6.0
* Updated compatibility info to WordPress 5.8
* Updated minimum WC version to 3.3
* Added filter on error message displayed at checkout
* Added error code alongside the failed transaction response reason in order notes

= 1.0.3 =
* Updated "WC tested up to" header to 4.8
* Compatible to WordPress 5.6

= 1.0.2 =
* Print failed transaction response reason in order notes
* Updated min WC version to 3.3 and "WC tested up to" header to 4.3
* Fixed order line items

= 1.0.1 =
* Updated “WC tested up to” header to 4.2

= 1.0.0 =
* Initial release version
