@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary"><i class="bi bi-people-fill me-2"></i>Quản Lý Người Dùng</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Trở Về Dashboard</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th class="text-start ps-4">Họ Tên</th>
                        <th>Email</th>
                        <th>Vai Trò</th>
                        <th>Ngày Tham Gia</th>
                        <th class="text-end pe-4">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="text-start ps-4 fw-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Quản trị viên</span>
                                @else
                                    <span class="badge bg-secondary">Khách hàng</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end pe-4">
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.toggle-role', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-outline-info me-1" title="Đổi quyền">
                                            <i class="bi bi-arrow-repeat"></i> Quyền
                                        </button>
                                    </form>
                                @endif

                                @if($user->role !== 'admin')
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản này? Hành động này không thể hoàn tác.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Xóa</button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled>Không thể xóa</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Chưa có người dùng nào đăng ký.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white pt-3 pb-1">
        {{ $users->links() }}
    </div>
</div>
@endsection
