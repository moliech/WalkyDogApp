@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navegación de Paginación" class="flex items-center justify-between">
        <!-- Vista Móvil -->
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 text-xs font-bold text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1.5 text-xs font-bold text-white rounded-lg no-underline transition" style="background-color: #242E3D !important;">
                    Anterior
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1.5 text-xs font-bold text-white rounded-lg no-underline transition" style="background-color: #242E3D !important;">
                    Siguiente
                </a>
            @else
                <span class="px-3 py-1.5 text-xs font-bold text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    Siguiente
                </span>
            @endif
        </div>

        <!-- Vista Escritorio -->
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 mb-0">
                    Mostrando
                    <span class="font-black text-brand-dark">{{ $paginator->firstItem() ?? 0 }}</span>
                    a
                    <span class="font-black text-brand-dark">{{ $paginator->lastItem() ?? 0 }}</span>
                    de
                    <span class="font-black text-brand-dark">{{ $paginator->total() }}</span>
                    resultados
                </p>
            </div>

            <div>
                <div class="inline-flex rounded-lg shadow-sm text-white overflow-hidden items-center" style="background-color: #242E3D !important; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                    {{-- Anterior --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Anterior">
                            <span class="px-3.5 py-2 text-xs font-bold text-slate-400 opacity-40 cursor-not-allowed inline-flex items-center" style="border-right: 1px solid rgba(255, 255, 255, 0.15) !important;">
                                &lt;
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3.5 py-2 text-xs font-bold text-slate-200 hover:text-white transition no-underline inline-flex items-center" style="border-right: 1px solid rgba(255, 255, 255, 0.15) !important;" aria-label="Anterior">
                            &lt;
                        </a>
                    @endif

                    {{-- Elementos de Paginación --}}
                    @foreach ($elements as $element)
                        {{-- Separador "..." --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="px-3.5 py-2 text-xs font-bold text-slate-400" style="border-right: 1px solid rgba(255, 255, 255, 0.15) !important;">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array de Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="px-4 py-2 text-xs font-black text-white inline-block" style="background-color: #121822 !important; border-right: 1px solid rgba(255, 255, 255, 0.15) !important;">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="px-4 py-2 text-xs font-bold text-slate-300 hover:text-white transition no-underline inline-block" style="border-right: 1px solid rgba(255, 255, 255, 0.15) !important;" aria-label="Página {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Siguiente --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3.5 py-2 text-xs font-bold text-slate-300 hover:text-white transition no-underline inline-flex items-center" aria-label="Siguiente">
                            &gt;
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Siguiente">
                            <span class="px-3.5 py-2 text-xs font-bold text-slate-500 opacity-40 cursor-not-allowed inline-flex items-center">
                                &gt;
                            </span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </nav>
@endif
