@extends('layouts.app')

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/tournaments') }}" class="text-success text-decoration-none">Giải đấu</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/tournaments/'.$tournament->id) }}" class="text-success text-decoration-none">{{ $tournament->name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Đăng ký Cúp</li>
  </ol>
</nav>

<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi bi-shield-plus me-2"></i>Đăng Ký Đội Bóng</h4>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info border-0 rounded">
                    Bạn đang đăng ký đội bóng để tham gia giải <strong>{{ $tournament->name }}</strong>.
                </div>

                <form method="POST" action="{{ url('/tournaments/'.$tournament->id.'/teams') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Tên Đội Bóng (FC)</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="VD: FC Chân Gỗ" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="contact_phone" class="form-label fw-bold">Điện thoại Đội Trưởng</label>
                        <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}" required>
                        @error('contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="logo" class="form-label fw-bold">Logo Đội Bóng (Tùy chọn)</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mt-4">
                        @auth
                            <button type="submit" class="btn btn-success btn-lg">Xác Nhận Đăng Ký</button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg">Bạn cần đăng nhập để Đăng ký!</a>
                        @endauth
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
