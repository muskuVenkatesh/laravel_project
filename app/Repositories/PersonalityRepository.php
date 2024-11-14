<?php
namespace App\Repositories;

use App\Models\PersonalityTrait;
use Illuminate\Http\Request;
use App\Interfaces\PersonalityInterface;

class PersonalityRepository implements PersonalityInterface
{
    protected $personality;

    public function __construct(PersonalityTrait $personality)
    {
        $this->personality = $personality;
    }

    public function CreatePersonality($data)
    {
        return $this->personality->create($data);
    }

    public function GetAllPersonality(Request $request)
    {
        $limit = $request->input('_limit');
        $branch_id = $request->input('branch_id');
        $allpersonality = PersonalityTrait::where('branch_id', $branch_id)->where('status', 1);
        $total = $allpersonality->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $allpersonality->where('name', 'like', "%{$search}%");
        }
        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allpersonality->orderBy($sortBy, $sortOrder);
        } else {
            $allpersonality->orderBy('created_at', 'asc');
        }
        if ($limit <= 0) {
            $allpersonalityData = $allpersonality->get();
        } else {
            $allpersonalityData = $allpersonality->paginate($limit);
            $allpersonalityData = $allpersonalityData->items();
        }
        return ['data'=>$allpersonalityData,'total'=>$total];
    }

    public function GetPersonalityById($id)
    {
        return $this->personality->find($id);
    }

    public function UpdatePersonality($id, $data)
    {
        $personality = $this->personality->find($id);
        if ($personality) {
            $personality->update($data);
            return "Updated Successfully.";
        }
    }

    public function DeletePersonalityTraits($id)
    {
        $Personality = $this->personality->find($id);
        if ($Personality) {
            $Personality->status = 0;
            $Personality->save();
            return true;
        }
        return false;
    }
}
