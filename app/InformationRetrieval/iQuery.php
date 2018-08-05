<?php

namespace App\InformationRetrieval;

use App\InformationRetrieval\StringCleaner;
use App\InformationRetrieval\Stemmers\PorterStemmer;

abstract class IQuery {

    /**
     * Query to be parsed
     */
    protected $query;
    
    /**
     * Errors
     */
    public $hasError;

    public function __construct($keyword){
        $this->query = $keyword;

        $this->hasError = false;
    }
    
    protected function cleanAndStem($word){
        return trim(
            strtolower(
                PorterStemmer::Stem(
                    (new StringCleaner($word))->run()
                )
            )
        );
    }

    public abstract function parse();

    public abstract function findResults();

}