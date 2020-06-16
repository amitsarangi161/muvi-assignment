@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <a href="/home" class="btn btn-primary">Product List</a>
          @if(Session::has('msg'))
           <p class="alert alert-info text-center">{{ Session::get('msg') }}</p>
          @endif
                  @if (sizeof($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        @endif
         <div class="well">
          <form action="/save-product" method="post" enctype="multipart/form-data">
          <table class="table">
           {{csrf_field()}}
          <tr>
            <td><strong>Product Name<span style="color: red"> *</span></strong></td>
            <td>
              <input type="text" name="product_name" class="form-control" autocomplete="off" placeholder="Enter Product Name" required="">
            </td>
          </tr>
           <tr>
            <td><strong>Product Price<span style="color: red"> *</span></strong></td>
            <td>
              <input type="text" name="price" class="form-control" autocomplete="off" placeholder="Enter Product Price" required="">
            </td>
          </tr>
            <tr>
            <td><strong>Product Code<span style="color: red"> *</span></strong></td>
            <td>
              <input type="text" name="product_code" class="form-control" autocomplete="off" placeholder="Enter Product Code" required="">
            </td>
          </tr>
          <tr>
             <td><strong>Product Description<span style="color: red"> *</span></strong></td>
           <td >
             <textarea name="description" placeholder="Enter Product Description" class="form-control" autocomplete="off"></textarea>
          </td>
          </tr>
      <tr>
     <td><strong>Product Image<span style="color: red"> *</span></strong></td>
     <td>
      <input name="image" type="file" onchange="readURL(this);" required="">
            <p class="help-block">Upload .png, .jpg or .jpeg image files only</p>

            <img style="height:70px;width:95px;" alt="noimage" id="imgshow">
     </td>
     
      </tr>

      <tr>
       <td colspan="2"><button type="submit" class="btn btn-success pull-right">SAVE</button></td> 
      </tr>


         </table>
          </form>

          </div>  
        </div>
</div>
</div>
<script type="text/javascript">
     function readURL(input) {
    

       if (input.files && input.files[0]) {
            var reader = new FileReader();
              
            reader.onload = function (e) {
                $('#imgshow')
                    .attr('src', e.target.result)
                    .width(95)
                    .height(70);
          
            };

            reader.readAsDataURL(input.files[0]);

        }
    }
</script>
@endsection
