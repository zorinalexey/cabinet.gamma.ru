<?php

namespace App\Http\Services\Checker\Fromu;

use App\Http\Services\Checker\Checker;
use App\Models\FromuBase;
use Illuminate\Support\Facades\DB;
use JsonException;
use SimpleXMLElement;

final class Fromu extends Checker
{
    protected static string $fileName = 'fromu.xml';

    /**
     * @throws JsonException
     */
    public static function updateData(): void
    {
        self::start(FromuBase::class);
    }

    protected static function saveData(SimpleXMLElement $data): void
    {
        $count = 0;
        foreach ($data->{'АктуальныйСписок'} as $subjectList) {
            foreach ($subjectList as $subject) {
                if (isset($subject->{'ФЛ'})) {
                    $fromuBase = new FromuBase();
                    $fromuBase->data = $subject->{'ФЛ'}->{'ФИО'};
                    $fromuBase->remark = self::clear($subject->{'Примечание'});
                    $fromuBase->save();
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
