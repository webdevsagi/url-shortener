<?php

namespace App\Http\Controllers;

use App\Jobs\LogLinkHit;
use App\Models\Link;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function redirect(Request $request, string $slug)
    {
        $link = Link::where("slug", $slug)->first();

        if (!$link) {
            abort(404, "Link not found");
        }



        if (!$link->is_active) {
            abort(410, "Link is no longer active");
        }

        LogLinkHit::dispatch(
            $link->id,
            $request->ip(),
            $request->userAgent()
        );

        return redirect($link->target_url, 302);
    }
}
