<?php

namespace App\Interfaces;

interface StaffInterface
{
    public function StoreStaff($data);
    public function GetAllStaff($branchId, $search = null, $sortBy = 'first_name', $sortOrder = 'asc', $perPage = 15);
    public function GetStaffById($id);
    public function DestroyStaff($id);
    public function UpdateStaff($data, $id);
}
