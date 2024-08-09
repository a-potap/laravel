@extends('index')

@section('content')
    <div id="content-free">
        @if (App::isLocale('en'))
            <div class="row">
                <div class="col-12">
                    <h2>Helsinki 2019</h2>
                    <p>2019-07-29</p>
                    <p>
                        24 hours in Hensilki. It was cool. Beautiful buildings, an amusement park,
                        the metro where the clip was shot for old song Bomfunk MC's - Freestyler and the harsh
                        nightlife.
                        Only I didn’t have enough phone charge to shot what is happening at night, probably it’s even
                        better.
                    </p>
                    <div class="embed-responsive">
                        <video class="embed-responsive-item" controls style="width: 100%">
                            <source src="/videos/helsinki.mp4" type="video/mp4"/>
                        </video>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h2>Sochi 2018</h2>
                    <p>2018-08-29</p>
                    <p>
                        I love Sochi. Mountains, sea, sunsets. Rosa Khutor is one of the most beautiful places on earth.
                        Almost every year I go there. This time we went with my sister on a mini-trip,
                        in honor of her admission to the university.
                    </p>
                    <div class="embed-responsive">
                        <video class="embed-responsive-item" controls style="width: 100%">
                            <source src="/videos/sochy.mp4" type="video/mp4"/>
                        </video>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h2>Izhevsk 2016</h2>
                    <p>2016-09-05</p>
                    <p>
                        Morning mists on the outskirts of the city.
                    </p>
                    <div class="embed-responsive">
                        <video class="embed-responsive-item" controls style="width: 100%">
                            <source src="/videos/izhevsk.mp4" type="video/mp4"/>
                        </video>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h2>B. Sardyk 2016</h2>
                    <p>2016-07-18</p>
                    <p>
                        Friends gave a quadrocopter, tried a new video format. There is nothing better than spending a
                        summer weekend in the village. Barbecue, fresh air, bath. Here I grew up.
                    </p>
                    <div class="embed-responsive">
                        <video class="embed-responsive-item" controls style="width: 100%">
                            <source src="/videos/b-sardyk.mp4" type="video/mp4"/>
                        </video>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h2>Sochi 2008</h2>
                    <p>2009-11-25</p>
                    <p>
                        My first trip with friends. Heat, sea and an unknown world ahead.
                        The first time I saw the sea and mountains. That is how my love for travel came about.
                    </p>
                    <div class="embed-responsive">
                        <video class="embed-responsive-item" controls style="width: 100%">
                            <source src="/videos/sochy-2008.mp4" type="video/mp4"/>
                        </video>
                    </div>
                </div>
            </div>

        @else
            <div class="row">
                <div class="col-xs-12">
                    <h2>Хельсинки 2019</h2>
                    <p>2019-07-29</p>
                    <p>
                        24 часа в хенсильки. Это было круто. Красивые здания, парк развлечений,
                        метро где сняли клип на тарую песню Bomfunk MC's - Freestyler и суровая
                        ночная жизнь. Только у меня не хватило заряда телефона что бы снять что творится ночью,
                        наверное это даже лучше.
                    </p>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video class="embed-responsive-item" controls style="width: 100%">
                            <source src="/videos/helsinki.mp4" type="video/mp4"/>
                        </video>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h2>Сочи 2018</h2>
                    <p>2018-08-29</p>
                    <p>
                        Люблю Сочи. Горы, море, закаты. Роза Хутор - одно из самых красивых мест на земле.
                        Почти каждый год бываю там. Вэтот раз ездили с сестрой в минипутешествие,
                        в честь её поступления в университет.
                    </p>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video class="embed-responsive-item" controls style="width: 100%">
                            <source src="/videos/sochy.mp4" type="video/mp4"/>
                        </video>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h2>Ижевск 2016</h2>
                    <p>2016-09-05</p>
                    <p>
                        Утренние туманы у нас за домом, на окраине города.
                    </p>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video class="embed-responsive-item" controls style="width: 100%">
                            <source src="/videos/izhevsk.mp4" type="video/mp4"/>
                        </video>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h2>Б. Сардык 2016</h2>
                    <p>2016-07-18</p>
                    <p>
                        Друзья подарили квадрокоптер, попробовал новый формат видеосъемки. Нет ни чего лучше,
                        чем проводить летние выходные в деревне. Шашлык, свежый воздух, баня. Здесь я вырос.
                    </p>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video class="embed-responsive-item" controls style="width: 100%">
                            <source src="/videos/b-sardyk.mp4" type="video/mp4"/>
                        </video>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h2>Сочи 2008</h2>
                    <p>2009-11-25</p>
                    <p>
                        Первое моя поездка с друзьями. Жара, море и неизведанный мир впереди.
                        Так появилась моя любовь к путешествиям.
                    </p>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video class="embed-responsive-item" controls style="width: 100%">
                            <source src="/videos/sochy-2008.mp4" type="video/mp4"/>
                        </video>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
