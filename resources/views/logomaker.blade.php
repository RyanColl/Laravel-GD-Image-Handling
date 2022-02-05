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
    <h4 class="jumbotron">Upload and image to see variations of the image returned. Fill out the watermark to see it on the image.</h4>

    <form class='' method="post" action="{{url('create')}}" enctype="multipart/form-data">
        @csrf
        <div class="row w-full">
          <div class="col-md-4"></div>
          <div class="form-group col-md-4 w-full">
          <div class="relative inline-block text-left">
            <div>
              <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500" id="menu-button" aria-expanded="true" aria-haspopup="true">
                Business
                <!-- Heroicon name: solid/chevron-down -->
                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
            </div>

            <!--
              Dropdown menu, show/hide based on menu state.

              Entering: "transition ease-out duration-100"
                From: "transform opacity-0 scale-95"
                To: "transform opacity-100 scale-100"
              Leaving: "transition ease-in duration-75"
                From: "transform opacity-100 scale-100"
                To: "transform opacity-0 scale-95"
            -->
            <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
              <div class="py-1" role="none">
                <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-0">Account settings</a>
                <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-1">Support</a>
                <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-2">License</a>
              </div>
            </div>
          </div>
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