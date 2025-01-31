<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GuestCheckoutController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\SellerPackageController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use App\Models\CustomerPackage;
use App\Models\SellerPackage;
use App\Models\User;
use Session;


class StripeController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function pay()
    {
        return view('frontend.payment.stripe');
    }

    public function create_checkout_session(Request $request) {
        $amount = 0;
        //echo '<pre>';print_r($request->session()->has('payment_type'));die;
        if($request->session()->has('payment_type')){
            if($request->session()->get('payment_type') == 'cart_payment'){
                $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
                $amount = round($combined_order->grand_total * 100);
            }
            elseif ($request->session()->get('payment_type') == 'wallet_payment') {
                $amount = round($request->session()->get('payment_data')['amount'] * 100);
            }
            elseif ($request->session()->get('payment_type') == 'customer_package_payment') {
                $customer_package = CustomerPackage::findOrFail(Session::get('payment_data')['customer_package_id']);
                $amount = round($customer_package->amount * 100);
            }
            elseif ($request->session()->get('payment_type') == 'seller_package_payment') {
                $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
                $amount = round($seller_package->amount * 100);
            }
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code,
                        'product_data' => [
                            'name' => "Payment"
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                    ]
                ],
            'mode' => 'payment',
            'success_url' => route('stripe.success'),
            'cancel_url' => route('stripe.cancel'),
        ]);
        //echo "<pre>";print_r($session);die;
        return response()->json(['id' => $session->id, 'status' => 200]);
    }

    public function success() {
        //die('success');
        try{
            
            $payment = ["status" => "Success"];

            $payment_type = Session::get('payment_type');
            //echo '<pre>';print_r(Session::get('user_id'));die;
            //echo '<pre>';print_r(new GuestCheckoutController)->checkout_done(session()->get('combined_order_id'), json_encode($payment));die('success try');
            $user_comb_id = CombinedOrder::where('id',session()->get('combined_order_id'))->first();
            //echo '<pre>';print_r($user_comb_id->user_id);die;
            $is_guest_user = 0;
            $guest_id = '';
            if(!empty($user_comb_id)){
                $guest_id = User::where('id', $user_comb_id->user_id)->first();
                if(!empty($guest_id)){
                   $is_guest_user = $guest_id->is_guest;
                }
            }
            

            if ($payment_type == 'cart_payment') {
            ///echo '<pre>';print_r(session()->get('combined_order_id'));die('success try');
                if($is_guest_user = 1){
                    return (new GuestCheckoutController)->checkout_done(session()->get('combined_order_id'), json_encode($payment));
                }else{
                return (new CheckoutController)->checkout_done(session()->get('combined_order_id'), json_encode($payment));
                }
            }
            if ($payment_type == 'wallet_payment') {
                return (new WalletController)->wallet_payment_done(session()->get('payment_data'), json_encode($payment));
            }
            if ($payment_type == 'customer_package_payment') {
                return (new CustomerPackageController)->purchase_payment_done(session()->get('payment_data'), json_encode($payment));
            }
            if($payment_type == 'seller_package_payment') {
                return (new SellerPackageController)->purchase_payment_done(session()->get('payment_data'), json_encode($payment));
            }
        }
        catch (\Exception $e) {
            flash(translate('Payment failed'))->error();
    	    return redirect()->route('home');
        }
    }

    public function cancel(Request $request){
        
        flash(translate('Payment is cancelled'))->error();
        return redirect()->route('home');
    }
}
