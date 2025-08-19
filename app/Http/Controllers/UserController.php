<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, '管理者権限が必要です。');
            }
            return $next($request);
        });
    }

    /**
     * ユーザー一覧を表示
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    /**
     * ユーザー作成フォームを表示
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * 新しいユーザーを保存
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'nullable|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,pmo_manager,user',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'ユーザーが正常に作成されました。');
    }

    /**
     * ユーザー編集フォームを表示
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * ユーザー情報を更新
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,pmo_manager,user',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'ユーザー情報が正常に更新されました。');
    }

    /**
     * ユーザーを削除
     */
    public function destroy(User $user)
    {
        // 自分自身は削除できない
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', '自分自身を削除することはできません。');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'ユーザーが正常に削除されました。');
    }
}
