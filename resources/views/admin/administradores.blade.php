@extends('layouts.app')
@section('title', 'Auditoría de Administradores')

@section('content')
<div class="py-6 mb-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-3xl font-black text-brand-dark tracking-tight">Auditoría de Administradores</h2>
        <p class="text-gray-400 font-semibold mt-1">Consulta y gestiona las cuentas administrativas registradas en el sistema</p>
    </div>
    <span class="text-xs font-extrabold px-3 py-1.5 rounded-full bg-brand-primary/10 text-brand-primary uppercase tracking-wider">
        Rol: Administrador
    </span>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
        </svg>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

<div class="flex gap-2 border-b border-gray-100 mb-6">
    <a href="{{ route('admin.paseadores') }}" class="py-3 px-4 text-sm font-bold text-gray-400 hover:text-brand-primary no-underline transition flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
        </svg>
        Paseadores
    </a>
    <a href="{{ route('admin.usuarios') }}" class="py-3 px-4 text-sm font-bold text-gray-400 hover:text-brand-primary no-underline transition flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
        </svg>
        Clientes
    </a>
    <a href="{{ route('admin.administradores') }}" class="py-3 px-4 text-sm font-extrabold text-brand-primary border-b-2 border-brand-primary no-underline transition flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
        Administradores ({{ $administradores->count() }})
    </a>
</div>

<div class="space-y-6">
    <div>
        <h4 class="text-lg font-black text-brand-dark mb-4 flex items-center gap-2">
            <span>Administradores del Sistema</span>
        </h4>
        
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-gray-100">
                            <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Administrador</th>
                            <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Teléfono</th>
                            <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Dirección</th>
                            <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs">Registro</th>
                            <th class="p-4 font-bold text-gray-400 uppercase tracking-wider text-xs text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($administradores as $admin)
                            <tr class="border-b border-gray-50 hover:bg-slate-50/30 transition">
                                <td class="p-4">
                                    <span class="font-extrabold text-brand-dark block">{{ $admin->nombres }} {{ $admin->apellidos }}</span>
                                    <span class="text-xs text-gray-400 font-semibold">{{ $admin->email }}</span>
                                </td>
                                <td class="p-4 font-bold text-gray-500">{{ $admin->telefono ?? 'No registrado' }}</td>
                                <td class="p-4 text-gray-500 font-semibold">{{ $admin->direccion }}</td>
                                <td class="p-4 text-xs font-bold text-gray-400">
                                    {{ \Carbon\Carbon::parse($admin->created_at)->format('d/m/Y g:i A') }}
                                </td>
                                <td class="p-4 text-right">
                                    @if($admin->id !== auth()->id())
                                        <button onclick="openEditRoleModal('{{ $admin->id }}', '{{ $admin->nombres }} {{ $admin->apellidos }}', '{{ $admin->rol }}')" class="bg-brand-primary/5 hover:bg-brand-primary text-brand-primary hover:text-white font-bold text-xs px-3 py-1.5 rounded-lg transition border-0 cursor-pointer">
                                            Asignar Rol
                                        </button>
                                    @else
                                        <span class="text-xs font-extrabold text-gray-400 px-3 py-1.5 bg-gray-100 rounded-lg select-none">
                                            Tú (Actual)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400 italic">No hay administradores registrados en el sistema.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Rol -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-2xl p-6 bg-white rounded-3xl">
            <div class="modal-header border-0 pb-0 flex justify-between items-center">
                <h5 class="text-lg font-black text-brand-dark">Gestionar Rol</h5>
                <button type="button" class="btn-close focus:outline-none border-0 bg-transparent text-xl font-bold cursor-pointer text-gray-400 hover:text-gray-600" data-bs-dismiss="modal" aria-label="Close">×</button>
            </div>
            <form id="edit-role-form" method="POST" action="">
                @csrf
                <div class="modal-body py-4">
                    <p class="text-xs text-gray-400 font-semibold mb-3">Cambiar el rol de <span id="modal-user-name" class="font-bold text-brand-dark"></span> en el sistema:</p>
                    <select name="rol" id="modal-user-role" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white">
                        <option value="propietario">Propietario</option>
                        <option value="paseador">Paseador</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 border-0 pt-2">
                    <button type="button" class="border border-gray-200 text-brand-dark hover:border-brand-primary font-bold text-xs px-4 py-2.5 rounded-xl transition cursor-pointer bg-white" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="bg-brand-primary hover:bg-brand-primary-hover text-white font-extrabold text-xs px-4 py-2.5 rounded-xl transition shadow-sm cursor-pointer border-0">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditRoleModal(userId, userName, userRole) {
        document.getElementById('modal-user-name').innerText = userName;
        document.getElementById('modal-user-role').value = userRole;
        
        const form = document.getElementById('edit-role-form');
        form.action = `/admin/usuarios/${userId}/actualizar-rol`;

        const modal = new bootstrap.Modal(document.getElementById('editRoleModal'));
        modal.show();
    }
</script>
@endsection
