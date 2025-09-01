<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectTeamRequest;
use App\Http\Resources\ProjectTeamMemberResource;
use App\Http\Resources\UserResource;
use App\Models\Project;
use App\Models\ProjectTeamMember;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectTeamController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        $teamMembers = $project->teamMembers()->with('user')->get();
        return ProjectTeamMemberResource::collection($teamMembers);

        // Original v1
        // $teamWithRoles = $project->team()->with('roles')->get();
        // return UserResource::collection($teamWithRoles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    // Original v1
    // public function store(StoreProjectTeamRequest $request, Project $project)
    public function store(StoreProjectTeamRequest $request)
    {
        // Original v1
        // $this->authorize('update', $project);

        $validatedData = $request->validated();
        // --- Changes 29/08/25 ---
        $project = Project::findOrFail($validatedData['project_id']);
        $this->authorize('update', $project);
        // -----

        // Cek skenario mana yang dijalankan
        if (!empty($validatedData['user_id'])) {
            // Skenario 1: Menambahkan user yang terdaftar
            $project->team()->attach($validatedData['user_id'], ['role_in_project' => $validatedData['role_in_project']]);
        } else {
            // Skenario 2: Menambahkan anggota eksternal (manual)
            // Jika insert manual langsung ke pivot table tidak bisa menggunakan relasi team().
            DB::table('project_user')->insert([
                'project_id' => $project->id,
                'user_id' => null,
                'external_member_name' => $validatedData['external_member_name'],
                'role_in_project' => $validatedData['role_in_project'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Anggota tim berhasil ditambahkan.']);

        /**
         * Original v1
         */
        // $this->authorize('update', $project);
        // $project->team()->attach($request->validated()['user_id']);
        // return response()->json(['message' => 'Anggota tim berhasil ditambahkan.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, User $user)
    {
        $this->authorize('update', $project);
        $project->team()->detach($user->id);
        return response()->noContent();
    }
}
