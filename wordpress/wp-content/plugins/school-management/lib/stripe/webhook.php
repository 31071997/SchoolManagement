 <?php
 require_once('Stripe/init.php');
	require_once('Stripe/lib/Stripe.php');
\Stripe\Stripe::setApiKey("sk_test_XfXCNhjeE9GbRFxQsEhytHDJ");

// Retrieve the request's body and parse it as JSON
$input = @file_get_contents("php://input");
$event_json = json_decode($input);

fopen("demofsf.txt","w");

?>