@if ($paginator->hasPages())
    <ul class="tf-pagination-wrap tf-pagination-list">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled" aria-disabled="true">
                <span class="pagination-link">
                    <i class="icon icon-arrow-left"></i>
                </span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link animate-hover-btn" rel="prev">
                    <i class="icon icon-arrow-left"></i>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled" aria-disabled="true">
                    <span class="pagination-link">{{ $element }}</span>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active" aria-current="page">
                            <span class="pagination-link">{{ $page }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}" class="pagination-link animate-hover-btn">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link animate-hover-btn" rel="next">
                    <i class="icon icon-arrow-right"></i>
                </a>
            </li>
        @else
            <li class="disabled" aria-disabled="true">
                <span class="pagination-link">
                    <i class="icon icon-arrow-right"></i>
                </span>
            </li>
        @endif
    </ul>

    <style>
    .tf-pagination-wrap {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
        list-style: none;
        margin: 0;
        padding: 0;
        flex-wrap: wrap;
    }

    .tf-pagination-wrap li {
        display: flex;
    }

    .pagination-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 45px;
        height: 45px;
        padding: 0 15px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        color: #6b7280;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        overflow: hidden;
    }

    .pagination-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s ease;
    }

    .pagination-link:hover::before {
        left: 100%;
    }

    .pagination-link:hover {
        background: #3498db;
        color: #fff;
        border-color: #3498db;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }

    .tf-pagination-wrap .active .pagination-link {
        background: #3498db;
        color: #fff;
        border-color: #3498db;
        box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
        transform: translateY(-1px);
    }

    .tf-pagination-wrap .disabled .pagination-link {
        background: #f8f9fa;
        color: #adb5bd;
        border-color: #e9ecef;
        cursor: not-allowed;
    }

    .tf-pagination-wrap .disabled .pagination-link:hover {
        background: #f8f9fa;
        color: #adb5bd;
        border-color: #e9ecef;
        transform: none;
        box-shadow: none;
    }

    .pagination-link i {
        font-size: 14px;
    }

    @media (max-width: 576px) {
        .pagination-link {
            min-width: 40px;
            height: 40px;
            padding: 0 10px;
            font-size: 0.8rem;
        }
        
        .tf-pagination-wrap {
            gap: 3px;
        }
    }

    /* Animation for hover effect */
    .animate-hover-btn {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .animate-hover-btn:hover {
        transform: translateY(-2px);
    }
    </style>
@endif