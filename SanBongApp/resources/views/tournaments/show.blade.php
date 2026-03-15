@extends('layouts.app')

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/tournaments') }}" class="text-success text-decoration-none">Giải đấu</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $tournament->name }}</li>
  </ol>
</nav>

<div class="row mt-4">
    <div class="col-12 text-center mb-5">
        <h2 class="display-5 fw-bold text-primary">{{ $tournament->name }}</h2>
        <p class="text-muted fs-5"><i class="bi bi-calendar-check me-2"></i>{{ $tournament->start_date->format('d/m/Y') }} - {{ $tournament->end_date ? $tournament->end_date->format('d/m/Y') : 'Chưa xác định' }}</p>
        <p class="lead">{{ $tournament->description }}</p>
        
        @auth
            @if(Auth::user()->role === 'admin')
                <div class="mt-3 d-flex justify-content-center gap-2 align-items-center">
                    <a href="{{ route('admin.teams.create', $tournament->id) }}" class="btn btn-success"><i class="bi bi-person-plus"></i> Thêm Đội Bóng Vào Giải</a>
                    <a href="{{ url('/admin/tournaments/'.$tournament->id.'/matches/create') }}" class="btn btn-warning"><i class="bi bi-calendar-plus"></i> Xếp Lịch Bằng Tay</a>
                    
                    {{-- <form action="{{ route('admin.matches.generate', $tournament->id) }}" method="POST" class="d-inline d-flex gap-2" onsubmit="return confirm('Hệ thống sẽ bốc 2 đội chưa bị loại (nếu là vòng bảng thì sẽ bốc cùng bảng). Tiếp tục?');">
                        @csrf
                        <select name="round_name" class="form-select form-select-sm" style="width: 180px;" required>
                            <option value="" disabled selected>-- Tên Vòng --</option>
                            <option value="Vòng Bảng">Vòng Bảng</option>
                            <option value="Vòng 1/8">Vòng 1/8</option>
                            <option value="Tứ Kết">Tứ Kết</option>
                            <option value="Bán Kết">Bán Kết</option>
                            <option value="Tranh Hạng 3">Tranh Hạng 3</option>
                            <option value="Chung Kết">Chung Kết</option>
                        </select>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-shuffle"></i> Tự Động Bốc 1 Trận</button>
                    </form> --}}
                </div>
            @endif
        @endauth
    </div>
</div>

@auth
    @if(Auth::user()->role === 'admin' && $pendingTeams->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-dark fw-bold">
                    <i class="bi bi-shield-exclamation me-2"></i>Yêu Cầu Tham Gia Giải Chờ Duyệt ({{ $pendingTeams->count() }} đội)
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Đội Bóng</th>
                                    <th>SĐT Đại Diện</th>
                                    <th>Trạng Thái</th>
                                    <th class="text-end pe-3">Xử lý</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingTeams as $pendingTeam)
                                <tr>
                                    <td class="ps-3 fw-bold text-primary">
                                        @if($pendingTeam->logo)
                                            <img src="{{ $pendingTeam->logo }}" width="24" height="24" class="rounded-circle me-1" alt="Logo">
                                        @endif
                                        {{ $pendingTeam->name }}
                                    </td>
                                    <td>{{ $pendingTeam->contact_phone }}</td>
                                    <td><span class="badge bg-secondary">Chờ duyệt</span></td>
                                    <td class="text-end pe-3">
                                        <form action="{{ url('/admin/teams/'.$pendingTeam->id.'/approve') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Duyệt</button>
                                        </form>
                                        <form action="{{ url('/admin/teams/'.$pendingTeam->id.'/reject') }}" method="POST" class="d-inline ms-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn từ chối đội bóng này?')">Từ chối</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endauth

