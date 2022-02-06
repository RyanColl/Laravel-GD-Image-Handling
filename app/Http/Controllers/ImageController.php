<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageModel;
use Image;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rainbow()
    {
        function createRainbow($base_width, $base_height) {
            //create the image object based on input width and height
            $image_width=$base_width;$image_height=$base_height;
            //make image object
            $img = imagecreatetruecolor($image_width,$image_height);

            // create a white background to host the image, could probably make it transparent somehow
            $color = imagecolorallocate($img,255,255,255);
            imagefilledrectangle($img, 0, 0, $image_width, $image_height, $color);

            function createArc($img, $d, $red, $green, $blue, $image_width, $image_height)
            {
                // make circle radius
                $r=$image_width/2 - $image_width/32 ; //radius

                // use d to center the image based on its size
                $cx=$image_width/$d/2;
                $cy=$image_height/$d/2;

                // allocate colour to arc
                $color = imagecolorallocate($img, $red, $green, $blue);
                // create image filled arc
                imagefilledarc($img, $cx, $cy, $r*1, $r*1,  180, 0, $color, IMG_ARC_PIE);
            }
            // create 7 arcs with unique red, green, blue, and size values
            createArc($img, 2.1, 237, 28, 36, $image_width*2.1, $image_height*2.1);
            createArc($img, 1.8, 255, 127, 39, $image_width*1.8, $image_height*1.8);
            createArc($img, 1.5, 255, 252, 1, $image_width*1.5, $image_height*1.5);
            createArc($img, 1.2, 32, 177, 75, $image_width*1.2, $image_height*1.2);
            createArc($img, 0.9, 63, 72, 204, $image_width*0.9, $image_height*0.9);
            createArc($img, 0.6, 163, 73, 164, $image_width*0.6, $image_height*0.6);
            // return the image object
            return $img;
        }
        $img = createRainbow(500, 500);
        $newImg = Image::make($img);
        $path = public_path().'/arcs/';
        $newImg->save($path.time().'arc.png');
        $imagemodel= new ImageModel();
        $imagemodel->filename=time().'arc.png';
        $imagemodel->save();

        $image = ImageModel::latest()->first();
        return view('rainbow', compact('image'));
        // return view('createimage');
    }

    public function createLogo(Request $request) {

        // clearing results folder pre-emptively
        function killFilesInResultsFolder() {
            $files = glob(public_path().'/logoMakerImages/results/*'); // get all file names
            foreach($files as $file) { // iterate files
                if(is_file($file)) {
                    unlink($file); // delete file
                }
            }
        }
        killFilesInResultsFolder();

        // grab input of selected business from user
        // grab input of text from user
        // combine image with text
        // convert and write as both png and jpg
        // delete old images
        // provide user option to download as jpg or png
        $businessType = $request->input('businessTypes'); // works
        $businessName = $request->input('businessName'); // works

        $logoMakerImagesPath = public_path().'/logoMakerImages';
        $ogAnimalPath = $logoMakerImagesPath.'/animals/animal.png';
        $ogEducationPath = $logoMakerImagesPath.'/education/education.png';
        



        // make an image model based off of the original file

        
        

        function applyTextToModel($model, $text, $top, $left) {
            $model->text($text, $top, $left, function($font) {
                $font->file(app_path().'/Fonts/NunitoSans-Regular.ttf');
                $font->size(24);
                $font->color([45, 51, 59, 1]);
                $font->align('center');
                $font->valign('top');
                $font->angle(0);
            });
        }
        if($businessType === 'Animals') {
            $animalLogoImage = Image::make($ogAnimalPath);
        
            applyTextToModel($animalLogoImage, $businessName, $animalLogoImage->height()/2, $animalLogoImage->width()*0.4);
            $resultsFolder = $logoMakerImagesPath.'/results/';
            $animalLogoImage->save($resultsFolder.'/createdAnimalLogo.png');
            $animalLogoImage->save($resultsFolder.'/createdAnimalLogo.jpg');
            $animalLogoImage->destroy();
            return back()
            ->with('success', 'Logo Making Complete')
            ->with('returnedImage1', "/createdAnimalLogo.png")
            ->with('returnedImage2', '/createdAnimalLogo.jpg');
        } else if ($businessType === 'Education') {
            $educationLogoImage = Image::make($ogEducationPath);
        
            applyTextToModel($educationLogoImage, $businessName, $educationLogoImage->height()/2, $educationLogoImage->width()*0.4);
            $resultsFolder = $logoMakerImagesPath.'/results/';
            $educationLogoImage->save($resultsFolder.'/createdEducationLogo.png');
            $educationLogoImage->save($resultsFolder.'/createdEducationLogo.jpg');
            $educationLogoImage->destroy();
            return back()
            ->with('success', 'Logo Making Complete')
            ->with('returnedImage1', "/createdEducationLogo.png")
            ->with('returnedImage2', '/createdEducationLogo.jpg');
        } else if ($businessType === 'Finance') {
            $ogFinancePath1 = $logoMakerImagesPath.'/finance/finance1.png';
            $ogFinancePath2 = $logoMakerImagesPath.'/finance/finance2.png';
            $ogFinancePath3 = $logoMakerImagesPath.'/finance/finance3.png';
            $financeLogoImage1 = Image::make($ogFinancePath1);
            $financeLogoImage2 = Image::make($ogFinancePath2);
            $financeLogoImage3 = Image::make($ogFinancePath3);
            applyTextToModel($financeLogoImage1, $businessName, $financeLogoImage1->height()*0.65, $financeLogoImage1->width()*0.4);
            applyTextToModel($financeLogoImage2, $businessName, $financeLogoImage2->height()*0.65, $financeLogoImage2->width()*0.4);
            applyTextToModel($financeLogoImage3, $businessName, $financeLogoImage3->height()*0.65, $financeLogoImage3->width()*0.4);
            $resultsFolder = $logoMakerImagesPath.'/results/';
            $financeLogoImage1->save($resultsFolder.'/createdFinanceLogo1.png');
            $financeLogoImage1->save($resultsFolder.'/createdFinanceLogo1.jpg');
            $financeLogoImage2->save($resultsFolder.'/createdFinanceLogo2.png');
            $financeLogoImage2->save($resultsFolder.'/createdFinanceLogo2.jpg');
            $financeLogoImage3->save($resultsFolder.'/createdFinanceLogo3.png');
            $financeLogoImage3->save($resultsFolder.'/createdFinanceLogo3.jpg');
            $financeLogoImage1->destroy();
            $financeLogoImage2->destroy();
            $financeLogoImage3->destroy();
            return back()
            ->with('success', 'Logo Making Complete')
            ->with('returnedImage1png', "createdFinanceLogo1.png")
            ->with('returnedImage1jpg', 'createdFinanceLogo1.jpg')
            ->with('returnedImage2png', "createdFinanceLogo2.png")
            ->with('returnedImage2jpg', 'createdFinanceLogo2.jpg')
            ->with('returnedImage3png', "createdFinanceLogo3.png")
            ->with('returnedImage3jpg', 'createdFinanceLogo3.jpg');
        } else {
            return back()->with('fail', 'error somehow?');
        }

        
        
        

        
        
        

        
       
        
        
        

    }

    public function creationStation(Request $request)
    {
        $image = ImageModel::latest()->first();
        return view('createimage', compact('image'));
    }

    public function logoMaker(Request $request)
    {
        return view('logomaker');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'filename' => 'image|required|mimes:jpeg,png,jpg,gif'
         ]);
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

        // get time
        $time = date('Mhis', time());
        // get extension
        $ext = $request->file('filename')->extension();


         // THUMBNAIL
        // get file
        $ogFileName = $request->file('filename');

        // make an image model based off of the original file
        $thumbnailImage = Image::make($ogFileName);

        // make width and height variables
        $width = $thumbnailImage->width();
        $height = $thumbnailImage->height();

        // thumbnail and original paths
        $thumbnailPath = public_path().'/thumbnail/';
        $ogFilePath = public_path().'/images/';
        $ogFullPath = $ogFilePath.$time.$ogFileName->getClientOriginalName();

        // create variable to hold our thumbnail path for the front end
        $thumbNail = $time.$ogFileName->getClientOriginalName();
        $thumbNailFullPath = $thumbnailPath.$thumbNail;
        // save the image model into the public directory with its own path.
        $thumbnailImage->save($ogFullPath);


        // using laravel ImageModel, we resize the image
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


        // using laravel ImageModel, we rotate the image
        // $thumbnailImage->rotate(180);
        // using laravel ImageModel, we save the image to the front end
        $thumbnailImage->save($thumbNailFullPath);



        // WATERMARK

        //get watermark from user input
        $watermark = $request->input('watermark');


        //intervention uses gd library
        function applyTextToModel($model, $text, $width, $height) {
            $model->text($text, $width, $height, function($font) {
                $font->file(app_path().'/Fonts/NunitoSans-Regular.ttf');
                $font->size(48);
                $font->color([255, 255, 255, 0.65]);
                $font->align('center');
                $font->valign('top');
                $font->angle(45);
            });
        }
        // gd library saving image to public path so it can be made into a model -> optional
        function createWatermarkImage($text, $ext, $path) {
            // assign proper type to img
            function addText($img, $text){
                $font_path = app_path().'/Fonts/NunitoSans-Regular.ttf'; // font file path
                $white = imagecolorallocatealpha($img, 255, 255, 255, 80); // Allocate A Color For The Text
                imagestring($img, 5, 5, 50, $text, $white); // Print text on image
            }
            $watermarkPath = public_path().'/thumbnail/watermark';
            if($ext === 'jpeg' || $ext === 'jpg') {
                $img = imagecreatefromjpeg($path);
                addText($img, $text);
                imagejpeg($img, "$watermarkPath.$ext");
                imagedestroy($img);
            } else if ($ext === 'png') {
                $img = imagecreatefrompng($path);
                addText($img, $text);
                imagepng($img, "$watermarkPath.$ext");
                imagedestroy($img);
            } else if ($ext === 'gif') {
                $img = imagecreatefromgif($path);
                addText($img, $text);
                imagegif($img, "$watermarkPath.$ext");
                imagedestroy($img);
            }
        }
        // insert text, the extension type, and the full path of the thumbnail, create a watermark copy
        // createWatermarkImage($watermark, $ext, $thumbNailFullPath); // -> optional

        // ALTERNATIVE WAY TO WATERMARK, BUT WITH ROTATION

        // apply text to model
        // applyTextToModel($thumbnailImage, $watermark, $width/10/2, $height/10/2);
        $thumbnailImage->text($watermark, $width/10/2, $height/10/2, function($font) {
            $font->file(app_path().'/Fonts/NunitoSans-Regular.ttf');
            $font->size(24);
            $font->color([255, 255, 255, 0.65]);
            $font->align('center');
            $font->valign('top');
            $font->angle(45);
        });
        // //save model
        $thumbnailImage->save(public_path()."/thumbnail/watermark.$ext");
        //destroy it
        $thumbnailImage->destroy();


        // create from original path the file
        $smallVariant = Image::make($ogFullPath);
        // use change size to make it small
        changeSize($smallVariant, 2);

        // save it as small
        $smallVariant->save(public_path()."/images/small.$ext");

        $smallVariant->text($watermark, $width/5/2, $height/5/2, function($font) {
            $font->file(app_path().'/Fonts/NunitoSans-Regular.ttf');
            $font->size(32);
            $font->color([255, 255, 255, 0.65]);
            $font->align('center');
            $font->valign('top');
            $font->angle(45);
        });
        // use intervention to apply text to image with filters
        // applyTextToModel($smallVariant, $watermark, $width/5/2, $height/5/2);


        // save it as watermarked
        $smallVariant->save(public_path()."/watermarked/small.$ext");

        $smallVariant->destroy();

        // create image from original's full path
        $medVariant = Image::make($ogFullPath);
        // use change size to make it small
        changeSize($medVariant, 3);
        // save it as med
        $medVariant->save(public_path()."/images/med.$ext");
        // apply text to model
        applyTextToModel($medVariant, $watermark, $width/3.33333/2, $height/3.33333/2);
        // save it as watermarked
        $medVariant->save(public_path()."/watermarked/med.$ext");
        $medVariant->destroy();

        $largeVariant = Image::make($ogFullPath);
        changeSize($largeVariant, 4);
        $largeVariant->save(public_path()."/images/large.$ext");
        $largeVariant->text($watermark, $width/5, $height/5, function($font) {
            $font->file(app_path().'/Fonts/NunitoSans-Regular.ttf');
            $font->size(64);
            $font->color([255, 255, 255, 0.65]);
            $font->align('center');
            $font->valign('top');
            $font->angle(45);
        });
        $largeVariant->save(public_path()."/watermarked/large.$ext");
        $largeVariant->destroy();

        $xtraLargeVariant = Image::make($ogFullPath);
        changeSize($xtraLargeVariant, 5);
        $xtraLargeVariant->save(public_path()."/images/xtralarge.$ext");
        $xtraLargeVariant->text($watermark, $width/5.5, $height/5.5, function($font) {
            $font->file(app_path().'/Fonts/NunitoSans-Regular.ttf');
            $font->size(80);
            $font->color([255, 255, 255, 0.65]);
            $font->align('center');
            $font->valign('top');
            $font->angle(45);
        });
        $xtraLargeVariant->save(public_path()."/watermarked/xtralarge.$ext");
        $xtraLargeVariant->destroy();
        // create empty model.
//        $imagemodel= new ImageModel();
//          // give it a file name.
//        $imagemodel->filename=$time.$ogFileName->getClientOriginalName();
//        // save it to the db => => => I think <= <= <=
//        $imagemodel->save();



        return back()
            ->with('success', 'Your images has been successfully Uploaded')
            ->with('thumbnail', $thumbNail)
            ->with('thumbnailWatermark', "watermark.$ext")
            ->with('small', "small.$ext")
            ->with('med', "med.$ext")
            ->with('large', "large.$ext")
            ->with('xtralarge', "xtralarge.$ext");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function newPage() {
        return view('newPage');
    }
}

