<?php
// Tell Payfast that this page is reachable by triggering a header 200
header( 'HTTP/1.0 200 OK' );
flush();
$pfParamString = '' ;
$pfPassphrase =  get_option('payfast_salt_passphrase');
$live_mode =get_option('payfast_live_mode');
if($live_mode == 'no')
{
    define( 'SANDBOX_MODE', true );
}
$pfHost = SANDBOX_MODE ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
// Posted variables from ITN
$pfData = $_POST;
$results = $_POST;
// Strip any slashes in data
foreach( $pfData as $key => $val ) {
    $pfData[$key] = stripslashes( $val );
}
// Convert posted variables to a string
foreach( $pfData as $key => $val ) {
    if( $key !== 'signature' ) {
        $pfParamString .= $key .'='. urlencode( $val ) .'&';
    } else {
        break;
    }
}
$pfParamString = substr( $pfParamString, 0, -1 );

function pfValidSignature_payfast( $pfData, $pfParamString, $pfPassphrase = null ) {
    // Calculate security signature
    if($pfPassphrase === null) {
       $tempParamStrings = $pfParamString;
    } 
    else {
        $tempParamString = $pfParamString.'&passphrase='.urlencode( $pfPassphrase );
    }
    $signature = md5( $tempParamString );
    return ( $pfData['signature'] = $signature );
}
function pfValidIP_payfast() {
    // Variable initialization
    $validHosts = array(
        'www.payfast.co.za',
        'sandbox.payfast.co.za',
        'w1w.payfast.co.za',
        'w2w.payfast.co.za',
        );

    $validIps = [];

    foreach( $validHosts as $pfHostname ) {
        $ips = gethostbynamel( $pfHostname );

        if( $ips !== false )
            $validIps = array_merge( $validIps, $ips );
    }

    // Remove duplicates
    $validIps = array_unique( $validIps );
    $referrerIp = gethostbyname(parse_url($_SERVER['HTTP_REFERER'])['host']);
    if( in_array( $referrerIp, $validIps, true ) ) {
        return true;
    }
    return false;
}
	
function pfValidPaymentData( $cartTotal, $pfData ) {
    return !(abs((float)$cartTotal - (float)$pfData['amount_gross']) > 0.01);
}

function pfValidServerConfirmation( $pfParamString, $pfHost = 'sandbox.payfast.co.za', $pfProxy = null ) {
    // Use cURL (if available)
    if( in_array( 'curl', get_loaded_extensions(), true ) ) {
        // Variable initialization
        $url = 'https://'. $pfHost .'/eng/query/validate';

        // Create default cURL object
        $ch = curl_init();
    
        // Set cURL options - Use curl_setopt for greater PHP compatibility
        // Base settings
        curl_setopt( $ch, CURLOPT_USERAGENT, NULL );  // Set user agent
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );      // Return output as string rather than outputting it
        curl_setopt( $ch, CURLOPT_HEADER, false );             // Don't include header in output
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
        
        // Standard settings
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $pfParamString );
        if( !empty( $pfProxy ) )
            curl_setopt( $ch, CURLOPT_PROXY, $pfProxy );
    
        // Execute cURL
        $response = curl_exec( $ch );
        curl_close( $ch );
        if ($response === 'VALID') {
            return true;
        }
    }
    return false;
}
 
$check1 = pfValidSignature_payfast($pfData, $pfParamString);
$check2 = pfValidIP_payfast();
$check3 = pfValidPaymentData($pfData['amount_gross'] , $pfData);
$check4 = pfValidServerConfirmation($pfParamString, $pfHost);
if($check1 && $check2 && $check3 && $check4) 
{
	$root = dirname(dirname(dirname(dirname(__FILE__))));
	if (file_exists($root.'/wp-load.php')) 
	{
	require_once($root.'/wp-load.php');
	} 
	else 
	{
	  require_once($root.'/wp-config.php');
	}
	$obj_fees_payment = new mj_smgt_feespayment(); 
	$feedata['fees_pay_id'] = $pfData['m_payment_id'];
	$feedata['amount']=$pfData['amount_gross'];
	$feedata['payment_method']= 'PayFast';
	$feedata['trasaction_id']=$pfData['pf_payment_id'];
	$feedata['created_by']=$pfData['custom_int1'];
	$feedata['paid_by_date']=date('Y-m-d');		
	$feedata['email_address']=$pfData['email_address'];
	$feedata['name_first']=$pfData['name_first'];	
	$feedata['name_last']=$pfData['name_last'];
	$results = $obj_fees_payment->mj_smgt_add_feespayment_history_For_payfast($feedata);
	if($results)
	{
		  wp_redirect(home_url().'?dashboard=user&page=feepayment&tab=feepaymentlist&action=success&payment=paystack_success');
	} 
	else
	{
         
         wp_redirect(home_url().'?dashboard=user&page=feepayment&action=cancel');
    }
}
?>