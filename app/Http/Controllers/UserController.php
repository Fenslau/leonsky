<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\ReadOnlyRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected ReadOnlyRepositoryInterface $repo,
    ) {}

    public function index(Request $request): View
    {
        $users = $this->repo->index($request->all());
        return view('user.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user = $this->repo->show($user);
        return view('user.show', compact('user'));
    }
}
