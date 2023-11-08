<?php

namespace App\Services\Omitted;

use App\Http\Services\DocumentService;
use App\Models\Omitted;
use App\Models\OmittedProtocol;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class OmittedDocumentsService implements OmittedDocumentsServiceInterface
{
    /**
     * @throws \Exception
     */
    public function generateProtocol(Omitted $omitted): OmittedProtocol|bool|Builder|Model|int
    {
        $config = config('company_details');
        $document_name = "Протокол заседания инвестиционного комитета комбинированного " .
            "закрытого паевого инвестиционного фонда «{$omitted->fund->name}» №{$omitted->id}";
        $document = new DocumentService();
        $section = $document::gammaHeader();
        $section->addTextBreak();
        $section->addText('Протокол заседания инвестиционного комитета', ['bold' => true], ['align' => 'center']);
        $section->addText("Комбинированного закрытого паевого инвестиционного фонда «{$omitted->fund->name}»", ['bold' => true], ['align' => 'center']);
        $section->addText('(Правила доверительного управления Фондом согласованы Специализированным депозитарием и зарегистрированы Банком России ДД.ММ.ГГ г. за № _______)', ['size' => 7]);
        $section->addText('под управлением Общества с ограниченной ответственностью', ['bold' => true], ['align' => 'center']);
        $section->addText("Управляющей компании «{$config['short_company_name']}»", ['bold' => true], ['align' => 'center']);
        $section->addTextBreak(2);
        $section->addText("г. Новосибирск                                                                                                      " . DocumentService::getDate(date('d.m.Y')) . " г.");

        $tableTextStyle = [
            'size' => 10
        ];

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
        ]);
        $row = $table->addRow();
        $row->addCell(3000)->addText('Название Фонда', $tableTextStyle);
        $row->addCell(6800)->addText("Комбинированный закрытый паевой инвестиционный фонд «{$omitted->fund->name}»", $tableTextStyle);
        $row = $table->addRow();
        $row->addCell(3000)->addText('Полное фирменное наименование Управляющей компании Фонда', $tableTextStyle);
        $row->addCell(6800)->addText($config['company_full_name'], $tableTextStyle);

        $row = $table->addRow();
        $row->addCell(3000)->addText('Форма проведения заседания инвестиционного комитета', $tableTextStyle);
        $row->addCell(6800)->addText('Заочное голосование', $tableTextStyle);

        $row = $table->addRow();
        $row->addCell(3000)->addText('Дата подведения итогов заседания инвестиционного комитета', $tableTextStyle);
        $row->addCell(6800)->addText(DocumentService::getDate($omitted->total_date) . ' г.', $tableTextStyle);

        $row = $table->addRow();
        $row->addCell(3000)->addText('Лица, принимавшие участие в голосовании', $tableTextStyle);
        $text = $row->addCell(6800)->addTextRun();

        $i = 1;
        $allPifCount = 0;

        foreach ($omitted->getUsers() as $user) {
            $allPifCount += $omitted->getCountUserPif($user);
            $text->addText("{$i}) {$user->lastname} {$user->name} {$user->patronymic}", $tableTextStyle);
            $text->addTextBreak();
            $i++;
        }

        $row = $table->addRow();
        $row->addCell(3000)->addText('Сделки, требующие одобрения Инвестиционным комитетом', $tableTextStyle);
        $text = $row->addCell(6800)->addTextRun();

        $i = 1;
        $votings = $omitted->votings;

        foreach ($votings as $voting) {
            $text->addText("{$i}) Вид сделки: ", ['bold' => true, ...$tableTextStyle]);
            $text->addText($voting->type_transaction, $tableTextStyle);
            $text->addTextBreak();
            $text->addText("Стороны по сделке: ", ['bold' => true, ...$tableTextStyle]);
            $text->addText($voting->parties_transaction, $tableTextStyle);
            $text->addTextBreak();
            $text->addText("Предмет сделки: ", ['bold' => true, ...$tableTextStyle]);
            $text->addText($voting->subject_transaction, $tableTextStyle);
            $text->addTextBreak();
            $text->addText("Цена сделки: ", ['bold' => true, ...$tableTextStyle]);
            $text->addText($voting->cost_transaction, $tableTextStyle);
            $text->addTextBreak();
            $text->addText("Прочие условия: ", ['bold' => true, ...$tableTextStyle]);
            $text->addText($voting->other_conditions ?: '-', $tableTextStyle);
            $text->addTextBreak(2);
            $i++;
        }

        $row = $table->addRow();
        $row->addCell(3000)->addText('Количество инвестиционных паев, принадлежащих лицам, включенным в список лиц, имеющих право на участие в заседании Инвестиционного комитета', $tableTextStyle);
        $row->addCell(6800)->addText($allPifCount, $tableTextStyle);

        $row = $table->addRow();
        $row->addCell(3000)->addText('Число голосов, отданных за каждый из вариантов голосования:', $tableTextStyle);
        $row->addCell(3400)->addText('«ЗА»', $tableTextStyle, ['align' => 'center']);
        $row->addCell(3400)->addText('«ПРОТИВ»', $tableTextStyle, ['align' => 'center']);

        $q =1;
        foreach ($votings as $voting){
            $row = $table->addRow();
            $text = $row->addCell(3000,)->addTextRun();
            $text->addText('Утверждение условий действия, требующая одобрения Инвестиционным комитетом по ', $tableTextStyle);
            $text->addText("Вопросу {$q}", ['bold' => true, ...$tableTextStyle]);
            $row->addCell(3400)->addText($voting->getVotesFor()." голосов", ['bold' => true, ...$tableTextStyle], ['align' => 'center']);
            $row->addCell(3400)->addText($voting->getVotesAgainst()." голосов", ['bold' => true, ...$tableTextStyle], ['align' => 'center']);
            $q++;
        }


        $row = $table->addRow();
        $row->addCell(3000)->addText('', $tableTextStyle);
        $row->addCell(6800)->addText('', $tableTextStyle);


        $row = $table->addRow();
        $row->addCell(3000)->addText('Дата составления Протокола заседания Инвестиционного комитета', $tableTextStyle);
        $row->addCell(6800)->addText(DocumentService::getDate(date('d.m.Y')).' г.', $tableTextStyle);

        $section->addTextBreak(3);
        $section->addText("Генеральный Директор {$config['company_name']}   ___".random_int(100000, 999999)."___/ {$config['short_office_holder']} /");

        $docxFile = $config['root_catalog'] . '/storage/app/omitteds/' . $omitted->id . '/' . Str::slug($document_name, '_') . '.docx';
        $protocol = OmittedProtocol::query()->where('omitted_id', $omitted->id)->first();
        $data = [
            'omitted_id' => $omitted->id,
            'name' => $document_name,
            'docx' => str_replace([$config['root_catalog'], '/app'], '', $docxFile),
            'pdf' => str_replace('/storage/app/', '', $document::toPdf($docxFile, false)),
        ];

        if (file_exists($docxFile)) {
            if ($protocol?->update($data)) {
                return $protocol;
            }

            return OmittedProtocol::query()->create($data);
        }

        return false;
    }
}
