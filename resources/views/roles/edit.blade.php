@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Role: {{ $role->name }}</h2>

    <form method="POST" action="{{ route('roles.update', $role->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Role Name</label>
            <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Permissions</label>
            <div class="form-check">
                @foreach($permissions as $permission)
                    <div>
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                            id="perm_{{ $permission->id }}"
                            {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                            {{ $permission->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
