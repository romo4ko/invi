<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class InviteController extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    public function invite(string $slug): View
    {
        $invite = Invite::query()
            ->with('guest')
            ->with('event')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('invite', ['invite' => $invite->toArray()]);
    }

    public function approve(int $id, Request $request): JsonResponse
    {
        $invite = Invite::query()->findOrFail($id);

        $approval = $request->post('approval');

        $invite->approval = $approval;
        $invite->plus_one = $approval === true || $approval === '1' || $approval === 1
            ? (bool) $request->boolean('plus_one')
            : false;
        $invite->save();

        return new JsonResponse([
            'status' => 'success',
        ]);
    }
}
