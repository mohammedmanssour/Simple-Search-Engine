<?php

namespace App\Console\Commands;

use App\Token;
use App\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\InformationRetrieval\StringCleaner;
use App\InformationRetrieval\DocumentParser;
use App\InformationRetrieval\StopWordsChecker;
use App\InformationRetrieval\Stemmers\ArabicStemmer;
use App\InformationRetrieval\Stemmers\PorterStemmer;

class BooleanIndexBuilder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:build:boolean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build Index Using specified Algorithm';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $files = Storage::disk('local')->files('data');

        collect($files)->each(function($file){
            $this->info("saving Document ${file} in database");
            $model = Document::where('name', $file)->first();
            if(!$model){
                $model = Document::create(['name' => $file]);
            }

            $this->info("Parsing file {$file}...");

            $this->parseFile($model);

        });
    }

    /**
     * Parse File and clean and stem it's words and save them in database
     *
     * @param \App\Document $document
     * @return void
     */
    private function parseFile($document){
        $stopWords = new StopWordsChecker;

        $text = DocumentParser::parseFromFile(storage_path("app/{$document->name}"));
        
        $words = collect(
            explode(" ", $text)
        )->transform(function($word) use($document){
            $word = PorterStemmer::Stem(
                with(new StringCleaner($word))->run()
            );

            return strtolower($word);
        })->filter(function($word) use($stopWords){
            return $word != '' && !$stopWords->isStopWord( $word );
        })->transform(function($word){
            $model = Token::where('word',$word)->first();
            if(!$model){
                $model = Token::create(['word' => $word]);
            }
            return $model;
        })
        ->groupBy('id')
        ->map(function($words){
            return $words->count();
        })
        ->transform(function($times, $id){
            return [
                'token_id' => $id,
                'times' => $times
            ];
        });

        $document->tokens()->sync($words->toArray());

        $this->info("{$words->count()} Words Saved and parsed..");
    }
}
