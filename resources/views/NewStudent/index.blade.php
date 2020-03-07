@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Naam</th>
                        <th>E-mail</th>
                        <th>Schooljaar</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($aStudents as $Student)
                        <tr>
                            <td>{{$Student->id}}</td>
                            <td>{{$Student->firstname}}</td>
                            <td>{{$Student->email}}</td>
                            <td>{{$Student->schoolyear}}</td>
                            <td>
                                <a href="#" class="btn btn-success">Accepteren</a>
                                <a href="#" class="btn btn-danger">Verwijderen</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
