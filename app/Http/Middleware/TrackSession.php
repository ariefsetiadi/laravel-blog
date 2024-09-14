<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Carbon\Carbon;
use Str;

use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

use App\Models\PageView;
use App\Models\Session;

class TrackSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionId  =   $request->cookie('session_id') ?? Str::uuid();
        $session    =   Session::where('session_id', $sessionId)->first();

        if ($session) {
            $lastActive =   Carbon::parse($session->last_active_at);

            if ($lastActive->diffInMinutes(Carbon::now()) > 30) {
                $sessionId = Str::uuid();
                $this->createNewSession($sessionId, $request);
            } else {
                $session->last_active_at = Carbon::now();
                $session->total_page += 1;
                $session->save();
            }
        } else {
            $this->createNewSession($sessionId, $request);
        }

        return $next($request)->cookie('session_id', $sessionId, 30);
    }

    protected function createNewSession($sessionId, $request)
    {
        $agent  =   new Agent();

        $session                    =   new Session;
        $session->session_id        =   $sessionId;
        $session->country           =   $this->getCountryFromIP($request->ip());
        $session->ip_address        =   $request->ip();
        $session->device            =   $agent->device();
        $session->platform          =   $agent->platform();
        $session->browser           =   $agent->browser();
        $session->first_active_at   =   Carbon::now();
        $session->last_active_at    =   Carbon::now();
        $session->save();
    }

    protected function getCountryFromIP($ip)
    {
        return Location::get($ip)->countryName ?? 'Unknown';
    }
}
