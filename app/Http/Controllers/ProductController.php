<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PizzaModel;
use Auth;
use Validator;

class ProductController extends Controller
{	
    function __construct() {
        $this->middleware('auth');
    }
	/**
	*
	* function total price  
    *
	* @return number
	*/
	public function totalprice($data) {
		$totalprice = 0;
    	for($i = 0 ; $i < count($data); $i++) {
    		$totalprice += (float)($data[$i]->price);
    	}
    	return round($totalprice + ($totalprice * 50 ) / 100 , 2);
	}

	/**
	*
	* new pizza page 
    *
	* @return mixid
	*/
    
    public function newpizza() {
    	return view('template.newpizza');
    }

    /**
    *
	* add new pizza function
    *
	* @return void
    */

    protected function addpizza(Request $request) {
        $ingredient = json_decode($request->ingredient);
        if(empty($request->name) && empty($ingredient)) {
            return 'error';
        }
    	$obj = new PizzaModel();
    	$obj->user_id = Auth::user()->id;
    	$obj->name = $request->pizzaname;
    	$obj->ingredient = $request->ingredient;
    	$obj->price = $this->totalprice($ingredient);
    	$obj->save();
    }

    /**
    *
	* delete pizza function by id
    *
	* @return array
    */

    protected function deletepizza(Request $request){
    	PizzaModel::where('id',$request->id)->delete();
    	return $this->getpizza();
    }

    /**
	*	
	* update function by id
    *
	* @return array
    */

    protected function updatepizza(Request $request) {
    	PizzaModel::where('id',$request->id)->update([
    		'name' => $request->pizzaname,
    		'ingredient' => $request->ingredient,
    		'price' => $this->totalprice(json_decode($request->ingredient))
    	]);
    	return $this->getpizza();
    }

    /**
	* 
	* get pizza by user_id
    *
	* @return array
    */

    public function getpizza() {
    	$results = PizzaModel::select('*')->where('user_id',Auth::user()->id)->get();
    	return $results;
    }
}
