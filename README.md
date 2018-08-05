# Information Retrieval

This is a simple Information Retrieval project (Search Engine) that indexes `DOCX` file and and allow users to search in those files.

## Supported Algorithms
1. Boolean Model
2. Extended Boolean Model.
3. Vector Space Model.

## Indexing file
Files can be indexed using `php artisan index:build:boolean`.

This command will build an index in the database that can be utilized by all mentioned search algorithms.

## Work In Progress.

Right now only English is supported, and I'm working to get this ready for Arabic as well, The only thing mission for Arabic is the ArabicStemmer.