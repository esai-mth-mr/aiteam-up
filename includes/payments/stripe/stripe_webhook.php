<?php
include 'stripe-php/init.php';

$webhook_secret =  ORM::for_table($config['db']['pre'] . 'options')->where('option_name', 'stripe_webhook_secret')->find_one();  

die($webhook_secret->option_value);
$secret_key = get_option('stripe_secret_key');

\Stripe\Stripe::setApiKey($secret_key);

$payload = @file_get_contents('php://input');
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $_SERVER['HTTP_STRIPE_SIGNATURE'],
        $webhook_secret // Replace with your actual webhook secret
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

// Handle the event
if ($event->type == 'checkout.session.completed') {
    $session = $event->data->object;

    // Retrieve relevant data from $session
    $customer_email = $session->customer_email;

    $user = ORM::for_table($config['db']['pre'] . 'user')->where('email', $customer_email)->find_one();

    if(!$user) return;

    email_template("signup-details", $user->id);
    
    $user->plan_type = $_SESSION['billed_type'];
    $user->group_id = $_SESSION['group_id'];
    $user->save();
    // Other necessary actions like sending emails, updating database, etc.
}

http_response_code(200);
?>
