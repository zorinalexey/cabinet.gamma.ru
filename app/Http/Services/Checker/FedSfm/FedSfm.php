<?php

namespace App\Http\Services\Checker\FedSfm;

use App\Http\Services\Checker\Checker;
use App\Models\FedSfmBase;
use Illuminate\Support\Facades\DB;
use JsonException;
use SimpleXMLElement;

/**
 *
 */
final class FedSfm extends Checker
{
    /**
     * @var string
     */
    protected static string $fileName = 'fedsfm.xml';

    /**
     * @return void
     * @throws JsonException
     */
    public static function updateData(): void
    {
        self::start(FedSfmBase::class);
    }

    /**
     * @param SimpleXMLElement $data
     * @return void
     */
    protected static function saveData(SimpleXMLElement $data): void
    {
        $count = 0;
        foreach ($data->{'АктуальныйПеречень'} as $subjects) {
            foreach ($subjects as $subject) {
                if (isset($subject->{'ФЛ'})) {
                    $subjectData = $subject->{'ФЛ'}->{'ФИО'} . ' ';
                    if (isset($subject->{'ФЛ'}->{'СписокДокументов'})) {
                        foreach ($subject->{'ФЛ'}->{'СписокДокументов'} as $document) {
                            if (isset($document->{'Серия'})) {
                                $subjectData .= $document->{'Серия'} . ' ';
                            }
                            if (isset($document->{'Номер'})) {
                                $subjectData .= $document->{'Номер'} . ' ';
                            }
                        }
                    }
                    if (isset($subject->{'ФЛ'}->{'МестоРождения'})) {
                        $subjectData .= $subject->{'ФЛ'}->{'МестоРождения'} . ' ';
                    }
                    if (isset($subject->{'СписокАдресов'})) {
                        foreach ($subject->{'СписокАдресов'} as $addresses) {
                            foreach ($addresses as $address) {
                                if (!isset($address->{'ТекстАдреса'})) {
                                    $subjectData .= class_basename($address) . ' ';
                                } else {
                                    $subjectData .= $address->{'ТекстАдреса'} . ' ';
                                }
                            }
                        }
                    }
                    $remark = trim($subject->{'ТипСубъекта'}->{'Наименование'}, '.') . ' ';
                    if ((isset($subject->{'Примечание'}) && !is_object($subject->{'Примечание'})) || $subject->{'Террорист'}) {
                        $remark .= '. Примечание : ';
                        if (isset($subject->{'Террорист'}) && $subject->{'Террорист'} === 1) {
                            $remark .= 'Террорист ';
                        }
                        if (isset($subject->{'Примечание'}) && !is_object($subject->{'Примечание'})) {
                            $remark .= $subject->{'Примечание'};
                        }
                    }
                    $rfmBase = new FedSfmBase();
                    $rfmBase->data = mb_strtoupper(trim(preg_replace('~(stdClass)(\s+)?~ui', '', $subjectData)));
                    $rfmBase->remark = self::clear($remark);
                    $rfmBase->save();
                }
            }
            if ($count >= 1000) {
                DB::commit();
                $count = 0;
            }
        }
    }
}
