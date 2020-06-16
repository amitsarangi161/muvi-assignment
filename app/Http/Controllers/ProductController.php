<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Order;
use App\Transaction;
use Session;
use Srmklive\PayPal\Services\ExpressCheckout;
class ProductController extends Controller
{

	public function fetchtransactions(Request $request)
	{
		   $orders=Order::select('orders.*','transactions.token')
		           ->where('product_id',$request->pid)

		           ->leftJoin('transactions','transactions.order_id','=','orders.id')
		           ->where(function($q) {
                      $q->where('status','SUCCESS')
                       ->orWhere('status','FAILED');
                     })
		           ->get();
           return response()->json($orders);
	}

	public function payNow(Request $request)
	{
      $product=Product::find($request->pid);
      $order=new Order();
      $order->product_id=$product->id;
      $order->price=$product->price;
      $order->save();

		$data = [];

        $data['items'] = [

            [

                'name' =>$product->product_name,

                'price' =>$product->price,

                'desc'  =>$product->description,

                'qty' => 1

            ]

        ];

        $data['invoice_id'] = $order->id;
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = route('payment.success');
        $data['cancel_url'] = route('payment.cancel');
        $data['total'] = $product->price;

        //dd($data);
        $provider = new ExpressCheckout;
        $response = $provider->setExpressCheckout($data);
        $response = $provider->setExpressCheckout($data, true);
        
       

        return redirect($response['paypal_link']);
	}

	    public function cancel(Request $request)

    {
    	 $provider = new ExpressCheckout;
        $response = $provider->getExpressCheckoutDetails($request->token);
           $transaction=new Transaction();
            $transaction->order_id=$response['INVNUM'];
            $transaction->token=$response['TOKEN'];
            $transaction->billingagreementacceptedstatus=$response['BILLINGAGREEMENTACCEPTEDSTATUS'];
            $transaction->checkoutstatus=$response['CHECKOUTSTATUS'];
            $transaction->ack=$response['ACK'];
            $transaction->invnum=$response['INVNUM'];

            $transaction->save();
             $order=Order::where('id',$response['INVNUM'])->first();
            $order->status='FAILED';
            $order->save();
            return redirect('/home');
    }

 
    public function success(Request $request)

    {
         $provider = new ExpressCheckout;
        $response = $provider->getExpressCheckoutDetails($request->token);

  

        if (in_array(strtoupper($response['ACK']), ['SUCCESS','SUCCESSWITHWARNING'])) {
            $transaction=new Transaction();
            $transaction->order_id=$response['INVNUM'];
            $transaction->token=$response['TOKEN'];
            $transaction->billingagreementacceptedstatus=$response['BILLINGAGREEMENTACCEPTEDSTATUS'];
            $transaction->checkoutstatus=$response['CHECKOUTSTATUS'];
            $transaction->ack=$response['ACK'];
            $transaction->email=$response['EMAIL'];
            $transaction->payerid=$response['PAYERID'];
            $transaction->payerstatus=$response['PAYERSTATUS'];
            $transaction->firstname=$response['FIRSTNAME'];
            $transaction->lastname=$response['LASTNAME'];
            $transaction->countrycode=$response['COUNTRYCODE'];
            $transaction->amt=$response['AMT'];
            $transaction->desc=$response['DESC'];
            $transaction->invnum=$response['INVNUM'];

            $transaction->save();

            $order=Order::where('id',$response['INVNUM'])->first();
            $order->status='SUCCESS';
            $order->save();


           return redirect('/home');

        }

  

        

    }

	 public function index(Request $request)
	 {
	 	
	 	 $products=Product::where('id','>','0');

	 	 if ($request->has('search') && $request->get('search')!='') {
	 	 	$keyword=$request->get('search');
	 	$products=$products->where(function ($query) use($keyword) {
        $query->where('id', 'like', '%' . $keyword . '%')
           ->orWhere('product_name', 'like', '%' . $keyword . '%')
           ->orWhere('product_code', 'like', '%' . $keyword . '%')
           ->orWhere('description', 'like', '%' . $keyword . '%')
           ->orWhere('price', 'like', '%' . $keyword . '%')
           ->orWhere('created_at', 'like', '%' . $keyword . '%');
      });
	 	}

	 	$products=$products->paginate(10);



	 	 return view('home',compact('products'));
	 }
     public function addProduct()
     {
     	  

     	  return view('add-product',compact('products'));
     }

     public function saveProduct(Request $request)
     {
         $product=new Product();
         $this->validate($request,[
         'product_name'=>'required',
         'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
         'description'=>'required',
         'product_code'=>'required|unique:products',
         'image' => 'mimes:jpeg,jpg,png,gif|required'
         ]);
         $product->product_name=$request->product_name;
         $product->description=$request->description;
         $product->price=$request->price;
         $product->product_code=$request->product_code;
        $rarefile = $request->file('image');    
        if($rarefile!=''){
        $raupload = public_path() .'/img/product-image/';
        $rarefilename=time().'.'.$rarefile->getClientOriginalName();
        $success=$rarefile->move($raupload,$rarefilename);
        $product->image = $rarefilename;
        }
         $product->save();

         Session::flash('msg','Product Saved Suceessfully');

         return back();
     }
}
