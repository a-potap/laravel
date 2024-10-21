@php
    $pageTitle = __('common.music');
@endphp

@extends('index')

@section('content')
    <h1>{{$pageTitle}}</h1>

    <div >
        <label>africanskye gluky</label><br>
        <audio controls="controls" title="africanskye gluky">
            <source src="http://a-potap.ru/albums/mus/mytracs/africanskye gluky.mp3" />
        </audio>

        <br><br>
        <label>capability</label><br>
        <audio  controls="controls">
            <source src="http://a-potap.ru/albums/mus/mytracs/capability.mp3" />
        </audio>

        <br><br>

        <label>cosmic trash</label><br>
        <audio controls="controls">
            <source src="http://a-potap.ru/albums/mus/mytracs/cosmic trash.mp3" />
        </audio>

        <br><br>

        <label>easy rock</label><br>
        <audio controls="controls">
            <source src="http://a-potap.ru/albums/mus/mytracs/easy rock.mp3" />
        </audio>

        <br><br>

        <label>fgh</label><br>
        <audio controls="controls">
            <source src="http://a-potap.ru/albums/mus/mytracs/fgh.mp3" />
        </audio>

        <br><br>

        <label>grey day</label><br>
        <audio controls="controls">
            <source src="http://a-potap.ru/albums/mus/mytracs/grey day.mp3" />
        </audio>

        <br><br>

        <label>happy ufo</label><br>
        <audio controls="controls">
            <source src="http://a-potap.ru/albums/mus/mytracs/happy_ufo.mp3" />
        </audio>

        <br><br>

        <label>i need you</label><br>
        <audio controls="controls">
            <source src="http://a-potap.ru/albums/mus/mytracs/i need you.mp3" />
        </audio>

        <br><br>

        <label>i whont be with you</label><br>
        <audio controls="controls">
            <source src="http://a-potap.ru/albums/mus/mytracs/i whont be with you.mp3" />
        </audio>

        <br><br>
        <label>listen to me</label><br>
        <audio  controls="controls">
            <source src="http://a-potap.ru/albums/mus/mytracs/listen_to_me,_looking_at_me.mp3" />
        </audio>

        <br><br>
        <label>you_v got my hart</label><br>
        <audio controls="controls">
            <source src="http://a-potap.ru/albums/mus/mytracs/you_v got my hart.mp3" />
        </audio>
    </div>
@endsection
