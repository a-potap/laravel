@extends('index')

@section('content')

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Date</th>
            <th>Title</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($blog as $item)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $item->date }}</td>
                <td>{{ $item->title }}</td>
                <td>
                    <form action="{{ route('blog.destroy',$item->id) }}" method="POST">

                        <a class="btn btn-info" href="{{ route('blog.show',$item->id) }}">Show</a>

                        <a class="btn btn-primary" href="{{ route('blog.edit',$item->id) }}">Edit</a>

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $blog->links() !!}

@endsection
