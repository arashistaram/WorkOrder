@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
@endphp

<div class="pagination d-flex justify-content-between align-items-center flex-wrap gap-3">

    {{-- اطلاعات نمایش --}}
    <div class="pagination-info">
        نمایش
        <b>{{ number_format($paginator->firstItem() ?? 0) }}–{{ number_format($paginator->lastItem() ?? 0) }}</b>
        از
        <b>{{ number_format($paginator->total()) }}</b>
        واحد
    </div>

    {{-- دکمه‌های صفحه‌بندی --}}
    @if ($paginator->hasPages())
        <div class="pagination-controls d-flex align-items-center gap-1">

            {{-- دکمه قبلی --}}
            @if ($paginator->onFirstPage())
                <button class="icon-btn icon-btn--bordered icon-btn--sm" disabled aria-label="صفحه قبل">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 6 6 6-6 6"/>
                    </svg>
                </button>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled"
                        class="icon-btn icon-btn--bordered icon-btn--sm" aria-label="صفحه قبل">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 6 6 6-6 6"/>
                    </svg>
                </button>
            @endif

            {{-- شماره صفحات با Ellipsis --}}
            @foreach ($elements as $element)

                {{-- سه نقطه --}}
                @if (is_string($element))
                    <span class="page-ellipsis">{{ $element }}</span>
                @endif

                {{-- آرایه‌ای از لینک‌ها --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button class="page-btn is-active"
                                    wire:key="page-{{ $page }}"
                                    aria-current="page">
                                {{ $page }}
                            </button>
                        @else
                            <button class="page-btn"
                                    wire:key="page-{{ $page }}"
                                    wire:click="gotoPage({{ $page }})">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif

            @endforeach

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled"
                        class="icon-btn icon-btn--bordered icon-btn--sm" aria-label="صفحه بعد">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 6-6 6 6 6"/>
                    </svg>
                </button>
            @else
                <button class="icon-btn icon-btn--bordered icon-btn--sm" disabled aria-label="صفحه بعد">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 6-6 6 6 6"/>
                    </svg>
                </button>
            @endif

        </div>
    @endif
</div>
