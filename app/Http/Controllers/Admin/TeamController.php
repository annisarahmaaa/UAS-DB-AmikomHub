<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();
        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'photo_url' => 'required|url|max:255',
        ]);

        Team::create($request->all());

        return redirect()->route('admin.teams.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit(Team $team)
    {
        return view('admin.teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'photo_url' => 'required|url|max:255',
        ]);

        $team->update($request->all());

        return redirect()->route('admin.teams.index')->with('success', 'Anggota tim berhasil diperbarui.');
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('admin.teams.index')->with('success', 'Anggota tim berhasil dihapus.');
    }
}
