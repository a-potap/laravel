@extends('index')

@section('content')

    <div class="starter text-end">
        <h1>This is Potapov Alexey's blog!</h1>

        <p class="lead">
            Welcome to my site, I'm glad to see you here
        </p>

        <p>
            <br>
            <br>
            <a href="">The best journeys are not always in straight lines.</a>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <h2>About me </h2>

            <p>
                I like to ride a bike, travel, chat with friends and just usefully spend time. I work in the field of web programming. I like to read dystopias.
            </p>

            <h2>What can be found here</h2>

            <p>
                I opened this site primarily for my friends and acquaintances. Here I spread mainly reports about my travels, photos and some thoughts about everything.
            </p>

            <p>
                I think that the best thing about a person is his work. Therefore, there is only what I did myself.
            </p>

            <p>
                Leave a comment, if you like a story, this is the best motivation for me to write more!
            </p>
        </div>

        <div class="col-sm-6">
            <h2>News</h2>

            <div class="news">
                @foreach ($news as $item)
                    <div class="row">
                        <div class="col-12">
                            <i>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</i>
                            <p>
                                {!! $item->text !!}
                            </p>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
