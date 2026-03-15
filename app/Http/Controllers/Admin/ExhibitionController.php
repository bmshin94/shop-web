<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExhibitionRequest;
use App\Http\Requests\Admin\UpdateExhibitionRequest;
use App\Models\Exhibition;
use App\Services\Admin\ExhibitionManagementService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExhibitionController extends Controller
{
    private const DEFAULT_PER_PAGE = 6;

    public function __construct(
        private readonly ExhibitionManagementService $exhibitionManagementService
    ) {
    }

    /**
     * 관리자 기획전 목록을 조회한다.
     *
     * @param  Request  $request
     * @return View
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'search',
            'status',
            'start_from',
            'start_to',
        ]);

        $exhibitions = $this->exhibitionManagementService->paginateExhibitions($filters, self::DEFAULT_PER_PAGE);
        $stats = $this->exhibitionManagementService->getSummaryStats();

        return view('admin.exhibitions.index', [
            'exhibitions' => $exhibitions,
            'stats' => $stats,
            'trashedExhibitionsCount' => Exhibition::onlyTrashed()->count(),
            'statusOptions' => Exhibition::STATUSES,
        ]);
    }

    /**
     * 관리자 기획전 등록 폼을 조회한다.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.exhibitions.create', [
            'statusOptions' => Exhibition::STATUSES,
            'products' => \App\Models\Product::all(),
        ]);
    }

    /**
     * 관리자 기획전을 등록한다.
     *
     * @param  StoreExhibitionRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreExhibitionRequest $request): RedirectResponse
    {
        $exhibition = $this->exhibitionManagementService->createExhibition($request->validated());

        return redirect()
            ->route('admin.exhibitions.edit', $exhibition)
            ->with('success', '기획전이 등록되었습니다.');
    }

    /**
     * 관리자 기획전 상세 정보를 조회한다. 
     *
     * @param  Exhibition  $exhibition
     * @return View
     */
    public function show(Exhibition $exhibition): View
    {
        // 연결된 상품들과 함께 상세 정보를 가져와요! 
        $exhibition->load(['products.images', 'products.category']);

        return view('admin.exhibitions.show', [
            'exhibition' => $exhibition,
        ]);
    }

    /**
     * 관리자 기획전 수정 폼을 조회한다.
     *
     * @param  Exhibition  $exhibition
     * @return View
     */
    public function edit(Exhibition $exhibition): View
    {
        return view('admin.exhibitions.edit', [
            'exhibition' => $exhibition,
            'statusOptions' => Exhibition::STATUSES,
            'products' => \App\Models\Product::all(),
        ]);
    }

    /**
     * 관리자 기획전을 수정한다.
     *
     * @param  UpdateExhibitionRequest  $request
     * @param  Exhibition  $exhibition
     * @return RedirectResponse
     */
    public function update(UpdateExhibitionRequest $request, Exhibition $exhibition): RedirectResponse
    {
        $this->exhibitionManagementService->updateExhibition($exhibition, $request->validated());

        return redirect()
            ->route('admin.exhibitions.edit', $exhibition)
            ->with('success', '기획전 정보가 업데이트되었습니다.');
    }

    /**
     * 관리자 기획전을 soft delete 처리한다.
     *
     * @param  Exhibition  $exhibition
     * @return RedirectResponse
     */
    public function destroy(Exhibition $exhibition): RedirectResponse
    {
        $exhibitionTitle = $exhibition->title;

        $this->exhibitionManagementService->deleteExhibition($exhibition);

        return redirect()
            ->route('admin.exhibitions.index')
            ->with('success', "기획전 {$exhibitionTitle} 이(가) 삭제 처리되었습니다.");
    }

    /**
     * 삭제된 기획전 목록을 조회한다.
     *
     * @param  Request  $request
     * @return View
     */
    public function trash(Request $request): View
    {
        $filters = $request->only([
            'search',
            'status',
            'start_from',
            'start_to',
        ]);

        $exhibitions = $this->exhibitionManagementService->paginateTrashedExhibitions($filters, self::DEFAULT_PER_PAGE);

        return view('admin.exhibitions.trash', [
            'exhibitions' => $exhibitions,
            'statusOptions' => Exhibition::STATUSES,
        ]);
    }

    /**
     * soft delete 기획전을 복구한다.
     *
     * @param  Exhibition  $exhibition
     * @return RedirectResponse
     */
    public function restore(Exhibition $exhibition): RedirectResponse
    {
        if (! $this->exhibitionManagementService->restoreExhibition($exhibition)) {
            return redirect()
                ->route('admin.exhibitions.trash')
                ->with('error', '복구할 수 없는 기획전입니다.');
        }

        return redirect()
            ->route('admin.exhibitions.trash')
            ->with('success', "기획전 {$exhibition->title} 이(가) 복구되었습니다.");
    }

    /**
     * soft delete 기획전을 영구 삭제한다.
     *
     * @param  Exhibition  $exhibition
     * @return RedirectResponse
     */
    public function forceDestroy(Exhibition $exhibition): RedirectResponse
    {
        $exhibitionTitle = $exhibition->title;

        if (! $this->exhibitionManagementService->forceDeleteExhibition($exhibition)) {
            return redirect()
                ->route('admin.exhibitions.trash')
                ->with('error', '영구 삭제할 수 없는 기획전입니다.');
        }

        return redirect()
            ->route('admin.exhibitions.trash')
            ->with('success', "기획전 {$exhibitionTitle} 이(가) 영구 삭제되었습니다.");
    }
}
