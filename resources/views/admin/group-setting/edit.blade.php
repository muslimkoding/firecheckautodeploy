@extends('admin.template')

@section('title', 'Group Edit')

@section('breadcrumb')
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item "><a href="{{ route('user.group') }}" class="text-decoration-none text-secondary">Config Group</a></li>
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
            <a href="{{ route('user.group') }}" class="text-decoration-none text-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
          </div>
        </div>
        <div class="card-body p-1 bg-light">
          <div class="form-input">
            <form action="{{ route('user.group.update', $user->id) }}" method="post">
              @csrf
             <div class="mb-3">
                        <label for="group_id" class="form-label">Grup</label>
  
                        <select name="group_id"
                          class="form-control @error('group_id')
                  is-invalid
                @enderror">
                          {{-- <option value="" hidden>-- Pilih --</option> --}}
                          <option value="" >-- Tanpa Group --</option>
                          @foreach ($groups as $item)
                            <option value="{{ $item->id }}" {{ $item->id === old('group_id', $user->group_id) ? 'selected' : '' }}>
                              {{ $item->name }}</option>
                          @endforeach
                        </select>
  
                        @error('group_id')
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
          {{-- <form action="{{ route('user.group.update', $user->id) }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="group_id" class="form-label">Grup</label>
                <div class="input-group">
                    <select name="group_id" class="form-control @error('group_id') is-invalid @enderror">
                        <option value="" {{ old('group_id', $user->group_id) == '' ? 'selected' : '' }}>-- Tanpa Group --</option>
                        @foreach ($groups as $item)
                            <option value="{{ $item->id }}" {{ $item->id == old('group_id', $user->group_id) ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-danger" onclick="clearGroup()">Hapus Group</button>
                </div>
                @error('group_id')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="text-end">
                <button type="reset" class="btn btn-warning btn-sm">Reset Form</button>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </form>
        
        <script>
        function clearGroup() {
            document.querySelector('select[name="group_id"]').value = '';
        }
        </script> --}}
        </div>
      </div>
    </div>
  </div>
@endsection
