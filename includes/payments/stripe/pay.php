<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'stripe-php/init.php';

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');


// manually set action for stripe payments
if (empty($action)) {
    $action = 'stripe_payment';
}

$currency = $config['currency_code'];
$user_id = $_SESSION['user']['id'];
$code = '';

if (isset($access_token)) {
    $payment_type = $_SESSION['quickad'][$access_token]['payment_type'];

    $title = $_SESSION['quickad'][$access_token]['name'];
    $total = $_SESSION['quickad'][$access_token]['amount'];
    $taxes_ids = isset($_SESSION['quickad'][$access_token]['taxes_ids']) ? $_SESSION['quickad'][$access_token]['taxes_ids'] : null;

    if ($payment_type == "subscr") {
        $base_amount = $_SESSION['quickad'][$access_token]['base_amount'];
        $plan_interval = $_SESSION['quickad'][$access_token]['plan_interval'];
        $payment_mode = $_SESSION['quickad'][$access_token]['payment_mode'];
        $package_id = $_SESSION['quickad'][$access_token]['sub_id'];

        if ($plan_interval == 'LIFETIME') {
            $payment_mode = 'one_time';
        }

        $cancel_url = $link['PAYMENT'] . "/?access_token=" . $access_token . "&status=cancel";

        $stripe_secret_key = get_option('stripe_secret_key');
        $stripe_publishable_key = get_option('stripe_publishable_key');
    } /*else if ($payment_type == "prepaid_plan") {
        $base_amount = $_SESSION['quickad'][$access_token]['base_amount'];
        $payment_mode = $_SESSION['quickad'][$access_token]['payment_mode'];
        $package_id = $_SESSION['quickad'][$access_token]['sub_id'];

        $payment_mode = 'one_time';

        $cancel_url = $link['PAYMENT'] . "/?access_token=" . $access_token . "&status=cancel";

        $stripe_secret_key = get_option('stripe_secret_key');
        $stripe_publishable_key = get_option('stripe_publishable_key');
    }
    elseif ($payment_type == "premium" || $payment_type == "banner-advertise") {
        $payment_mode = "one_time";
        $item_pro_id = $_SESSION['quickad'][$access_token]['product_id'];
        $amount = $_SESSION['quickad'][$access_token]['amount'];
        $base_amount = isset($_SESSION['quickad'][$access_token]['base_amount']) ? $_SESSION['quickad'][$access_token]['base_amount'] : $amount;
        $trans_desc = $_SESSION['quickad'][$access_token]['trans_desc'];

        if ($payment_type == "premium") {
            $item_featured = $_SESSION['quickad'][$access_token]['featured'];
            $item_urgent = $_SESSION['quickad'][$access_token]['urgent'];
            $item_highlight = $_SESSION['quickad'][$access_token]['highlight'];
        } else {
            $item_featured = 0;
            $item_urgent = 0;
            $item_highlight = 0;
        }

        $cancel_url = $link['PAYMENT'] . "/?access_token=" . $access_token . "&status=cancel";

        $stripe_secret_key = get_option('stripe_secret_key');
        $stripe_publishable_key = get_option('stripe_publishable_key');
    }*/ else {
        $plan_interval = 'Order';
        $payment_mode = 'one_time';
        $order_id = $_SESSION['quickad'][$access_token]['order_id'];
        $restaurant_id = $_SESSION['quickad'][$access_token]['restaurant_id'];
        $restaurant = ORM::for_table($config['db']['pre'] . 'restaurant')
            ->find_one($restaurant_id);

        $userdata = get_user_data(null, $restaurant['user_id']);
        $currency = !empty($userdata['currency']) ? $userdata['currency'] : get_option('currency_code');

        $cancel_url = $link['PAYMENT'] . "/?access_token=" . $access_token;

        $stripe_secret_key = get_restaurant_option($restaurant_id, 'restaurant_stripe_secret_key');
        $stripe_publishable_key = get_restaurant_option($restaurant_id, 'restaurant_stripe_publishable_key');
    }
}

