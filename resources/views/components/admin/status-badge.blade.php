@props([
    'type' => 'order',
    'value',
    'label' => null,
])

@php
    $styles = [
        'order' => [
            '주문접수' => 'bg-blue-50 text-blue-600',
            '상품준비중' => 'bg-amber-50 text-amber-600',
            '배송중' => 'bg-indigo-50 text-indigo-600',
            '배송완료' => 'bg-green-50 text-green-600',
            '취소완료' => 'bg-red-50 text-red-600',
        ],
        'payment' => [
            '결제대기' => 'bg-gray-100 text-gray-500',
            '결제완료' => 'bg-green-50 text-green-600',
            '환불완료' => 'bg-purple-50 text-purple-600',
            '취소완료' => 'bg-red-50 text-red-600',
        ],
        'shipping' => [
            '배송대기' => 'bg-gray-100 text-gray-500',
            '출고완료' => 'bg-sky-50 text-sky-600',
            '배송중' => 'bg-indigo-50 text-indigo-600',
            '배송완료' => 'bg-green-50 text-green-600',
        ],
        'member' => [
            '활성' => 'bg-green-50 text-green-600',
            '휴면' => 'bg-amber-50 text-amber-600',
            '정지' => 'bg-red-50 text-red-600',
        ],
        'operator' => [
            '활성' => 'bg-green-50 text-green-600',
            '휴면' => 'bg-amber-50 text-amber-600',
            '정지' => 'bg-red-50 text-red-600',
        ],
        'event' => [
            '진행예정' => 'bg-slate-100 text-slate-600',
            '진행중' => 'bg-emerald-50 text-emerald-600',
            '종료' => 'bg-gray-100 text-gray-500',
            '비노출' => 'bg-rose-50 text-rose-600',
        ],
        'exhibition' => [
            '진행예정' => 'bg-slate-100 text-slate-600',
            '진행중' => 'bg-cyan-50 text-cyan-600',
            '종료' => 'bg-gray-100 text-gray-500',
            '비노출' => 'bg-rose-50 text-rose-600',
        ],
    ];

    $badgeClass = $styles[$type][$value] ?? 'bg-gray-100 text-gray-500';
@endphp

<span {{ $attributes->class("inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-bold {$badgeClass}") }}>
    @if($label)
        <span class="opacity-70">{{ $label }}</span>
    @endif
    <span>{{ $value }}</span>
</span>
