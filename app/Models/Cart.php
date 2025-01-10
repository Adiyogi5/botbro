<?php

namespace App\Models;

use App\Models\GeneralSetting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use HasFactory;

    public function addcart($user, $data)
    {

        $carttotal = Cart::where('product_id', '=', $data['product_id'])
            ->where('customer_id', $user)->count();

        if ($carttotal == 0) {
            $ucart = new Cart;

            if (!empty($user)) {
                $ucart->customer_id = $user;
            }

            $ucart->product_id = $data['product_id'];
            $ucart->quantity = $data['quantity'];

            $ucart->save();
            $cartId = $ucart->id;

        } else {

            Cart::where('product_id', '=', $data['product_id'])
                ->where('customer_id', $user)
                ->update([
                    'quantity' => DB::raw('quantity + ' . $data['quantity']),
                ]);

            $ucart = Cart::where('product_id', '=', $data['product_id'])
                ->where('customer_id', $user)
                ->first();

            $cartId = $ucart->id;
        }

        return $cartId;
    }

    public function countCart($user, $request)
    {
        return $carttotal = Cart::where('customer_id', $user)->count();
    }

    public function countProducts($user, $request)
    {
        $carttotal = Cart::where('customer_id', $user)->get();

        $product_total = 0;
        foreach ($carttotal as $product) {
            $product_total += $product['quantity'];
        }

        return $product_total;
    }

    public function getCartProducts($user_id, $request, $cart_id = null)
    {
        $setting_data = GeneralSetting::all()->toArray();
        $settings = array_combine(array_column($setting_data, 'setting_name'), array_column($setting_data, 'filed_value'));

        # get cart items.
        if (!empty($cart_id)) {
            $carttotal = Cart::where('id', $cart_id)->get();
        } else {
            $carttotal = Cart::where('customer_id', $user_id)->get();
        }

        $product_data = [];
        $total_amount = 0;
        $total_qty = 0;

        foreach ($carttotal as $index => $cart) {

            $query = Product::select('products.*')
                ->where('products.id', '=', $cart['product_id'])
                ->where('products.status', 1)
                ->where('products.deleted_at', null);
            //  ->groupBy('products.id');

            $product_query = $query->get()->toArray();

            foreach ($product_query as $pkey => $product) {
                $price = $product['price'];
                $product_image = $product['image'];

                $product_data[] = array(
                    'cart_id' => $cart['id'],
                    'product_id' => $product['id'],
                    'slug' => $product['slug'],
                    'model' => $product['model'],
                    'name' => $product['name'],
                    'image' => get_image($product_image, 'product_grid', 1),
                    'quantity' => $cart['quantity'],
                    'price' => $price,
                    'referral_price' => $product['referral_price'],
                    'refer_price' => $product['refer_price'],
                    'total' => ($price * $cart['quantity']),
                );

                $total_amount += $price * $cart['quantity'];
                $total_qty += $cart['quantity'];

            }
        }
        return $product_data;
    }

    public function cartTotal($user, $request)
    {

        $CartProducts = $this->getCartProducts($user, $request, null);
        $total = ['subtotal' => 0, 'total' => 0];
        $json = [];

        $subtotal = 0;
        $ftotal = 0;

        foreach ($CartProducts as $finaltotal) {

            # Subtotal
            if (!isset($total['subtotal'])) {
                $subtotal = $finaltotal['total'];
            } else {
                $subtotal += $finaltotal['total'];
            }
            $total['subtotal'] = $subtotal;

            # Final Total
            if (!isset($total['total'])) {
                $ftotal = $finaltotal['total'];
            } else {
                $ftotal += $finaltotal['total'];
            }
            $total['total'] = $ftotal;
        }

        # Collect;
        $subtotal = !empty($total['subtotal']) ? $total['subtotal'] : 0;
        $total = !empty($total['total']) ? $total['total'] : 0;

        $total = $total;

        #format and prepare
        $json['subtotal'] = round($subtotal, 2);
        $json['total'] = round($total, 2);

        return $json;

    }

    public function updateCart($user, $request)
    {
        return $data = Cart::where('id', '=', $request['cart_id'])
            ->where('customer_id', $user)
            ->update([
                'quantity' => $request['quantity'],
            ]);
    }

    public function removeCart($user, $request)
    {
        return $data = Cart::where('id', $request['cart_id'])
            ->where('customer_id', $user)
            ->delete();
    }

    public function getcartDetail($user, $request)
    {
        $json['warning'] = "";
        $json['products'] = [];
        $json['totals'] = [];

        $products = $this->getCartProducts($user, $request);

        if (!empty($products)) {
            foreach ($products as $product) {
                $product_total = 0;
                foreach ($products as $product_2) {
                    if ($product_2['product_id'] == $product['product_id']) {
                        $product_total += $product_2['quantity'];
                    }
                }

                $image = $product['image'];

                $json['products'][] = array(
                    'cart_id' => $product['cart_id'],
                    'image' => $image,
                    'slug' => $product['slug'],
                    'name' => $product['stock'] ? $product['name'] : $product['name'] . ' ',
                    'product_id' => $product['product_id'],
                    'model' => $product['model'],
                    'tax_name' => $product['tax_name'],
                    'tax_value' => $product['tax_value'],
                    'quantity' => $product['quantity'],
                    'pro_qty' => $product['pro_qty'],
                    'price' => round($product['price'], 2),
                    'tax_price' => round($product['tax_price'], 2),
                    'total_tax' => round($product['total_tax'], 2),
                    'total' => round($product['total'], 2),
                );
            }
            $total = [];
            $CartValue = $this->cartTotal($user, $request, null);
            $total = isset($CartValue['totals']) ? $CartValue['totals'] : [];
            $json['total'] = $total;

            return $json;

        } else {
            return $json;
        }
    }

    public function validate_cart($user_id, $request)
    {

        $response = ['status' => 0, 'msg' => 'Please check your information'];

        $products = $this->getCartProducts($user_id, $request, null);

        if (empty($products)) {
            $response['msg'] = "Your cart is empty";
            return $response;
        }

        $CartValue = $this->cartTotal($user_id, $request);

        $cartTotal = ($CartValue['subtotal']);

        $response['status'] = 1;
        $response['msg'] = "";

        return $response;

    }

}
