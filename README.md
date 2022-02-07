# Ryan Collicutt and Alexander Webster Present...

An interactive image app made on laravel! Using Laravel's routing, we host 3 seperate pages, each with unique features! We use [Intervention Image](https://appdividend.com/2018/04/13/laravel-image-intervention-tutorial-with-example/) to handle the backend image processing. Intervention utilizes the GD library in simple and easy to use commands to manipulate and save images.

1. Our first page is a png image of a rainbow. Nothing super special, but you will be happy to hear it was made using php's GD library!
2. Our second page is a logo maker! Using Laravel's backend controllers, a user can select a business type and write a slogan. The information is sent to an image controller to be rendered to the page accordingly.
3. Finally, we have a watermarker. The user can upload an image and it will be returned to them in multiple different variations: Thumbnail, Watermarked, Small, Medium, Large, and Extra Large.

### Killing Un-needed Files 

When we started doing large amounts of photo manipulation, we found ourselves sending many requests and a lot of form data to laravel. As a result, our folders became filled with copies of images we already had. We derived this function to kill all of the files in these folders.
```php
function killFilesInPublicPath($path) {
    $files = glob(public_path().$path); // get all file names
    foreach($files as $file) { // iterate files
        if(is_file($file)) {
            unlink($file); // delete file
        }
    }
}
//clear out folders folders
killFilesInPublicPath('/thumbnail/*');
killFilesInPublicPath('/images/*');
killFilesInPublicPath('/arcs/*');
killFilesInPublicPath('/watermarked/*');
```

## Part 1 Highlights: Rainbow


