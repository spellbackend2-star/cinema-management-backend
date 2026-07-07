<?php

namespace App\Services;

use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Repositories\Interfaces\StaffRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CompanyService
{
    public function __construct(
        protected CompanyRepositoryInterface $repository,
        protected StaffRepositoryInterface $staffRepository
    ) {}

    public function index(array $filters = [])
    {
        return $this->repository->index($filters);
    }

    public function show(int $id)
    {
        return $this->repository->find($id);
    }

    public function store(array $data)
    {

        $company = $this->repository->create($data);
        if ($data['owner_details']) {
            $data['owner_details']['company_id'] = $company->id;
            $data['owner_details']['employee_code'] = 'EMP-' . $company->id . '-' . rand(1000, 9999);

            $staff =  $this->staffRepository->create($data['owner_details']);
            $staff->assignRole('company_admin');
        }
       
        return $company;
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function destroy(int $id)
    {
        return $this->repository->delete($id);
    }
}
