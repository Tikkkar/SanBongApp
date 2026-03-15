@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-success"><i class="bi bi-trophy text-warning me-2"></i>Hệ Thống Giải Đấu Bóng Đá</h2>
</div>

<div class="row">
    @forelse($tournaments as $tournament)
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0 position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="card-title fw-bold text-primary mb-0">{{ $tournament->name }}</h4>
                        @if($tournament->status === 'upcoming')
                            <span class="badge bg-secondary p-2">Sắp Diễn Ra</span>
                        @elseif($tournament->status === 'ongoing')
                            <span class="badge bg-success p-2">Đang Diễn Ra</span>
                        @else
                            <span class="badge bg-danger p-2">Đã Kết Thúc</span>
                        @endif
                    </div>
                    
                    <p class="text-muted"><i class="bi bi-calendar-event me-2"></i>{{ $tournament->start_date->format('d/m/Y') }} @if($tournament->end_date) - {{ $tournament->end_date->format('d/m/Y') }} @endif</p>
                    <p class="card-text">{{ $tournament->description ?? 'Giải đấu thường niên dành cho các câu lạc bộ phong trào.' }}</p>
                    
                    <a href="{{ url('/tournaments/'.$tournament->id) }}" class="btn btn-outline-success mt-3 w-100 fw-bold">Xem Chi Tiết Giải Đấu <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-trophy" style="font-size: 4rem; color: #dee2e6;"></i>
            <h4 class="mt-3">Chưa có giải đấu nào được tổ chức.</h4>
        </div>
    @endforelse
</div>
@endsection
