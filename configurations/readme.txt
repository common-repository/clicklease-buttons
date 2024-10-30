=== Clicklease Buttons ===
Contributors: fjimenezq, natetanr, arodriguezj16, jjimenezcls
Tags: woocommerce, Clicklease, finance, sales, leasing
Requires at least: 5.0
Tested up to: 6.0.0
Stable tag: 2.0.4
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Increase your sales by adding a "finance with Clicklease button".

== Description ==

= What is Clicklease =

Whether customers don't have the cash, or don't qualify for traditional financing, Clicklease's custom, flexible payment plans make buying fast, easy, and affordable for US customers regardless of their credit or time in business. This means sellers can increase speed-to-market and close more deals. Instant approvals are available from $500 to $25,000.

https://www.youtube.com/watch?v=LDrTG6xT5Rc

= Getting Started =

To get started with Clicklease, request a partner account from our [website](https://www.clicklease.com/get-started/#form-jumplink). 

= The Clicklease Button Plugin =

The Clicklease plugin provides an easy way for equipment sellers to advertise affordable monthly payments for the equipment you sell and links business buyers to the Clicklease financing application. 
 
Buttons that can calculate an estimated monthly payment can be added to any product page or buttons can simply be added to a financing page so customers can get approved before shopping.

Learn more about setting up and using the plugin on our [Developer Portal](https://docs.clicklease.com/docs/woo-com-buttons).


= WooCommerce Required =

* This plugin is meant to be installed on a Wordpress site running WooCommerce

== Fully Customizable ==

* Choose between several styles of buttons 
* The position of the button
* The pages the button should appear


== Installation ==

= Automatic installation =

1. Log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.
2. Search for "Clicklease Buttons", and press "Install now".
3. Or, press "Upload Plugin" and select the zip file, then press "Install Now".


= Settings =

After the installation of the plugin, the button named "CL Buttons" should appear in the administrative bar on the left.
Once installed and activated, the CL Buttons links to an admin page where you will enter your Clicklease token and specify other settings such as where the button will display and the type of button to use.

More details available on our [Developer Portal](https://docs.clicklease.com/docs/woo-com-buttons). 

== Frequently Asked Questions ==

= How do I get a Clicklease token =

After Clicklease reviews and approves your [request to become an equipment partner](https://www.clicklease.com/get-started/#form-jumplink) you will receive the token that links the lease applications with your store. 


== Upgrade Notice ==

= 1.0 =

This is the first release.

== Screenshots ==

1. Clicklease icon: [wc_btns_plugin\src\assets\cl-icon.svg]
2. After installing our plugin, you will be able to see the Clicklease buttons icon on the right menu bar : [wc_btns_plugin\src\assets\screenShots\ScreenShot-1.png]
3. The admin panel asks for basic information like what pages to display the button and the personal token. The save button will be disabled until the token is entered successfully : [wc_btns_plugin\src\assets\screenShots\ScreenShot-2.png]
4. By clicking on the "Button Color" button you can choose the color and design for the button to display in the store:  [wc_btns_plugin\src\assets\screenShots\ScreenShot-3.png]
5. By clicking the "Button Placement In Product Details Page" button you can choose the position to display the button on the product page: [wc_btns_plugin\src\assets\screenShots\ScreenShot-4.png]
6. By clicking the "Button Placement In Shopping Cart" button you can choose the position to display the button on the shopping cart page.: [wc_btns_plugin\src\assets\screenShots\ScreenShot-5.png]


== Changelog ==

= 1.0.0 = 03-07-2022

* No changes

= 1.0.1 = 03-29-2022

* Added meaningful information to the readme.
* Changed screenShots and banners to prenset plugin on the store.
* Updated plugin contributors.

= 1.0.3 = 04-05-2022

* Plugin uses the styles which come on fetching svgs.
* Buttons are asynchronous rendered.
* Category Page uses "wp_footer" hook to place buttons blueprints.
* Blueprint tags to place the buttons can be either remoded o stayed.

= 1.0.4 = 04-19-2022

* Added alternative ways to try rendering the button. It uses either some more wc hooks or js code in case the common way did not work.

=2.0.0 = 06-07-2022

* New admin page design.
* New buttons designs and options.
* Validation for token.
* Validations for the Max Amount rage from the vendor token information.

=2.0.1 = 06-15-2022
* Includes a functionality of our online app using an iframe when the buttons are clicked.
* The time it takes for the iframe to load on the page is much less.

=2.0.2 = 06-15-2022
* Performance improvement.
* The time it takes for the iframe to load on the page is much less.

=2.0.3 = 06-30-2022
* Performance improvement.
* Simplifying services to improve plugin loading speed.

