<?php

namespace App\Http\Services\SpecDep;

use App\Http\Services\DocumentService;
use App\Models\Fund;
use App\Models\User;
use App\Models\UserRubleAccount;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

class Documents
{
    /**
     * Генерация анкеты зарегистрированного лица в репозитории спец.деп
     *
     * @param  string|bool|null  $signCode
     */
    public static function createBlank(User $user, UserRubleAccount $account, Fund $fund, string $signCode = null): ?string
    {
        $tableStyle = [
            'borderColor' => '000000',
            'borderSize' => 6,
            'cellMargin' => 50,
            'valign' => 'center',
        ];
        $doc = self::startDocument();
        DocumentService::$docx = $doc;
        DocumentService::$user = $user;
        DocumentService::$hash = $user->id.'_anketa_account_'.$account->id.'_fund_'.$fund->id;
        $document_name = 'АНКЕТА ЗАРЕГИСТРИРОВАННОГО ФИЗИЧЕСКОГО ЛИЦА В РЕЕСТРЕ ВЛАДЕЛЬЦЕВ ИНВЕСТИЦИОННЫХ ПАЕВ';
        $section = self::specDepHeader($doc, $signCode);
        $section->addTextBreak();
        $section->addText('АНКЕТА ЗАРЕГИСТРИРОВАННОГО ФИЗИЧЕСКОГО ЛИЦА', ['bold' => true], ['align' => 'center']);
        $section->addText('В РЕЕСТРЕ ВЛАДЕЛЬЦЕВ ИНВЕСТИЦИОННЫХ ПАЕВ', ['bold' => true], ['align' => 'center']);
        $section->addTextBreak();
        $section->addText('Открытый паевой инвестиционный фонд рыночных финансовых инструментов', ['size' => 9, 'bold' => true], ['align' => 'center']);
        $section->addText('«'.$fund->name.'»', ['size' => 9, 'bold' => true], ['align' => 'center']);
        $section->addText('(Название ПИФ в соответствии с Правилами доверительного управления)', ['size' => 6], ['align' => 'center']);
        $table = $section->addTable($tableStyle);
        $row = $table->addRow();
        $row->addCell(3000, ['bgColor' => 'E0E0E0'])->addText('Цель подачи анкеты:', ['bold' => true]);
        $cell = $row->addCell(2600);
        $cell->addTextRun()->addFormField('checkbox')->setValue(' открытие лицевого счета');
        $cell = $row->addCell(4000);
        $text = $cell->addTextRun(['size' => 10]);
        $text->addFormField('checkbox');
        $text->addText('изменение данных анкеты зарегистрированного лица по лицевому счету №:________________________');
        $cell->addText('(указывается один номер лицевого счета в реестре)', ['size' => 6], ['align' => 'right']);
        $section->addTextBreak();
        $table = $section->addTable($tableStyle);
        $table->addRow()->addCell(9600, ['bgColor' => 'E0E0E0'])->addText('Сведения о зарегистрированном лице:', ['bold' => true]);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Фамилия, имя и, если имеется, отчество:', ['bold' => true]);
        $row->addCell(7000)->addText($user->lastname.' '.$user->name.' '.$user->patronymic);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Гражданство либо указание на его отсутствие:', ['bold' => true]);
        $row->addCell(7000)->addText('Российская Федерация');
        $row = $table->addRow();
        $row->addCell(2600)->addText('Дата рождения:', ['bold' => true]);
        $row->addCell(7000)->addText(date('d.m.Y', strtotime($user->birth_date)));
        $row = $table->addRow();
        $row->addCell(2600)->addText('Место рождения:', ['bold' => true]);
        $row->addCell(7000)->addText($user->birth_place);
        $table->addRow()->addCell(9600)->addText('Данные документа, удостоверяющего личность:', ['bold' => true]);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Вид документа', null, ['align' => 'center']);
        $row->addCell(800)->addText('Серия', null, ['align' => 'center']);
        $row->addCell(800)->addText('Номер', null, ['align' => 'center']);
        $row->addCell(1200)->addText('Дата выдачи', null, ['align' => 'center']);
        $row->addCell(4200)->addText('Наименование органа, выдавшего документ', null, ['align' => 'center']);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Паспорт гражданина РФ ', null, ['align' => 'center']);
        $row->addCell(800)->addText($user->passport->series, null, ['align' => 'center']);
        $row->addCell(800)->addText($user->passport->number, null, ['align' => 'center']);
        $row->addCell(1200)->addText(date('d.m.Y', strtotime($user->passport->when_issued)), null, ['align' => 'center']);
        $row->addCell(4200)->addText($user->passport->issued_by, null, ['align' => 'center']);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Идентификационный номер налогоплательщика (ИНН): (при наличии)1', ['bold' => true]);
        if ($user->inn) {
            $row->addCell(4200)->addText($user->inn->number, null, ['align' => 'left']);
        } else {
            $row->addCell(4200)->addText('', null, ['align' => 'left']);
        }
        $row = $table->addRow();
        $row->addCell(2600)->addText('Адрес места регистрации:', [], ['bold' => true]);
        if ($user->address_registration()) {
            $row->addCell(4200)->addText($user->address_registration()->address, [], ['align' => 'left']);
        } else {
            $row->addCell(4200)->addText('', [], ['align' => 'left']);
        }
        $row = $table->addRow();
        $row->addCell(2600)->addText('Адрес фактического места жительства:', [], ['bold' => true]);
        if ($user->address_fact()) {
            $row->addCell(4200)->addText($user->address_fact()->address, [], ['align' => 'left']);
        } else {
            $row->addCell(4200)->addText('', [], ['align' => 'left']);
        }
        $row = $table->addRow();
        $cell = $row->addCell(2600);
        $cell->addText('Телефон: ', ['bold' => true]);
        $cell->addText('(при наличии)');
        $row->addCell(1600)->addText($user->phone);
        $cell = $row->addCell(2600);
        $cell->addText('Адрес электронной почты:', ['bold' => true]);
        $cell->addText('(при наличии)');
        $row->addCell(2800)->addText($user->email);
        $row = $table->addRow(null, ['align' => 'left']);
        $row->addCell(2600)->addText('Способ получения выписок, уведомлений:', ['bold' => true]);
        $cell = $row->addCell(2000);
        $text = $cell->addTextRun(['size' => 10]);
        $text->addFormField('checkbox');
        $text->addTextBreak();
        $text->addText('письмо по адресу фактического места жительства');
        $cell = $row->addCell(2000);
        $text = $cell->addTextRun(['size' => 10]);
        $text->addFormField('checkbox');
        $text->addTextBreak();
        $text->addText('у регистратора');
        $cell = $row->addCell(3000);
        $text = $cell->addTextRun(['size' => 10]);
        $text->addFormField('checkbox')->setValue(' в месте подачи заявки/заявления/ запроса/распоряжения');
        $text = $table->addRow()->addCell(9600)->addTextRun();
        $text->addFormField('checkbox');
        $text->addText(' - Документы, необходимые для изменения данных анкеты, и запросы на предоставление информации из реестра могут быть направлены почтовым отправлением');
        $section->addTextBreak();
        $table = $section->addTable($tableStyle);
        $table->addRow()->addCell(9600)->addText('Банковские реквизиты для выплаты доходов по инвестиционным паям:', ['bold' => true]);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Расчетный счет');
        $row->addCell(7000)->addText($account->payment_account);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Наименование банка');
        $row->addCell(3000)->addText($account->bank_name);
        $row->addCell(800)->addText('Город');
        $row->addCell(3200)->addText($account->bank_city);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Корр. Счет');
        $row->addCell(7000)->addText($account->cor_account);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Лицевой счет');
        $row->addCell(7000)->addText($account->payment_account);
        $row = $table->addRow();
        $row->addCell(2600)->addText('БИК банка');
        $row->addCell(7000)->addText($account->bic);
        $footer = $section->addFooter();
        $footer->addText('__________________', ['bold' => true]);
        $footer->addText('1 - **ИНН физического лица указывается в обязательном порядке при осуществлении обмена электронными документами, подписанными квалифицированной и неквалифицированной электронной подписью физического лица. ИНН может не указываться при осуществлении обмена электронными документами, подписанными простой электронной подписью физического лица, если сведения об ИНН отсутствуют, о чем в указанном пункте ставится соответствующая отметка.', ['size' => 6]);

        $section = $doc->addSection(['marginTop' => 300, 'marginBottom' => 300]);
        $table = $section->addTable($tableStyle);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Образец подписи зарегистрированного физического лица:', ['bold' => true]);
        $cell = $row->addCell(7000);
        $cell->addText('ПОДПИСЬ', ['bold' => true], ['align' => 'center']);
        if (is_string($signCode)) {
            $cell->addText($signCode, ['bold' => true], ['align' => 'center']);
        } else {
            $cell->addText('', ['bold' => true], ['align' => 'center']);
        }
        $section->addText('Дополнительные сведения:', ['bold' => true]);
        $table = $section->addTable($tableStyle);
        $row = $table->addRow();
        $text = $row->addCell(9600)->addTextRun();
        $text->addFormField('checkbox');
        $text->addTextBreak();
        $text->addText('Собственность на инвестиционные паи является общей. Доля____________________________');
        $table = $section->addTable($tableStyle);
        $table->addRow()->addCell(9600)->addText('Сведения о законных представителях зарегистрированного физического лица:', ['bold' => true]);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Законный представитель:', ['bold' => true]);
        $text = $row->addCell(1750)->addTextRun();
        $text->addFormField('checkbox');
        $text->addText('родитель');
        $text = $row->addCell(1750)->addTextRun();
        $text->addFormField('checkbox');
        $text->addText('усыновитель');
        $text = $row->addCell(1750)->addTextRun();
        $text->addFormField('checkbox');
        $text->addText('опекун');
        $text = $row->addCell(1750)->addTextRun();
        $text->addFormField('checkbox');
        $text->addText('попечитель');
        $row = $table->addRow();
        $row->addCell(2600)->addText('Фамилия, имя и, если имеется, отчество:', ['bold' => true]);
        $row->addCell(7000);
        $row = $table->addRow();
        $row->addCell(9600)->addText('Данные документа, удостоверяющего личность:', ['bold' => true]);
        $table = $section->addTable();
        $row = $table->addRow();
        $row->addCell(2600)->addText('Вид документа', null, ['align' => 'center']);
        $row->addCell(800)->addText('Серия', null, ['align' => 'center']);
        $row->addCell(800)->addText('Номер', null, ['align' => 'center']);
        $row->addCell(1200)->addText('Дата выдачи', null, ['align' => 'center']);
        $row->addCell(4200)->addText('Место выдачи документа и наименование органа, выдавшего документ', null, ['align' => 'center']);
        $row = $table->addRow();
        $row->addCell(2600);
        $row->addCell(800);
        $row->addCell(800);
        $row->addCell(1200);
        $row->addCell(4200);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Реквизиты акта о назначении опекуна, попечителя:', ['bold' => true, 'align' => 'center']);
        $row->addCell(7000);
        $section->addTextBreak();
        $table = $section->addTable($tableStyle);
        $row = $table->addRow(1000, ['valign' => 'center']);
        $row->addCell(2600)->addText('Образец подписи законного представителя:', ['bold' => true, 'align' => 'center']);
        $row->addCell(7000);
        $section->addTextBreak();
        $section->addText('Согласие на обработку персональных данных зарегистрированного физического лица', ['bold' => true], ['align' => 'center']);
        $section->addTextBreak();
        $section->addText('     Настоящим в соответствии с Федеральным законом от 27.07.2006 № 152-ФЗ «О персональных данных»  своей волей и в своем интересе даю согласие на обработку всех, указанных в настоящей анкете, моих персональных данных Оператору ОАО «Специализированный депозитарий «ИНФИНИТУМ» (115162, г. Москва, ул. Шаболовка, дом 31, корп. Б), а также ');
        $section->addText('Общество с ограниченной ответственностью Управляющая компания «ИНВЕСТ-ВС» (129090, Москва, вн. тер. г. муниципальный округ Красносельский, туп. Спасский, д. 8, стр. 1, этаж 2, помещение I, комн.№8),', ['size' => 8]);
        $section->addText('осуществляющих обработку персональных данных по поручению Оператора, в целях исполнения ими функций, возложенных законодательством Российской Федерации о паевых инвестиционных фондах.');
        $section->addText('     Настоящее согласие выдается на осуществление любых законных действий (операций) в отношении моих персональных данных, которые необходимы для достижения указанных выше целей, включая сбор, запись, систематизацию, использование, накопление, хранение, уточнение (обновление, изменение), уничтожение, удаление, блокирование, обезличивание, передача (предоставление).');
        $section->addText('     Обработка персональных данных осуществляется с соблюдением требований законодательства Российской Федерации следующими способами: с применением средств автоматизации и без применения средств автоматизации.');
        $section->addText('     Настоящее согласие действует до истечения сроков хранения соответствующей информации или документов, содержащих мои персональные данные, устанавливаемых в соответствии с законодательством Российской Федерации, и может быть отозвано мною на основании письменного заявления. При этом прекращение обработки и уничтожение персональных данных осуществляется в сроки и в порядке, установленном законодательством о паевых инвестиционных фондах и иными требованиями законов и нормативных актов Российской Федерации.');
        $section->addTextBreak();
        $table = $section->addTable($tableStyle);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Подпись зарегистрированного физического лица или его законного представителя:', ['bold' => true]);
        $cell = $row->addCell(7000, ['valign' => 'bottom']);
        if (is_string($signCode)) {
            $cell->addText('__________'.$signCode.'_____________/ '.$user->lastname.' '.$user->name.' '.$user->patronymic, null, ['align' => 'center']);
        } else {
            $cell->addText('_____________________________/ '.$user->lastname.' '.$user->name.' '.$user->patronymic, null, ['align' => 'center']);
        }
        $cell->addText('подпись                                            ФИО', ['size' => 8], ['align' => 'center']);
        $section->addTextBreak();
        $section->addText('Согласие на обработку персональных данных законного представителя', ['bold' => true], ['align' => 'center']);
        $section->addTextBreak();
        $section->addText('     Настоящим в соответствии с Федеральным законом от 27.07.2006 № 152-ФЗ «О персональных данных»  своей волей и в своем интересе даю согласие на обработку всех, указанных в настоящей анкете, моих персональных данных Оператору ОАО «Специализированный депозитарий «ИНФИНИТУМ» (115162, г.Москва, ул. Шаболовка, дом 31, корп. Б), а также ');
        $section->addText('_____________________________________________________________________________________(указать наименование и адрес),', ['size' => 8]);
        $section->addText('_____________________________________________________________________________________(указать наименование и адрес),', ['size' => 8]);
        $section->addText('осуществляющих обработку персональных данных по поручению Оператора, в целях исполнения ими функций, возложенных законодательством Российской Федерации о паевых инвестиционных фондах.');
        $section->addText('     Настоящее согласие выдается на осуществление любых законных действий (операций) в отношении моих персональных данных, которые необходимы для достижения указанных выше целей, включая сбор, запись, систематизацию, использование, накопление, хранение, уточнение (обновление, изменение), уничтожение, удаление, блокирование, обезличивание, передача (предоставление).');
        $section->addText('     Обработка персональных данных осуществляется с соблюдением требований законодательства Российской Федерации следующими способами: с применением средств автоматизации и без применения средств автоматизации.');
        $section->addText('     Настоящее согласие действует до истечения сроков хранения соответствующей информации или документов, содержащих мои персональные данные, устанавливаемых в соответствии с законодательством Российской Федерации, и может быть отозвано мною на основании письменного заявления. При этом прекращение обработки и уничтожение персональных данных осуществляется в сроки и в порядке, установленном законодательством о паевых инвестиционных фондах и иными требованиями законов и нормативных актов Российской Федерации.');
        $section->addTextBreak();
        $table = $section->addTable($tableStyle);
        $row = $table->addRow();
        $row->addCell(2600)->addText('Подпись законного представителя:', ['bold' => true]);
        $cell = $row->addCell(7000, ['valign' => 'bottom']);
        $cell->addText('_______________________/ ФИО', null, ['align' => 'center']);
        $cell->addText('подпись                                            ФИО', ['size' => 8], ['align' => 'center']);
        $section->addTextBreak();
        $section->addFooter();

        return self::saveDocument($user, $account, $fund, $document_name, $signCode);
    }

