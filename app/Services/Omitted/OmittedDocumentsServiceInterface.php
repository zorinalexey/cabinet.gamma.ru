<?php

namespace App\Services\Omitted;

use App\Models\Omitted;
use App\Models\OmittedProtocol;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface OmittedDocumentsServiceInterface
{
    public function generateProtocol(Omitted $omitted): OmittedProtocol|bool|Builder|Model|int;
}
