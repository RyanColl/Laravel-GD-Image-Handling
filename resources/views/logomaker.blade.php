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
          width: 100%;
      }
      .img-arc {
          transform: translateY(50px);
      }
      .control {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
      }
      .container {}
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
    <h3 class="jumbotron">Laravel Creation Station <a href="/"><button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Return to Main Page</button></a></h3>
    <h4 class="jumbotron">Upload and image to see variations of the image returned. Fill out the watermark to see it on the image.</h4>

    <form class='' method="post" action="{{url('create')}}" enctype="multipart/form-data">
        @csrf
        <div class="row w-full">
          <div class="col-md-4"></div>
          <div class="form-group col-md-4 w-full">
            <label for="watermark">Watermark:</label>
            <input type="text" name="watermark" class="form-control">
            <input type="file" name="filename" class="form-control">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4"></div>
          <div class="form-group col-md-4">
          <button type="submit" class="btn btn-success" style="margin-top:10px">Upload Image</button>
          </div>
        </div>
        @if(session('success'))
   	    <div class="row xtra">
         <div class="col-md-4 control">
            <strong>Thumbnail Image:</strong>
            <br/>
            <img src="/thumbnail/{{session('thumbnail')}}"  />
       	 </div> 
          <div class="col-md-4 control">
            <strong>Watermarked Version:</strong>
            <br/>
            <img src="/thumbnail/{{session('thumbnailWatermark')}}"  />
       	 </div> 
        </div>
        @endif       
  </form>
  </div>
</body>
</html>