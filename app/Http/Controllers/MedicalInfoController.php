<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalInfo;

class MedicalInfoController extends Controller
{  
    public function show($id)
    {
        $medical = MedicalInfo::where('user_id', $id)
            ->select('user_id', 'sex', 'blood_type', 'allergies', 'medical_conditions', 'medications')
            ->first();

        if (!$medical) {
            return response()->json([
                'sex' => 'Not yet set',
                'blood_type' => 'Not yet set',
                'allergies' => 'Not yet set',
                'medical_conditions' => 'Not yet set',
                'medications' => 'Not yet set'
            ]);
        }

        return response()->json($medical, 200, [], JSON_UNESCAPED_UNICODE);
    }

//     public function show($id)
// {
//     $medical = MedicalInfo::where('user_id', $id)
//         ->select('user_id', 'sex', 'blood_type', 'allergies', 'medical_conditions', 'medications')
//         ->first();

//     return view('dashboard.users.students.show', compact('medical'));
// }

}

