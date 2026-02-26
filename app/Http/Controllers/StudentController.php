<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $student = new Student();
        $student->student_id = 'S12347';
        $student->name = 'John Doe';
        $student->email = 'john@example3.com';   
        $student->contact = '123-456-7890';
        $student->saveOrFail();

        return 'Student created successfully';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function fetchAllModelsForYear($year)
    {
        // Fetch current year models
        $url = "https://vpic.nhtsa.dot.gov/api/vehicles/getmodelsformakeyear/make/honda/modelyear/{$year}?format=json";
        $response = Http::get($url)->json();
        return $response['Results'];
    }

    public function fetchDiscontinuedModels($year)
    {
        
        // last 2 years data
        $json1 = $this->fetchAllModelsForYear($year - 1);
        $json2 = $this->fetchAllModelsForYear($year);

        $mapOfLast2Years = [];
        $setOfLast2Years = [];
        $mapofRestYears = [];
        $setOfRestYears = [];
        foreach ($json1 as $model) {
            $mapOfLast2Years[$model['Model_ID']] = $model;
            $setOfLast2Years[$model['Model_ID']] = 1;
        }

        foreach ($json2 as $model) {
            $mapOfLast2Years[$model['Model_ID']] = $model;
            $setOfLast2Years[$model['Model_ID']] = 1;

        }

        $json3 = $this->fetchAllModelsForYear($year - 2);
        $json4 = $this->fetchAllModelsForYear($year - 3);
        $json5 = $this->fetchAllModelsForYear($year - 4);
        $json6 = $this->fetchAllModelsForYear($year - 5);
        $json7 = $this->fetchAllModelsForYear($year - 6);
        $json8 = $this->fetchAllModelsForYear($year - 7);
        $json9 = $this->fetchAllModelsForYear($year - 8);
        $json10 = $this->fetchAllModelsForYear($year - 9);

        foreach ($json3 as $model) {
            $mapofRestYears[$model['Model_ID']] = $model;
            $setOfRestYears[$model['Model_ID']] = 1;
        }
        foreach ($json3 as $model) {
            $mapofRestYears[$model['Model_ID']] = $model;
            $setOfRestYears[$model['Model_ID']] = 1;
        }
        foreach ($json4 as $model) {
            $mapofRestYears[$model['Model_ID']] = $model;
            $setOfRestYears[$model['Model_ID']] = 1;
        }
        foreach ($json5 as $model) {
            $mapofRestYears[$model['Model_ID']] = $model;
            $setOfRestYears[$model['Model_ID']] = 1; 
        }
        foreach ($json6 as $model) {
            $mapofRestYears[$model['Model_ID']] = $model;
            $setOfRestYears[$model['Model_ID']] = 1;
        }
        foreach ($json7 as $model) {
            $mapofRestYears[$model['Model_ID']] = $model;
            $setOfRestYears[$model['Model_ID']] = 1;
        }
        foreach ($json8 as $model) {
            $mapofRestYears[$model['Model_ID']] = $model;
            $setOfRestYears[$model['Model_ID']] = 1;
        }
        foreach ($json9 as $model) {
            $mapofRestYears[$model['Model_ID']] = $model;
            $setOfRestYears[$model['Model_ID']] = 1;
        }

        foreach ($json10 as $model) {
            $mapofRestYears[$model['Model_ID']] = $model;
            $setOfRestYears[$model['Model_ID']] = 1;
        }

        // Find discontinued models
        $modelsId = [];

        foreach ($setOfRestYears as $modelId => $value) {
            if (!isset($setOfLast2Years[$modelId])) {
                $modelsId[] = $modelId;
            }
        }

        $discontinuedModels = [];
        $filledModels = [];
        foreach ($modelsId as $modelId) {
            if(isset($filledModels[$modelId])) {
                continue;
            }
            $filledModels[$modelId] = 1;
            $discontinuedModels[] = $mapofRestYears[$modelId];
        }

        return $discontinuedModels;
    }
}
