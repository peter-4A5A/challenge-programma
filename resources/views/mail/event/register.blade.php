@extends('mail.template')

@section('content')
    <div>
        Beste {{ $oUser->firstname }},
        <br>
        <br>
        Hierbij ontvangt u een bevestigingsmail van uw inschrijving van {{ $oEvent->name }}.
        <br>
        De gegevens van het evenement, zijn als volgt:
        <ul>
          <li>Aantal punten: {{ $oEvent->points }}</li>
          <li>Start tijd: {{ date('d-M-y H:i', strtotime($oEvent->event_start_date_time)) }}</li>
          <li>Eind tijd: {{ date('d-M-y H:i', strtotime($oEvent->event_start_date_time)) }}</li>
        </ul>
        <br>
        <p>Het adres is<br>
          {{ $oEvent->street }} {{ $oEvent->house_number }}{{ $oEvent->house_number_addition }}
          <br>
          {{ $oEvent->city }} {{ $oEvent->zipcode }}
        </p>
        <br>
        <br>
        Met vriendelijke groet,
        <br>
        <br>
        Het Challenge programma
    </div>
@endsection
