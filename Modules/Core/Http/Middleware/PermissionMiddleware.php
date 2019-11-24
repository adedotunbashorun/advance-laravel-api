<?php namespace Modules\Core\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redirect;
use Krucas\Notification\Facades\Notification;
use Modules\Users\Repositories\AuthenticationInterface as Authentication;

class PermissionMiddleware
{
    /**
     * @var Authentication
     */
    private $auth;
    /**
     * @var Route
     */
    private $route;

    /**
     * @param Authentication $auth
     * @param Route          $route
     */
    public function __construct(Authentication $auth, Route $route)
    {
        $this->auth = $auth;
        $this->route = $route;
    }

    /**
     * @param Request $request
     * @param \Closure $next
     * @return Redirect
     */
    public function handle(Request $request, \Closure $next)
    {
        $permission = str_replace(['api.', 'admin.'], '', $this->route->getName());
        if(!str_contains($permission,'datatable'))
        {
            if (!$this->auth->hasAccess($permission))
            {
                return redirect('admin/dashboard')->withError((trans('core::global.permission_denied', ['permission' => $permission])));
            }
        }

        return $next($request);
    }

    /**
     * Get the correct segment position based on the locale or not
     *
     * @param $request
     * @return mixed
     */
    private function getSegmentPosition(Request $request)
    {
        $segmentPosition = 3;
        if ($request->segment($segmentPosition) == config('core.admin_prefix')) {
            return ++ $segmentPosition;
        }

        return $segmentPosition;
    }

    /**
     * @param $moduleName
     * @param $entityName
     * @param $actionMethod
     * @return string
     */
    private function getPermission($moduleName, $actionMethod)
    {
        return ltrim($moduleName  . '.' . $actionMethod, '.');
    }

    /**
     * @param Request $request
     * @param         $segmentPosition
     * @return string
     */
    protected function getModuleName(Request $request, $segmentPosition)
    {
        return $request->segment($segmentPosition - 1);
    }

    /**
     * @param Request $request
     * @param         $segmentPosition
     * @return string
     */
    protected function getEntityName(Request $request, $segmentPosition)
    {
        $entityName = $request->segment($segmentPosition);

        return $entityName ?: 'dashboard';
    }
}
