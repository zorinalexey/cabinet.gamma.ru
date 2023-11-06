<?php

namespace App\Http\Services\Checker\Mvk;

use App\Http\Services\Checker\Checker;
use App\Models\MvkBase;
use Illuminate\Support\Facades\DB;
use JsonException;
use SimpleXMLElement;

final class Mvk extends Checker
{
    protected static string $fileName = 'mvk.xml';

    /**
     * @throws JsonException
     */
    public static function updateData(): void
    {
        self::start(MvkBase::class);
    }

    protected static function saveData(SimpleXMLElement $data): void
    {
        $count = 0;
        foreach ($data->{'СписокАктуальныхРешений'} as $topicalSolutions) {
            foreach ($topicalSolutions as $list) {
                foreach ($list->{'СписокСубъектов'} as $subjects) {
                    foreach ($subjects as $subject) {
                        if (isset($subject->{'ФЛ'})) {
                            $data = $subject->{'ФЛ'}->{'Фамилия'}.' '.$subject->{'ФЛ'}->{'Имя'}.' ';
                            $data .= $subject->{'ФЛ'}->{'Отчество'}.' ';
                            $data .= date('d.m.Y', strtotime($subject->{'ФЛ'}->{'ДатаРождения'})).' ';
                            $data .= $subject->{'ФЛ'}->{'МестоРождения'};
                            $baseData = new MvkBase();
                            $baseData->data = mb_strtoupper($data);
                            $baseData->remark = self::clear($subject->{'РешениеПоСубъекту'});
                            $baseData->save();
                            $count++;
                            if ($count >= 1000) {
                                DB::commit();
                                $count = 0;
                            }
                        }
                    }
                }
            }
        }
    }
}