    private static function startDocument(): PhpWord
    {
        $doc = new PhpWord();
        $doc->setDefaultFontName('Times New Roman');
        $doc->setDefaultFontSize(10);

        return $doc;
    }

    /**
     * Заголовок документов для спец. депозитария Инфинитум
     */
    private static function specDepHeader(PhpWord $doc, string|bool|null $signCode): Section
    {
        $cellStyle = [
            'valign' => 'center',
            'borderColor' => '000000',
            'borderSize' => 6,
            'cellMargin' => 50,
        ];
        $sectionStyle = [
            'marginTop' => 600,
            'marginBottom' => 300,
        ];
        $company_details = config('company_details');
        $section = $doc->addSection($sectionStyle);
        $table = $section->addTable();
        $row = $table->addRow();
        $row->addCell(3000, ['valign' => 'center'])->addImage($company_details['root_catalog'].'/public/images/infinitum_logo.png', ['width' => 120]);
        $cell = $row->addCell(4600, $cellStyle);
        $cell->addText('ПРИНЯТО: '.mb_strtoupper($company_details['company_name']), ['size' => 10], ['align' => 'center']);
        $cell->addText('(наименование организации)', ['size' => 6], ['align' => 'center']);
        $cell->addText('Подпись проверил', ['bold' => true], ['align' => 'center']);
        $cell->addText($company_details['office-holder'].', ', ['size' => 10], ['align' => 'center']);
        if (! is_string($signCode)) {
            $cell->addText($company_details['job-title'].'  ____________________', ['size' => 10], ['align' => 'center']);
        } else {
            $cell->addText($company_details['job-title'].'  _______'.random_int(100000, 999999).'_______', ['size' => 10], ['align' => 'center']);
        }
        $cell->addText('(Ф.И.О., должность ответственного исполнителя)        (подпись)', ['size' => 6], ['align' => 'center']);
        if (is_string($signCode)) {
            $cell->addText('вх. № '.DocumentService::setDocumentNumber().' от «'.date('d').'» '.date('m.Y').' г.');
        } else {
            $cell->addText('вх. № '.DocumentService::setDocumentNumber().' от «___» __________ 20___ г.');
        }
        $row->addCell(2000, $cellStyle)->addText('М.П.', null, ['align' => 'center']);

        return $section;
    }

