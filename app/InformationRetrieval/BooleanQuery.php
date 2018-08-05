<?php

namespace App\InformationRetrieval;

use App\Token;

class BooleanQuery  extends IQuery{

    /**
     * Parse Query with left, right and required action
     *
     * @return $this
     */
    public function parse(){
        $this->query = explode(" ", $this->query);

        if(count($this->query) < 3){
            $this->hasError = true;
            $this->query = false;

            return $this;
        }
        

        $this->query = [
            'left' => $this->cleanAndStem( $this->query[0] ),
            'right' => $this->cleanAndStem( $this->query[2] ),
            'action' => strtolower($this->query[1])
        ];

        if(!in_array($this->query['action'], ['or', 'and'])){
            $this->query = false;
            $this->hasError = true;
        }
        return $this;
    }

    public function findResults(){
        if($this->query === false){
            return collect([]);
        }
        
        $left = Token::where('word', $this->query['left'])->first();
        $right = Token::where('word', $this->query['right'])->first();

        if($left){
            $documentsLeft = $left->documents()->get();
        }else{
            $documentsLeft = collect([]);
        }

        if($right){
            $documentsRight = $right->documents()->get();
        }else{
            $documentsRight = [];
        }

        if($this->query['action'] == 'and'){
            $result = $documentsLeft->intersect($documentsRight);
        }else{
            $result = $documentsLeft->merge($documentsRight);
        }

        return $result->sortByDesc(function($document){
            return $document->pivot->times;
        });
    }

}