We use the GD library to create a rainbow. We create a white background, and create 7 seperate arcs at different sizes and colours, pasting our largest ones to the image first. After creating the image using gd, we use [Intervention Image](https://appdividend.com/2018/04/13/laravel-image-intervention-tutorial-with-example/) to further handle our image and save it to the database. We used migrations earlier in the app to create an image model for the database.
```php
$img = imagecreatetruecolor($image_width,$image_height);
$color = imagecolorallocate($img,255,255,255);
imagefilledrectangle($img, 0, 0, $image_width, $image_height, $color);
function createArc($img, $d, $red, $green, $blue, $image_width, $image_height) {
    $r=$image_width/2 - $image_width/32 ; // make circle radius
    $cx=$image_width/$d/2; $cy=$image_height/$d/2; // use d to center the image based on its size
    $color = imagecolorallocate($img, $red, $green, $blue);  // allocate colour to arc
    imagefilledarc($img, $cx, $cy, $r*1, $r*1,  180, 0, $color, IMG_ARC_PIE);  // create image filled arc
}
// create 7 arcs with unique red, green, blue, and size values
createArc($img, 2.1, 237, 28, 36, $image_width*2.1, $image_height*2.1);
createArc($img, 1.8, 255, 127, 39, $image_width*1.8, $image_height*1.8);
createArc($img, 1.5, 255, 252, 1, $image_width*1.5, $image_height*1.5);
createArc($img, 1.2, 32, 177, 75, $image_width*1.2, $image_height*1.2);
createArc($img, 0.9, 63, 72, 204, $image_width*0.9, $image_height*0.9);
createArc($img, 0.6, 163, 73, 164, $image_width*0.6, $image_height*0.6);
```

After creating the image using GD, we let Intervention take over.
```php
$newImg = Image::make($img); //Intervention image creating image model based on GD image.
$path = public_path().'/arcs/'; // path to arcs in public
$newImg->save($path.time().'arc.png'); // saving the image
$imagemodel= new ImageModel(); // new image model for the database
$imagemodel->filename=time().'arc.png'; // saving the filename for new image model
$imagemodel->save(); // saving into the database
$image = ImageModel::latest()->first(); // pulling the image from the database
return view('rainbow', compact('image')); // returning the image to the view 'rainbow'
```

## Part 2 Highlights: Logo Maker

Using Intervention image, we created a complete application that takes users inputs of business type and business name, and prints them onto 3 different photos, all unique based on the business type. We use the following to grab the user inputs.
```php
$businessType = $request->input('businessTypes'); // grabs the business type
$businessName = $request->input('businessName'); // grabs the business name
```

We use Intervention Image to add text to the image model. This function takes in the model, the text, and how far from the top and left of the image the text should be printed. There is also a callback function with the following: a path to a specific font, a specified size, a specific colour, and angle of 0, and a text placement of center. ```$font->align('center');``` Is very important, it starts writing the text in the middle of the top and left coordinates, not from the left of the text. This means the text always ends up perfectly in the middle of the photo.
```php
function applyTextToModel($model, $text, $top, $left) {
    $model->text($text, $top, $left, function($font) {
        $font->file(app_path().'/Fonts/NunitoSans-Regular.ttf');
        $font->size(28);
        $font->color([203, 105, 101, 1]);
        $font->align('center');
        $font->valign('top');
        $font->angle(0);
    });
}
```

This function takes in a specific image path, a number, a type, and a specific text. An image model is made from the path of the image provided, and the function written above is called. We use ```$img->height()*0.6``` to grab the height of the image created, and place it 60% down the image, just below the built in logo.
We then save two variations, a jpg and a png, to a folder comprised of a few variables. Given that a number of 2 was given, and a type of finance, the file would be saved into ```/logoMakerImages/results/finance2.png```. The model is then destroyed, as resources are valuable.
```php
function createLogoOnImagePath($imagePath, $number, $type, $text) { 
    $resultsFolder = public_path().'/logoMakerImages/results/';
    $img = Image::make($imagePath);
    applyTextToModel($img, $text, $img->width()*0.5, $img->height()*0.6);
    $img->save("$resultsFolder"."$type".$number.".png");
    $img->save("$resultsFolder"."$type".$number.".jpg");
    $img->destroy();
}
```

If the user types in Animal for the type and Stampeed for the name, the following code will be run. It has the fixed location of our ready-to-use images. It runs the two functions above, saving the images as ```/logoMakerImages/results/animal1.png, /logoMakerImages/results/animal2.png, and /logoMakerImages/results/animal3.png```, along with their jpg variants. The variants are then returned to the front end using ```return back()->with()```. Inside fo the with() we send text for the blade file to receive.
```php
$ogAnimalPath1 = $logoMakerImagesPath.'/animal/animal1.png';
$ogAnimalPath2 = $logoMakerImagesPath.'/animal/animal2.png';
$ogAnimalPath3 = $logoMakerImagesPath.'/animal/animal3.png';
createLogoOnImagePath($ogAnimalPath1, 1, 'animal', $businessName);
createLogoOnImagePath($ogAnimalPath2, 2, 'animal', $businessName);
createLogoOnImagePath($ogAnimalPath3, 3, 'animal', $businessName);
return back()
->with('success', 'Logo Making Complete')
->with('returnedImage1png', "animal1.png")
->with('returnedImage1jpg', 'animal1.jpg')
->with('returnedImage2png', "animal2.png")
->with('returnedImage2jpg', 'animal2.jpg')
->with('returnedImage3png', "animal3.png")
->with('returnedImage3jpg', 'animal3.jpg');
```

The following code captures the images on the front end when ```return back()->with()``` is run. This is from ```logomaker.blade.php```. This code doesn't care what business type was selected, but is given the image urls from ```return back()->with()```. Two buttons are created at the bottom of each image, filled accordingly with the correct iamge url returned from the Image Controller.
```php
@if(session('success'))
<div class="row xtra">
    <div class="col-md-4 control">
    <strong>Option 1:</strong>
    <br/>
    <img src="/logoMakerImages/results/{{session('returnedImage1png')}}" />
    <div class="control flexy-row">
        <button class="btn btn-primary"><a download href="/logoMakerImages/results/{{session('returnedImage1png')}}" target="_blank">PNG</a></button>
        <button class="btn btn-primary"><a download href="/logoMakerImages/results/{{session('returnedImage1jpg')}}" target="_blank">JPG</a></button>
    </div>
    </div>
    <div class="col-md-4 control">
    <strong>Option: 2</strong>
    <br/>
    <img src="/logoMakerImages/results/{{session('returnedImage2png')}}"  />
    <div class="control flexy-row">
        <button class="btn btn-primary"><a download href="/logoMakerImages/results/{{session('returnedImage2png')}}" target="_blank">PNG</a></button>
        <button class="btn btn-primary"><a download href="/logoMakerImages/results/{{session('returnedImage2jpg')}}" target="_blank">JPG</a></button>
    </div>
    </div>
    <div class="col-md-4 control">
    <strong>Option: 3</strong>
    <br/>
    <img src="/logoMakerImages/results/{{session('returnedImage3png')}}"  />
    <div class="control flexy-row">
        <button class="btn btn-primary"><a download href="/logoMakerImages/results/{{session('returnedImage3png')}}" target="_blank">PNG</a></button>
        <button class="btn btn-primary"><a download href="/logoMakerImages/results/{{session('returnedImage3jpg')}}" target="_blank">JPG</a></button>
    </div>
    </div>
</div>
@endif
```

## Part 3 Higlights: Watermarker

In part 3 we use Intervention Image to create watermarked variations of a users image. We start by taking the users image as an upload to the backend. We then create a thumbnail, small, medium, large, and extra large variation, alongside a watermarked version. 

We start by grabbing the file extensions and setting the current time.
```php
$time = date('Mhis', time()); // get time
$ext = $request->file('filename')->extension(); // get extension
```

Then we grab the image from the file uploaded and make a model of it.
```php
$ogFileName = $request->file('filename'); // get file
$thumbnailImage = Image::make($ogFileName); // make an image model from the uploaded file
```

We also set width and height variables.
```php
$width = $thumbnailImage->width();
$height = $thumbnailImage->height();
```

Creating a function called changeSize, it takes in the image model whose size to change, and the multiplyer. 
If the image is too small, it will be set at 200x150 or 150x200, depending on if its landscape or portrait orientation. In this case, we are changing our thumbnail size to the base amount, with a multiplyer of 1. The name of the file is based on the time and its original name, and after modification the model is saved.
```php
function changeSize($model, $multiplyer) {
    if($model->width() > 1500 || $model->height() > 1500) {
        $model->resize($model->width()/10*$multiplyer,$model->height()/10*$multiplyer);
    } else {
        if($model->width() > $model->height()) {
            $model->resize(200*$multiplyer, 150*$multiplyer);
        } else {
            $model->resize(150*$multiplyer, 200*$multiplyer);
        }
    }
}
changeSize($thumbnailImage, 1);
$thumbnailImage->save($thumbNailFullPath);
```

Using a similar function as before, we apply text to the image using a custom built function applyTextToModel, which takes in the model, the text to be applied, and a custom top and left positions. It would then apply a text of NunitoSans-Regular font at an angle of 45 degrees at 65% opacity.
```php
function applyTextToModel($model, $text, $left, $top) {
    $model->text($text, $left, $top, function($font) {
        $font->file(app_path().'/Fonts/NunitoSans-Regular.ttf');
        $font->size(48);
        $font->color([255, 255, 255, 0.65]);
        $font->align('center');
        $font->valign('top');
        $font->angle(45);
    });
}
```

The entire code that creates an image model, changes its size, watermarks it, and then saves the new variation can be summed into the following, which can be used for any of the variant sizes, with multipylers of 1-5 accordingly:
```php
$xtraLargeVariant = Image::make($ogFullPath); // create model from image path
changeSize($xtraLargeVariant, 5); // change size, multiplyer is 5 because this on is extra large
$xtraLargeVariant->save(public_path()."/images/xtralarge.$ext"); // save the extra large version
$xtraLargeVariant->text($watermark, $width/5.5, $height/5.5, function($font) { // apply text to image
    $font->file(app_path().'/Fonts/NunitoSans-Regular.ttf');
    $font->size(80);
    $font->color([255, 255, 255, 0.65]);
    $font->align('center');
    $font->valign('top');
    $font->angle(45);
});
$xtraLargeVariant->save(public_path()."/watermarked/xtralarge.$ext"); // save the watermarked version
$xtraLargeVariant->destroy(); // destroy for resource purposes.
```

This sums up our app!

We hope you enjoyed the readme.

Check out our portfolios => 

[Alexander Webster](www.google.ca)

[Ryan Collicutt](www.rcoll-dev.com)

## Old Laravel Readme

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[CMS Max](https://www.cmsmax.com/)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**
- **[Romega Software](https://romegasoftware.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
