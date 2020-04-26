@extends('layout')
@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset('css/galery.css')}}">
@endsection
@section('content')

<div class="container">
    <h2 class="row justify-content-center">Tijdlijn</h2>
    <div class="row justify-content-center">
        @if(Auth::user() && (Auth::user()->role == 'admin' || Auth::user()->role == 'content-writer' ))
            <button type="button" class="btn btn-primary">
                <a href="{{ route('photoalbum.create') }}">Fotoalbum toevoegen</a>
            </button>
        @endif
    </div>
        <div class="row justify-content-center">
            @if($aPhotoalbum->isEmpty())
                <h5>Er zijn op het moment geen fotoalbums beschikbaar</h5>
            @endif
            @foreach ($aPhotoalbum as $album)
            <div class="col-md-8 p-4">
                <div class="card">
                    <div class="card-header">
                        <h4>{{$album->title}} - Bedrijfsnaam, 07-04-2020</h4>
                    </div>
                    <div class="card-body">
                        @if(Auth::user() && (Auth::user()->role == 'admin' || Auth::user()->role == 'content-writer' ))
                            <a class="btn btn-primary float-right"
                               href="{{route('photoalbum.edit', ['id' => $album->id])}}">Bewerken</a>
                        @endif
                        <h5 class="p-2">{{$album->description}}</h5>
                        <div class="widget-container">
                            <div class="widget row image-tile">
                                    @foreach($album->photos as $oPhoto)
                                        <div class="col-md-5">
                                            <img class="img-thumbnail rounded mx-auto d-block" src="{{Storage::url($oPhoto->path)}}">
                                        </div>
                                        @break($loop->index == 1)
                                    @endforeach
                                <div class="tile more-images col-md-2">
                                    <div class="images-number">{{count($album->photos)}}</div>
                                    Foto's
                                </div>
                            </div>
                            <a class="btn btn-primary float-left"
                               href="{{route('photoalbum.photos', ['id' => $album->id])}}">Foto's bekijken</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
</div>

@endsection
