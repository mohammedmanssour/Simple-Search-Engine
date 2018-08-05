<html>
    <head>
        <title>Simple Search Engine</title>

        <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>

        <div class="container">

            <div class="text-center">
                <h1> Simple Search Engine</h1>
                <h3>Made by Mohammed Manssour mohammed_88132</h3>
            </div>

            <form action="{{ url('/') }}">
                <div class="row">
                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
                        <input type="text" name="s" value="{{ request('s') }}" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                        <div class="col-md-12">
                            <select name="algorithm" class="form-control">
                                <option value="boolean" {{ request('algorithm', 'boolean') == 'boolean' ? 'selected': '' }}>Boolean Model</option>  
                                <option value="vector" {{ request('algorithm', 'boolean') == 'vector' ? 'selected': '' }}>Vector Space Model</option>  
                            </select>
                        </div>
                        <div class="col-md-12 text-center" style="margin-top: 15px">
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </form>

            @if(request('s'))
                @if(request('algorithm') == 'boolean' && $hasError)
                <div class="alert alert-danger">
                    Please write valid Boolean Query
                </div>
                @endif
                <h1 style="margin-top=100px" class="text-center">{{ $results ? $results->count() : 0 }} Results Found</h1>
                @if($results)
                    <table class="table table-hover table-striped">
                        @foreach($results as $document)
                            <tr>
                                <td class="text-center">{{ $document->id }}</td>
                                <td class="text-center">{{ $document->name }}</td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            @endif
        </div>
    </body>
</html>