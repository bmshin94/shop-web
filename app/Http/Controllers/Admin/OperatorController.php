<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOperatorRequest;
use App\Http\Requests\Admin\UpdateOperatorRequest;
use App\Models\Operator;
use App\Services\Admin\OperatorManagementService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OperatorController extends Controller
{
    private const DEFAULT_PER_PAGE = 6;

    public function __construct(
        private readonly OperatorManagementService $operatorManagementService
    ) {
    }

    /**
     * 운영자 목록을 조회한다.
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'search',
            'status',
            'joined_from',
            'joined_to',
        ]);

        $operators = $this->operatorManagementService->paginateOperators($filters, self::DEFAULT_PER_PAGE);
        $stats = $this->operatorManagementService->getSummaryStats();

        return view('admin.operators.index', [
            'operators' => $operators,
            'stats' => $stats,
            'trashedOperatorsCount' => Operator::onlyTrashed()->count(),
            'statusOptions' => Operator::STATUSES,
        ]);
    }

    /**
     * 운영자 등록 화면을 조회한다.
     */
    public function create(): View
    {
        return view('admin.operators.create', [
            'statusOptions' => Operator::STATUSES,
            'menuDefinitions' => Operator::menuDefinitions(),
            'selectedMenuPermissions' => array_keys(Operator::menuDefinitions()),
        ]);
    }

    /**
     * 운영자를 등록한다.
     */
    public function store(StoreOperatorRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $payload['menu_permissions'] = $this->extractMenuPermissions($request, $payload);
        $payload['password'] = Hash::make((string) $payload['password']);
        unset($payload['menu_permissions_submitted']);

        $operator = $this->operatorManagementService->createOperator($payload);

        return redirect()
            ->route('admin.operators.show', $operator)
            ->with('success', '운영자 계정이 등록되었습니다.');
    }

    /**
     * 운영자 상세를 조회한다.
     */
    public function show(Operator $operator): View
    {
        return view('admin.operators.show', [
            'operator' => $operator,
            'statusOptions' => Operator::STATUSES,
            'menuDefinitions' => Operator::menuDefinitions(),
            'selectedMenuPermissions' => old('menu_permissions', $operator->resolvedMenuPermissions()),
        ]);
    }

    /**
     * 운영자 정보를 수정한다.
     */
    public function update(UpdateOperatorRequest $request, Operator $operator): RedirectResponse
    {
        $payload = $request->validated();
        $payload['menu_permissions'] = $this->extractMenuPermissions($request, $payload);
        unset($payload['menu_permissions_submitted']);

        if (! empty($payload['password'])) {
            $payload['password'] = Hash::make((string) $payload['password']);
        } else {
            unset($payload['password']);
        }

        unset($payload['password_confirmation']);

        $this->operatorManagementService->updateOperator($operator, $payload);

        return redirect()
            ->route('admin.operators.show', $operator)
            ->with('success', '운영자 정보가 업데이트되었습니다.');
    }

    /**
     * 운영자 메뉴 권한 입력값을 저장 가능한 배열로 정리한다.
     *
     * @param  array<string, mixed>  $payload
     * @return array<int, string>
     */
    private function extractMenuPermissions(Request $request, array $payload): array
    {
        $menuKeys = array_keys(Operator::menuDefinitions());
        $permissions = $payload['menu_permissions'] ?? null;
        $isSubmitted = $request->boolean('menu_permissions_submitted');

        if (! is_array($permissions)) {
            return $isSubmitted ? [] : $menuKeys;
        }

        if (! $isSubmitted && $permissions === []) {
            return $menuKeys;
        }

        return array_values(array_intersect($menuKeys, $permissions));
    }

    /**
     * 운영자를 soft delete 처리한다.
     */
    public function destroy(Operator $operator): RedirectResponse
    {
        $operatorName = $operator->name;

        $this->operatorManagementService->deleteOperator($operator);

        return redirect()
            ->route('admin.operators.index')
            ->with('success', "운영자 {$operatorName} 님이 삭제 처리되었습니다.");
    }

    /**
     * 휴지통 운영자 목록을 조회한다.
     */
    public function trash(Request $request): View
    {
        $filters = $request->only([
            'search',
            'status',
            'joined_from',
            'joined_to',
        ]);

        $operators = $this->operatorManagementService->paginateTrashedOperators($filters, self::DEFAULT_PER_PAGE);

        return view('admin.operators.trash', [
            'operators' => $operators,
            'statusOptions' => Operator::STATUSES,
        ]);
    }

    /**
     * soft delete 운영자를 복구한다.
     */
    public function restore(Operator $operator): RedirectResponse
    {
        if (! $this->operatorManagementService->restoreOperator($operator)) {
            return redirect()
                ->route('admin.operators.trash')
                ->with('error', '복구할 수 없는 운영자입니다.');
        }

        return redirect()
            ->route('admin.operators.trash')
            ->with('success', "운영자 {$operator->name} 님이 복구되었습니다.");
    }

    /**
     * soft delete 운영자를 영구 삭제한다.
     */
    public function forceDestroy(Operator $operator): RedirectResponse
    {
        $operatorName = $operator->name;

        if (! $this->operatorManagementService->forceDeleteOperator($operator)) {
            return redirect()
                ->route('admin.operators.trash')
                ->with('error', '영구 삭제할 수 없는 운영자입니다.');
        }

        return redirect()
            ->route('admin.operators.trash')
            ->with('success', "운영자 {$operatorName} 님이 영구 삭제되었습니다.");
    }
}
