=== Plugin Name ===
Contributors: myBadStudios
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=76A3BCV838GKW
Tags: Unity,unity3d,videogame,AssetStore,publisher,form,submit,myBad,Studios
Requires at least: 3.0.1
Tested up to: 4.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows Asset Store publishers to accept online customer support requests with automatic invoice validating beforehand

== Description ==

Allows Asset Store publishers to accept online customer support requests with automatic invoice validating beforehand.

Unity provides the api located at http://api.assetstore.unity3d.com/ to be used to validate invoices by requiring a publisher identifier and an invoice number(s). Once it receives both of these it will return an array containing the following information:
1. Purchase date
2. Was the transaction reversed / refunded
3. The invoice number you supplied
4. The package/product name

Invalid invoices are not returned so this kit checks the number of returned invoices against the number sent to Unity as well as the refund status of the invoices. If all invoices return as validated, the contact form will be sent to the configured address.

In terms of the data collected to be sent to Unity, the publisher's identification is achieved via the private API  key the publisher gets on the Unity Publisher's Administration page. This info is entered in the Dashboard and fetched before contacting the Unity API. This field is never revealed to anyone other than Unity and people with the authority to manage site options. The invoice numbers to be validated is provided by the person completing the form.

So in summary:
In the dashboard, provide the email address you want the contact form to be sent to and your private key to access the Unity API.
From the customer, collect their invoice number(s)
These two fields are sent to Unity who then returns anonymous info regarding the transaction including only purchases / refund status and purchase date.

== Installation ==

1. Upload the `asset_store_support` folder to the `/wp-content/plugins/` directory after extracting the zip file
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter the email address you want the contact form to be mailed to
4. Enter your publisher's API key in the settings page (Find this on your publisher's administration page on Unity3d.com)
5. Create a new page and add a as_contact_form short code. Done!

== Frequently Asked Questions ==

= Can this kit do X or Y or does it have feature A or B or C? =

If this kit has any specific feature it will be listed in the product description. If it is not there then you can always send me a request and I might add it in a future update.

== Screenshots ==

== Changelog ==
v1.1
Now not only verifies the invoice before sending the form but also sends the invoice number and your products on the invoice in the email also so you can verify the product in question is actually on the invoice.
The initial release also didn't include the name field from the contact form. That has been fixed. All fields are now returned.

== Upgrade Notice ==

