@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Products
                <a href="/add-product" class="btn btn-primary">Add Product</a>
                </div>
            <form method="get">
                <table class="table">
                    

                    <tr>
                        <td>
                        <input type="text" class="form-control" placeholder="Enter a Keyword" value="{{Request::get('search')}}" name="search">
                        </td>
                        <td>
                            <button type="submit" class="btn btn-warning">Search</button>
                        </td>
                        @if(Request::has('search'))
                         <td><a href="/" class="btn btn-danger">Clear</a></td>
                         @endif

                    </tr>
                    
                    
                </table>
                </form>
                <table class="table table-bordered">
             <thead>
                 <tr>
                  <th>ID</th>
                  <th>PRODUCT NAME</th>
                  <th>PRICE</th>
                  <th>DESCRIPTION</th>
                  <th>Product Code</th>
                  <td>IMAGE</td>
                  <td>CREATED_AT</td>
                  <td>NO OF TRANSACTIONS</td>
                 </tr>
              </thead>
              <tbody>
                @foreach($products as $product)
                 <tr>
                     <td>{{$product->id}}</td>
                     <td onclick="openproductmodals('{{$product->id}}','{{$product->price}}','{{$product->product_name}}','{{$product->description}}','{{$product->product_code}}','{{$product->image}}')" style="cursor: pointer;">{{$product->product_name}}</td>
                     <td>{{$product->price}}</td>
                     <td>{{$product->description}}</td>
                     <td>{{$product->product_code}}</td>
                   <td> 
                    <a href="{{ asset('img/product-image/'.$product->image )}}" target="_blank">
                    <img style="height:70px;width:95px;" alt="{{($product->image!='')?$product->image:'No image'}}" src="{{ asset('img/product-image/'.$product->image )}}"></a>
                  </td>
                  <td>{{$product->created_at}}</td>
                  <td><button class="btn btn-info" type="button" onclick="opentrnmodal('{{$product->id}}')">NO OF TRANSACTIONS</button></td>
                 </tr>
                @endforeach
      
              </tbody>
            </table>

             
              </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Product Details</h4>
        </div>
        <div class="modal-body">
           <form action="/pay-now" method="post">
            {{csrf_field()}}
            <table class="table">
                <input type="hidden" id="pid" name="pid">
                 <tr>
                     <td>Product Name</td>
                     <td><label id="productname"></label></td>
                 </tr> 
                 <tr>
                     <td>Product Code</td>
                     <td><label id="productcode"></label></td>
                 </tr> 
                 <tr>
                     <td>Product Description</td>
                     <td><label id="description"></label></td>
                 </tr> 
                  <tr>
                     <td>IMAGE</td>
                     <td><img style="height:70px;width:95px;" alt="noimage" id="imgshow"></td>
                 </tr>  
                 <tr>
                     <td>Price</td>
                     <td>INR &nbsp;<label id="price"></label></td>
                 </tr>   
                 <tr>
                     <td colspan="2">
                         <button type="submit" onclick="return confirm('Do You want to Proceed?')" class="btn btn-success pull-right">PAY NOW</button>
                     </td>
                 </tr>           
            </table>

               
           </form>
           {{$products->links()}}
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

  <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Product Details</h4>
        </div>
        <div class="modal-body">
           <form action="/pay-now" method="post">
            {{csrf_field()}}
            <table class="table">
                <thead>
                    <tr>
                        <td>ORDER ID</td>
                        <td>PRODUCT ID</td>
                        <td>PRICE</td>
                        <td>PAY PAL TOKEN</td>
                        <td>STATUS</td>
                    </tr>
                </thead>
                <tbody id="transactions">
                    
                </tbody>
                
            </table>

               
           </form>
           
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<script type="text/javascript">
    function openproductmodals(pid,price,pname,description,pcode,pimage)
    {
        $("#pid").val(pid);
        $("#price").text(price);
        $("#productname").text(pname);
        $("#description").text(description);
        $("#productcode").text(pcode);
        $("#imgshow").attr('src','/img/product-image/'+pimage)
                    .width(95)
                    .height(70);

        $("#myModal").modal('show')
    }

function opentrnmodal(pid)
{
      $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf_token"]').attr('content')
            }
        });

           $.ajax({
               type:'POST',
              
               url:'{{url("/fetchtransactions")}}',
              
               data: {
                     "_token": "{{ csrf_token() }}",
                     pid:pid,
                     },

               success:function(data) { 
                      $("#transactions").empty();
                    
                     if(data)
                     {
                         $.each(data,function(key,value){
                            var x='<tr><td>'+value.id+'</td><td>'+value.product_id+'</td><td>'+value.price+'</td><td>'+value.token+'</td><td>'+value.status+'</td></tr>';
                             $("#transactions").append(x);
                         });
                          $("#myModal1").modal('show');
                     }
                     else
                     {
                         alert('No Transaction Found');
                     }
               }
               
             });
     
}
</script>
@endsection
