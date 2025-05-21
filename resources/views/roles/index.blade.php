@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Role List</h2>
    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Create New Role</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Permissions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>
                    @foreach($role->permissions as $perm)
                        <span class="badge bg-secondary">{{ $perm->name }}</span>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <!-- Tambahkan tombol delete jika diperlukan -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
