<?php

namespace App\InformationRetrieval;

use App\Token;
use App\Document;

class VectorQuery extends IQuery{
    public function parse(){
        $stopWords = new StopWordsChecker();

        $this->query = collect(
            explode(" ", $this->query)
        )->filter(function($word) use($stopWords){
            return $word != '' && !$stopWords->isStopWord($word);
        })->transform(function($word){
            return $this->cleanAndStem($word);
        })
        ->toArray();

        return $this;
    }

    public function findResults(){
        $matches = [];
        $docCount = Document::count();
        foreach($this->query as $token){
            $tokenModel = Token::where('word', $token)->first();
            if(!$tokenModel){
                continue;
            }

            //get all document that has token
            $documents = $tokenModel->documents()->get();
            $df = $documents->count();
            $documents->each(function($document) use(&$matches, $docCount, $df){
                $tf = $document->pivot->times;

                if(! isset($matches[$document->id]) ){
                    $match = new \stdClass();
                    $match->score = 0;
                    $match->document = $document;
                    $matches[$document->id] = $match;
                }

                ($matches[$document->id])->score += $tf * log($docCount + 1 / $df + 1, 2);
            });

            foreach($matches as $docID => $match){
                $match = $matches[$docID];
                $match->score = $match->score/$match->document->tokens()->count();
                $matches[$docID] = $match;
            }

            return collect($matches)->sortByDesc('score')->pluck('document');
        }
    }

}