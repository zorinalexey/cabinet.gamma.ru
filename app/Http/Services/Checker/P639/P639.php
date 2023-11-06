<?php

namespace App\Http\Services\Checker\P639;

use App\Http\Services\Checker\Checker;
use App\Models\P639Base;
use Illuminate\Support\Facades\DB;
use JsonException;
use SimpleXMLElement;

/**
 *
 */
final class P639 extends Checker
{

    /**
     * @var string
     */
    protected static string $fileName = 'p639.xml';

    /**
     * @return void
     * @throws JsonException
     */
    public static function updateData(): void
    {
        self::start(P639Base::class);
    }

    /**
     * @param SimpleXMLElement $data
     * @return void
     */
    protected static function saveData(SimpleXMLElement $data): void
    {
        $count = 0;
        foreach ($data->{'ИнформЧасть'}->{'Раздел2'} as $partner) {
            $newPartner = new P639Base();
            $data = $partner->{'Участник'}->{'СведФЛИП'}->{'ФИОФЛИП'}->{'Фам'} . ' ';
            $data .= $partner->{'Участник'}->{'СведФЛИП'}->{'ФИОФЛИП'}->{'Имя'} . ' ';
            $data .= $partner->{'Участник'}->{'СведФЛИП'}->{'ФИОФЛИП'}->{'Отч'} . ' ';
            $data .= date('d.m.Y', strtotime($partner->{'Участник'}->{'СведФЛИП'}->{'ДатаРождения'})) . ' ';
            $data .= $partner->{'Участник'}->{'СведФЛИП'}->{'СведДокУдЛичн'}->{'СерияДок'} . ' ';
            $data .= $partner->{'Участник'}->{'СведФЛИП'}->{'СведДокУдЛичн'}->{'НомДок'};
            $newPartner->data = mb_strtoupper($data);
            $newPartner->remark = 'Числится в списках П-639';
            $newPartner->save();
            $count++;
            if ($count >= 1000) {
                DB::commit();
                $count = 0;
            }
        }
    }
}
