<?php

namespace App\Http\Services;

use App\Http\Services\InfinitumXml\InfinitumRepository;
use App\Http\Services\SpecDep\Documents;
use App\Models\Fund;
use App\Models\Omitted;
use App\Models\User;
use App\Models\UserDocument;
use App\Models\UserRubleAccount;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JsonException;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use RuntimeException;

final class DocumentService
{
    public static ?PhpWord $docx = null;

    public static ?string $hash = null;

    public static ?User $user = null;

    private static ?string $doc_number = null;

    /**
     * @throws JsonException
     */
    public static function setSignCodeOfDocument(UserDocument $document): void
    {
        $code = random_int(10000, 99999);
        $user = $document->user;
        $message = 'Для подписания документа "'.$document->name.'" введите код : '.$code;
        $document->sign_code = $code;
        $document->save();
        SmsService::send($user->phone, $message);
    }

    public static function createSignDocument(UserDocument $document, string $sign_code): ?string
    {
        $user = $document->user;
        if (preg_match('~(\d+)_omitted_(?<omitted_id>\d+)~u', $document->search_hash, $matches)) {
            $omitted = Omitted::find($matches['omitted_id']);

            return self::createBlankBulletin($user, $omitted, $sign_code);
        }
        if (preg_match('~(\d+)_anketa_account_(?<account>\d+)_fund_(?<fund>\d+)~u', $document->search_hash, $matches)) {
            if ($document->sign_status) {
                $account = UserRubleAccount::find($matches['account']);
                $fund = Fund::find($matches['fund']);
                $file = Documents::createBlank($user, $account, $fund, $sign_code);
                if ($file) {
                    InfinitumRepository::userBlank($document, $user, $account, $fund);
                }

                return $file;
            }

            return $document->path;
        }
        if (preg_match('~(\d+)_app_account_(?<account>\d+)_fund_(?<fund>\d+)~u', $document->search_hash, $matches)) {
            if ($document->sign_status) {
                $fund = Fund::find($matches['fund']);
                $account = UserRubleAccount::find($matches['account']);
                $file = Documents::appPurchase($user, $account, $fund, $sign_code);
                if ($file) {
                    InfinitumRepository::appAccount($document, $user, $account, $fund);
                }

                return $file;
            }

            return $document->path;
        }
        if (preg_match('~(\d+)_request_account_(?<account>\d+)_fund_(?<fund>\d+)~u', $document->search_hash, $matches)) {
            if ($document->sign_status) {
                $fund = Fund::find($matches['fund']);
                $account = UserRubleAccount::find($matches['account']);
                $file = Documents::requestOpenAccount($user, $account, $fund, $sign_code);
                if ($file) {
                    InfinitumRepository::requestAccount($document, $user, $account, $fund);
                }

                return $file;
            }

            return $document->path;
        }

        return null;
    }

