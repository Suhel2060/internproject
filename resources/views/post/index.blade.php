@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="#">Post</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('post.create') }}">Create</a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">S.N</th>
                            <th scope="col">Title</th>
                            <th scope="col">Content</th>
                            <th scope="col">Status</th>
                            <th scope="col">Catagory</th>
                            <th scope="col">Author</th>
                            <th scope="col">Image</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($post as $item)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->content }}</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->catagory->catagory }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>
                                    @if ($item->image)
                                        <img src="{{ asset('images/' . $item->image) }}" alt="{{ $item->title }}"
                                            width="100" height="100">
                                    @else
                                        <img src="" alt="" width="100" height="100">
                                    @endif

                                </td>
                                {{-- <td>
                                @if ($item->postimage)
                                @foreach ($item->postimage as $images)
                                <img src="{{ asset('images/'.$images->images) }}" alt="{{ $item->title }}" width="100" height="100">
                                @endforeach
                                
                                @else
                                <img src="" alt=""  width="100" height="100">
                                @endif
                                
                            </td>  --}}

                                <td>
                                    <a name="edit" id="" class="btn btn-primary"
                                        href="{{ route('post.edit', $item) }}" role="button">Edit</a>
                                    <button type="button" class="btn btn-danger delete-button">Delete</button>
                                    <form action='{{ route('post.destroy', $item) }}' method="post">
                                        @method('DELETE')
                                        @csrf
                                    </form>

                                </td>

                            </tr>
                        @endforeach


                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.delete-button').on('click', function() {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().submit();
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success"
                        });
                    }
                });
            })
        })


    </script>
        
           
@endsection
