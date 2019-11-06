<?php

namespace App;

use App\PublicTrainingInfoPdf;
use Illuminate\Database\Eloquent\Model;

class PublicTrainingInfo extends Model
{
    protected $table = 'public_train_info_sections';

    public function GetPdfAttribute() {
        $pdfs = PublicTrainingInfoPdf::where('section_id', $this->id)->get();

        return $pdfs;
    }
}
