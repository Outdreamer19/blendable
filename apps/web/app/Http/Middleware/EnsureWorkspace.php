<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureWorkspace
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return $next($request);
        }

        // Check if user has a current workspace
        $currentWorkspace = $user->currentWorkspace();

        if (! $currentWorkspace) {
            // If user has workspaces, set the first one as current
            $workspaces = $user->workspaces()->get();
            if ($workspaces->isNotEmpty()) {
                $user->setCurrentWorkspace($workspaces->first());
            } else {
                // This shouldn't happen with the new registration flow, but just in case
                return redirect()->route('workspaces.create')
                    ->with('error', 'Please create a workspace to continue.');
            }
        }

        return $next($request);
    }
}
