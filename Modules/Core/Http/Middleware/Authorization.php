<?php namespace Modules\Core\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Users\Repositories\AuthenticationInterface as Authentication;

class Authorization
{
    /**
     * @var Authentication
     */
    private $auth;

    /**
     * Authorization constructor.
     * @param Authentication $auth
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param $request
     * @param \Closure $next
     * @param $permission
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function handle($request, \Closure $next, $permission)
    {
        if ($this->auth->hasAccess($permission) === false) {
            return $this->handleUnauthorizedRequest($request, $permission);
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @param $permission
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    private function handleUnauthorizedRequest(Request $request, $permission)
    {
        if ($request->ajax()) {
            return response('Unauthorized.', Response::HTTP_UNAUTHORIZED);
        }
        if (! $request->user()) {
            if($request->ajax()) return response()->json([
                'msg' => trans('core::core.permission denied', ['permission' => $permission])
            ], 400);
            return redirect()->guest('auth/login');
        }

        if($request->ajax()) return response()->json([
            'msg' => trans('core::core.permission denied', ['permission' => $permission])
        ], 400);

        flash()->error(trans('core::core.permission denied', ['permission' => $permission]));

        return redirect()->back();
    }
}