    /**
     * Создать бланк бюллетеня голосования
     *
     * @param  User  $user Пользователь голосования
     * @param  Omitted  $omitted Голосование
     * @param  string|bool|null  $sign Подпись
     */
    public static function createBlankBulletin(User $user, Omitted $omitted, string|bool $sign = null): ?string
    {
        self::$user = $user;
        self::$hash = $user->id.'_omitted_'.$omitted->id;

        $section = self::gammaHeader();
        $section->addTextBreak();
        $section->addText('БЮЛЛЕТЕНЬ ', null, ['align' => 'center']);
        $section->addText('ДЛЯ ГОЛОСОВАНИЯ НА ИНВЕСТИЦИОННОМ КОМИТЕТЕ ВЛАДЕЛЬЦЕВ', null, ['align' => 'center']);
        $section->addText('ИНВЕСТИЦИОННЫХ ПАЕВ', null, ['align' => 'center']);
        $section->addTextBreak();
        $text = $section->addTextRun();
        $text->addText('Название фонда: ', ['bold' => true]);
        $text->addText($omitted->fund->name);
        $text->addTextBreak();
        $text->addText('Полное фирменное наименование управляющей компании фонда: ', ['bold' => true]);
        $text->addText('Общество с ограниченной ответственностью Управляющая компания «Гамма Групп».');
        $text->addTextBreak();
        $text->addText('Форма проведения заседания инвестиционного комитета: ', ['bold' => true]);
        $text->addText('заочное голосование.');
        $text->addTextBreak();
        $text->addText('Дата подведения итогов заседания инвестиционного комитета: ', ['bold' => true]);
        $text->addText(date('d.m.Y', strtotime($omitted->total_date)));
        $text->addTextBreak();
        $text->addText('Дата окончания приема заполненных бюллетеней для голосования: ', ['bold' => true]);
        $text->addText(
            'до '.date('H:i', strtotime($omitted->end_date)).
            ' по новосибирскому времени «'.date('d', strtotime($omitted->end_date)).
            '» '.date('m.Y', strtotime($omitted->end_date)).' г.'
        );
        $text->addTextBreak();
        $text->addText('Почтовый адрес, по которому должны направляться заполненные бюллетени для голосования: ', ['bold' => true]);
        $text->addText('630049, Новосибирская область, Г.О. Город Новосибирск, г. Новосибирск, Пр-кт Красный, д. 157/1, офис 215');
        $section->addTextBreak();
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
        ]);
        $row = $table->addRow();
        $text = $row->addCell(6200)->addTextRun();
        $text->addText('Вопросы, поставленные на голосование: ', ['bold' => true], ['align' => 'center']);
        $text->addTextBreak();
        $text->addText('Сделки, требующие одобрения Инвестиционным комитетом', ['bold' => true], ['align' => 'center']);
        $row->addCell(3600, ['align' => 'center', 'valign' => 'center'])->addText('Вариант голосования', ['bold' => true], ['align' => 'center']);
        foreach ($omitted->votings as $voting) {
            $row = $table->addRow();
            $text = $row->addCell(6200)->addTextRun();
            $text->addText('Вид сделки: '.$voting->type_transaction, ['bold' => true], ['align' => 'left']);
            $text->addTextBreak();
            $text->addText('Стороны по сделке: '.$voting->parties_transaction, ['bold' => true], ['align' => 'left']);
            $text->addTextBreak();
            $text->addText('Предмет сделки: '.$voting->subject_transaction, ['bold' => true], ['align' => 'left']);
            $text->addTextBreak();
            $text->addText('Цена сделки: '.$voting->cost_transaction, ['bold' => true], ['align' => 'left']);
            if ($voting->other_conditions) {
                $text->addTextBreak();
                $text->addText('Дополнительные условия голосования: '.$voting->other_conditions, ['bold' => true], ['align' => 'left']);
            }

            $answer = OmittedService::checkAnswer($user->id, $omitted->id, $voting->id);

            $text = $row->addCell(1500, ['align' => 'center', 'valign' => 'center'])->addTextRun(['align' => 'center', 'valign' => 'center']);
            $checkBox = $text->addFormField('checkbox', null, ['align' => 'center', 'valign' => 'center']);
            if ($answer && $answer->answer) {
                $checkBox->setDefault(true);
            }
            $text->addText(' За', ['bold' => true], ['align' => 'center']);

            $text = $row->addCell(1500, ['align' => 'center', 'valign' => 'center'])->addTextRun(['align' => 'center', 'valign' => 'center']);
            $checkBox = $text->addFormField('checkbox', null, ['align' => 'center', 'valign' => 'center']);
            if ($answer && ! $answer->answer) {
                $checkBox->setDefault(true);
            }
            $text->addText(' Против', ['bold' => true], ['align' => 'center']);
        }
        $text = $section->addTextRun(['align' => 'center']);
        $text->addText('Оставьте ');
        $text->addText('только один ', ['bold' => true]);
        $text->addText('вариант голосования по вопросу.');
        $text->addTextBreak();
        $text->addText('Отметьте крестиком нужный вариант голосования!', ['bold' => true]);
        $section->addTextBreak();
        $section->addTextBreak();
        $section->addText(
            'Данные, необходимые для идентификации лица, включенного в список лиц, '.
            'имеющих право на участие в заседании Инвестиционного комитета:'
        );
        $section->addTextBreak();
        $section->addTextBreak();
        $section->addText('ФИО '.$user->lastname.' '.$user->name.' '.$user->patronymic, ['bold' => true]);
        $text = $section->addTextRun();
        $text->addText('Паспорт гражданина РФ серия '.$user->passport->series);
        $text->addText(', номер '.$user->passport->number);
        $text->addText(', выдан '.mb_strtoupper($user->passport->issued_by));
        $text->addText(', дата выдачи '.date('d.m.Y', strtotime($user->passport->when_issued)));
        $text->addText(', код подразделения '.$user->passport->division_code);
        $section->addText(
            'Количество инвестиционных паев, принадлежащих лицу, включенному в список лиц, '.
            'имеющих право на участие в заседании Инвестиционного комитета: '.$user->countPifUserByFund($omitted->fund)
        );
        $section->addText('Бюллетень должен быть обязательно подписан владельцем инвестиционных паев (его представителем).');
        $section->addTextBreak();

        // Блок подписи документа
        $table = $section->addTable();
        $row = $table->addRow();
        $text = $row->addCell(5000, ['align' => 'center', 'valign' => 'center'])->addTextRun(['align' => 'center']);
        $text->addText('Подпись владельца инвестиционных паев', null, ['align' => 'center', 'valign' => 'center']);
        $text->addTextBreak();
        $text->addText('(его представителя)');
        $text = $row->addCell(3300, ['align' => 'center', 'valign' => 'center'])->addTextRun(['align' => 'center']);
        if (is_string($sign)) {
            $text->addText('_________'.$sign.'_________', ['underline' => Font::UNDERLINE_DASH], ['align' => 'center']);
        } else {
            $text->addText('________________________', ['underline' => Font::UNDERLINE_DASH], ['align' => 'center']);
        }
        $text->addTextBreak();
        $text->addText($user->lastname.' '.$user->name.' '.$user->patronymic);
        $text = $row->addCell(1500, ['align' => 'center', 'valign' => 'center'])->addTextRun(['align' => 'center']);
        $text->addText('Дата', null, ['align' => 'center', 'valign' => 'center']);
        $text->addTextBreak();
        $date = null;
        if (is_string($sign)) {
            $date = date('d.m.Y');
        }
        $text->addText($date);

        $document_name = mb_strtoupper('Бюллетень голосования "'.$omitted->name.'"');
        $config = config('company_details');
        $path = $config['root_catalog'].'/public/storage/user_'.$user->id.
            '/omitted_'.$omitted->id.'/'.Str::slug($document_name, '_').'.docx';
        $file = self::toPdf($path);
        if (is_string($sign)) {
            self::addUserDocuments($document_name, $file, true, $sign);
        } else {
            self::addUserDocuments($document_name, $file, true);
        }

        return $file;
    }

    /**
     * Заголовок для документов УК
     */
    private static function gammaHeader(): Section
    {
        $config = config('company_details');
        $doc = new PhpWord();
        self::$docx = $doc;
        $section = $doc->addSection();
        $section->addImage(
            $config['root_catalog'].'/storage/app/document_header.png',
            [
                'width' => 500,
                'height' => 80,
            ]
        );

        return $section;
    }

    public static function toPdf(string $path): string
    {
        $config = config('company_details');
        $pdfPath = str_replace('.docx', '.pdf', $path);
        try {
            $dir = dirname($path);
            if (! is_dir($dir) && ! mkdir($dir, 0777, true) && ! is_dir($dir)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
            self::$docx->save($path);
        } catch (Exception $e) {
            return print_r($e, true);
        }
        if (is_file($path)) {
            exec('unoconv -fpdf "'.$path.'"');
            unlink($path);
        }
        if (is_file($pdfPath)) {
            $storeFile = str_replace([$config['root_catalog'], '/public/storage/'], '', $pdfPath);
            if (Storage::disk('local')->put($storeFile, file_get_contents($pdfPath))) {
                return Storage::disk('local')->url($storeFile);
            }
        }

        return $path;
    }

    public static function addUserDocuments(string $name, ?string $file, bool $is_sign = null, string $signCode = null): void
    {
        $is_sign ??= true;
        if (! $document = UserDocument::where('search_hash', self::$hash)->first()) {
            $document = new UserDocument();
            $document->search_hash = self::$hash;
        }
        $document->name = $name;
        $document->is_sign = $is_sign;
        $document->path = $file;
        if (! $signCode) {
            $document->sign_status = false;
        } else {
            $document->sign_status = true;
        }
        $document->user_id = self::$user->id;
        $document->save();
    }

    public static function setDocumentNumber(): string
    {
        if (self::$doc_number) {
            return self::$doc_number;
        }
        $count = (string) (count(UserDocument::all()) + 1);
        if (strlen($count) < 10) {
            $count = '00'.$count;
        } elseif (strlen($count) < 100) {
            $count = '0'.$count;
        } elseif (strlen($count) < 1000) {
            $count = '0'.$count;
        }
        $number = '032-474-'.$count.'Э';
        self::$doc_number = $number;

        return self::$doc_number;
    }

    public static function getLink(User $user, UserRubleAccount $account, Fund $fund, UserDocument $document, string $document_name): ?string
    {
        $config = config('company_details');
        $link = $config['root_catalog'].'/public/storage/blankets/user_'.$user->id.
            '/bank_'.$account->id.'/fund_'.$fund->id.'/'.Str::slug($document_name, '_').'.pdf';
        $storeFile = str_replace([$config['root_catalog'], '/public/storage/'], '', $link);
        if (Storage::disk('local')->exists($storeFile) && $document->sign_status) {
            return Storage::disk('local')->url($storeFile);
        }

        return null;
    }
}
