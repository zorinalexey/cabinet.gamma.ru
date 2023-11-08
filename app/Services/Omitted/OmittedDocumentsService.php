<?php

namespace App\Services\Omitted;

use App\Http\Services\DocumentService;
use App\Models\Omitted;
use App\Models\OmittedProtocol;
use App\Services\Omitted\OmittedDocumentsServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class OmittedDocumentsService implements OmittedDocumentsServiceInterface
{
    public function generateProtocol(Omitted $omitted): OmittedProtocol|bool|Builder|Model|int
    {
        $document = new DocumentService();
        $section = $document::gammaHeader();
        $section->addTextBreak();
        $section->addText('БЮЛЛЕТЕНЬ ', null, ['align' => 'center']);
        $section->addText('ДЛЯ ГОЛОСОВАНИЯ НА ИНВЕСТИЦИОННОМ КОМИТЕТЕ ВЛАДЕЛЬЦЕВ', null, ['align' => 'center']);
        $section->addText('ИНВЕСТИЦИОННЫХ ПАЕВ', null, ['align' => 'center']);
        $section->addTextBreak();


        $document_name = "Протокол заседания инвестиционного комитета комбинированного ".
            "закрытого паевого инвестиционного фонда «{$omitted->fund->name}» №{$omitted->id}";
        $protocol = OmittedProtocol::query()->where('omitted_id', $omitted->id)->first();
        $config = config('company_details');
        $docxFile = $config['root_catalog'].'/public/storage/omitteds/'.$omitted->id.'/'.Str::slug($document_name, '_').'.docx';

        $data = [
            'docx' => str_replace([$config['root_catalog'], '/public/storage/'], '', $docxFile),
            'pdf' => $document::toPdf($docxFile, false),
        ];

        if($protocol?->update($data)){
            return $protocol;
        }

        return OmittedProtocol::query()->create($data);
    }
}
