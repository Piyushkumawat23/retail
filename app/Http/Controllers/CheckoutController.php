<?php

namespace App\Http\Controllers;

use App\Utility\PayfastUtility;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Address;
use App\Models\Country;
use App\Models\State;
use App\Models\CombinedOrder;
use App\Models\User;
use App\Utility\PayhereUtility;
use App\Utility\NotificationUtility;
use Session;
use Auth;

class CheckoutController extends Controller
{

    public function __construct()
    {
        //
    }

    //check the selected payment gateway and redirect to that controller accordingly
    public function checkout(Request $request)
    {
       //echo "<pre>";print_r($_POST);die;
        // Minumum order amount check
        if(get_setting('minimum_order_amount_check') == 1){
            $subtotal = 0;
            foreach (Cart::where('user_id', Auth::user()->id)->get() as $key => $cartItem){
                $subtotal += $cartItem['price'] * $cartItem['quantity'];
            }
            if ($subtotal < get_setting('minimum_order_amount')) {
                flash(translate('You order amount is less then the minimum order amount'))->warning();
                return redirect()->route('home');
            }
        }
        // Minumum order amount check end

        // if(get_setting('out_stock_minimum_order')){
            
        // }
        
        if ($request->payment_option != null) {
            (new OrderController)->store($request);

            $request->session()->put('payment_type', 'cart_payment');
            
            $data['combined_order_id'] = $request->session()->get('combined_order_id');
            $request->session()->put('payment_data', $data);

            if ($request->session()->get('combined_order_id') != null) {

                // If block for Online payment, wallet and cash on delivery. Else block for Offline payment
                $decorator = __NAMESPACE__ . '\\Payment\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $request->payment_option))) . "Controller";
                if (class_exists($decorator)) {
                    return (new $decorator)->pay($request);
                }
                else {
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));
                    foreach ($combined_order->orders as $order) {
                        $order->manual_payment = 1;
                        $order->save();
                    }
                    flash(translate('Your order has been placed successfully. Please submit payment information from purchase history'))->success();
                    return redirect()->route('order_confirmed');
                }
            }
        } else {
            flash(translate('Select Payment Option.'))->warning();
            return back();
        }
    }

    //redirects to this method after a successfull checkout
    public function checkout_done($combined_order_id, $payment)
    {
        $combined_order = CombinedOrder::findOrFail($combined_order_id);

        foreach ($combined_order->orders as $key => $order) {
            $order = Order::findOrFail($order->id);
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();

            calculateCommissionAffilationClubPoint($order);
        }
        Session::put('combined_order_id', $combined_order_id);
        return redirect()->route('order_confirmed');
    }

    public function getTaxRate(Request $request)
    {
        $address = Address::where('id', $request->address_id)->first();
        $State = State::where('id', $address['state_id'])->first();
        $country = Country::where('id', $address->country_id)->first();

        $country_code = $country->code;
                Cart::where('user_id', Auth::user()->id)
                ->update(
                        [
                            'address_id' => $request->address_id
                        ]
        );
        //$gettaxrate=TaxRate($request->zip_code);
       
        //echo '<pre>gettaxrate'; print_r($gettaxrate);die;
        //$gettaxrate = '{"Zip":"99559","City":"BETHEL","State":"AK","TaxRate":0.06}';
       // $tax_rate = !empty($gettaxrate) ? json_decode($gettaxrate) : '';
        $tax_rate_value = 0;//isset($tax_rate->TaxRate) ? $tax_rate->TaxRate : 0;

        $shipping_country = array();
        $shipping_country['address_id'] = $request->address_id;
        $shipping_country['state_id'] = $address['state_id'];
        $shipping_country['normal_shipping_cost'] = $State['normal_shipping_cost'];
        $shipping_country['shipping_cost'] = $State['shipping_cost'];
        $shipping_country['country_code'] = $country_code;
        $shipping_country['taxrate'] = $tax_rate_value;
        //echo '<pre>';print_r($shipping_country);die;
        session_start();
        $_SESSION['session_address_data'] = $shipping_country;
       ///echo $tax_rate_value; die;
        $result = array('res'=>1);
        echo json_encode($result); die;
        
    }
    

    public function get_shipping_info(Request $request)
    { 
        //
         session_start();
        $shipping_country = isset($_SESSION['session_address_data']) ? $_SESSION['session_address_data'] : '';
       //dd($_SESSION);
        //echo'<pre>';print_r($shipping_country);die;
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        //if (Session::has('cart') && count(Session::get('cart')) > 0) {
        if ($carts && count($carts) > 0) {
            $categories = Category::all();
            $customerlist = User::select('name', 'id', 'email')->where('user_type', 'customer')->where('salesperson_id', Auth::user()->id)->where('banned', 0)->get();
            //echo "<pre>";print_r($customerlist);die;
            //return view('frontend.shipping_info', compact('categories', 'carts', 'customerlist'));
        }

        /*Delivery Info*/

        $carts_del_info = Cart::where('user_id', Auth::user()->id)
                ->get();

        if($carts_del_info->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        $shipping_info = Address::where('id', $carts_del_info[0]['address_id'])->first();
        $total = 0;
        $tax = 0;
        $shipping = 0;
        $subtotal = 0;
        $subtotal = 0;
        $wholesaleCommissionAmount = 0;

        if ($carts_del_info && count($carts_del_info) > 0) {
        
            $wholesaleCommissionAmount=wholesaleCommissionAmount($subtotal);
            $total = ($subtotal + $tax + $shipping) - $wholesaleCommissionAmount;
            //echo '<pre>';print_r($total);die;
            return view('frontend.shipping_info', compact('categories','customerlist','carts','carts_del_info','shipping_info', 'total','shipping_country'));

            } else {
            flash(translate('Your Cart was empty'))->warning();
            return redirect()->route('home');
        }

        /*flash(translate('Your cart is empty'))->success();
        return back();*/
    }


   public function store_shipping_info(Request $request)
    {   
        if ($request->address_id == null) {
            flash(translate("Please add shipping address"))->warning();
            return back();
        }
        $total = 0;
        $tax = 0;
        $shipping = 0;
        $subtotal = 0;
        $subtotal = 0;
        $wholesaleCommissionAmount = 0;

        $carts = Cart::where('user_id', Auth::user()->id)->get();

        foreach ($carts as $key => $cartItem) {
            if (isset($request->customer_id) && !empty($request->customer_id)) {
                
                $cartItem->salesperson_id = Auth::user()->id;
                $cartItem->for_customer_id = $request->customer_id;
            }
            $cartItem->address_id = $request->address_id;
            $cartItem->shipping_type_payment = $request->shipping_type_payment;

               $product = \App\Models\Product::find($cartItem['product_id']);
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                $subtotal += $cartItem['price'] * $cartItem['quantity'];

                if ($request['shipping_type_' . $product->user_id] == 'pickup_point') {
                    $cartItem['shipping_type'] = 'pickup_point';
                    $cartItem['pickup_point'] = $request['pickup_point_id_' . $product->user_id];
                } else {
                    $cartItem['shipping_type'] = 'home_delivery';
                }
                $cartItem['shipping_cost'] = 0;
                if ($cartItem['shipping_type'] == 'home_delivery') {
                    if ($cartItem['shipping_type_payment'] == 'priority') {
                        $cartItem['shipping_cost'] = getShippingCost($carts, $key);
                    }                    
                }

                if(isset($cartItem['shipping_cost']) && is_array(json_decode($cartItem['shipping_cost'], true))) {

                    foreach(json_decode($cartItem['shipping_cost'], true) as $shipping_region => $val) {
                        if($shipping_info['city'] == $shipping_region) {
                            $cartItem['shipping_cost'] = (double)($val);
                            break;
                        } else {
                            $cartItem['shipping_cost'] = 0;
                        }
                    }
                } else {
                    if (!$cartItem['shipping_cost'] ||
                            $cartItem['shipping_cost'] == null ||
                            $cartItem['shipping_cost'] == 'null') {

                        $cartItem['shipping_cost'] = 0;
                    }
                }

                $shipping += $cartItem['shipping_cost'];
                $cartItem->save();

        }

        if(get_setting('minimum_order_amount_check') == 1){
            $subtotal = 0;
            foreach (Cart::where('user_id', Auth::user()->id)->get() as $key => $cartItem){
                $subtotal += $cartItem['price'] * $cartItem['quantity'];
            }
            if ($subtotal < get_setting('minimum_order_amount')) {
                flash(translate('You order amount is less then the minimum order amount'))->warning();
                return redirect()->route('home');
            }
        }
        // Minumum order amount check end

        // if(get_setting('out_stock_minimum_order')){
            
        // }
        $request->payment_option = 'stripe';
        if ($request->payment_option != null) {
            (new OrderController)->store($request);
            
            $request->session()->put('payment_type', 'cart_payment');
            
            $data['combined_order_id'] = $request->session()->get('combined_order_id');
            $request->session()->put('payment_data', $data);

            if ($request->session()->get('combined_order_id') != null) {

                // If block for Online payment, wallet and cash on delivery. Else block for Offline payment
                $decorator = __NAMESPACE__ . '\\Payment\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $request->payment_option))) . "Controller";
                if (class_exists($decorator)) {
                    return (new $decorator)->pay($request);
                }
                else {
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));
                    foreach ($combined_order->orders as $order) {
                        $order->manual_payment = 1;
                        $order->save();
                    }
                    flash(translate('Your order has been placed successfully. Please submit payment information from purchase history'))->success();
                    return redirect()->route('order_confirmed');
                }
            }
        } else {
            flash(translate('Select Payment Option.'))->warning();
            return back();
        }
       // return redirect()->route('checkout.shipping_info');
        //return view('frontend.shipping_info', compact('carts'));
        // return view('frontend.payment_select', compact('total'));
    }

    public function shipping_charges()
    {
        //die('hello');
        if ($request->address_id == null) {
            flash(translate("Please add shipping address"))->warning();
            return back();
        }
        $total = 0;
        $tax = 0;
        $shipping = 0;
        $subtotal = 0;
        $subtotal = 0;
        $wholesaleCommissionAmount = 0;

        $carts = Cart::where('user_id', Auth::user()->id)->get();

        foreach ($carts as $key => $cartItem) {
            if (isset($request->customer_id) && !empty($request->customer_id)) {
                
                $cartItem->salesperson_id = Auth::user()->id;
                $cartItem->for_customer_id = $request->customer_id;
            }
            $cartItem->address_id = $request->address_id;
            $cartItem->shipping_type_payment = $request->shipping_type_payment;

               $product = \App\Models\Product::find($cartItem['product_id']);
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                $subtotal += $cartItem['price'] * $cartItem['quantity'];

                if ($request['shipping_type_' . $product->user_id] == 'pickup_point') {
                    $cartItem['shipping_type'] = 'pickup_point';
                    $cartItem['pickup_point'] = $request['pickup_point_id_' . $product->user_id];
                } else {
                    $cartItem['shipping_type'] = 'home_delivery';
                }
                $cartItem['shipping_cost'] = 0;
                if ($cartItem['shipping_type'] == 'home_delivery') {
                    if ($cartItem['shipping_type_payment'] == 'priority') {
                        $cartItem['shipping_cost'] = getShippingCost($carts, $key);
                    }                    
                }

                if(isset($cartItem['shipping_cost']) && is_array(json_decode($cartItem['shipping_cost'], true))) {

                    foreach(json_decode($cartItem['shipping_cost'], true) as $shipping_region => $val) {
                        if($shipping_info['city'] == $shipping_region) {
                            $cartItem['shipping_cost'] = (double)($val);
                            break;
                        } else {
                            $cartItem['shipping_cost'] = 0;
                        }
                    }
                } else {
                    if (!$cartItem['shipping_cost'] ||
                            $cartItem['shipping_cost'] == null ||
                            $cartItem['shipping_cost'] == 'null') {

                        $cartItem['shipping_cost'] = 0;
                    }
                }

                $shipping += $cartItem['shipping_cost'];
                

        }

        if(get_setting('minimum_order_amount_check') == 1){
            $subtotal = 0;
            foreach (Cart::where('user_id', Auth::user()->id)->get() as $key => $cartItem){
                $subtotal += $cartItem['price'] * $cartItem['quantity'];
            }

                return $shipping;
        }

    }

    public function store_delivery_info(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)
                ->get();

        if($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        $total = 0;
        $tax = 0;
        $shipping = 0;
        $subtotal = 0;
        $subtotal = 0;
        $wholesaleCommissionAmount = 0;

        if ($carts && count($carts) > 0) {
            foreach ($carts as $key => $cartItem) {
                $product = \App\Models\Product::find($cartItem['product_id']);
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                $subtotal += $cartItem['price'] * $cartItem['quantity'];

                if ($request['shipping_type_' . $product->user_id] == 'pickup_point') {
                    $cartItem['shipping_type'] = 'pickup_point';
                    $cartItem['pickup_point'] = $request['pickup_point_id_' . $product->user_id];
                } else {
                    $cartItem['shipping_type'] = 'home_delivery';
                }
                $cartItem['shipping_cost'] = 0;
                if ($cartItem['shipping_type'] == 'home_delivery') {
                    if ($cartItem['shipping_type_payment'] == 'priority') {
                        $cartItem['shipping_cost'] = getShippingCost($carts, $key);
                    }                    
                }

                if(isset($cartItem['shipping_cost']) && is_array(json_decode($cartItem['shipping_cost'], true))) {

                    foreach(json_decode($cartItem['shipping_cost'], true) as $shipping_region => $val) {
                        if($shipping_info['city'] == $shipping_region) {
                            $cartItem['shipping_cost'] = (double)($val);
                            break;
                        } else {
                            $cartItem['shipping_cost'] = 0;
                        }
                    }
                } else {
                    if (!$cartItem['shipping_cost'] ||
                            $cartItem['shipping_cost'] == null ||
                            $cartItem['shipping_cost'] == 'null') {

                        $cartItem['shipping_cost'] = 0;
                    }
                }

                $shipping += $cartItem['shipping_cost'];
                $cartItem->save();

            }
            $wholesaleCommissionAmount=wholesaleCommissionAmount($subtotal);
            $total = ($subtotal + $tax + $shipping) - $wholesaleCommissionAmount;
            return view('frontend.payment_select', compact('carts', 'shipping_info', 'total'));

        } else {
            flash(translate('Your Cart was empty'))->warning();
            return redirect()->route('home');
        }
    }

    public function apply_coupon_code(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        $response_message = array();

        if ($coupon != null) {
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                    $coupon_details = json_decode($coupon->details);

                    $carts = Cart::where('user_id', Auth::user()->id)
                                    ->where('owner_id', $coupon->user_id)
                                    ->get();

                    if ($coupon->type == 'cart_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach ($carts as $key => $cartItem) {
                            $subtotal += $cartItem['price'] * $cartItem['quantity'];
                            $tax += $cartItem['tax'] * $cartItem['quantity'];
                            $shipping += $cartItem['shipping_cost'];
                        }
                        $sum = $subtotal + $tax + $shipping;

                        if ($sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }

                        }
                    } elseif ($coupon->type == 'product_base') {
                        $coupon_discount = 0;
                        foreach ($carts as $key => $cartItem) {
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['product_id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += ($cartItem['price'] * $coupon->discount / 100) * $cartItem['quantity'];
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                    }
                                }
                            }
                        }
                    }

                    if($coupon_discount > 0){
                        Cart::where('user_id', Auth::user()->id)
                            ->where('owner_id', $coupon->user_id)
                            ->update(
                                [
                                    'discount' => $coupon_discount / count($carts),
                                    'coupon_code' => $request->code,
                                    'coupon_applied' => 1
                                ]
                            );
                        $response_message['response'] = 'success';
                        $response_message['message'] = translate('Coupon has been applied');
                    }
                    else{
                        $response_message['response'] = 'warning';
                        $response_message['message'] = translate('This coupon is not applicable to your cart products!');
                    }
                    
                } else {
                    $response_message['response'] = 'warning';
                    $response_message['message'] = translate('You already used this coupon!');
                }
            } else {
                $response_message['response'] = 'warning';
                $response_message['message'] = translate('Coupon expired!');
            }
        } else {
            $response_message['response'] = 'danger';
            $response_message['message'] = translate('Invalid coupon!');
        }

        $carts = Cart::where('user_id', Auth::user()->id)
                ->get();
        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        $returnHTML = view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'))->render();
        return response()->json(array('response_message' => $response_message, 'html'=>$returnHTML));
    }

    public function remove_coupon_code(Request $request)
    {
        Cart::where('user_id', Auth::user()->id)
                ->update(
                        [
                            'discount' => 0.00,
                            'coupon_code' => '',
                            'coupon_applied' => 0
                        ]
        );

        $coupon = Coupon::where('code', $request->code)->first();
        $carts = Cart::where('user_id', Auth::user()->id)
                ->get();

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        return view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'));
    }

    public function apply_club_point(Request $request) {
        if (addon_is_activated('club_point')){

            $point = $request->point;

            if(Auth::user()->point_balance >= $point) {
                $request->session()->put('club_point', $point);
                flash(translate('Point has been redeemed'))->success();
            }
            else {
                flash(translate('Invalid point!'))->warning();
            }
        }
        return back();
    }

    public function remove_club_point(Request $request)
    {
        $request->session()->forget('club_point');
        return back();
    }

    public function order_confirmed()
    {

        $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
        Cart::where('user_id', $combined_order->user_id)
                ->delete();

        //Session::forget('club_point');
        //Session::forget('combined_order_id');
        
        foreach($combined_order->orders as $order){
            NotificationUtility::sendOrderPlacedNotification($order);
        }
        return view('frontend.order_confirmed', compact('combined_order'));
    }


}
