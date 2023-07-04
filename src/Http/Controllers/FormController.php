<?php

namespace OoBook\LaravelForm\Http\Controllers;

use Illuminate\Support\Facades\Route;

class FormController extends Controller
{
  public $routePrefix = 'create'; 

  public function edit(){
    return;
  }
  public function create($viewPath){
    $viewPath = explode('.', $viewPath);
    //exclude routeprefix from routeName if it exists
    if ($this->routePrefix != '') {
      $routeName = str_replace($this->routePrefix, '', $viewPath);
    }
   
    $formData = config('unusualForms');
    $inputs = $formData[$routeName[0]]['inputs'];
    $view = 'auth'.'.'.'form';
    foreach($inputs as $input){
      if(isset($input['placeholder']))
        $input['placeholder'] = __($input['placeholder']);
    }
    return view(
      $view,
      [
        'inputs' => $inputs,
        'formTitle' => $formData[$routeName[0]]['title'],
      ]
    );
  }
  public function getRouteName(){
    return Route::currentRouteName();
  }

}
