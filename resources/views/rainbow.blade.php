<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .cent, .xtra {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .img-arc {
            transform: translateY(50px);
        }
        .return-button {
            transform: translate(calc(50% - 25px), -100px)
        }
    </style>
    <title>Document</title>
</head>
<body>
    <div class="container">
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
    <div class='return-button'>
        <a href='/'><button>Return</button></a>
    </div>
    </div>
</body>
</html>