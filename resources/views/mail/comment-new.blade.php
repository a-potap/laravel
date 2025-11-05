<div>
    Nik: {{ $comment->iduser }}
</div>
<div>
    Text: {{ $comment->text }}
</div>

<a href="{{ url('https://a-potap.ru/post/' . $comment->blog_id) }}">post link</a>