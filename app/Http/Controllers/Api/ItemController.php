<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Resources\ItemResource;

class ItemController extends Controller
{

    public function __construct($value='')
    {
        $this->middleware('auth:api')->except('index','filter');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();
        return response()->json([
            "status" => "ok",
            "totalResult" => count($items),
            "items" => Itemresource::collection($items)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator= $request->validate([
            'codeno' => 'required|string',
            'name' => 'required|string',
            'photo' => 'required|mimes:jpeg,bmp,png',
            'price' => 'required|integer',
            'discount' => 'required|integer',
            'description' => 'required|string',
            'brand' => 'required',
            'subcategory' => 'required',
        ]);


      /*  $customAttributes = [
    'codeno' => 'codeno',
    'name' => 'name',
    'photo' => 'photo',
    'price' => 'price',
    'discount' => 'discount',
    'description' => 'description',
];

$validator = Validator::make($input, $rules, $messages, $customAttributes);
*/



        //File Upload
$imageName = time().'.'.$request->photo->extension();  

$request->photo->move(public_path('backendtemplate/itemimg'), $imageName);
$myfile='backendtemplate/itemimg/'.$imageName;


        //Store Data
$item=new Item;
$item->codeno=$request->codeno;
$item->name=$request->name;
$item->photo=$myfile;
$item->price=$request->price;
$item->discount=$request->discount;
$item->description=$request->description;
$item->brand_id=$request->brand;
$item->subcategory_id=$request->subcategory;

$item->save();

return new ItemResource($item);

}   

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return new ItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }

    public function filter($sid,$bid)
    {
        $items = array();
        //if two id has
        if($sid && $bid) {
            $items = Item::where('subcategory_id',$sid)->where('brand_id',$bid)->get();
        }else{
            //if has one or other.
            $items = Item::where('subcategory_id',$sid)->or_where('brand_id',$bid)->get();
        }
        return $items;
    }

    public function search(Request $request) {
        $name = $request->get('name');
        $sid=$request->get('subcategory');
        $bid=$request->get('brand');
        $search=array();



   if($name || $sid || $bid)
   {// if something have do condition

    if($name && $sid && $bid){//All data include
        $search = Item::where('subcategory_id',$sid)->where('brand_id',$bid)->where('name', 'like', "%{$name}%")->get();

    }else if($name && $sid){//if name and subcategory include
        $search=Item::where('subcategory_id',$sid)->where('name', 'like', "%{$name}%")->get();
    }
    else if($name && $bid){//if name and brand contain
        $search=Item::where('brand_id',$bid)->where('name', 'like', "%{$name}%")->get();
    }
    else if($sid && $bid){//if subcategory and brand exist
        $search=Item::where('subcategory_id',$sid)->where('brand_id',$bid)->get();
    }else if($sid || $bid){//if subcategory or brand
        $search=Item::where('subcategory_id',$sid)->orWhere('brand_id',$bid)->get();

    }else{//if subcategory or name
     $search=Item::where('subcategory_id',$sid)->orWhere('name', 'like', "%{$name}%")->get();

 }

}else{//if no display all items
    $search=Item::all();
}
return $search;
}
}
