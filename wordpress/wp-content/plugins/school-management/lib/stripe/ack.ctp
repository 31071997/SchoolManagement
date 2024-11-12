<script>
$(function(){
		alert('load');
});
</script>
<?php 
	

 try {

     \Stripe\Stripe::setApiKey("sk_test_XfXCNhjeE9GbRFxQsEhytHDJ");
		$token = $_POST['stripeToken'];	
		
		$customer = \Stripe\Customer::create(array(
			"source" => $token,
			"plan" => "2",
			"email" => "ahirjignesh4@gmail.com")
			);

$string_json=json_encode($customer);
		
	mail("ahirjignesh4@gmail","push","work");
	var_dump($string_json);
 
} catch(\Stripe\Error\Card $e) {
  // Since it's a decline, \Stripe\Error\Card will be caught
  $body = $e->getJsonBody();
  $err  = $body['error'];

  print('Status is:' . $e->getHttpStatus() . "\n");
  print('Type is:' . $err['type'] . "\n");
  print('Code is:' . $err['code'] . "\n");
  // param is '' in this case
  print('Param is:' . $err['param'] . "\n");
  print('Message is:' . $err['message'] . "\n");
} catch (\Stripe\Error\RateLimit $e) {
  // Too many requests made to the API too quickly
} catch (\Stripe\Error\InvalidRequest $e) {
  // Invalid parameters were supplied to Stripe's API
} catch (\Stripe\Error\Authentication $e) {
  // Authentication with Stripe's API failed
  // (maybe you changed API keys recently)
} catch (\Stripe\Error\ApiConnection $e) {
  // Network communication with Stripe failed
} catch (\Stripe\Error\Base $e) {
  // Display a very generic error to the user, and maybe send
  // yourself an email
} catch (Exception $e) {
  // Something else happened, completely unrelated to Stripe
  echo $e->getMessage();
}

?>