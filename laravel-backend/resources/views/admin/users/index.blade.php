
@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h3 align='center'>All Users</h3>
   <div class="d-flex justify-content-between align-items-center mb-3">
    <!-- Left side: Add Category button -->
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">+ Add Users</a>

    <!-- Right side: Search form -->
    <form action="{{ route('users.index') }}" method="GET" class="d-flex">
        <input type="text" name="search" value="{{ $search }}" 
               class="form-control form-control-sm me-2" 
               style="width: 150px;" placeholder="Search...">
              
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
    </form>
</div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phoneno</th>
                <th>Gender</th>
                <th>Email</th>
                <th>User Role</th>
                <th>Change Password</th>
                <th>Action</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                   <td>{{ $user->firstname }}</td>
                    <td>{{ $user->lastname }}</td>
                    <td>{{ $user->phoneno }}</td>
                    <td>{{ $user->gender }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                    <select name="role_id" class="form-control form-control-sm" style="height:25px; padding:2px 6px;" data-user-id="{{ $user->id }}">
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>
                    {{ $role->name }}
                    </option>
                    @endforeach
                    </select>
                </td>
        <td>
        <!-- Change Password Form -->
        <form action="{{ route('users.changePassword', $user->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')
            <div class="input-group input-group-sm ">
            <input type="password" name="password" class="form-control" style="height:25px;" placeholder="New Password" >
             <div class="input-group-append">
            <button type="submit" class="btn btn-warning btn-sm py-0 px-2" >Change</button>
</div></div>
        </form>
    </td>
                  <td><a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm py-0 px-2">Edit</a>

                    </td>
                    <td>
                         <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm py-0 px-2"
                        onclick="return confirm('Are you sure you want to delete this user?')">
                        Delete
                    </button>
                </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No Users Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <script>
document.querySelectorAll('.change-role').forEach(function(select) {
    select.addEventListener('change', function() {
        let userId = this.dataset.userId;
        let roleId = this.value;

        fetch(`/admin/users/${userId}/change-role`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ role_id: roleId })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message); // show success
        })
        .catch(err => console.error(err));
    });
});
</script>

  <div class="d-flex justify-content-center mt-3">
    {{ $users->links() }}
    </div>

</div>
@endsection
