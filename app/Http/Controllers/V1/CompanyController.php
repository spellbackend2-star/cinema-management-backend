<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CompanyIndexRequest;
use App\Http\Requests\Company\CompanyStoreRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Http\Resources\CompanyResource;
use App\Services\CompanyService;
use App\Traits\AuthorizesWithPermission;
use App\Traits\ResponseTrait;

class CompanyController extends Controller
{
    use ResponseTrait;
    use AuthorizesWithPermission;
    public function __construct(
        protected CompanyService $service
    ) {}

    public function index(CompanyIndexRequest $request)
    {
        $this->authorizePermission('company.view');
        return CompanyResource::collection(
            $this->service->index($request->validated())
        );
    }

    public function store(CompanyStoreRequest $request)
    {
        $this->authorizePermission('company.create');
        $company = $this->service->store($request->validated());

        return $this->successResponse(
            new CompanyResource($company),
            'Company created successfully.',
            201
        );
    }

    public function show(int $id)
    {
         $this->authorizePermission('company.show');
        return $this->successResponse(
            new CompanyResource(
                $this->service->show($id)
            )
        );
    }

    public function update(CompanyUpdateRequest $request, int $id)
    {
         $this->authorizePermission('company.update');
        $company = $this->service->update(
            $id,
            $request->validated()
        );

        return $this->successResponse(
            new CompanyResource($company),
            'Company updated successfully.'
        );
    }

    public function destroy(int $id)
    {
         $this->authorizePermission('company.delete');
        $this->service->destroy($id);

        return $this->successResponse(
            null,
            'Company deleted successfully.'
        );
    }
}
