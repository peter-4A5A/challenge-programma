@extends('layout')

@section('content')
    <div class="container no-max-width">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
                <h1>Weet je zeker dat je deze review wilt verwijderen?</h1>
                <form method="post">
                    @csrf
                    <input type="submit" class="btn btn-danger" value="Verwijderen">
                </form>
            </div>
            <div class="col-2"></div>
        </div>
    </div>
@endsection
