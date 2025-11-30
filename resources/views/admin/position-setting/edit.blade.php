@extends('admin.template')

@section('title', 'Position Edit')

@section('breadcrumb')
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item "><a href="{{ route('user.position') }}" class="text-decoration-none text-secondary">Config Position</a></li>

        <li class="breadcrumb-item ">Edit</li>
    </ol>
@endsection

@section('content')

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

  <div class="row">
    <div class="col-md-12">
      <div class="card mb-4">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <a href="{{ route('user.position') }}" class="text-decoration-none text-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
          </div>
        </div>
        <div class="card-body p-1 bg-light">
          <div class="form-input">
            <form action="{{ route('user.position.update', $user->id) }}" method="post">
              @csrf
             <div class="mb-3">
                        <label for="position_id" class="form-label">Grup</label>
  
                        <select name="position_id"
                          class="form-control @error('position_id')
                  is-invalid
                @enderror">
                          {{-- <option value="" hidden>-- Pilih --</option> --}}
                          <option value="" >-- Tanpa Position --</option>
                          @foreach ($positions as $item)
                            <option value="{{ $item->id }}" {{ $item->id === old('position_id', $user->position_id) ? 'selected' : '' }}>
                              {{ $item->name }}</option>
                          @endforeach
                        </select>
  
                        @error('position_id')
                          <div class="alert alert-danger mt-2">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
              <div class="text-end">
                <button type="reset" class="btn btn-warning btn-sm ">Reset</button>
                <button type="submit" class="btn btn-primary btn-sm ">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
