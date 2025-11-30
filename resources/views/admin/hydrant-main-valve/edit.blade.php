@extends('admin.template')

@section('title', 'Halaman Kondisi Main Valve Hydrant')

@section('breadcrumb')
    <h1 class="mt-4">Kondisi Main Valve Hydrant</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item "><a href="{{ route('master.index') }}" class="text-decoration-none text-secondary">Master</a></li>
      <li class="breadcrumb-item "><a href="{{ route('hydrant-main-valve.index') }}" class="text-decoration-none text-secondary">Kondisi Main Valve Hydrant</a></li>
      <li class="breadcrumb-item ">Edit</li>
  </ol>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-middle">
              <div class="">
              <a href="{{ route('hydrant-main-valve.index') }}" class="text-decoration-none text-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
              </div>
            </div>
        </div>
        <div class="card-body p-1 bg-light">
          <div class="form-input">
            <form action="{{ route('hydrant-main-valve.update', $hydrantMainValve->id) }}" method="post">
              @csrf
              @method('PUT')

              <div class="mb-3">
                <label for="name" class="form-label">Nama Kondisi Main Valve Hydrant</label>
                <input type="text" name="name" id="name" class="form-control @error('name')
                  is-invalid
                @enderror" autofocus required value="{{ old('name', $hydrantMainValve->name) }}">
  
                @error('name')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="mb-3">
                <label for="name" class="form-label">Deskripsi</label>
                <textarea name="description" id="description" cols="30" rows="5" class="form-control @error('description')
                  is-invalid
                @enderror" >{{ old('description', $hydrantMainValve->description) }}</textarea>

                @error('description')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="text-end">
                <button type="reset" class="btn btn-sm btn-light border"><i class="fas fa-brush text-warning"></i></button>
                <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-floppy-disk"></i></button>
              </div>
            </form>
          </div>
        </div>
    </div>
@endsection
