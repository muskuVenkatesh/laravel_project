<?php
namespace App\Repositories;
use Illuminate\Http\Request;
use App\Models\AcademicDetail;
use App\Interfaces\AcademicDetailsInterface;
use carbon\Carbon;

class AcademicDetailsRepository implements AcademicDetailsInterface
{
    protected $academicdetails;
    public function __construct(AcademicDetail $academicdetails)
    {
        $this->academicdetails = $academicdetails;
    }

    public function store($data){
         return $this->academicdetails->create($data);
    }

    public function getall(Request $request, $limit)
    {
        $academicdetails = AcademicDetail::query();
        $academicdetails->where('status', 1)->withoutTrashed();
        $total = $academicdetails->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $academicdetails->where('academic_years', 'like', "%{$search}%");
        }
        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $academicdetails->orderBy($sortBy, $sortOrder);
        } else {
            $academicdetails->orderBy('created_at', 'asc');
        }
        if ($limit <= 0) {
            $allacademicdetails = $academicdetails->get();
        } else {
            $allacademicdetails = $academicdetails->paginate($limit);
            $allacademicdetails = $allacademicdetails->items();
        }
        foreach ($allacademicdetails as $detail) {
            $startDate = Carbon::parse($detail->start_date);
            $endDate = Carbon::parse($detail->end_date);
            $detail->academic_years = $startDate->format('M Y') . ' - ' . $endDate->format('M Y');
        }

        return ['data' => $allacademicdetails, 'total' => $total];
    }

    public function showAcademic($id)
    {
        return $this->academicdetails->find($id);
    }

    public function update($id,$data)
    {
        $academicDetail = $this->academicdetails->findOrFail($id);
        $academicDetail->update($data);
        return $academicDetail;
    }

    public function destroy($id)
    {
        $academic = $this->academicdetails->find($id);
        if (!$academic) {
            return false;
        }
        try {
            $academic->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
