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
    public function create()
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
        $img1 = createRainbow(500, 500);
        $newImg = Image::make($img1);
        $path = public_path().'/arcs/';
        $newImg->save($path.time().'arc.png');
        $imagemodel= new ImageModel();
        $imagemodel->filename=time().'arc.png';
        $imagemodel->save();

        $image = ImageModel::latest()->first();
        return view('createimage', compact('image'));
        // return view('createimage');
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
            'filename' => 'image|required|mimes:jpeg,png,jpg,gif,svg'
         ]);
        




        
         // THUMBNAIL AND ORIGINAL 
        $time = date('Mhis', time());
        $originalImage= $request->file('filename');
        $thumbnailImage = Image::make($originalImage);
        $thumbnailPath = public_path().'/thumbnail/';
        $originalPath = public_path().'/images/';

        // $thumbnailImage->save($originalPath.time().$originalImage->getClientOriginalName());
        $thumbNail = $time.$originalImage->getClientOriginalName();
        $thumbnailImage->resize(150,150);
        // $thumbnailImage->rotate(180);
        $thumbnailImage->save($thumbnailPath.$thumbNail); 

        $imagemodel= new ImageModel();
        $imagemodel->filename=$time.$originalImage->getClientOriginalName();
        $imagemodel->save();

        return back()
            ->with('success', 'Your images has been successfully Uploaded')
            ->with('thumbnail', $thumbNail);

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
}

