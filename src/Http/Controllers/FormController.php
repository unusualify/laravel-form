<?php

namespace OoBook\LaravelForm\Http\Controllers;

use Illuminate\Http\Request;
use A17\Twill\Models\User;



class FormController extends Controller
{
  public function edit(){
    return;
  }
  public function create(){
    return view(
      'register.pages.index',
      ['item' => new User()]
    );
  }
}
