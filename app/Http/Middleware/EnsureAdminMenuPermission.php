<?php

namespace App\Http\Middleware;

use App\Models\Operator;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminMenuPermission
{
    /**
     * 운영자 메뉴 접근 권한을 검증한다.
     *
     * 세션에 운영자 정보가 없으면 기존 동작을 유지하기 위해 통과시킨다.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        if (! is_string($routeName)) {
            return $next($request);
        }

        $menuKey = $this->resolveMenuKey($routeName);

        if ($menuKey === null) {
            return $next($request);
        }

        $operator = $this->resolveCurrentOperator($request);

        if (! $operator) {
            return $next($request);
        }

        if ($operator->hasMenuAccess($menuKey)) {
            return $next($request);
        }

        abort(Response::HTTP_FORBIDDEN, '해당 메뉴에 접근할 권한이 없습니다.');
    }

    /**
     * 현재 세션의 운영자를 조회한다.
     */
    private function resolveCurrentOperator(Request $request): ?Operator
    {
        $sessionKey = (string) config('admin_permissions.session_key', 'admin_operator_id');
        $operatorId = $request->session()->get($sessionKey);

        if (! is_numeric($operatorId)) {
            return null;
        }

        return Operator::query()->find((int) $operatorId);
    }

    /**
     * 라우트명을 메뉴 권한 키로 변환한다.
     */
    private function resolveMenuKey(string $routeName): ?string
    {
        /** @var array<string, array<string, mixed>> $menus */
        $menus = config('admin_permissions.menus', []);

        foreach ($menus as $menuKey => $menu) {
            $patterns = $menu['patterns'] ?? [];

            if (! is_array($patterns)) {
                continue;
            }

            foreach ($patterns as $pattern) {
                if (is_string($pattern) && Str::is($pattern, $routeName)) {
                    return $menuKey;
                }
            }
        }

        return null;
    }
}

