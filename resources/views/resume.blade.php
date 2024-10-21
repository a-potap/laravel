@php
    $pageTitle = __('common.resume');
@endphp

@extends('index')

@section('content')
    <div id="content-free">
        @if (App::isLocale('en'))
            <h2>ALEKSEY POTAPOV</h2>
            <p>
                <strong>
                    First of all, I want to achieve success in my profession and, of course, make money.
                </strong>
            </p>

            <h3>Education</h3>
            <p>
                Kalashnikov Izhevsk State Technical University, master of Applied Mathematics,
                specialty: information systems and technologies.
            </p>

            <h3>Work experience:</h3>
            <p>
            <ul>
                <li>
                    1.5 years in the company Kedr-Consulting LLC as a 1C programmer in 7.7 and 8.1
                    , (completion, implementation, support of various configurations);
                </li>
                <li>
                    1 year at the <a href="http://udmteatr.ru/" target="_blank">Udmurt National Theater</a>
                    as a leading programmer;
                </li>
                <li>
                    2 years at <a href="http://www.saigas.ru" target="_blank">Saigas</a> as a network programmer.
                    I support the work of the corporate portal and external site.
                    In combination, I worked at <a href="https://izhlife.ru/" target="_blank">izhlife.ru</a> (revision, creation of new modules, editing the engine,
                    creating templates);
                </li>
                <li>
                    For 6 years in the company
                    <a href="http://russianit.ru/" target="_blank">Russian Information Technologies</a> as a web programmer;
                </li>
                <li>
                    For 5 years in the company a <a href="https://www.fastdev.se" target="_blank">FastDev</a> as full stack web programmer.
                </li>
                <li>
                    Currently working as web programmer in <a href="https://www.fixparts-online.com/" target="_blank">Fixparts</a>.
                </li>
            </ul>
            </p>

            <h3>Technical skills:</h3>
            <p>
            <ul>
                <li>knowledge of programming languages: Delphi, PHP, MSSQL / MySQL, JavaScript;</li>
                <li>currently working on the web: PHP, HTML5, CSS (twitter bootstrap, SASS), javascript,
                    familiar with CMS Drupal, WordPress, DLE, etc.;
                </li>
                <li> PHP frameworks: Yii2, ZF, a bit of Laravel;</li>
                <li> MSSQL, MySQL, a bit of Mongodb;</li>
                <li> CSS: SASS, LESS, Twitter Bootstrap, Zurb Foundation;</li>
                <li> JS: AngularJS, React+Redux, Vie;</li>
                <li> currently learning TypeScript and Angular.</li>
            </ul>
            </p>

            <h3>Personal Information</h3>
            <p>
                Year of birth: 1986<br>
                Character balanced, responsible, punctual, sociable.<br>
                Knowledge of foreign languages: English.<br>
                <b>mail: </b> <span class="text-primary">a-potap@mail.ru</span><br>
                <b>skype: </b><span class="text-primary">potapov25</span><br>
                <b>github: </b><span class="text-primary">a-potap</span><br>
            </p>
        @else
            <h2>ПОТАПОВ АЛЕКСЕЙ ФЕДОРОВИЧ</h2>
            <p>
                <strong>
                    Хочу достичь успехов в своей профессии и, конечно, заработать.
                </strong>
            </p>
            <h3>Образование</h3>

            <p>
                ИжГТУ факультет Прикладная Математика,  специальность информационные системы и технологии.
            </p>

            <h3>Опыт работы</h3>
            <p>
            <ul>
                <li> 1,5 года работы в компании ООО«Кедр-Консалтинг» программистом 1С в 7.7 и 8.1, (доработка, внедрение,
                    поддержка различных конфигураций).
                </li>
                <li> 1 год в удмуртском национальном театре ведущим программистом. Занимался поддержкой 1С, оборудования и
                    работой <a href="http://udmteatr.ru/" target="_blank">сайта</a>.
                </li>
                <li> 2 года работал в <a href="http://www.saigas.ru" target="_blank">Сайгасе</a> сетевым программистом. Поддерживаю работу корпоративного портала и внешнего
                    сайта. По совместительству работал в <a href="http://izhlife.ru" title="Я люблю ижевск">izhlife.ru</a>
                    (доработка, создание новых модулей, правка движка, создание шаблонов)
                </li>
                <li> 6 лет работал в компании <a href="http://russianit.ru/">Русские информационные
                        технологии</a> веб программистом.
                </li>
                <li>
                    5 летработал в компании <a href="https://www.fastdev.se" target="_blank">FastDev</a> фулл-стек веб программистом.
                </li>
                <li>
                    В настоящее время работаю в компании <a href="https://www.fixparts-online.com/" target="_blank">Fixparts</a> веб программистом.
                </li>
            </ul>
            </p>

            <h3>Навыки</h3>
            <p>
            <ul>
                <li>Знание языков: PHP, MSSQL / MySQL, JavaScript;</li>
                <li>В данный момент работаю с: PHP, HTML5, CSS (twitter bootstrap, SASS), javascript,
                    знаком с CMS Drupal, WordPress, DLE;
                </li>
                <li> PHP фреймворк: Yii2, ZF, знаком с  Laravel;</li>
                <li> MSSQL, MySQL, a bit of Mongodb;</li>
                <li> CSS: SASS, LESS, Twitter Bootstrap, Zurb Foundation;</li>
                <li> JS: AngularJS, React+Redux, Vie;</li>
                <li> изучаю TypeScript и Angular.</li>
            </ul>
            </p>

            <h3>Сведения о себе</h3>
            <p>
                Год рождения: 1986<br>
                Характер уравновешенный, ответственен, пунктуален, коммуникабелен.<br>
                Знание иностранных языков: английский<br><br>
                <b>mail: </b> <span class="text-primary">a-potap@mail.ru</span><br>
                <b>skype: </b><span class="text-primary">potapov25</span><br>
                <b>github: </b><span class="text-primary">a-potap</span><br>
            </p>
        @endif
    </div>
@endsection
