<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Storage;

use Image;
use App\Photo;
use App\Product;

class UploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('deleteAll');;
    }
    //show upload form
    public function create($id)
    {
        // Product::create([
        //   'id' => 90561001,
        //   'catalog_id' => 1,
        //   'sort_id' => 1,
        //   'name' => 'Carmen Белый Выключатель',
        //   'purchase_price' => 12,
        //   'price' => 15,
        //   'currency_id' => 'USD',
        //   'instock' => 1,
        //   'balance' => 100,
        //   'unit_id' => 1,
        //   'warehouse_id' => 1
        // ]);
        
        $product = Product::find($id);
        
        if($product) {
          return view('upload.create', compact('product'));    
        } else {
          return 'product dont found';
        }
        
    }
    
    //store main photo
    public function main(Request $request, $id)
    {
        
        $this->validate($request, [
            'file' => 'image|required|mimes:jpeg,png,jpg,gif,svg'
        ]);
        
        //return 'ok';
        //Storage::cloud()->put('hello.txt', 'Wold', 'public');
        //Storage::cloud()->delete('hello.txt');
        //64*64px
        //242*340px
        //1500*2707px
        
        $file = $request->file('file');
        
        $product = Product::find($id);

        //Resize images

        $uniqid = md5(uniqid(rand(),true)) . '.' . $file->getClientOriginalExtension();

        //Height = 64
        // $img_64 = Image::make($file)->resize(null, 64, function ($constraint) {
        //     $constraint->aspectRatio();
        //     $constraint->upsize();
        // });
        
        // $resource_64 = $img_64->stream()->detach();
                
        // //Save to S3
        // $cloud = Storage::cloud()->put(
        //     '64.' . $uniqid,
        //     $resource_64,
        //     'public'
        // );

        //Height = 250
        $img_250 = Image::make($file)->resize(null, 250, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        $resource_250 = $img_250->stream()->detach();
                
        //Save to S3
        $cloud = Storage::cloud()->put(
            '250.' . $uniqid,
            $resource_250,
            'public'
        );

        //Height = 800
        $img_800 = Image::make($file)->resize(null, 800, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        $resource_800 = $img_800->stream()->detach();
                
        //Save to S3
        $cloud = Storage::cloud()->put(
            '800.' . $uniqid,
            $resource_800,
            'public'
        );
        
        //delete old photo
        //Storage::cloud()->delete('64.' . $product->image_url);
        Storage::cloud()->delete('250.' . $product->image_url);
        Storage::cloud()->delete('800.' . $product->image_url);
        
        //save to Mysql
        if($cloud) {
          $product->image_url = $uniqid;
          $product->save();
        }
        
        return redirect()->back();
    }
    
    //store more Photos
    public function more(Request $request, $id)
    {
        
        $this->validate($request, [
            'file.*' => 'image|required|mimes:jpeg,png,jpg,gif,svg'
        ]);
        
        //return 'ok';
        //Storage::cloud()->put('hello.txt', 'Wold', 'public');
        //Storage::cloud()->delete('hello.txt');
        //64*64px
        //242*340px
        //1500*2707px
        
        $file = $request->file('file');
        
        if ($file) {
            
            foreach ($file as $f) {
                
                //Resize images
                $uniqid = md5(uniqid(rand(),true)) . '.' . $f->getClientOriginalExtension();
                
                //Height = 64
                $img_64 = Image::make($f)->resize(null, 64, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                $resource_64 = $img_64->stream()->detach();
                
                //Save to S3
                $cloud = Storage::cloud()->put(
                    '64.' . $uniqid,
                    $resource_64,
                    'public'
                );
                
                //Height = 250
                // $img_250 = Image::make($f)->resize(null, 250, function ($constraint) {
                //     $constraint->aspectRatio();
                //     $constraint->upsize();
                // });
                
                // $resource_250 = $img_250->stream()->detach();
                        
                // //Save to S3
                // $cloud = Storage::cloud()->put(
                //     '250.' . $uniqid,
                //     $resource_250,
                //     'public'
                // );
        
                //Height = 800
                $img_800 = Image::make($f)->resize(null, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                $resource_800 = $img_800->stream()->detach();
                        
                //Save to S3
                $cloud = Storage::cloud()->put(
                    '800.' . $uniqid,
                    $resource_800,
                    'public'
                );
                
                //save to Mysql
                if($cloud) {
                  Photo::create([
                    'product_id' => $id,
                    'img' => $uniqid
                  ]);    
                }
                
                
            }
        }
        
        return redirect()->back();
    }
    
    //Delete photo
    public function delete(Request $request)
    {
      
      Storage::cloud()->delete('64.' . $request->img);
      //Storage::cloud()->delete('250.' . $request->img);
      Storage::cloud()->delete('800.' . $request->img);

      $photo = Photo::where('img', $request->img);
      //delete old photo
      if($photo) {
     
        $photo->delete();
        
        return redirect()->back();
      } else {
        return redirect()->back()->withErrors('Photo dont found');
      }
      
    }

    //Delete All API photo
    public function deleteAll($id)
    {

      $product = Product::find($id);

      Storage::cloud()->delete('64.' . $product->image_url);
      Storage::cloud()->delete('250.' . $product->image_url);
      Storage::cloud()->delete('800.' . $product->image_url);

      foreach ($product->photos as $photo) {
        Storage::cloud()->delete('64.' . $photo->img);
        //Storage::cloud()->delete('250.' . $photo->img);
        Storage::cloud()->delete('800.' . $photo->img);
      }

      $product->photos()->delete();
      
    }
    
    /**
     * Desc editor Pro
     */
    public function descShow($id) {
        $product = Product::find($id);
        
        if($product) {
          return view('desc.edit', compact('product'));    
        } else {
          return 'Product dont found';
        }
    }
    
    /**
     * Update Desc
     */
    public function descUpdate(Request $request, $id) {
    
        $this->validate($request, [
            'desc' => 'max:65535'
        ]);
        
        $product = Product::find($id);
        
        $product->description = $request->desc;
        
        $product->save();
        
        if($product) {
          return redirect()->back();
        } else {
          return redirect()->back()->withErrors('Product dont found');
        }
    }
    
    
}
