@extends('layouts.app');
@section('content')
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

        </ul>
    </div>
</nav>
    <div class="container">


       
        <h3>Create Roles and permission</h3>

        <div class="roles border border-dark p-5" >
            <h4>Create Roles</h4>
            <form class="role-form" action="" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="">Insert role</label>
                    <input type="text" class="form-control" name="role" id="role" aria-describedby="helpId"
                        placeholder="Enter The Roles">
                </div>
                <div class="form-group">
                    <label for="permissions">Permissions</label>
                    <select class="form-control" name="permissions[]" id="permissions" multiple>
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                        @endforeach
                    </select>
                </div>
                

                <button type="submit" name="submit" id="submit" class="btn btn-primary">
                    Submit</button>
            </form>

            <div class="rolestable">
                <h4>Roles table</h4>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">S.N</th>
                            <th scope="col">Roles</th>
                            <th scope="col">Permission</th>
                            <th scope="col">Action</th>

                        </tr>
                    </thead>
                    <tbody class="role-table-body">
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{ $role->name }}</td>
                                <td>@foreach ($role->permissions as $permission )
                                    <span>{{ $permission->name }}, </span>
                                 @endforeach</td>
                                <td>
                                    <a name="edit" class="btn btn-primary update-btn"
                                        role="button">Edit</a>
                                    <button type="button" class="btn btn-danger delete-button"
                                        >Delete</button>


                                </td>


                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>

            {{-- permission part --}}
        <div class="permission border border-dark p-5 m-4">
            <h4>Create Permissions</h4>
            <form class="permission-form" action="" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for=""></label>
                    <input type="text" class="form-control" name="permission" id="permission" aria-describedby="helpId"
                        placeholder="Enter Permission">
                </div>
                <button type="submit" name="submit" id="submit" class="btn btn-primary">
                    Submit</button>
            </form>

            <div class="permissionstable">
                <h4>RPermission table</h4>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">S.N</th>
                            <th scope="col">Roles</th>
           
                            <th scope="col">Action</th>

                        </tr>
                    </thead>
                    <tbody class="permission-table-body">
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    <a name="edit" class="btn btn-primary update-btn"
                                        role="button">Edit</a>
                                    <button type="button" class="btn btn-danger delete-button"
                                        >Delete</button>


                                </td>


                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>




        <script>

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('.role-form').submit(function(e) {
                    console.log("submitform")
                    e.preventDefault();

                    let formdata = new FormData(this);

                    $.ajax({
                        type: "POST",
                        url: '{{ route("role.create") }}',
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            let i=0;
                            console.log(response)
                            let roledata=response.data.map((data)=>{
                                return `<tr>
                                    <td>${i++}</td>
                                    <td>${data.name}</td>
                                    <td>${data.permissions.map((permission)=>{
                                        return `<span>${permission.name},</span>`
                                    }).join('')}</td>
                                    <td><a name="edit" class="btn btn-primary update-btn"
                                        role="button">Edit</a>
                                    <button type="button" class="btn btn-danger delete-button"
                                        >Delete</button></td>
                                    </tr>`

                            })
                            $('.role-table-body').html(roledata);
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            alert('An error occurred while creating the role.');
                        }
                    });
                });


                //permission
                $('.permission-form').submit(function(e) {

                    e.preventDefault();

                    let formdata = new FormData(this);

                    $.ajax({
                        type: "POST",
                        url: '{{ route("permission.create") }}',
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            let j=1;
                            console.log(response)
                            let roledata=response.data.map((data)=>{
                                return `<tr>
                                    <td>${j++}</td>
                                    <td>${data.name}</td>
                                    <td><a name="edit" class="btn btn-primary update-btn"
                                        role="button">Edit</a>
                                    <button type="button" class="btn btn-danger delete-button"
                                        >Delete</button></td>
                                    </tr>`

                            })
                            $('.permission-table-body').html(roledata);
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            alert('An error occurred while creating the role.');
                        }
                    });
                });

        </script>
    @endsection
