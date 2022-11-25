<?php

namespace App\Modules\Invoice\Http\Controllers;

use Illuminate\Support\Facades\App;
use App\Core\Exceptions\NoPermissionException;
use App\Http\Controllers\Controller;
use App\Modules\Invoice\Http\Requests\InvoiceRepeatRequest;
use App\Modules\Invoice\Services\InvoiceRepeatService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class InvoiceRepeatController
 *
 * @package App\Modules\Invoice\Http\Controllers
 */
class InvoiceRepeatController extends Controller
{
    /**
     * Invoice repository
     *
     * @var InvoiceRepeatService
     */
    private $invoiceRepeatService;

    /**
     * Set repository and apply auth filter
     *
     * @param  InvoiceRepeatService  $invoiceRepeatService
     */
    public function __construct(InvoiceRepeatService $invoiceRepeatService)
    {
        $this->middleware('auth');

        $this->invoiceRepeatService = $invoiceRepeatService;
    }

    /**
     * Return list of InvoiceRepeat
     *
     * @return JsonResponse
     *
     * @throws NoPermissionException
     */
    public function index()
    {
        $this->checkPermissions(['invoice.repeat-index']);

        $list = $this->invoiceRepeatService->paginate();

        return response()->json($list);
    }

    /**
     * Display the specified InvoiceRepeat
     *
     * @param  int  $id
     *
     * @return JsonResponse
     *
     * @throws ModelNotFoundException
     * @throws NoPermissionException
     */
    public function show(int $id)
    {
        $this->checkPermissions(['invoice.repeat-show']);

        $result = $this->invoiceRepeatService->show($id);

        return response()->json(['item' => $result]);
    }

    /**
     * Return module configuration for store action
     *
     * @return JsonResponse
     *
     * @throws NoPermissionException
     */
    public function create()
    {
        $this->checkPermissions(['invoice.repeat-store']);

        $rules = $this->invoiceRepeatService->getRequestRules();

        return response()->json($rules);
    }

    /**
     * Store a newly created InvoiceRepeat in storage.
     *
     * @param  InvoiceRepeatRequest  $invoiceRepeatRequest
     *
     * @return JsonResponse
     *
     * @throws NoPermissionException
     */
    public function store(InvoiceRepeatRequest $invoiceRepeatRequest)
    {
        $this->checkPermissions(['invoice.repeat-store']);

        $result = $this->invoiceRepeatService->create($invoiceRepeatRequest, null);

        return response()->json($result, 200);
    }

    /**
     * Display InvoiceRepeat and module configuration for update action
     *
     * @param  int  $id
     *
     * @return JsonResponse
     *
     * @throws ModelNotFoundException
     * @throws NoPermissionException
     */
    public function edit(int $id)
    {
        $this->checkPermissions(['invoice.repeat-update']);

        $model = $this->invoiceRepeatService->show($id);

        return response()->json($model);
    }

    /**
     * Update the specified InvoiceRepeat in storage.
     *
     * @param  InvoiceRepeatRequest  $invoiceRepeatRequest
     * @param  int  $id
     *
     * @return JsonResponse
     *
     * @throws NoPermissionException
     */
    public function update(InvoiceRepeatRequest $invoiceRepeatRequest, int $id)
    {
        $this->checkPermissions(['invoice.repeat-update']);

        $result = $this->invoiceRepeatService->update($invoiceRepeatRequest, $id);

        return response()->json($result, 200);
    }

    /**
     * Remove the specified Invoice from storage.
     *
     * @param  int  $id
     *
     * @return JsonResponse
     *
     * @throws HttpException
     * @throws NoPermissionException
     */
    public function destroy(int $id)
    {
        $this->checkPermissions(['invoice.repeat-destroy']);

        //$this->invoiceRepeatService->destroy($id);

        return response()->json([], 404);
    }
}
