@extends('admin.template')

@section('title', 'Edit Role')

@section('breadcrumb')
    <h1 class="mt-4">Role</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item" class="text-decoration-none"><a href="{{ route('role.index') }}"
                class="text-decoration-none text-secondary">Role</a></li>
        <li class="breadcrumb-item fw-normal text-dark">Edit</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body p-1 bg-light">
                    <div class="header-content pt-1 ps-3 pe-3">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('role.index') }}" class="btn btn-sm bg-white btn-custom-hover"
                                style="border: 1px solid rgba(0,0,0,0.15)"><i class="fa-solid fa-arrow-left"></i></a>


                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="form-input">
                            <form action="{{ route('role.update', $role) }}" method="post">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Role</label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name')
                    is-invalid
                  @enderror"
                                        autofocus value="{{ old('name', $role->name) }}">

                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                 
                                </div>



                                <div class="mb-3">
                                    <label for="guard_name">Guard Name</label>
                                    <input type="text" name="guard_name" id="guard_name"
                                        class="form-control @error('guard_name') is-invalid @enderror"
                                        value="{{ old('guard_name', $role->guard_name) }}" disabled>
                                    @error('guard_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Default: web</small>
                                </div>

                                <div class="text-end">
                                    <button type="reset" class="btn btn-sm btn-warning"><i
                                            class="fa-solid fa-brush"></i></button>
                                    <button type="submit" class="btn btn-sm btn-primary"><i
                                            class="fa-solid fa-floppy-disk"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
