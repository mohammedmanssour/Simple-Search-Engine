<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InformationRetrieval\VectorQuery;
use App\InformationRetrieval\BooleanQuery;

class HomeController extends Controller
{
    public function index(){
        $keyword = request('s');
        $algorithm = request('algorithm');

        if($algorithm == 'boolean'){
            $queryParser = new BooleanQuery($keyword);
            $results = ($queryParser)->parse()->findResults();
            $hasError = $queryParser->hasError;
        }else{
            $hasError = false;
            $results = (new VectorQuery($keyword))->parse()->findResults();
        }

        return view('home.index',[
            'results' => $results,
            'hasError' => $hasError
        ]);
    }
}
