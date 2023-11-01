<?php

namespace Unusualify\LaravelForm\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\View;

class FormController extends Controller
{

  public $formKey = 'form';
  public $basePath = 'form';
  public $formData;
  public $view;
  public function edit(){
    return;
  }

  public function create(){

    $formData = config('unusualForms');
    $formData = $formData[$this->formKey];

    if(key_exists('inputs', $formData)){
      foreach ($formData['inputs'] as $input) {
        if (isset($input['placeholder']))
          $input['placeholder'] = __($input['placeholder']);
      }
    }else{
      if(key_exists('steps',$formData))
        foreach ($formData['steps'] as $forms) {
          foreach ($forms['inputs'] as $input) {
            if (isset($input['placeholder']))
            $input['placeholder'] = __($input['placeholder']);
          }
        }
      else
        foreach($formData as $forms){
          foreach ($forms['inputs'] as $input) {
            if (isset($input['placeholder']))
              $input['placeholder'] = __($input['placeholder']);
          }
        }
    }
    // dd($this->view);
    $view = Collection::make([
      "$this->basePath"."."."$this->view" ,
      "$this->basePath.form",
    ])->first(function ($view) {
      return View::exists($view);
    });

    return View::make($view, ['formData'=> $formData]);

    // return view(
    //   $view,
    //   [
    //     'formData' => $formData,
    //   ]
    // );
  }
  public function getRouteName(){
    return Route::currentRouteName();
  }
}