    private static function saveDocument(User $user, UserRubleAccount $account, Fund $fund, string $document_name, string $signCode = null): string
    {
        $config = config('company_details');
        $path = $config['root_catalog'].'/public/storage/user_'.$user->id.
            '/bank_'.$account->id.'/fund_'.$fund->id.'/'.Str::slug($document_name, '_').'.docx';
        $file = DocumentService::toPdf($path);
        DocumentService::addUserDocuments($document_name, $file, true, $signCode);

        return $file;
    }

    public static function appPurchase(User $user, UserRubleAccount $account, Fund $fund, string|bool $signCode = null): string
    {
        $doc = self::startDocument();
        DocumentService::$docx = $doc;
        DocumentService::$user = $user;
        DocumentService::$hash = $user->id.'_app_account_'.$account->id.'_fund_'.$fund->id;
        $document_name = mb_strtoupper('Заявка на приобретение инвестиционных паев');
        $bold = ['bold' => true];
        $number = DocumentService::setDocumentNumber();
        $colorLine = '000000';
        $styleCell = [
            'valign' => 'center',
            'borderTopColor' => $colorLine,
            'borderTopSize' => 6,
            'borderRightColor' => $colorLine,
            'borderRightSize' => 6,
            'borderBottomColor' => $colorLine,
            'borderBottomSize' => 6,
            'borderLeftColor' => $colorLine,
            'borderLeftSize' => 6,
        ];
        $alignCenter = ['alignment' => Jc::CENTER, 'align' => 'center'];
        $doc->setDefaultFontSize(8);
        $section = $doc->addSection();
        //$section->addImage(ROOT_CATALOG. '/public/images/document_header.png', ['width' => 470, 'height' => 60]);
        //$section->addTextBreak();
        $table = $section->addTable();
        $text = $table->addRow(600)->addCell(3000, ['valign' => 'center'])->addTextRun();
        $text->addText('Заявка № '.$number, $bold);
        $table->addCell(4000, ['valign' => 'center'])->addText('На приобретение инвестиционных паев', $bold);
        $table->addCell(600)->addText('');
        $table->addCell(2000, $styleCell)->addText('Для физических лиц');
        $table->addRow(400)->addCell(3500, $styleCell)->addText('Полное наименование УК', $bold);
        $table->addCell(6100, $styleCell)->addText('Общество с ограниченной ответственностью Управляющая компания «Гамма Групп»', $bold, $alignCenter);
        $text = $table->addRow(600)->addCell(9600, $styleCell)->addTextRun();
        $text->addText('Название Фонда: ', $bold);
        $text->addText($fund->name, $bold, $alignCenter);

        $section->addTextBreak();

        $table = $section->addTable();
        $text = $table->addRow(600)->addCell(2500, $styleCell)->addTextRun();
        $text->addText('Дата принятия заявки', $bold);
        $text->addTextBreak();
        $text->addText('(число, месяц, год)', $bold);
        $table->addCell(2000, $styleCell)->addText(date('d.m.Y'), $bold, $alignCenter);
        $table->addCell(600)->addText();
        $table->addCell(2500, $styleCell)->addText('Время принятия заявки', $bold, $alignCenter);
        $table->addCell(2000, $styleCell)->addText(date('H:i'), $bold, $alignCenter);

        $section->addTextBreak();

        $cellRowSpan = ['vMerge' => 'restart', 'valign' => 'center'];
        $cellRowContinue = ['vMerge' => 'continue'];
        $cellColSpan3 = ['gridSpan' => 3, 'valign' => 'center'];

        $table = $section->addTable();
        $table->addRow(400);
        $table->addCell(2000, [
            'valign' => 'center',
            'borderTopColor' => $colorLine,
            'borderTopSize' => 6,
            'borderRightColor' => $colorLine,
            'borderRightSize' => 6,
            'borderLeftColor' => $colorLine,
            'borderLeftSize' => 6,
        ]);
        $table->addCell(1600, $styleCell)->addText('Фамилия');
        $table->addCell(6000, $styleCell)->addText($user->lastname, $bold);

        $table->addRow(400);
        $table->addCell(2000, [
            'valign' => 'center',
            'borderRightColor' => $colorLine,
            'borderRightSize' => 6,
            'borderLeftColor' => $colorLine,
            'borderLeftSize' => 6,
        ])->addText('Заявитель', $bold, $alignCenter);
        $table->addCell(1600, $styleCell)->addText('Имя');
        $table->addCell(6000, $styleCell)->addText($user->name, $bold);

        $table->addRow(400);
        $table->addCell(2000, [
            'valign' => 'center',
            'borderRightColor' => $colorLine,
            'borderRightSize' => 6,
            'borderBottomColor' => $colorLine,
            'borderBottomSize' => 6,
            'borderLeftColor' => $colorLine,
            'borderLeftSize' => 6, ]);
        $table->addCell(1600, $styleCell)->addText('Отчество');
        $table->addCell(6000, $styleCell)->addText($user->patronymic, $bold);

        $section->addTextBreak();

        $table = $section->addTable();
        $table->addRow(400);
        $table->addCell(2000, array_merge($styleCell, $cellRowSpan))->addText('Документ, удостоверяющий личность', $bold, $alignCenter);
        $table->addCell(1600, $styleCell)->addText('Наименование');
        $table->addCell(3000, $styleCell)->addText('паспорт гражданина РФ', $bold);
        $table->addCell(1500, $styleCell)->addText('Серия');
        $table->addCell(1500, $styleCell)->addText($user->passport->series, $bold);

        $table->addRow(400);
        $table->addCell(2000, [
            'valign' => 'center',
            'vMerge' => 'continue',
            'borderRightColor' => $colorLine,
            'borderRightSize' => 6,
            'borderBottomColor' => $colorLine,
            'borderBottomSize' => 6,
            'borderLeftColor' => $colorLine,
            'borderLeftSize' => 6,
        ]);
        $table->addCell(1600, $styleCell)->addText('Номер');
        $table->addCell(3000, $styleCell)->addText($user->passport->number, $bold);
        $text = $table->addCell(1500, $styleCell)->addTextRun();
        $text->addText('Дата выдачи');
        $text->addTextBreak();
        $text->addText('(ДД.ММ.ГГ)');
        $table->addCell(1500, $styleCell)->addText(date('d.m.Y', strtotime($user->passport->when_issued)), $bold);

        $table->addRow(400);
        $table->addCell(2000, [
            'valign' => 'center',
            'vMerge' => 'continue',
            'borderRightColor' => $colorLine,
            'borderRightSize' => 6,
            'borderBottomColor' => $colorLine,
            'borderBottomSize' => 6,
            'borderLeftColor' => $colorLine,
            'borderLeftSize' => 6,
        ]);
        $table->addCell(1600, $styleCell)->addText('Кем выдан');
        $table->addCell(6000, [
            'valign' => 'center',
            'vMerge' => 'continue',
            'gridSpan' => 3,
            'borderRightColor' => $colorLine,
            'borderRightSize' => 6,
            'borderBottomColor' => $colorLine,
            'borderBottomSize' => 6,
            'borderLeftColor' => $colorLine,
            'borderLeftSize' => 6,
        ])->addText($user->passport->issued_by, $bold);

        $section->addTextBreak();

        $table = $section->addTable();
        $table->addRow(400);
        $table->addCell(3500, $styleCell)->addText('Телефон', $bold);
        $table->addCell(2000, $styleCell)->addText($user->phone, $bold);
        $table->addCell(700)->addText('Или', ['valign' => 'center']);
        $table->addCell(3400, $styleCell)->addText('Адрес эл почты: '.$user->email, $bold);

        $section->addTextBreak();
        $table = $section->addTable();
        $table->addRow(400);
        $table->addCell(2000, array_merge($styleCell, $cellRowSpan))->addText('Уполномоченный представитель', $bold, $alignCenter);
        $table->addCell(1600, $styleCell)->addText('ФИО / Наименование');
        $table->addCell(6000, $styleCell)->addText('', $bold);

        $table->addRow(400);
        $table->addCell(2000, [
            'valign' => 'center',
            'vMerge' => 'continue',
            'borderBottomColor' => $colorLine,
            'borderBottomSize' => 6,
            'borderLeftColor' => $colorLine,
            'borderLeftSize' => 6, ]);
        $table->addCell(1600, $styleCell)->addText('Документ, подтверждающий полномочия');
        $table->addCell(900, $styleCell)->addText('');
        $table->addCell(800, $styleCell)->addText('ФИО / Наименование');
        $table->addCell(700, $styleCell)->addText('Номер');
        $table->addCell(600, $styleCell)->addText('');
        $table->addCell(1000, $styleCell)->addText('Дата выдачи');
        $table->addCell(600, $styleCell)->addText('');
        $table->addCell(600, $styleCell)->addText('Срок действия');
        $table->addCell(400, $styleCell)->addText('с');
        $table->addCell(400, $styleCell)->addText('по');

        $table->addRow(400);
        $text = $table->addCell(2400, [
            'valign' => 'center',
            'vMerge' => 'continue',
            'borderRightColor' => $colorLine,
            'borderRightSize' => 6,
            'borderBottomColor' => $colorLine,
            'borderBottomSize' => 6, ])->addTextRun();
        $text->addText('Для физических лиц', $bold);
        $text->addTextBreak();
        $text->addText('Документ, удостоверяющий личность', ['size' => 6]);
        $table->addCell(2000, $styleCell)->addText('Паспорт гражданина РФ', $bold);
        $table->addCell(800, $styleCell)->addText('Серия');
        $table->addCell(1800, $styleCell)->addText($user->passport->series, $bold);
        $table->addCell(800, $styleCell)->addText('Номер');
        $table->addCell(1800, $styleCell)->addText($user->passport->number, $bold);

        $table->addRow(400);
        $table->addCell(2400, $cellRowContinue);
        $table->addCell(1200, [
            'valign' => 'center',
            'borderTopColor' => $colorLine,
            'borderTopSize' => 6,
            'borderLeftColor' => $colorLine,
            'borderLeftSize' => 6,
            'borderRightColor' => $colorLine,
            'borderRightSize' => 6,
            'borderBottomColor' => $colorLine,
            'borderBottomSize' => 6,
        ])->addText('Кем, когда выдан');
        $table->addCell(5000, $styleCell)->addText($user->passport->issued_by.', дата выдачи '.date('d.m.Y', strtotime($user->passport->when_issued)), $bold);

        $table->addRow(400);
        $text = $table->addCell(3600, $styleCell)->addTextRun();
        $text->addText('Для юридических лиц ', ['bold' => true, 'underline' => 'single']);
        $text->addTextBreak();
        $text->addText('Свид. о рег. №, кем выдано, дата выдачи', ['size' => 6]);
        $table->addCell(6000, $styleCell)->addTextRun()->addText('');

        $table->addRow(400);
        $text = $table->addCell(3600, $styleCell)->addTextRun();
        $text->addText('В лице (Ф.И.О.)');
        $table->addCell(6000, $styleCell)->addTextRun()->addText('');

        $table->addRow(400);
        $text = $table->addCell(3600, $styleCell)->addTextRun();
        $text->addText('Документ, удостоверяющий личность');
        $text->addTextBreak();
        $text->addText('(наименование, серия, номер, кем и когда выдан)', ['size' => 6]);
        $table->addCell(6000, $styleCell)->addTextRun()->addText('');

        $table->addRow(400);
        $text = $table->addCell(3600, $styleCell)->addTextRun();
        $text->addText('Действующий на основании');
        $text->addTextBreak();
        $text->addText('(наимен. документа, №, серия, кем выдан, дата, срок действия)', ['size' => 6]);
        $table->addCell(6000, $styleCell)->addTextRun()->addText('');

        $table->addRow(400);
        $text = $table->addCell(9600, $styleCell)->addTextRun();
        $text->addText('Настоящим прошу зачислить инвестиционные паи (указать верное): ', ['bold' => true]);

        $table->addRow(400);
        $text = $table->addCell(9600, $styleCell)->addTextRun();
        $text->addText(' на счет в реестре Фонда № ___________ ');
        $text->addTextBreak();
        $text->addText(' открыть счет зарегистрированного лица в реестре Фонда');

        $table->addRow(400);
        $text = $table->addCell(9600, $styleCell)->addTextRun();
        $text->addText('Прошу выдавать мне инвестиционные паи Фонда при каждом поступлении денежных средств на счет Фонда');
        $text->addTextBreak();
        $text->addText('Платежный док-т. (указывается в случае предоплаты) №_________________________Дата________________________');

        $table->addRow(400);
        $text = $table->addCell(4800, $styleCell)->addTextRun();
        $text->addText('Реквизиты банковского счета лица, передавшего денежные ');
        $text->addTextBreak();
        $text->addText('средства в оплату инвестиционных паев: ');
        $text->addTextBreak();
        $text->addText('(плательщик, ИНН плательщика, наименование банка, город банка, БИК, к/с, р/с)', ['size' => 6]);
        $inn = null;
        if ($user->inn) {
            $inn = $user->inn->number;
        }
        $appRek = $user->lastname.' '.$user->name.' '.$user->patronymic.', ИНН '.$inn.', '.$account->bank_name.', '
            .$account->bank_country.', БИК '.$account->bic.', к/с '.$account->cor_account.', р/с '
            .$account->payment_account;
        $table->addCell(4800, $styleCell)->addTextRun()->addText($appRek, $bold);

        $table->addRow(400);
        $table->addCell(9600, $styleCell)->addText('Настоящая заявка носит безотзывный характер. Расчетная стоимость инвестиционного пая определяется в соответствии с требованиями Правил Фонда. Положения Правил Фонда являются условиями договора с Управляющим Фондом о доверительном управлении денежными средствами, перечисленными заявителем в состав имущества Фонда, а также иным имуществом, приобретенным в связи с доверительным управлением имуществом, составляющим Фонд. С Правилами Фонда ознакомлен.', ['size' => 6]);

        $section->addTextBreak();
        $table = $section->addTable();
        $table->addRow();
        $text = $table->addCell(1750, $styleCell)->addTextRun();
        $text->addText('Подпись', $bold, $alignCenter);
        $text->addTextBreak();
        $text->addText('Заявителя / ', $bold, $alignCenter);
        $text->addTextBreak();
        $text->addText('Уполномоченного', $bold, $alignCenter);
        $text->addTextBreak();
        $text->addText('представителя', $bold, $alignCenter);

        $text = $table->addCell(1750, $styleCell)->addTextRun();
        $text->addText('', null, $alignCenter);
        $text->addTextBreak();
        if (is_string($signCode)) {
            $text->addText($signCode, null, $alignCenter);
        } else {
            $text->addText('', null, $alignCenter);
        }
        $text->addTextBreak();
        $text->addText('', null, $alignCenter);
        $text->addTextBreak();
        $text->addText('', null, $alignCenter);
        $text->addText($user->lastname.' '.$user->name.' '.$user->patronymic, null, $alignCenter);

        $table->addCell(300)->addText();

        $text = $table->addCell(1750, $styleCell)->addTextRun();
        $text->addText('Подпись ', $bold, $alignCenter);
        $text->addTextBreak();
        $text->addText('лица принявшего', $bold, $alignCenter);
        $text->addTextBreak();
        $text->addText('заявку', $bold, $alignCenter);

        $company_details = config('company_details');

        $text = $table->addCell(1750, $styleCell)->addTextRun();
        $text->addText('', null, $alignCenter);
        $text->addTextBreak();
        if (is_string($signCode)) {
            $text->addText(random_int(10000, 99999), null, $alignCenter);
        } else {
            $text->addText('', null, $alignCenter);
        }
        $text->addTextBreak();
        $text->addText('', null, $alignCenter);
        $text->addTextBreak();
        $text->addText('', null, $alignCenter);
        $text->addText($company_details['office-holder'], null, $alignCenter);
        $table->addCell(300)->addText();
        $table->addCell(2000, $styleCell)->addText('М.П.', null, ['align' => 'center']);
        $section->addTextBreak();
        $text = $section->addTextRun();
        $text->addText('ВНИМАНИЕ: ', $bold);
        $text->addText('В случае оплаты приобретаемых инвестиционных паев Фонда после подачи данной заявки - следует '.
            'обязательно указывать номер данной Заявки в Платежном документе (графа - назначение платежа). При каждой '.
            'последующей оплате рекомендуется связаться с Управляющим Фондом для уточнения реквизитов банковского счета'.'
             Фонда', ['size' => 6]
        );

        return self::saveDocument($user, $account, $fund, $document_name, $signCode);
    }

