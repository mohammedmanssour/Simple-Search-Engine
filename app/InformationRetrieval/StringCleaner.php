<?php

namespace App\InformationRetrieval;

class StringCleaner{

    /**
     * Text to clean
     *
     * @var string
     */
    protected $text;

    public function __construct($text){
        $this->text = $text;
    }

    public function run(){
        $this->text = trim(
            $this->cleanHtmlTags()
                ->cleanUnwantedCharacter()
                ->cleanSpaces()
                ->text
        );   

        return $this->text;
    }

    /**
     * remove spaces from text
     *
     * @return $this
     */
    private function cleanSpaces(){
        $this->text = preg_replace('/\s+/', " ",$this->text);
        return $this;
    }

    /**
     * remove html tags from string
     *
     * @return $this
     */
    private function cleanHtmlTags(){
        $this->text = strip_tags($this->text);
        return $this;
    }

    /**
     * remove unwanted chars from string
     *
     * @return string
     */
    private function cleanUnwantedCharacter(){
        $this->text = preg_replace("/[^A-Za-z0-9 ]/", ' ', $this->text);
        return $this;
    }
}