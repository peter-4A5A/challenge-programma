@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Gebruikers accepteren of weigeren</h1>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Voornaam</th>
                        <th>Tussenvoegsel</th>
                        <th>Achternaam</th>
                        <th>E-mail</th>
                        <th>Schooljaar</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($aUsers as $oUser)
                        <tr>
                            <td>{{$oUser->id}}</td>
                            <td>{{$oUser->firstname}}</td>
                            <td>{{$oUser->prefix}}</td>
                            <td>{{$oUser->lastname}}</td>
                            <td>{{$oUser->email}}</td>
                            <td>{{$oUser->studentInfo->school_year }}</td>
                            <td>
                                <a href="{{ route('user.accept', $oUser->id) }}" id="accept" class="btn btn-success">Accepteren</a>
                                <a href="{{ route('user.delete', $oUser->id) }}" id="delete" class="btn btn-danger">Verwijderen</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection