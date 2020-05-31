<?php

namespace App\Http\Middleware;

use App\Photo;
use Closure;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\Bool_;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ( ! $this->isValid($request->route('token')))
        {
            abort(404);
        }

        return $next($request);
    }

    public function isValid (string $token)
    {
        $photo = Photo::where('token', '=', $token)->first();

        return $photo && $photo->token_death_date > now()->toDateTimeString();
    }
}
