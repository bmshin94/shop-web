@extends('layouts.admin')

@section('page_title', '회원 상세')

@push('styles')
<style>
    .member-detail-grid {
        display: grid;
        gap: 24px;
    }

    @media (min-width: 1024px) {
        .member-detail-grid {
            grid-template-columns: 1.2fr 1fr;
            align-items: start;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6 lg:space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.members.index') }}" class="flex items-center justify-center size-10 rounded-xl bg-white border border-gray-200 text-text-muted hover:text-primary hover:border-primary transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h3 class="text-2xl font-extrabold text-text-main">{{ $member->name }}</h3>
                <p class="mt-1 text-[12px] font-bold text-text-muted">{{ $member->email }}</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <x-admin.status-badge type="member" label="회원상태" :value="$member->status" class="px-3 py-1.5 text-[12px]" />
        </div>
    </div>

    <div class="member-detail-grid">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
            <h4 class="text-base font-extrabold text-text-main mb-5">회원 기본 정보</h4>
            <dl class="space-y-4 text-sm">
                <div>
                    <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">회원 ID</dt>
                    <dd class="mt-1 font-bold text-text-main">#{{ number_format($member->id) }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">회원명</dt>
                    <dd class="mt-1 font-bold text-text-main">{{ $member->name }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">이메일</dt>
                    <dd class="mt-1 font-bold text-text-main">{{ $member->email }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">이메일 인증</dt>
                    <dd class="mt-1 font-bold text-text-main">{{ $member->email_verified_at ? '인증 완료' : '미인증' }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">연락처</dt>
                    <dd class="mt-1 font-bold text-text-main">{{ $member->phone ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">현재 상태</dt>
                    <dd class="mt-1"><x-admin.status-badge type="member" :value="$member->status" /></dd>
                </div>
                <div>
                    <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">가입일</dt>
                    <dd class="mt-1 font-bold text-text-main">{{ optional($member->created_at)->format('Y.m.d H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">최근 로그인</dt>
                    <dd class="mt-1 font-bold text-text-main">{{ optional($member->last_login_at)->format('Y.m.d H:i') ?: '-' }}</dd>
                </div>
            </dl>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <h4 class="text-base font-extrabold text-text-main mb-5">회원 정보 수정</h4>
                <form action="{{ route('admin.members.update', $member) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">회원명</label>
                        <input type="text" name="name" value="{{ old('name', $member->name) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('name')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">이메일</label>
                        <input type="email" name="email" value="{{ old('email', $member->email) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('email')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">연락처</label>
                        <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" placeholder="010-1234-5678" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('phone')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">회원상태</label>
                        <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none">
                            @foreach($statusOptions as $status)
                                <option value="{{ $status }}" {{ old('status', $member->status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($errors->any())
                        <div class="rounded-2xl bg-red-50 border border-red-100 px-4 py-3 text-[12px] font-bold text-red-600">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <button type="submit" class="w-full px-5 py-4 bg-primary text-white rounded-2xl text-sm font-extrabold hover:bg-red-600 transition-colors shadow-lg shadow-primary/20">
                        회원 정보 저장
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-3xl border border-red-100 shadow-sm p-6">
                <h4 class="text-base font-extrabold text-text-main mb-4">회원 삭제</h4>
                <p class="text-[12px] font-bold text-text-muted leading-relaxed">
                    회원을 삭제하면 화면 목록에서 숨겨지며(soft delete), 회원 데이터는 DB에 보관됩니다.
                </p>
                <form
                    action="{{ route('admin.members.destroy', $member) }}"
                    method="POST"
                    class="mt-5 js-confirm-submit"
                    data-confirm-title="회원 삭제"
                    data-confirm-message="이 회원을 soft delete 처리하시겠습니까? 목록에서 숨김 처리됩니다."
                    data-confirm-text="삭제 처리">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-5 py-4 bg-red-50 text-red-600 border border-red-200 rounded-2xl text-sm font-extrabold hover:bg-red-100 transition-colors">
                        회원 삭제
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
