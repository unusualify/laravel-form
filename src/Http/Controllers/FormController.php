<?php

namespace OoBook\LaravelForm\Http\Controllers;

use Illuminate\Support\Facades\Route;

class FormController extends Controller
{
  public $routePrefix = 'create'; 

  public function edit(){
    return;
  }
  public function create(){
    
    $routeNotation = $this->getRouteName();
    $routeNotation = explode('.', $routeNotation);
    //exclude routeprefix from routeName if it exists
    if ($this->routePrefix != '') {
      $routeName = str_replace($this->routePrefix, '', $routeNotation);
    }
   
    $formData = config('unusualForms');
    // dd($formData);
    $inputs = $formData[$routeName[0]]['inputs'];
    $view = $routeName[0].'.'.'form';
    foreach($inputs as $input){
      if(isset($input['placeholder']))
        $input['placeholder'] = __($input['placeholder']);
    }
    return view(
      $view,
      ['inputs' => $inputs]
    );
  }
  public function getRouteName(){
    return Route::currentRouteName();
  }

}
