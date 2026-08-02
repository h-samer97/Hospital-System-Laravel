<?php

namespace App\Observers;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Log;

class PatientFinancialObserver {

  public function fotgetCache(Model $model) : void {

    $patient_id = $model->patient_id;
    if(!$patient_id) return;

  Cache::forget("patient:{$patient_id}:financial-summary");
  Cache::forget("patient:{$patient_id}:ledger");

  Log::channel('finance')->info("Patient financial cache invalidated", [

      'Patient_number' => $patient_id,
      'model'         => \class_basename($model),

  ]);

  }

  public function created(Model $model) : void {

    $this->fotgetCache($model);

  }

  public function updated(Model $model) : void {

    $this->fotgetCache($model);

  }

  public function deleted(Model $model) : void {

    $this->fotgetCache($model);

  }


}