<div class="row">
    <!-- Cột Bảng Xếp Hạng -->
    <div class="col-md-8">
        @forelse($leaderboard as $groupName => $groupStats)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-ol me-2"></i>Bảng Xếp Hạng - {{ $groupName }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th class="text-start">Đội Bóng</th>
                                <th title="Trận đã đá">Trận</th>
                                <th title="Thắng">T</th>
                                <th title="Hòa">H</th>
                                <th title="Thua">B</th>
                                <th title="Bàn Thắng">BT</th>
                                <th title="Bàn Thua">BB</th>
                                <th title="Hiệu số">HS</th>
                                <th class="text-danger">Điểm</th>
                                @auth
                                    @if(Auth::user()->role === 'admin')
                                    <th>Cập nhật</th>
                                    @endif
                                @endauth
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groupStats as $index => $stat)
                                <tr>
                                    <td class="fw-bold">{{ $index + 1 }}</td>
                                    <td class="text-start">
                                        @if($stat->team->logo)
                                            <img src="{{ $stat->team->logo }}" width="24" height="24" class="rounded-circle me-1" alt="Logo">
                                        @endif
                                        <span class="fw-bold {{ $stat->team->is_eliminated ? 'text-decoration-line-through text-muted' : '' }}">{{ $stat->team->name }}</span>
                                        @if($stat->team->is_eliminated)
                                            <span class="badge bg-danger ms-1">Bị loại</span>
                                        @endif
                                    </td>
                                    <td>{{ $stat->played }}</td>
                                    <td>{{ $stat->won }}</td>
                                    <td>{{ $stat->drawn }}</td>
                                    <td>{{ $stat->lost }}</td>
                                    <td>{{ $stat->gf }}</td>
                                    <td>{{ $stat->ga }}</td>
                                    <td>{{ $stat->gd > 0 ? '+'.$stat->gd : $stat->gd }}</td>
                                    <td class="fw-bold text-danger fs-5">{{ $stat->points }}</td>
                                    @auth
                                        @if(Auth::user()->role === 'admin')
                                        <td>
                                            @if(!$stat->team->is_eliminated)
                                                <form action="{{ route('admin.teams.eliminate', $stat->team->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0" onclick="return confirm('Loại đội bóng này khỏi giải?')">Loại</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.teams.restore', $stat->team->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success py-0" onclick="return confirm('Khôi phục đội bóng này?')">Khôi phục</button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('admin.teams.assign-group', $stat->team->id) }}" method="POST" class="d-inline ms-1" title="Nhập Tên Bảng (A, B...) và Enter để lưu">
                                                @csrf
                                                <input type="text" name="group_name" value="{{ $stat->team->group_name }}" class="form-control form-control-sm d-inline-block text-center border-primary" style="width: 60px; padding: 0 0.2rem;" placeholder="Bảng" onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        @endif
                                    @endauth
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">Chưa có đội bóng nào trong bảng này.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($loop->last && $tournament->status === 'upcoming')
                <div class="card-footer bg-white text-center">
                    <a href="{{ url('/tournaments/'.$tournament->id.'/teams/create') }}" class="btn btn-outline-success fw-bold w-50">Đăng Ký Đội Bóng Bắt Đầu Tham Gia Giải</a>
                </div>
            @endif
        </div>
        @empty
            <div class="alert alert-info text-center">Chưa có đội bóng hoặc BXH chưa được tính toán.</div>
        @endforelse
    </div>

    <!-- Cột Lịch Thi Đấu -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Lịch Thi Đấu & Kết Quả</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($tournament->matches->sortByDesc('match_time') as $match)
                        <li class="list-group-item p-3">
                            <div class="d-flex justify-content-between text-muted small mb-2">
                                <span><i class="bi bi-clock"></i> {{ $match->match_time->format('H:i d/m/Y') }}</span>
                                @if($match->status == 'finished')
                                    <span class="badge bg-success">Đã hoàn thành</span>
                                @elseif($match->status == 'playing')
                                    <span class="badge bg-warning text-dark">Đang đá</span>
                                @else
                                    <span class="badge bg-secondary">Sắp tới</span>
                                @endif
                            </div>
                            
                            @if($match->round_name)
                                <div class="text-center mb-2"><span class="badge bg-info text-dark">{{ $match->round_name }}</span></div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-end fw-bold w-40 text-truncate" title="{{ $match->homeTeam->name }}">{{ $match->homeTeam->name }}</div>
                                <div class="w-20 text-center px-2">
                                    <span class="{{ $match->status === 'completed' ? 'bg-dark text-white' : 'bg-light border text-dark' }} rounded px-2 py-1 fs-5 fw-bold d-inline-block" style="min-width: 60px;">
                                        {{ $match->home_score }} - {{ $match->away_score }}
                                    </span>
                                </div>
                                <div class="text-start fw-bold w-40 text-truncate" title="{{ $match->awayTeam->name }}">{{ $match->awayTeam->name }}</div>
                            </div>

                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <div class="text-center mt-3 border-top pt-2">
                                        <a href="{{ url('/admin/matches/'.$match->id.'/edit') }}" class="btn btn-sm btn-outline-primary">Cập nhật kết quả</a>
                                    </div>
                                @endif
                            @endauth
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-4">Chưa có lịch thi đấu.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
