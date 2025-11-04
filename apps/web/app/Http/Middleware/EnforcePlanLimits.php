<?php

namespace App\Http\Middleware;

use App\Enums\Plan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforcePlanLimits
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

        $userPlan = $user->plan;

        if (! $userPlan) {
            return response()->json([
                'error' => 'Subscription required',
                'message' => 'Please subscribe to a plan to access this feature.',
                'subscription_required' => true,
            ], 403);
        }

        try {
            $plan = Plan::from($userPlan);
        } catch (\ValueError $e) {
            return response()->json([
                'error' => 'Invalid plan',
                'message' => 'Your plan configuration is invalid. Please contact support.',
            ], 403);
        }

        $planConfig = config("billing.plans.{$userPlan}");

        if (! $planConfig) {
            return response()->json([
                'error' => 'Invalid plan',
                'message' => 'Your plan configuration is invalid. Please contact support.',
            ], 403);
        }

        // Check token limits
        $estimatedTokens = $request->input('estimated_tokens', 0);
        if ($user->token_usage_month + $estimatedTokens > $planConfig['monthly_tokens']) {
            return response()->json([
                'error' => 'Token limit reached',
                'message' => 'You have reached your monthly token limit. Please upgrade to continue.',
                'upgrade_required' => true,
            ], 403);
        }

        // Check model access
        $requestedModel = $request->input('model');
        if ($requestedModel && ! $this->isModelAllowed($requestedModel, $planConfig['models'])) {
            return response()->json([
                'error' => 'Model not allowed',
                'message' => "The model '{$requestedModel}' is not available on your current plan. Please upgrade to access more models.",
                'upgrade_required' => true,
            ], 403);
        }

        return $next($request);
    }

    /**
     * Check if a model is allowed for the given plan
     */
    private function isModelAllowed(string $model, array $allowedModels): bool
    {
        // If plan allows all models
        if (in_array('*', $allowedModels)) {
            return true;
        }

        return in_array($model, $allowedModels);
    }
}
