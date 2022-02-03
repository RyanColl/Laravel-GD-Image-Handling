<html lang="en">
<head>
  <title>Laravel  Image Intervention</title>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <style>
      .cent, .xtra {
          display: flex;
          justify-content: center;
          align-items: center;
      }
      .img-arc {
          transform: translateY(50px);
      }
  </style>
</head>
<body>
  
  <div class="container">
  @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div> 
        @endif
        @if (count($errors) > 0)
      <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              <li>Please use filenames that do not contain weird characters like </li>
          @endforeach
        </ul>
      </div>
      @endif
    <h3 class="jumbotron">Laravel  Image Intervention </h3>
  <form method="post" action="{{url('create')}}" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-md-4"></div>
          <div class="form-group col-md-4">
          <input type="file" name="filename" class="form-control">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4"></div>
          <div class="form-group col-md-4">
          <button type="submit" class="btn btn-success" style="margin-top:10px">Upload Image</button>
          </div>
        </div>
        @if($image)
   	    <div class="row xtra">
         <!-- <div class="col-md-8">
              <strong>Original Image:</strong>
              <br/>
              <img src="/images/{{$image->filename}}" />
        </div> -->
        @if(session('success'))
        <div class="col-md-4">
            <strong>Thumbnail Image:</strong>
            <br/>
            <img src="/thumbnail/{{session('thumbnail')}}"  />
       	 </div> 
        @endif
         <div class="col-md-4 cent">
            
            <br/>
            <img class='img-arc' src="/arcs/{{$image->filename}}"  />
       	 </div>
   		</div>
        @endif       
  </form>
  </div>
</body>
</html>