@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href='{{ url("/assignroles") }}'
                    >Assign Roles and Permission</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href='{{ url("/roles") }}'
                       >Create Roles and permission</a>
                    {{-- <a class="nav-link" href="{{ route('post.create') }}">Create</a> --}}
                </li>
                <li class="nav-item">
                    <a class="nav-link" href='{{route('post.index') }}'
                       >Post</a>
                    {{-- <a class="nav-link" href="{{ route('post.create') }}">Create</a> --}}
                </li>
    
            </ul>
        </div>
    </nav>
</div>
    <div class="container">
        <h2>Assign Roles</h2>
        <form action="" method="post" enctype="multipart/form-data" class="assignrole">
            @csrf
            <div class="form-group">
                <label for="">Users</label>
                <select class="form-control" name="userid" id="">
                    @foreach ($users as $user)
                        <option value={{ $user->id }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for=""></label>
                <select class="form-control" name="roles[]" id="" multiple>
                    @foreach ($roles as $role)
                        <option value={{ $role->id }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Assign Roles</button>

        </form>
    </div>



    <div class="container">

        <div class="assign-table mt-3 p-4">
            <h4>Users with roles</h4>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">S.N</th>
                        <th scope="col">Users</th>
                        <th scope="col">Roles</th>
                        <th scope="col">Action</th>
    
                    </tr>
                </thead>
                <tbody class="assign-table-body">
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($userswithroles as $user)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $user->name }}</td>
                            <td>
                                @if (!is_null($user->roles))
                                    @foreach ($user->roles as $role)
                                        <span>{{ $role->name }},</span>
                                    @endforeach
                                @endif
                            </td>
    
    
                            <td>
                                <a name="edit" id="" class="btn btn-primary update-btn" role="button"
                                    data-bs-toggle="modal" data-bs-target="#updateModal">Edit</a>
                                <button type="button" class="btn btn-danger delete-button" id="">Delete</button>
    
    
                            </td>
    
    
                        </tr>
                    @endforeach
    
    
                </tbody>
            </table>
        </div>
    </div>


    <script>
        $('.assignrole').submit(function(e) {
            e.preventDefault();
            const formdata = new FormData(this);
            $.ajax({
                type: "POST",
                url: "{{ route('assignrole.create') }}",
                data: formdata,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response)
                    let i=1;
                    let assignroledata=response.data.map((data)=>{
                                return `<tr>
                                    <td>${i++}</td>
                                    <td>${data.name}</td>
                                    <td>${data.roles.map((role)=>{
                                        return `<span>${role.name},</span>`
                                    }).join('')}</td>
                                    <td><a name="edit" class="btn btn-primary update-btn"
                                        role="button">Edit</a>
                                    <button type="button" class="btn btn-danger delete-button"
                                        >Delete</button></td>
                                    </tr>`

                            })
                            $('.assign-table-body').html(assignroledata);

                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success"
                        })
                       
                },
                error: function(xhr, error) {
                    Swal.fire({
                        title: "Failed!",
                        text: "Assign role failed",
                        icon: "error"
                    });
                }
            });

        });
    </script>
@endsection
