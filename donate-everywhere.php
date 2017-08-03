<?php
/*
Plugin Name: Donate Everywhere
Plugin URI: http://www.slekx.com/donate-everywhere/
Description: Get your readers to donate more by linking to your PayPal donation page at the end of every post. Includes a donation link shortcode.
Version: 1.0
Author: Ryan Hodson
Author URI: http://www.slekx.com/
*/

/*
Copyright (c) 2010 by Ryan Hodson (aka Slekx)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

add_option('donate_autoInsertTop', 0);
add_option('donate_autoInsertBottom', 1);
add_option('donate_messageTop', 'If you find this information helpful, consider [donate]donating[/donate].');
add_option('donate_messageBottom', 'Did you find this information helpful? If you did, consider [donate]donating[/donate].');
add_option('donate_before', '<p>');
add_option('donate_after', '</p>');
add_option('donate_paypalurl',
		   'https://www.paypal.com/cgi-bin/webscr');
add_option('donate_paypalEmail', get_bloginfo('admin_email'));
add_option('donate_donationAmount', '');
add_option('donate_currency', 'USD');
add_option('donate_donationTitle',
		   'Donation to '.get_bloginfo('name'));
add_option('donate_returnurl', '');
add_option('donate_notifyurl', '');
add_option('donate_cbt', '');
add_option('donate_useNewWindow', 0);
add_option('donate_paypalPageStyle', '');

add_shortcode('donate', 'donate_shortcode');
add_filter('the_content', 'donate_content');
add_action('admin_menu', 'donate_admin');

function donate_shortcode($atts, $content){
	$default_email = get_option("donate_paypalEmail");
	$default_amount = get_option("donate_donationAmount");
	$default_currency = get_option("donate_currency");
	$default_title = get_option("donate_donationTitle");
	$default_returnurl = get_option("donate_returnurl");
	$default_notifyurl = get_option("donate_notifyurl");
	$default_cbt = get_option("donate_cbt");
	$default_style = get_option("donate_paypalPageStyle");
	extract(shortcode_atts(array(
		'email' => $default_email,
		'amount' => $default_amount,
		'currency' => $default_currency,
		'title' => $default_title,
		'return' => $default_returnurl,
		'notify' => $default_notifyurl,
		'returnmessage' => $default_cbt,
		'style' => $default_style
		), $atts));
	if(!$return){
		$return = get_permalink();
	}
	$webscr = get_option("donate_paypalurl");
	$link = "$webscr?cmd=_donations&business=$email&currency_code=$currency";
	$link .= "&amount=$amount&item_name=$title&return=$return&notify_url=$notify";
	$link .= "&cbt=$returnmessage&page_style=$style";
	if(get_option('donate_useNewWindow') == 1){
		$target = "_blank";
	}else{
		$target = "_self";
	}
	return "<a target='$target' href='$link'>$content</a>";
}

function donate_content($content){
	if(is_single()){
		if(get_option('donate_autoInsertTop') == 1){
    		$before = get_option("donate_before");
    		$message = get_option("donate_messageTop");
    		$after = get_option("donate_after");
			$content = $before . $message . $after . $content;
		}
		if(get_option('donate_autoInsertBottom') == 1){
    		$before = get_option("donate_before");
    		$message = get_option("donate_messageBottom");
    		$after = get_option("donate_after");
			$content = $content . $before . $message . $after;
		}
	}
	return $content;
}


// ----- admin functions -----

function donate_admin(){
    add_submenu_page('options-general.php', 'Donate Everywhere', 'Donate Everywhere', 'administrator', 'donate-everywhere-edit', 'donate_options');
	add_action('admin_init', 'register_options');
}

function register_options(){
    register_setting('donate-everywhere-settings', 'donate_messageTop');
    register_setting('donate-everywhere-settings', 'donate_messageBottom');
    register_setting('donate-everywhere-settings', 'donate_before');
    register_setting('donate-everywhere-settings', 'donate_after');
    register_setting('donate-everywhere-settings', 'donate_paypalurl');
    register_setting('donate-everywhere-settings', 'donate_paypalEmail');
    register_setting('donate-everywhere-settings', 'donate_donationAmount');
    register_setting('donate-everywhere-settings', 'donate_currency');
    register_setting('donate-everywhere-settings', 'donate_donationTitle');
    register_setting('donate-everywhere-settings', 'donate_autoInsertTop');
    register_setting('donate-everywhere-settings', 'donate_autoInsertBottom');
    register_setting('donate-everywhere-settings', 'donate_notifyurl');
    register_setting('donate-everywhere-settings', 'donate_returnurl');
	register_setting('donate-everywhere-settings', 'donate_cbt');
	register_setting('donate-everywhere-settings', 'donate_useNewWindow');
	register_setting('donate-everywhere-settings', 'donate_paypalPageStyle');
}

function donate_options(){
?>
<style type="text/css">
	.divide {
		background: #DDD;
		padding: 5px;
		width: 700px;
	}
	.radio {
		margin: 7px;
	}
	.docs {
		max-width: 700px;
		margin-bottom: 30px;
	}
	.indent {
		margin-left: 30px;
	}
</style>

<div class="wrap">
<h2>Donate Everywhere</h2>
<h3 class='divide'>Introduction</h3>
<div class='docs'>
<p>Thank you for downloading <strong>Donate Everywhere</strong>. If you're not sure where to start, take a look at the <a href='#docs'>Documentation Section</a>.
If you would like to pass on some of the donations that you've received via this plugin, consider <a target="_blank" href='https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ryan@slekx.com&currency_code=USD&amount=5&item_name=Donation for Donate Everywhere Plugin'>donating to the author</a>. 
</p>
</div>

<form method='POST' action='options.php'>
    <?php settings_fields('donate-everywhere-settings'); ?>
	<h3 class='divide'>PayPal Settings</h3>
    <p>PayPal Email:
    <input type='text' size='20' name='donate_paypalEmail' value='<?php echo get_option('donate_paypalEmail'); ?>' /></p>
	
    <p>PayPal URL:
    <input type='text' size='50' name='donate_paypalurl' value='<?php echo get_option('donate_paypalurl'); ?>' /></p>
	
    <p>PayPal Currency Code:
    <input type='text' size='10' name='donate_currency' value='<?php echo get_option('donate_currency'); ?>' /></p>
	
    <p>Donation Amount:
    <input type='text' size='10' name='donate_donationAmount' value='<?php echo get_option('donate_donationAmount'); ?>' /> (If omitted, the user will set the amount from the PayPal checkout page)</p>
	
    <p>Donation Title:
    <input type='text' size='50' name='donate_donationTitle' value='<?php echo get_option('donate_donationTitle'); ?>' /></p>
	
    <p>Return URL:
    <input type='text' size='50' name='donate_returnurl' value='<?php echo get_option('donate_returnurl'); ?>' /></p>
	
    <p>Return Message:
    <input type='text' size='50' name='donate_cbt' value='<?php echo get_option('donate_cbt'); ?>' /></p>
	
    <p>PayPal Page Style:
    <input type='text' size='50' name='donate_paypalPageStyle' value='<?php echo get_option('donate_paypalPageStyle'); ?>' /></p>
	
    <p>Notify URL:
    <input type='text' size='50' name='donate_notifyurl' value='<?php echo get_option('donate_notifyurl'); ?>' /></p>
	
    <p>Target Window:
	<input type="radio" name="donate_useNewWindow" value="0" class="radio" <?php if (get_option('donate_useNewWindow') == 0) {echo " checked='checked'"; }?> /><label>Current Window</label>
	<input type="radio" name="donate_useNewWindow" value="1" class="radio" <?php if (get_option('donate_useNewWindow') == 1) {echo " checked='checked'"; }?> /><label>New Window</label>
	<br/>
	
	<h3 class='divide'>Auto-Insert Settings</h3>
	
    <p>Top Message:
    <input type='text' size='100' name='donate_messageTop' value='<?php echo get_option('donate_messageTop'); ?>' /></p>
	
	<p>Auto-Insert Top:
	<input type="radio" name="donate_autoInsertTop" value="0" class="radio" <?php if (get_option('donate_autoInsertTop') == 0) {echo " checked='checked'"; }?> /><label>Disabled</label>
	<input type="radio" name="donate_autoInsertTop" value="1" class="radio" <?php if (get_option('donate_autoInsertTop') == 1) {echo " checked='checked'"; }?> /><label>Enabled</label>
	
    <p>Bottom Message:
    <input type='text' size='100' name='donate_messageBottom' value='<?php echo get_option('donate_messageBottom'); ?>' /></p>
	
	<p>Auto-Insert Bottom:
	<input type="radio" name="donate_autoInsertBottom" value="0" class="radio" <?php if (get_option('donate_autoInsertBottom') == 0) {echo " checked='checked'"; }?> /><label>Disabled</label>
	<input type="radio" name="donate_autoInsertBottom" value="1" class="radio" <?php if (get_option('donate_autoInsertBottom') == 1) {echo " checked='checked'"; }?> /><label>Enabled</label>
	
    <p>Before Text:
    <input type='text' size='10' name='donate_before' value='<?php echo get_option('donate_before'); ?>' />
    &nbsp;&nbsp;&nbsp;&nbsp;After Text:
    <input type='text' size='10' name='donate_after' value='<?php echo get_option('donate_after'); ?>' /></p>
	
    <p class='submit'>
        <input type='submit' class='button-primary' value='<?php _e('Save Changes') ?>' />
    </p>
</form>

<a name="docs"></a>
<h2>Documentation</h2>
<h3 class='divide'>General Usage</h3>
<div class='docs'>
<p>The <strong>Donate Everywhere</strong> Plugin can be used in two ways:
<ol>
<li><strong>Manually inserted</strong> into posts/pages as a shortcode:<br/>
<p>Add the following shortcode to any post or page in your website:<br/>
<div class='indent'><strong>[donate email='myEmail@example.com' currency='USD' amount='10.00' title='Donation to MyBlog'] Buy me a cup of coffee. [/donate]</strong></div>
<br/>
This will automatically transform the enclosed text into a link to your PayPal donation page. If you omit an attribute for the shortcode, the default values from 'PayPal Settings' will be used in its place.</p>
</li><br/>
<li><strong>Automatically inserted</strong> into the top/bottom of every post:<br/>
<p>Set the messages in 'Auto-Insert Settings' and make sure the desired auto-insert locations are set to 'Enabled'. These messages will appear on the top/bottom of each single post view, but not on static pages. The default values from 'PayPal Settings' will be used for all automatically generated links.
</p>
</li>
</ol>
</p></div>

<h3 class='divide'>Short Tag Attributes</h3>
<div class='docs'>
<p>Shortcode attributes override their respective default parameters defined in 'PayPal Settings'. The following is the list of available shortcode attributes and their corresponding settings.
<ul>
<li><strong>email</strong> - PayPal Email.</li>
<li><strong>currency</strong> - PayPal Currency Code.</li>
<li><strong>amount</strong> - Donation Amount.</li>
<li><strong>title</strong> - Donation Title.</li>
<li><strong>return</strong> - Return URL.</li>
<li><strong>returnmessage</strong> - Return Message.</li>
<li><strong>style</strong> - PayPal Page Style.</li>
<li><strong>notify</strong> - Notify URL.</li>
</p></div>

<h3 class='divide'>PayPal Settings</h3>
<div class='docs'>
<p><strong>PayPal Settings</strong> define the default parameters of the [donate][/donate] shortcode. If you omit attributes when using the shortcode, these values will be used. If you do pass attributes when using the shortcode, the corresponding defaults will be ignored. For example, you can promote a donation to your friend with '[donate email='myFriend@example.com']Send my friend some money[/donate]' without affecting your default email.</p>
<ul>
<li><strong>PayPal Email</strong> - The email of the PayPal account to which you would like to direct donations.</li>
<li><strong>PayPal URL</strong> - The URL of PayPal's web service. If you are testing in PayPal's sandbox, use: 'https://www.sandbox.paypal.com/cgi-bin/webscr'</li>
<li><strong>PayPal Currency Code</strong> - The currency in which you would like to accept donations. As of January, 2010, the <a href='https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_wa-outside'>supported currency codes from PayPal</a> are as follows:
<div class='indent'>
	Australian Dollars: AUD<br/>
	Canadian Dollars: CAD<br/>
	Euros: EUR<br/>
	Pounds Sterling: GBP<br/>
	Yen: JPY<br/>
	U.S. Dollars: USD<br/>
	New Zealand Dollar: NZD<br/>
	Swiss Franc: CHF<br/>
	Hong Kong Dollar: HKD<br/>
	Singapore Dollar: SGD<br/>
	Swedish Krona: SEK<br/>
	Danish Krone: DKK<br/>
	Polish Zloty: PLN<br/>
	Norwegian Krone: NOK<br/>
	Hungarian Forint: HUF<br/>
	Czech Koruna: CZK<br/>
	Israeli Shekel: ILS<br/>
	Mexican Peso: MXN<br/>
	Brazilian Real: BRL<br/>
	Malaysian Ringgits: MYR<br/>
	Philippine Pesos: PHP<br/>
	Taiwan New Dollars: TWD<br/>
	Thai Baht: THB<br/>
</div></li>
<li><strong>Donation Amount</strong> - The default donation amount for new donations. If this is omitted, the user will enter their own donation amount on the PayPal checkout page.</li>
<li><strong>Donation Title</strong> - The name that appears in the PayPal shopping cart during checkout.</li>
<li><strong>Return URL</strong> - The URL to direct the user to once the donation is complete. If this is omitted, the user will be directed back to the page from whence they came.</li>
<li><strong>Return Message</strong> - The message to display on the button that directs the user to 'Return URL'. This is the PayPal 'cbt' variable.</li>
<li><strong>PayPal Page Style</strong> - The page style displayed on the PayPal checkout page. This is the PayPal 'page_style' variable. If you're not sure what that is, just leave this field blank.</li>
<li><strong>Notify URL</strong> - The URL to notify of the transaction. This is used for implementing PayPal IPN. If you're not sure what that is, leave this field blank.</li>
<li><strong>Target Window</strong> - Where to open the PayPal checkout page. 'Current Window' will open it in the current window. 'New Window' will open it in a new window.</li>
</ul>
</div>
<h3 class='divide'>Auto-Insert Settings</h3>
<div class='docs'>
<p><strong>Auto-Insert Settings</strong> determine the plugin's donation request insertion behavior.</p>
<ul>
<li><strong>Top Message</strong> - The message to display at the top of each post. This message will be inserted into the beginning of every post. If 'Auto-Insert Top' is set to 'Disabled', this message will not be used. You must include the [donate] shortcode in the message in order to link to you PayPal account.</li>
<li><strong>Auto-Insert Top</strong> - Whether or not to insert 'Top Message' into each post.</li>
<li><strong>Bottom Message</strong> - The message to display at the bottom of each post. This message will be inserted into the end of every post. If 'Auto-Insert Bottom' is set to 'Disabled', this message will not be used. You must include the [donate] shortcode in the message in order to link to you PayPal account.</li>
<li><strong>Auto-Insert Bottom</strong> - Whether or not to insert 'Bottom Message' into each post.</li>
<li><strong>Before Text</strong> - Text that is inserted immediately before both messages.</li>
<li><strong>After Text</strong> - Text that is inserted immediately after both messages.</li>
</ul>
</div>

</div>
<?php }

?>
