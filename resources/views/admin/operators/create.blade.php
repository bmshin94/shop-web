@extends('layouts.admin')

@section('page_title', '운영자 등록')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.operators.index') }}" class="flex items-center justify-center size-10 rounded-xl bg-white border border-gray-200 text-text-muted hover:text-primary hover:border-primary transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h3 class="text-2xl font-extrabold text-text-main">운영자 등록</h3>
                <p class="mt-1 text-[12px] font-bold text-text-muted">신규 운영자 계정을 등록하고 상태를 설정합니다.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.operators.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <label class="text-sm font-bold text-text-main">운영자명</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                @error('name')
                    <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-text-main">이메일</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                @error('email')
                    <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-text-main">연락처</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="010-1234-5678" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                @error('phone')
                    <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-text-main">운영자 상태</label>
                <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none">
                    @foreach($statusOptions as $status)
                        <option value="{{ $status }}" {{ old('status', $statusOptions[0] ?? '') === $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                @error('status')
                    <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @include('admin.operators._menu-permissions', [
                'menuDefinitions' => $menuDefinitions,
                'selectedMenuPermissions' => $selectedMenuPermissions,
            ])

            <div class="space-y-2">
                <label class="text-sm font-bold text-text-main">비밀번호</label>
                <input type="password" name="password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                @error('password')
                    <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-text-main">비밀번호 확인</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                @error('password_confirmation')
                    <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if($errors->any())
                <div class="rounded-2xl bg-red-50 border border-red-100 px-4 py-3 text-[12px] font-bold text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit" class="w-full px-5 py-4 bg-primary text-white rounded-2xl text-sm font-extrabold hover:bg-red-600 transition-colors shadow-lg shadow-primary/20">
                운영자 등록
            </button>
        </form>
    </div>
</div>
@endsection
