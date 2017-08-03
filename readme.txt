=== Plugin Name ===
Contributors: Slekx
Donate link: http://slekx.com/
Tags: donate, donation, paypal, charity, monetize, post, money
Requires at least: 2.7
Tested up to: 2.9
Stable tag: 1.0.0

Get your readers to donate more by linking to your PayPal donation page at the end of every post. Includes a donation link shortcode.

== Description ==

Sometimes readers just need a little reminder that you are providing them with useful information. The Donate Everywhere Plugin lets you remind them of that every time they use your website. By default, this plugin adds the line "Did you find this information helpful? If you did, consider donating." to the end of every post, with a link to your PayPal donation page. Customize this message, enter your PayPal information, and wait for the donations to start rolling in. That's all there is to it.

Multiple currencies are supported, and most advanced PayPal HTML variables are as well (e.g., notify, return, cbt).

Donate Everywhere also includes a [donate] shortcode with the following attributes:

* email, Your PayPal email address.
* currency, The currency in which you would like to receive donations.
* amount, The donation amount.
* title, The name of the donation in the PayPal shopping cart.
* return, The URL to direct the user to after the transaction (e.g., a 'Thank You' page.
* returnmessage, The message to show on the return button in the PayPal confirmation page.
* style, The PayPal page style.
* notify, The notification URL for PayPal IPN.

All of these attributes are optional. If they are omitted, the default values that you set in the options page will be used. That way, you don't have to type out your account information every time you want to include a donation link.

Example usage of the shortcode:

`[donate email='myEmail@example.com' currency='USD' amount='10.00' title='Donation to MyBlog'] Buy me a cup of coffee. [/donate]`


Full documentation is included on the options page. Read more about this plugin on the [Donate Everywhere Plugin](http://slekx.com/donate-everywhere) page.


== Installation ==

1. Upload the `donate-everywhere` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter your PayPal information into the options page
4. Use the shortcode in any post or page
5. Configure the auto-insert parameters in the options page to place a donation link in every post on your website


== Frequently Asked Questions ==

= Where is the options page? =

The options page can be found under `Settings > Donate Everywhere`


== Screenshots ==

1. The options page.
2. Full documentation included on the options page.

== Changelog ==

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.0 =

Initial release.


