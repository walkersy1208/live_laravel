@extends('layouts.app')
@section('content')
    
    <div class="row">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h2>Articles Add</h2>
                </div>
                <div class="card-body">
                    <form action="/articles_save"  method="post">
                        {{ csrf_field() }}
                        
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" value="" class="form-control" placeholder="Enter Title"
                                id="title"  name="title">
                            @error('title')
                                <span class="feedback_msg" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" name="content" id="content" rows="3">
                            </textarea>
                            @error('content')
                                <span class="feedback_msg" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
    </div>
@endsection

