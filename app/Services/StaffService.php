<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Repositories\Interfaces\StaffRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffService
{
    public function __construct(
        protected StaffRepositoryInterface $staffRepository,
        protected CompanyRepositoryInterface $companyRepository
    ) {}

    public function index(array $filters)
    {
        return $this->staffRepository->index($filters);
    }

    public function show(int $id): User
    {
        return $this->staffRepository->find($id);
    }

    public function store(array $data, User $loggedInUser): User
    {
        return DB::transaction(function () use ($data, $loggedInUser) {

            $role = $data['role'];
            unset($data['role']);

            $allowedRoles = [
                'company_admin' => ['branch_manager', 'ticket_counter', 'cashier'],
                'branch_manager' => ['ticket_counter', 'cashier'],
            ];

            $currentRole = $loggedInUser->roles->first()->name;

            if (isset($allowedRoles[$currentRole])) {
                if (! in_array($role, $allowedRoles[$currentRole])) {
                    abort(403, 'You cannot assign this role.');
                }
            }
            $data['password'] = Hash::make($data['password']);

            $data['company_id'] = $loggedInUser->company_id;;


            if (empty($data['cinema_id'])) {
                $data['cinema_id'] = $loggedInUser->cinema_id;
            }

            // Branch manager can only create staff in their own cinema
            if ($loggedInUser->hasRole('branch_manager')) {
                $data['cinema_id'] = $loggedInUser->cinema_id;
            }
            $staff = $this->staffRepository->create($data);

            $staff->employee_code = $this->generateEmployeeCode($staff->id);
            $staff->save();

            $staff->assignRole($role);

            return $staff->load([
                'roles',
                'company',
                'cinema'
            ]);
        });
    }

    public function update(int $id, array $data): User
    {
        return DB::transaction(function () use ($id, $data) {

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $role = $data['role'] ?? null;
            unset($data['role']);

            $staff = $this->staffRepository->update($id, $data);

            if ($role) {
                $staff->syncRoles([$role]);
            }

            return $staff->load([
                'roles',
                'company',
                'cinema'
            ]);
        });
    }

    public function destroy(int $id): bool
    {
        return $this->staffRepository->delete($id);
    }

    private function generateEmployeeCode(int $id): string
    {
        return 'EMP' . str_pad($id, 6, '0', STR_PAD_LEFT);
    }
}
