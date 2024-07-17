<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {

        $groups = Auth::user()->groups()->with('members')->get();
        return response()->json($groups);
    }

    public function store(Request $request)
    {
        $group = Group::create($request->all());
        $group->members()->attach(Auth::id(), ['is_admin' => true]);

        return response()->json($group, 201);
    }

    public function show($id)
    {

        $group = Group::findOrFail($id);

        return response()->json($group);
    }

    public function update(Request $request, Group $group)
    {
        $this->authorize('update', $group);
        $group->update($request->all());

        return response()->json($group);
    }

    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);
        $group->delete();

        return response()->json(null, 204);
    }

    public function addMember(Request $request, Group $group)
    {
        $this->authorize('update', $group);
        $group->members()->attach($request->user_id);

        return response()->json(null, 204);
    }

    public function removeMember(Request $request, Group $group)
    {
        $this->authorize('update', $group);
        $group->members()->detach($request->user_id);

        return response()->json(null, 204);
    }
}
