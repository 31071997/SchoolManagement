<?php
// include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
session_start();
echo "123";
die;
if(get_option('amgt_paymaster_pack')=="yes" && is_plugin_active('apartment-management/apartment-management.php'))
{
	$amount =  $_REQUEST['amount'];
	$current_user_id = get_current_user_id();
	$currency = get_option("apartment_currency_code");
    $key_id =get_option('razorpay__key');
    $key_secret =get_option('razorpay_secret_mid');
}

$result = get_userdata($current_user_id);
$display_name = $result->display_name;
$user_login = $result->user_login;
$customer_email = $result->user_email;
$member_id = $user_info->ID;
$plan_name='Invoice Payment';
$plan_description='';
$plan_amount=$_REQUEST['amount'] * 100;
$pay_id = $_REQUEST['invoice_id'];
$payment_method = $_REQUEST['payment_method'];
$customer_id = $_REQUEST['member_id'];

try
{
    echo "123";
    die;
  session_start();
  $_SESSION['action_type']= 'frontend_invoice_payment';
  $_SESSION['invoice_id']= $_POST['invoice_id'];
  $_SESSION['paid_amount']= $_POST['amount'];

  $subdata = array(
    'amount' => $plan_amount,
    'currency' => "INR",
    'receipt' => 'receipt#101'
    );

    $suburl = 'https://api.razorpay.com/v1/orders';
    $subparams = http_build_query($subdata);

    //CURL Request
    $subch = curl_init();

    curl_setopt($subch, CURLOPT_URL, $suburl);
    curl_setopt($subch, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
    curl_setopt($subch, CURLOPT_TIMEOUT, 60);
    curl_setopt($subch, CURLOPT_POST, 1);
    curl_setopt($subch, CURLOPT_POSTFIELDS, $subparams);
    curl_setopt($subch, CURLOPT_RETURNTRANSFER, FALSE);
    curl_setopt($subch, CURLOPT_SSL_VERIFYPEER, true);
    $subResult = curl_exec($subch);
    $subres = json_decode($subResult);
    $http_status = curl_getinfo($subch, CURLINFO_HTTP_CODE);

    if($http_status === 200)
    {
        ?>
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
		<script>
            var options = {
                "key": "<?php echo $key_id; ?>", // Enter the Key ID generated from the Dashboard
                "amount": <?php echo $plan_amount;?>, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                "currency": "INR",
                "name": "<?php echo $plan_name; ?>",
                "description": "<?php echo $plan_description; ?>",
                "order_id": "<?php echo $subres->id;?>", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                "prefill": {
                //{
                "name": "<?php echo $user_name; ?>",
                "email": "<?php echo $user_email; ?>",
                "contact": "<?php echo $contact_number;?>"
                },
                "notes": {
                "address": ""
                },
                "theme": {
                "color": "#3399cc"
                }
            };
            var rzp1 = new Razorpay(options);

            rzp1.open();
            e.preventDefault();
		</script>
        <?php
        if(get_option('amgt_paymaster_pack')=="yes" && is_plugin_active('apartment-management/apartment-management.php'))
        {
            $paymentdata['invoice_id']=$pay_id;
            $paymentdata['amount']=$amount;
            $paymentdata['payment_method']=$payment_method;
            $paymentdata['member_id']=$customer_id;
            $paymentdata['created_by']=$customer_id;
            $obj_account =new Amgt_Accounts;
            $result = $obj_account->amgt_add_own_payment($paymentdata);

            if($result){
                wp_redirect( home_url()."?apartment-dashboard=user&page=accounts&tab=invoice-list&member_id=".$member_id);
            }
            else {
                wp_redirect( home_url(). get_option('amgt_stripe_cencal_url'));
            }
        }
    }
}
catch (\src\Error\RateLimit $e) {
	var_dump($e);
  // Too many requests made to the API too quickly
} catch (\src\Error\InvalidRequest $e) {
	var_dump($e);
  // Invalid parameters were supplied to Stripe's API
} catch (\src\Error\Authentication $e) {
	var_dump($e);
  // Authentication with Stripe's API failed
  // (maybe you changed API keys recently)
} catch (\src\Error\ApiConnection $e) {
	var_dump($e);
  // Network communication with Stripe failed
} catch (\src\Error\Base $e) {
	var_dump($e);
  // Display a very generic error to the user, and maybe send
  // yourself an email
} catch (Exception $e) {
	var_dump($e);
  // Something else happened, completely unrelated to Stripe
}

?>