    public static function requestOpenAccount(User $user, UserRubleAccount $account, Fund $fund, string|bool $signCode = null): ?string
    {
        $doc = self::startDocument();
        DocumentService::$docx = $doc;
        DocumentService::$user = $user;
        DocumentService::$hash = $user->id.'_request_account_'.$account->id.'_fund_'.$fund->id;
        $number = DocumentService::setDocumentNumber();
        $document_name = mb_strtoupper('ЗАЯВЛЕНИЕ ОБ ОТКРЫТИИ ЛИЦЕВОГО СЧЕТА ЗАРЕГИСТРИРОВАННОГО ЛИЦА');
        $tableStyle = [
            'borderColor' => '000000',
            'borderSize' => 6,
            'cellMargin' => 50,
        ];
        $section = self::specDepHeader($doc, $signCode);
        $section->addTextBreak();
        $section->addText('ЗАЯВЛЕНИЕ № '.$number.' ОБ ОТКРЫТИИ ЛИЦЕВОГО СЧЕТА ЗАРЕГИСТРИРОВАННОГО ЛИЦА', ['bold' => true], ['align' => 'center']);
        $section->addTextBreak();
        if (is_string($signCode)) {
            $section->addText('«'.date('d').'» '.date('m.Y').' г.', null, ['align' => 'right']);
        } else {
            $section->addText('«___» ___________ 20___ г.', null, ['align' => 'right']);
        }
        $section->addTextBreak();
        $section->addText('Открытый паевой инвестиционный фонд рыночных финансовых инструментов', ['bold' => true], ['align' => 'center']);
        $section->addText('«'.$fund->name.'»', ['bold' => true], ['align' => 'center']);
        $section->addText(' (Название ПИФ в соответствии с Правилами доверительного управления)', ['size' => 8], ['align' => 'center']);
        $section->addTextBreak();
        $table = $section->addTable($tableStyle);
        $table->addRow()->addCell(9600, ['valign' => 'center', 'bgColor' => 'E0E0E0'])->addText('Сведения о лице, которому открывается лицевой счет:', ['bold' => true]);
        $table->addRow();
        $table->addCell(3000, ['valign' => 'center'])->addText('Фамилия, имя и, если имеется, отчество (полное наименование)', ['bold' => true]);
        $table->addCell(6600, ['valign' => 'center'])->addText($user->lastname.' '.$user->name.' '.$user->patronymic, ['bold' => true]);
        $text = $table->addRow()->addCell(3000, ['valign' => 'center'])->addTextRun();
        $text->addText('Идентификационный номер налогоплательщика – для физических лиц', ['bold' => true]);
        $text->addText(' (при наличии)1', ['size' => 8]);
        $inn = null;
        if ($user->inn) {
            $inn = $user->inn->number;
        }
        $table->addCell(6600, ['valign' => 'center'])->addText($inn, ['bold' => true]);
        $section->addTextBreak();
        $table = $section->addTable($tableStyle)->addRow();
        $table->addCell(3000, ['bgColor' => 'E0E0E0'])->addText('Вид лицевого счета', ['bold' => true]);
        $text = $table->addCell(1650)->addTextRun();
        $text->addFormField('checkbox')->setValue(' - владелец');
        $text->addTextBreak();
        $text->addTextBreak();
        $text->addCheckBox('ch1', ' - депозитный лицевой счет');
        $table->addCell(1650)->addCheckBox('ch2', ' - номинальный держатель');
        $table->addCell(1650)->addCheckBox('ch3', ' - доверительный управляющий');
        $table->addCell(1650)->addCheckBox('ch4', ' - казначейский лицевой счет');
        $section->addTextBreak();
        $section->addTable($tableStyle)->addRow(800)->addCell(9600, ['valign' => 'center', 'bgColor' => 'E0E0E0'])
            ->addText('Прошу открыть лицевой счет в Реестре владельцев инвестиционных паев. Анкета и иные документы предоставлены.', ['bold' => true]);
        $section->addTextBreak();
        $table = $section->addTable($tableStyle)->addRow(800)->addCell(9600, ['valign' => 'center']);
        $text = $table->addTextRun();
        $text->addText('Прошу уведомить об открытии лицевого счета способом, указанным в анкете.', ['bold' => true]);
        $text->addTextBreak();
        $text->addText('(вычеркнуть, если уведомление не требуется)', ['size' => 8]);
        $section->addTextBreak();
        $table = $section->addTable($tableStyle)->addRow();
        $table->addCell(3600)->addText('Способ направления уведомления об отказе в открытии лицевого счета:', ['bold' => true]);
        $table->addCell(2000)->addFormField('checkbox')->setValue(' - в месте подачи данного заявления');
        $table->addCell(2000)->addCheckBox('ch5', ' - у регистратора');
        $address = null;
        if ($user->address_fact()) {
            $address = $user->address_fact()->address;
        }
        $table->addCell(2000)->addCheckBox('ch6', ' - письмо по адресу: '.$address);
        $section->addTextBreak();
        $table = $section->addTable($tableStyle)->addRow();
        $text = $table->addCell(4800)->addTextRun();
        $text->addText('Подпись зарегистрированного лица или его уполномоченного представителя', ['bold' => true]);
        $text->addTextBreak();
        $text->addTextBreak();
        $text->addTextBreak();
        if (is_string($signCode)) {
            $text->addText('________'.$signCode.'___________ / '.$user->lastname.' '.$user->name.' '.$user->patronymic);
        } else {
            $text->addText('_________________ / '.$user->lastname.' '.$user->name.' '.$user->patronymic);
        }
        $text->addTextBreak();
        $text->addText('          подпись                                 Ф.И.О.', null, ['align' => 'center']);
        $text->addTextBreak();
        $text->addTextBreak();
        $text->addText('Реквизиты доверенности, выданной уполномоченному представителю:');
        $text->addTextBreak();
        $text->addText('Доверенность №_______ от «___»_________20__г.');
        $text = $table->addCell(4800)->addTextRun();
        $text->addText('Подпись законного представителя зарегистрированного физического лица ', ['bold' => true]);
        $text->addTextBreak();
        $text->addTextBreak();
        $text->addTextBreak();
        $text->addText('___________________ / ___________________');
        $text->addTextBreak();
        $text->addText('                подпись                                 Ф.И.О.', null, ['align' => 'center']);
        $footer = $section->addFooter();
        $footer->addText('__________________', ['bold' => true]);
        $footer->addText('1  - ИНН физического лица указывается в обязательном порядке при осуществлении обмена электронными документами, '
            .'подписанными квалифицированной и неквалифицированной электронной подписью физического лица. ИНН может не указываться при'
            .' осуществлении обмена электронными документами, подписанными простой электронной подписью физического лица.', ['size' => 8]);

        return self::saveDocument($user, $account, $fund, $document_name, $signCode);
    }
}