if (!empty($action)) {
    switch ($action) {
        case 'stripe_payment':

            /* Initiate Stripe */
            \Stripe\Stripe::setApiKey($stripe_secret_key);
            \Stripe\Stripe::setApiVersion('2020-08-27');

            $stripe_formatted_price = in_array($currency, ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF']) ? number_format($total, 0, '.', '') : number_format($total, 2, '.', '') * 100;

            switch ($payment_mode) {
                case 'one_time':
                    if ($payment_type == "subscr") {
                        $meta_data = array(
                            'user_id' => $user_id,
                            'package_id' => $package_id,
                            'payment_type' => $payment_type,
                            'payment_frequency' => $plan_interval,
                            'base_amount' => $base_amount,
                            'taxes_ids' => $taxes_ids
                        );
                    } elseif ($payment_type == "prepaid_plan") {
                        $meta_data = array(
                            'user_id' => $user_id,
                            'package_id' => $package_id,
                            'payment_type' => $payment_type,
                            'base_amount' => $base_amount,
                            'taxes_ids' => $taxes_ids
                        );
                    } elseif ($payment_type == "premium" || $payment_type == "banner-advertise") {
                        $meta_data = array(
                            'user_id' => $user_id,
                            'product_id' => $item_pro_id,
                            'title' => $title,
                            'amount' => $amount,
                            'trans_desc' => $trans_desc,
                            'payment_type' => $payment_type,
                            'taxes_ids' => $taxes_ids,
                            'item_featured' => $item_featured,
                            'item_urgent' => $item_urgent,
                            'item_highlight' => $item_highlight
                        );
                    } else {
                        $meta_data = array(
                            'order_id' => $order_id,
                            'restaurant_id' => $restaurant_id,
                            'amount' => $total
                        );
                    }

                    try {
                        $freeTrail = date("Y-m-d", strtotime('+14 days'));
                        $stripeCheckout = [
                            'mode'          =>  'subscription',
                            'subscription_data' => array(
                                'metadata'      =>  $meta_data,
                            ),
                            'metadata'      =>  $meta_data,
                            'line_items'    =>  [
                                array(
                                    // 'name'  =>  $title,
                                    // 'description'   =>  $title,
                                    // 'amount'    =>  $stripe_formatted_price,
                                    // 'currency'  =>  $currency,
                                    // 'quantity'  =>  1,
                                    // ),
                                    'price_data' => [
                                        'product_data' => [
                                            'name' => $title,  // Use the product name here
                                            'description'   =>  $title,
                                        ],
                                        'currency' => $currency,
                                        'unit_amount' => $stripe_formatted_price, // Provide the amount in cents
                                    ],
                                    'quantity'  =>  1,
                                )
                            ],
                            'customer_email' => $_SESSION['quickad'][$access_token]['email'],
                            'success_url' => $link['PAYMENT'] . "/?access_token=" . $access_token . "&i=stripe&action=stripe_ipn&session_id={CHECKOUT_SESSION_ID}",
                            'cancel_url'    =>  $cancel_url,
                        ];
                        if(strtolower($_SESSION['quickad'][$access_token]['plan_interval']) == "monthly") {
                            $stripeCheckout['line_items'][0]['price_data']['recurring']['interval'] = 'month'; // Change this to the appropriate interval for your subscription
                        }
                        else if(strtolower($_SESSION['quickad'][$access_token]['plan_interval']) == "annually") {
                            $stripeCheckout['line_items'][0]['price_data']['recurring']['interval'] = 'year'; // Change this to the appropriate interval for your subscription
                        }

                        if($_SESSION['quickad'][$access_token]['free_plan'] == 1) {
                            $stripeCheckout['subscription_data']['trial_period_days'] = 14;
                            $stripeCheckout['subscription_data']['trial_settings'] = ['end_behavior' => ['missing_payment_method' => 'cancel']];
                        }
                        $stripe_session = \Stripe\Checkout\Session::create($stripeCheckout);

                    } catch (\Exception $exception) {
                        error_log($exception->getMessage());
                        payment_fail_save_detail($access_token);
                        payment_error("error", addslashes($exception->getMessage()), $access_token);
                    }
                break;

                case 'recurring':
                    try {
                        $stripe_product = \Stripe\Product::retrieve($package_id);
                    } catch (\Exception $exception) {  }

                    if (!isset($stripe_product)) {
                        try {
                            $stripe_product = \Stripe\Product::create(array(
                                'id' => $package_id,
                                'name' => $title,
                            ));
                        } catch (Exception $exception) {
                            error_log($exception->getMessage());
                            payment_fail_save_detail($access_token);
                            payment_error("error", $exception->getMessage(), $access_token);
                        }
                    }

                    $stripe_plan_id = $package_id . '_' . $plan_interval . '_' . $stripe_formatted_price . '_' . $currency;
                    try {
                        $stripe_plan = \Stripe\Plan::retrieve($stripe_plan_id);
                    } catch (\Exception $exception) {  }

                    if (!isset($stripe_plan)) {
                        try {
                            $stripe_plan = \Stripe\Plan::create([
                                'amount' => $stripe_formatted_price,
                                'interval' => 'day',
                                'interval_count' => $plan_interval == 'MONTHLY' ? 30 : 365,
                                'product' => $stripe_product->id,
                                'currency' => $currency,
                                'id' => $stripe_plan_id,
                            ]);
                        } catch (\Exception $exception) {
                            error_log($exception->getMessage());
                            payment_fail_save_detail($access_token);
                            payment_error("error", $exception->getMessage(), $access_token);
                        }
                    }

                    try {
                        $stripeCheckout = [
                            'mode'          =>  'subscription',
                            'subscription_data' => array(
                                'items' => array(
                                    array('plan' => $stripe_plan->id)
                                ),
                                'metadata' => array(
                                    'user_id' => $user_id,
                                    'package_id' => $package_id,
                                    'payment_frequency' => $plan_interval,
                                    'base_amount' => $base_amount,
                                    'taxes_ids' => $taxes_ids,
                                    'payment_type' => $payment_type
                                ),
                            ),
                            'metadata' => array(
                                'user_id' => $user_id,
                                'package_id' => $package_id,
                                'payment_frequency' => $plan_interval,
                                'base_amount' => $base_amount,
                                'taxes_ids' => $taxes_ids,
                                'payment_type' => $payment_type,
                            ),
                            'customer_email' => $_SESSION['quickad'][$access_token]['email'],
                            'success_url' => $link['PAYMENT'] . "/?access_token=" . $access_token . "&i=stripe&action=stripe_ipn&session_id={CHECKOUT_SESSION_ID}",
                            'cancel_url' => $link['PAYMENT'] . "/?access_token=" . $access_token . "&status=cancel",
                        ];
                        if($_SESSION['quickad'][$access_token]['free_plan'] == 1) {
                            $stripeCheckout['subscription_data']['trial_period_days'] = 14;
                            $stripeCheckout['subscription_data']['trial_settings'] = ['end_behavior' => ['missing_payment_method' => 'cancel']];
                        }
                        $stripe_session = \Stripe\Checkout\Session::create($stripeCheckout);

                    } catch (\Exception $exception) {
                        error_log($exception->getMessage());
                        payment_fail_save_detail($access_token);
                        payment_error("error", $exception->getMessage(), $access_token);
                    }
                break;
            }
            // redirect to stripe
            headerRedirect($stripe_session->url);
            die();
        break;

        case 'stripe_ipn':
            /* Success */
            if ($payment_type == "order") {
                $resto = ORM::for_table($config['db']['pre'] . 'restaurant')->find_one($restaurant_id);
            ?>
                <script>
                    <?php if(!empty($_SESSION['quickad'][$access_token]['whatsapp_url'])){ ?>
                    window.open("<?php echo $_SESSION['quickad'][$access_token]['whatsapp_url'] ?>", "_blank");
                    <?php } ?>
                    location.href = '<?php echo $config['site_url'] . $resto['slug'] . '?return=success' ?>';
                </script>
            <?php
            } else {
                // Retrieve Stripe session data from Stripe based on session_id, getting in url after payment done.
                \Stripe\Stripe::setApiKey($stripe_secret_key);
                \Stripe\Stripe::setApiVersion('2020-08-27');

                $session = \Stripe\Checkout\Session::retrieve($_GET['session_id'], []);
                if(($session->payment_status === 'paid') && ($_SESSION['quickad'][$access_token]['free_plan'] == 1)) {
                    // Retrieve Subscription detail from Stripe
                    $subData = \Stripe\Subscription::retrieve($session->subscription,[]);

                    // Store free trail details in upgrades table.
                    $term = 0;
                    switch ($subData->metadata->payment_frequency) {
                        case 'MONTHLY':
                            $term = 2678400;
                        break;
                        case 'ANNUALLY':
                            $term = 31536000;
                        break;
                        case 'LIFETIME':
                            $term = 3153600000;
                        break;
                    }
                    $upgradesInsert = ORM::for_table($config['db']['pre'] . 'upgrades')->create();
                    $upgradesInsert->sub_id             =   $_SESSION['quickad'][$access_token]['sub_id'];
                    $upgradesInsert->user_id            =   $_SESSION['user']['id'];
                    $upgradesInsert->upgrade_lasttime   =   strtotime(date('Y-m-d H:i:s', strtotime('+14 days')));
                    $upgradesInsert->upgrade_expires    =   (strtotime(date('Y-m-d H:i:s', strtotime('+14 days'))) + $term);
                    $upgradesInsert->pay_mode           =   $_SESSION['quickad'][$access_token]['payment_mode'];
                    $upgradesInsert->unique_id          =   'stripe###'.$session->subscription;
                    $upgradesInsert->stripe_customer_id =   $subData->customer;
                    $upgradesInsert->stripe_subscription_id =   $subData->id;
                    $upgradesInsert->invoice_id         =   $subData->latest_invoice;
                    $upgradesInsert->trial_days         =   14;
                    $upgradesInsert->date_trial_ends    =   date('Y-m-d', $subData->trial_end);
                    $upgradesInsert->status             =   "Active";
                    
                    if($upgradesInsert->save()) {
                        // Update Subscription plan info in user table
                        $userSubscript = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id']);
                        $userSubscript->set('group_id', $_SESSION['quickad'][$access_token]['sub_id']);
                        // $userSubscript->set('plan_type', 'free');
                        $userSubscript->set('plan_type', strtolower($subData->metadata->payment_frequency));
                        $userSubscript->save();
                    }
                }

                message(__('Success'), __('Payment Successful'), $link['TRANSACTION']);
            }
            unset($_SESSION['quickad'][$access_token]);
            exit();
        break;
    }
}

function sendStripePaymentInvoice ($stripSession)
{
    /* Initiate Stripe */
    \Stripe\Stripe::setApiKey($stripe_secret_key);
    \Stripe\Stripe::setApiVersion('2020-08-27');
    if (!$customers) {
        // Create a new Customer
        $customer = \Stripe\Customer::create([
          'email' => $email,
          'description' => 'Customer to invoice',
        ]);
        // Store the Customer ID in your database to use for future purchases
        $CUSTOMERS[] = [
          'stripeId' => $customer->id,
          'email' => $email
        ];
    
        $customerId = $customer->id;
      }
      else {
        // Read the Customer ID from your database
        $customerId = $customers[0]['stripeId'];
      }
    
      // Create an Invoice
      $invoice = \Stripe\Invoice::create([
        'customer' => $customerId,
        'collection_method' => 'send_invoice',
        'days_until_due' => 30,
      ]);
    
      // Create an Invoice Item with the Price, and Customer you want to charge
      $invoiceItem = \Stripe\InvoiceItem::create([
        'customer' => $customerId,
        'price' => $PRICES['basic'],
        'invoice' => $invoice->id
      ]);
    
    
      // Send the Invoice
      $invoice->sendInvoice();
}