@extends('layouts.admin')
@section('title', 'Kelola Pengguna')
@section('page_title', 'Daftar Pengguna Platform')

@section('content')
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b flex justify-between items-center">
        <h3 class="font-black text-xl">Daftar Pengguna</h3>
        <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition duration-200">
            + Tambah Pengguna
        </a>
    </div>

    @if(session('success'))
        <div class="px-8 py-4 bg-green-50 text-green-700 font-bold border-b border-green-100">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('info'))
        <div class="px-8 py-4 bg-blue-50 text-blue-700 font-bold border-b border-blue-100">
            {{ session('info') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Nama</th>
                    <th class="px-8 py-4">Email</th>
                    <th class="px-8 py-4">Role Saat Ini</th>
                    <th class="px-8 py-4 text-center">Ubah Role</th>
                    <th class="px-8 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-8 py-6 font-bold text-slate-800">
                            {{ $user->name }}
                        </td>
                        <td class="px-8 py-6 text-sm text-slate-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-8 py-6">
                            @if($user->role === 'superadmin')
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-bold uppercase">Superadmin</span>
                            @elseif($user->role === 'organizer')
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold uppercase">Penyelenggara</span>
                            @else
                                <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold uppercase">User</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="inline-flex items-center">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="text-sm border-slate-200 rounded-lg px-3 py-1.5 focus:ring-indigo-500 focus:border-indigo-500 mr-3" onchange="this.form.submit()">
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="organizer" {{ $user->role === 'organizer' ? 'selected' : '' }}>Penyelenggara</option>
                                    <option value="superadmin" {{ $user->role === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                </select>
                            </form>
                            @else
                                <span class="text-xs text-slate-400 font-medium italic">Anda (Superadmin)</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right whitespace-nowrap">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-block px-3 py-1.5 bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-lg text-xs font-bold uppercase transition mr-2">Edit</a>
                            
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-100 text-rose-700 hover:bg-rose-200 rounded-lg text-xs font-bold uppercase transition">Hapus</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium">
                            Belum ada data pengguna yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div class="p-6 border-t bg-slate-50">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
