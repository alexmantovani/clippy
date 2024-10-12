@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Carica file o incolla testo</h2>
    <form action="{{ route('uploads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="text">Incolla il testo qui:</label>
            <textarea name="text" class="form-control" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label for="file">Carica un file:</label>
            <input type="file" name="file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Carica</button>
    </form>
</div>
@endsection
