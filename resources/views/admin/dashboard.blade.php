@extends('layouts.app')

@section('content')
<div class="container">
    <h2>لوحة تحكم الادمن - إدارة الصلاحيات</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.dashboard.update') }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>اسم الصلاحية</th>
                    <th>مفعلة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td>
                    <td>
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                            {{ $permission->is_active ? 'checked' : '' }}>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">تحديث الصلاحيات</button>
    </form>
</div>
@endsection
