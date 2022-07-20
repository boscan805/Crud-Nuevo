<?php

namespace App\Http\Controllers;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ItemUpdateRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ItemCreateRequest;



class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $url = "https://fakestoreapi.com/products";
        $curl = curl_init($url);

         
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('User-Agent: php-curl'));
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($curl);
        
        $info = curl_getinfo($curl);
        $products = [];
        if ($info['http_code'] == 200) {
          //dd(json_decode($response));
            $products = json_decode($response);
           //print_r($info);
        } else {
        }
        curl_close($curl);

        // $products = Product::select('id','name', 'price', 'stock', 'images', 'url')->with('imagesproducts:name')->get();


        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = Product:: all();
        return view('admin.product.crear' , compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        $curlUrl = "https://fakestoreapi.com/products'";

        $datos = array("title" => "test product",  "price" => 13.5, "description" => "lorem ipsum dolor", "image" => "https://i.pravatar.cc", "category" => "electroni");
        
        $data_string = json_encode($datos);
        
        $ch=curl_init($curlUrl);
        
        curl_setopt($ch, CURLOPT_POST, true);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
        
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        
                     
        $respuesta = curl_exec( $ch );
        
        
        curl_close($ch);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $id = 0;
        
        $product = Product::find($id);
 
        $images = Product::find($id)->images;
 
        return view('admin/products.update', compact('images'), ['products' => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $id = 0;
        $product= Product::find($id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        
        $product->save();
 
        $ci = $request->file('img');
 
        if(!empty($ci)){
 
            $this->validate($request, [
 
                'name' => 'required',
                'img.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
 
            ]);        
 
            foreach($request->file('img') as $image)
                {
                    $imagen = $image->getClientOriginalName();
                    $format = $image->getClientOriginalExtension();
                    $image->move(public_path().'/uploads/', $imagen);
 
                    DB::table('images')->insert(
                        [
                            'name' => $imagen, 
                            'format' => $format,
                            'products_id' => $product->id,
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s")
                        ]
                    );
 
                } 
 
        }
 
        Session::flash('message', 'Editado Satisfactoriamente !');
        return Redirect::to('admin/products');
        
    }

    /**
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $id = 0;
        $product = Product::find($id);
 
        // Selecciono las imágenes a eliminar 
        $imagen = DB::table('images')->where('products_id', '=', $id)->get();        
        $imgfrm = $imagen->implode('name', ',');  
        //dd($imgfrm);        
 
        $imagenes = explode(",", $imgfrm);
        
        foreach($imagenes as $image){
            
            $dirimgs = public_path().'/uploads/'.$image;
            
            if(File::exists($dirimgs)) {
                File::delete($dirimgs);                
            }
 
        }    
 
        
        Product::destroy($id); 
 
        $product->images()->delete();
 
        Session::flash('message', 'Eliminado Satisfactoriamente !');
        return Redirect::to('admin/products');

        
    }


    public function eliminarimagen($id, $bid)
    {
        $product = Image::find($id);
 
        $bi = $bid;
 
        $imagen = Image::select('name')->where('id', '=', $id)->get();
        $imgfrm = $imagen->implode('name', ', ');
        //dd($imgfrm);
        Storage::delete($imgfrm);
 
        Image::destroy($id);
 
        Session::flash('message', 'Imagen Eliminada Satisfactoriamente !');
        return Redirect::to('admin/product/update/'.$bi.'');
    }
}
