@extends('index')

@section('content')

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Date</th>
            <th>Content</th>
            <th>Content EN</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($news as $item)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $item->date }}</td>
                <td>{{ $item->text }}</td>
                <td>{{ $item->text_en }}</td>
                <td>
                    <form action="{{ route('news.destroy',$item->id) }}" method="POST">

                        <a class="btn btn-info" href="{{ route('news.show',$item->id) }}">Show</a>

                        <a class="btn btn-primary" href="{{ route('news.edit',$item->id) }}">Edit</a>

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $news->links() !!}

@endsection
