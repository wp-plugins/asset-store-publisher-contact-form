<?php

class unity_invoice_data
{
	var $refunded	= false;
	var $invoice	= "";
	var $date		= "";
	var $package	= "";
}

class unity_invoices
{	
	var $validated_invoices = null;
	
	function validated_invoices_count()
	{
		if (null == $this->validated_invoices)
			return 0;
			
		return count($this->validated_invoices);
	}
	
	function path($invoices)
	{
		return "http://api.assetstore.unity3d.com/publisher/v1/invoice/verify.json?key=".get_option("as_pub_key")."&invoice=$invoices" ;
	}
	
	function validate_invoices($invoices)
	{
		$this->validated_invoices = Array();
		$path = $this->path($invoices);
		
		$unity_data = file_get_contents( $path );
		$data = json_decode($unity_data);		
		foreach($data as $key => $val)
			$this->validate_entry($key, $val);
	}
	
	function validate_entry($key, $val)
	{
		if ( is_array( $val ) )
		{
			if ( count($val) > 0 )
				foreach($val as $sub_entry)
				{
					$this->validate_entry($key, $sub_entry);
				}
		}
		else
		{
			$entry = new unity_invoice_data();
			$entry->date		= $val->purchase_date;
			$entry->refunded	= !($val->refunded == "No");
			$entry->invoice		= $val->invoice_no;
			$entry->package		= $val->package_name;
			$this->validated_invoices[] = $entry;
		}
	}
	
	function is_validated($invoice_no)
	{
		if ($invoice_no == "" || $this->validated_invoices == null)
			return false;
			
		foreach($this->validated_invoices as $invoice)
			if ($invoice->invoice == $invoice_no)
				return true;
				
		return false;
	}
	
}

function as_validate_form($invoice, $name, $email, $subject, &$message)
{	
	if (empty($name)) return "Name is required";
	if (empty($subject)) return "Subject is required";
	if (empty($message)) return "Message is required";
	if (!is_email($email)) return "Email address is not valid";
	$message = "<p><strong>Product support request from $name [ $email ]:</strong></p><p>$message</p>";

	if (isset($_POST['skip'])) return "";
	
	if (empty($invoice)) return "Please supply a valid invoice number(s) for the product(s) you require assistance with";
	
	$invoice = str_replace(' ','',$invoice);
	$invoice = str_replace(',,',',',$invoice);
//	$invoice_count = count ( explode(',',$invoice) );

	$invoices = new unity_invoices();
	$invoices->validate_invoices($invoice);
//	if ($invoices->validated_invoices_count() != $invoice_count) return "One or more invoices were incorrect";
	foreach($invoices->validated_invoices as $valid_invoice)
		if ($valid_invoice->refunded) return "Invoice {$valid_invoice->invoice} was refunded!";

	$message .= "<h3><u>Valid invoices / products provided:</u></h3><p>";

	foreach($invoices->validated_invoices as $valid_invoice)
	{
		$message .= "$valid_invoice->invoice: ";
		$message .= $valid_invoice->package . "<br>";
	}
	$message .= "</p>";
	return '';
}
function as_send_contact_form($invoice, $name, $email, $subject, $message, $form_email)
{
	$email = trim($email);
	$name = trim($name);
	$subject = trim($subject);
	$message = trim($message);
	$invoice = trim($invoice);
	
	$error = as_validate_form($invoice, $name, $email, $subject, $message);
	if (!empty($error)) return $error;
	if (!wp_mail($form_email, $subject, $message, array("From: $email", 'Content-Type: text/html; charset=UTF-8') ) ) return "The e-mail could not be sent";
	return "";
}

function __as_posted($field, $cleaned = false)
{
	if (isset($_POST[$field]))
	{
		if ($cleaned) return sanitize_text_field($_POST[$field]);
		return $_POST[$field];
	}
	return "";
}

function __as_contact_form($atts, $content=null)
{
	$params = shortcode_atts(
		array('width' => get_option('as_form_width'),
			'contact_email' => get_option('as_form_email'),
		), $atts );
		
	if ($params['width'] == "")
	{
		update_option('as_form_width','800px');
		$params['width'] = "800px";
	}
	
	$key = get_option('as_pub_key');
	$form_email = get_option('as_form_email');
	if (empty($key))
		return "Key not configured! Please check dashboard";
	if (empty($form_email))
		return "Email not configured! Please check dashboard";
		
	$fields = array('Name'=>'user_name','Email'=>'email','Subject'=>'subject','Invoice number(s)'=>'invoice');
	$result = '';
	
	if (isset($_POST['as_contact_form']))
	{
		foreach ($fields as $field => $key)
			$$key = __as_posted($key);
	
		$message = __as_posted('message');
		$error = as_send_contact_form($invoice, $user_name, $email, $subject, $message, $form_email);
		if (empty($error))
		{
			$result .= '<div class=as_send_success><pre>Contact form successfully sent</pre></div>';
			$user_name = $invoice = $email = $subject = $message = '';
			unset($_POST);
		}else
		{
			$result .= "<div class=as_send_fail><pre>$error</pre></div>";
		}
	}
	
	$result .= '<form method="post">
	<table id="asset_store_contact_form" width="'.$params['width'].'">';
	
	foreach($fields as $field => $key)
	{
		$result .= '<tr><td class="field_name">' . $field . '</td><td class="spacer">&nbsp;</td><td class="field"><input type=text name="'.$key.'" value="'.$$key.'"></td></tr>';
	}
		$result .= '<tr><td colspan=3>Message</td></tr>';
		$result .= '<tr><td colspan=3><textarea name="message">'.$message.'</textarea></td></tr>';
		$result .= $content;
		$result .= '</table>
	<input type ="hidden" name="as_contact_form" value="y">
	<input type="submit" class="button-primary" value="Send form">
	</form>
	';
	
	return $result;
}