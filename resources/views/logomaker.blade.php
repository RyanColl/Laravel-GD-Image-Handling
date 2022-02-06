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
    <h3 class="jumbotron">Laravel Creation Station <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"><a href="/">Return to Main Page</a></button></h3>
    <h4 class="jumbotron">Upload an image to see variations of the image returned. Fill out the watermark to see it on the image.</h4>

    <form class='w-1/2' method="POST" action="{{url('logomaker')}}" enctype="multipart/form-data">
        @csrf
        <div class="row w-full">
          <div class="col-md-4"></div>
            <div class="form-group col-md-4 w-full">
                <label id="businessTypes" for="businessTypes">Select Business Type:</label>
                <input list="businessList" placeholder="Select type" id="businessTypes" name="businessTypes" class="form-control">
                <datalist id="businessList">
                    <option value="Animals">Animals</option>
                    <option value="Education">Education</option>
                    <option value="Finance">Finance</option>
                    
                </datalist>
                <br>
                <label id="businessName" for="businessName" class="form-group col-md-4 w-full">Name of your business: </label>
                <br>
                <input name="businessName" class="form-group col-md-4 w-full" type="text" >

            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4"></div>
          <div class="form-group col-md-4">
          <button type="submit" class="btn btn-success" style="margin-top:10px; margin-left:40px">Create My Logo</button>
          </div>
        </div>
        @if(session('success'))
   	    <div class="row xtra">
         <div class="col-md-4 control">
            <strong>Resulting Images:</strong>
            <br/>
            <img src="/logoMakerImages/results/{{session('returnedImage1png')}}"  />
       	 </div>
          <div class="col-md-4 control">
            <strong>Resulting Images:</strong>
            <br/>
            <img src="/logoMakerImages/results/{{session('returnedImage2png')}}"  />
       	 </div>
          <div class="col-md-4 control">
            <strong>Resulting Images:</strong>
            <br/>
            <img src="/logoMakerImages/results/{{session('returnedImage3png')}}"  />
            <div class="control">
              <a href="/logoMakerImages/results/{{session('returnedImage3png')}}" target="_blank"><button class="btn btn-primary">PNG</button></a>
              <a href="/logoMakerImages/results/{{session('returnedImage3jpg')}}" target="_blank"><button class="btn btn-primary">JPG</button></a>
            </div>
       	 </div>
        </div>
        @endif
  </form>
  </div>
</body>
</html>
