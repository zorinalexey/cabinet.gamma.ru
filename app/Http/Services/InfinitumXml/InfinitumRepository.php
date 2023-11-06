<?php

namespace App\Http\Services\InfinitumXml;

use App\Models\Fund;
use App\Models\Infinitum;
use App\Models\User;
use App\Models\UserDocument;
use App\Models\UserRubleAccount;
use Illuminate\Support\Facades\Storage;

final class InfinitumRepository
{

    public static function appAccount(UserDocument $document, User $user, UserRubleAccount $account, Fund $fund)
    {
        $anketa = Infinitum::where('search_hash', $user->id . '_anketa')->first();
        $company_info = config('company_details');
        $xml = self::getGenerator();
        $xml->addElement('APPLICATION_FOR_ACCOUNT_OPEN', null, [
            'version' => "1", 'xsi:noNamespaceSchemaLocation' => "file:///C:/Схемы/specdep_v142.4-0.xsd",
            'xmlns:xsi' => "http://www.w3.org/2001/XMLSchema-instance"], null, false);
        //Header
        $xml->addElement('version', '1.4.2')
            ->addElement('header', null, null, null, false)
            ->addElement('doc_num', $document->id)
            ->addElement('doc_date', null, null, null, false)
            ->addElement('datetime', date('Y-m-d\TH:i:s'))
            ->closeElement()
            ->closeElement();

        $xml->addElement('doc_id', $document->id);

        // associated_docs
        $xml->addElement('associated_docs', null, null, null, false)
            ->addElement('dominant', 'Yes')
            ->addElement('reflink', null, null, null, false)
            ->addElement('ref_doc_num', $anketa->id)
            ->addElement('ref_doc_date', null, null, null, false)
            ->addElement('datetime', date('Y-m-d\TH:i:s', strtotime($anketa->updated_at)))
            ->closeElement()
            ->addElement('ref_name', 'Анкета зарегистрированного лица')
            ->closeElement()
            ->closeElement();

        // issuer 1
        $xml->addElement('issuer', null, null, null, false)
            ->addElement('issuer_name', null, null, null, false)
            ->addElement('party_id', null, null, null, false)
            ->addElement('id', '0')
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('party_name', $company_info['company_full_name'])
            ->closeElement()
            ->addElement('issuer_type', '01')
            ->closeElement();

        // issuer 2
        $xml->addElement('issuer', null, null, null, false)
            ->addElement('issuer_name', null, null, null, false)
            ->addElement('party_id', null, null, null, false)
            ->addElement('id', $fund->id)
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('party_name', $fund->name)
            ->closeElement()
            ->addElement('issuer_type', '02')
            ->closeElement();

        // agent_point_name
        $xml->addElement('agent_point_name', null, null, null, false)
            ->addElement('agent_name', null, null, null, false)
            ->addElement('id', '1')
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('agent_name_n', $account->bank_name)
            ->addElement('point_name', null, null, null, false)
            ->addElement('id', $account->id)
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('point_name_n', 'Доп.офис')
            ->closeElement();

        $xml->addElement('need_for_statement', 'Yes')
            ->addElement('account_type', '01')
            ->addElement('account_person', $user->lastname . ' ' . $user->name . ' ' . $user->patronymic)
            ->addElement('simple_electronic_signature', 'Yes');

        // consultants
        $xml->addElement('consultants', null, null, null, false)
            ->addElement('consultant', null, null, null, false)
            ->addElement('consultant_id_name', null, null, null, false)
            ->addElement('party_id', null, null, null, false)
            ->addElement('id', $company_info['id'])
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('party_name', $company_info['office-holder'])
            ->closeElement()
            ->addElement('consultant_type', null, null, null, false)
            ->addElement('party_id', null, null, null, false)
            ->addElement('id', '?')
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('party_name', '?')
            ->closeElement()
            ->closeElement()
            ->closeElement();

        // manager
        $xml->addElement('manager', null, null, null, false)
            ->addElement('manager_type', $company_info['job-title'])
            ->addElement('manager_FIO', $company_info['office-holder'])
            ->closeElement();

        $xml->addElement('add_info', ' ');
        $xml->closeElement();

        $xmlDom = $xml->getDomDocument();
        $search_hash = $user->id . '_app_account_' . $account->id . '_fund_' . $fund->id;
        $documentName = 'Заявка на приобретение инвестиционных паев';
        self::save($documentName, $user, $xmlDom, $search_hash);
    }

    private static function getGenerator(): XmlGenerator
    {
        return new XmlGenerator();
    }

    private static function save(string $documentName, User $user, string $content, string $search_hash)
    {
        $documentName = mb_strtoupper($documentName);
        $documentPath = 'infinitum/xml/' . $user->id . '/' . md5($search_hash) . '.xml';
        Storage::drive('local')->put($documentPath, $content);
        if (Storage::drive('local')->exists($documentPath)) {
            $file = Storage::drive('local')->url($documentPath);
            $infinitumRepositoryFile = Infinitum::where('search_hash', $search_hash)->first();
            if (!$infinitumRepositoryFile) {
                $infinitumRepositoryFile = new Infinitum();
            }
            $infinitumRepositoryFile->path = str_replace('/storage', '', $file);
            $infinitumRepositoryFile->name = $documentName;
            $infinitumRepositoryFile->user_id = $user->id;
            $infinitumRepositoryFile->download = false;
            $infinitumRepositoryFile->search_hash = $search_hash;
            $infinitumRepositoryFile->save();
        }
    }

    public static function requestAccount(UserDocument $document, User $user, UserRubleAccount $account, Fund $fund)
    {
        $xml = self::getGenerator();
        $anketa = Infinitum::where('search_hash', $user->id . '_anketa')->first();
        $appAccount = Infinitum::where('search_hash', $user->id . '_app_account_' . $account->id . '_fund_' . $fund->id)->first();
        $company_info = config('company_details');

        $xml->addElement('APPLICATION_TO_ACQUISITION', null, [
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            'version' => '1',
            'xsi:noNamespaceSchemaLocation' => 'file:///C:/Схемы/specdep_v142.4-0.xsd'
        ], null, false);

        //Header
        $xml->addElement('version', '1.4.2')
            ->addElement('header', null, null, null, false)
            ->addElement('doc_num', $document->id)
            ->addElement('doc_date', null, null, null, false)
            ->addElement('datetime', date('Y-m-d\TH:i:s'))
            ->closeElement()
            ->closeElement();

        $xml->addElement('doc_id', $document->id);

        // associated_docs
        $xml->addElement('associated_docs', null, null, null, false)
            ->addElement('dominant', 'Yes')
            ->addElement('reflink', null, null, null, false)
            ->addElement('ref_doc_num', $appAccount->id)
            ->addElement('ref_doc_date', null, null, null, false)
            ->addElement('datetime', date('Y-m-d\TH:i:s', strtotime($appAccount->updated_at)))
            ->closeElement()
            ->addElement('ref_name', 'Заявление об открытии счета зарегистрированного лица')
            ->closeElement()
            ->addElement('reflink', null, null, null, false)
            ->addElement('ref_doc_num', $anketa->id)
            ->addElement('ref_doc_date', null, null, null, false)
            ->addElement('datetime', date('Y-m-d\TH:i:s', strtotime($anketa->updated_at)))
            ->closeElement()
            ->addElement('ref_name', 'Анкета зарегистрированного лица')
            ->closeElement()
            ->closeElement();

        // issuer 1
        $xml->addElement('issuer', null, null, null, false)
            ->addElement('issuer_name', null, null, null, false)
            ->addElement('party_id', null, null, null, false)
            ->addElement('id', $company_info['id'])
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('party_name', $company_info['company_full_name'])
            ->closeElement()
            ->addElement('issuer_type', '01')
            ->closeElement();

        // issuer 2
        $xml->addElement('issuer', null, null, null, false)
            ->addElement('issuer_name', null, null, null, false)
            ->addElement('party_id', null, null, null, false)
            ->addElement('id', $fund->id)
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('party_name', $fund->name)
            ->closeElement()
            ->addElement('issuer_type', '02')
            ->closeElement();

        // agent_point_name
        $xml->addElement('agent_point_name', null, null, null, false)
            ->addElement('agent_name', null, null, null, false)
            ->addElement('id', '1')
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('agent_name_n', $account->bank_name)
            ->addElement('point_name', null, null, null, false)
            ->addElement('id', $account->id)
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('point_name_n', 'Доп.офис')
            ->closeElement();

        // shareholder
        $xml->addElement('shareholder', null, null, null, false)
            ->addElement('account_dtls', null, null, null, false)
            ->addElement('account_id', null, null, null, false)
            ->addElement('id', 'UKWN')
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('account_type', '01')
            ->closeElement()
            ->addElement('shareholder_info', null, null, null, false)
            ->addElement('shareholder_dtls', null, null, null, false)
            ->addElement('individual', null, null, null, false)
            ->addElement('individual_name', $user->lastname . ' ' . $user->name . ' ' . $user->patronymic)
            ->addElement('individual_document', null, null, null, false)
            ->addElement('indv_doc_type', null, null, null, false)
            ->addElement('individual_document_type_code', '21')
            ->addElement('narrative', 'Паспорт гражданина РФ')
            ->closeElement()
            ->addElement('indv_doc_ser', $user->passport->series)
            ->addElement('indv_doc_num', $user->passport->number)
            ->addElement('indv_doc_date', strtotime($user->passport->when_issued))
            ->addElement('org', $user->passport->when_issued)
            ->closeElement()
            ->closeElement()
            ->closeElement()
            ->addElement('bank_prop_rub', null, null, null, false)
            ->addElement('pay_name', 'UKWN')
            ->addElement('cash_rub_dtls', null, null, null, false)
            ->addElement('account', $account->payment_account)
            ->addElement('personal_account', $account->payment_account)
            ->addElement('bank_name', $account->bank_name)
            ->addElement('bank_city', $account->bank_city)
            ->addElement('ruic', $account->bic)
            ->addElement('bank_corr', $account->cor_account)
            ->closeElement()
            ->closeElement()
            ->closeElement()
            ->closeElement();

        $xml->addElement('date_instruction', null, null, null, false)
            ->addElement('datetime', date('Y-m-d\TH:i:s'))
            ->closeElement()
            ->addElement('instruction_type', 'RPTD')
            ->addElement('simple_electronic_signature', 'Yes');

        // consultants
        $xml->addElement('consultants', null, null, null, false)
            ->addElement('consultant', null, null, null, false)
            ->addElement('consultant_id_name', null, null, null, false)
            ->addElement('party_id', null, null, null, false)
            ->addElement('id', $company_info['id'])
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('party_name', $company_info['office-holder'])
            ->closeElement()
            ->addElement('consultant_type', null, null, null, false)
            ->addElement('party_id', null, null, null, false)
            ->addElement('id', '?')
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('party_name', '?')
            ->closeElement()
            ->closeElement()
            ->closeElement();

        // manager
        $xml->addElement('manager', null, null, null, false)
            ->addElement('manager_type', $company_info['job-title'])
            ->addElement('manager_FIO', $company_info['office-holder'])
            ->closeElement();

        $xml->addElement('add_info', ' ');
        $xml->closeElement();
        $xmlDom = $xml->getDomDocument();
        $search_hash = $user->id . '_request_account_' . $account->id . '_fund_' . $fund->id;
        $documentName = 'ЗАЯВЛЕНИЕ ОБ ОТКРЫТИИ ЛИЦЕВОГО СЧЕТА ЗАРЕГИСТРИРОВАННОГО ЛИЦА';
        self::save($documentName, $user, $xmlDom, $search_hash);
    }

    public static function userBlank(UserDocument $document, User $user, UserRubleAccount $account, Fund $fund)
    {
        $xml = self::getGenerator();
        $company_info = config('company_details');
        $regAddr = null;
        $factAddr = null;
        if ($user->address_registration()) {
            $regAddr = $user->address_registration()->address;
        }
        if ($user->address_fact()) {
            $factAddr = $user->address_fact()->address;
        }

        $xml->addElement('FORM_OF_SHAREHOLDERS', null, ['version' => '1'], null, false);

        //Header
        $xml->addElement('version', '1.4.2')
            ->addElement('header', null, null, null, false)
            ->addElement('doc_num', $document->id)
            ->addElement('doc_date', null, null, null, false)
            ->addElement('datetime', date('Y-m-d\TH:i:s'))
            ->closeElement()
            ->closeElement();

        $xml->addElement('doc_id', $document->id);

        // issuer 1
        $xml->addElement('issuer', null, null, null, false)
            ->addElement('issuer_name', null, null, null, false)
            ->addElement('party_id', null, null, null, false)
            ->addElement('id', 'TEST')
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('party_name', $company_info['company_full_name'])
            ->closeElement()
            ->addElement('issuer_type', '01')
            ->closeElement();

        // issuer 2
        $xml->addElement('issuer', null, null, null, false)
            ->addElement('issuer_name', null, null, null, false)
            ->addElement('party_id', null, null, null, false)
            ->addElement('id', $fund->id)
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('party_name', $fund->name)
            ->closeElement()
            ->addElement('issuer_type', '02')
            ->closeElement();


        // agent_point_name
        $xml->addElement('agent_point_name', null, null, null, false)
            ->addElement('agent_name', null, null, null, false)
            ->addElement('id', '1')
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('agent_name_n', $company_info['company_full_name'])
            ->addElement('point_name', null, null, null, false)
            ->addElement('id', '2')
            ->addElement('organization', 'SPED')
            ->closeElement()
            ->addElement('point_name_n', $company_info['company_full_name'])
            ->closeElement();

        $xml->addElement('form_for', 'ACCH');

        // account_id
        $xml->addElement('account_id', null, null, null, false)
            ->addElement('id', $company_info['id'])
            ->addElement('organization', 'SPED')
            ->closeElement();

        $xml->addElement('shareholder', null, null, null, false)
            ->addElement('shareholder_info', null, null, null, false)
            ->addElement('shareholder_dtls', null, null, null, false)
            ->addElement('individual', null, null, null, false)
            ->addElement('individual_name', $user->lastname . ' ' . $user->name . ' ' . $user->patronymic)
            ->addElement('nationality', 'РОССИЯ')
            ->addElement('individual_document', null, null, null, false)
            ->addElement('indv_doc_type', null, null, null, false)
            ->addElement('individual_document_type_code', '21')
            ->addElement('narrative', 'Паспорт гражданина РФ')
            ->closeElement()
            ->addElement('indv_doc_ser', $user->passport->series)
            ->addElement('indv_doc_num', $user->passport->number)
            ->addElement('indv_doc_date', strtotime($user->passport->when_issued))
            ->addElement('org', $user->passport->when_issued)
            ->closeElement()
            ->addElement('birthday', date('Y-m-d', strtotime($user->birth_date)))
            ->addElement('place_of_birth', $user->birth_place)
            ->addElement('legal_address', null, null, null, false)
            ->addElement('plain', $regAddr)
            ->closeElement()
            ->addElement('post_address', null, null, null, false)
            ->addElement('plain', $factAddr)
            ->closeElement()
            ->addElement('ability', 'full')
            ->closeElement()
            ->addElement('shareholder_contacts', null, null, null, false)
            ->addElement('phone_or_fax', null, null, null, false)
            ->addElement('phone_num', '+' . preg_replace('~(\D)~u', '', $user->phone))
            ->addElement('phone_type', 'BIZZ')
            ->closeElement()
            ->closeElement()
            ->addElement('bank_prop_rub', null, null, null, false)
            ->addElement('pay_name', 'UKWN')
            ->addElement('cash_rub_dtls', null, null, null, false)
            ->addElement('account', $account->payment_account)
            ->addElement('personal_account', $account->payment_account)
            ->addElement('bank_name', $account->bank_name)
            ->addElement('bank_city', $account->bank_city)
            ->addElement('ruic', $account->bic)
            ->addElement('bank_corr', $account->cor_account)
            ->closeElement()
            ->closeElement()
            ->closeElement()
            ->closeElement();


        $xml->addElement('ownership', null, null, null, false)
            ->addElement('ownership_type', 'NTJN')
            ->closeElement();

        $xml->closeElement();
        $xml->addElement('letter_go_type', 'AGNT');
        $xml->addElement('consent_to_process', 'Yes');
        $xml->addElement('simple_electronic_signature', 'Yes');
        $xml->addElement('date_form', null, null, null, false)
            ->addElement('datetime', date('Y-m-d\TH:i:s'))
            ->closeElement();

        // manager
        $xml->addElement('manager', null, null, null, false)
            ->addElement('manager_type', $company_info['job-title'])
            ->addElement('manager_FIO', $company_info['office-holder'])
            ->closeElement();

        $xml->closeElement();

        $xmlDom = $xml->getDomDocument();
        $search_hash = $user->id . '_anketa';
        $documentName = 'АНКЕТА ЗАРЕГИСТРИРОВАННОГО ФИЗИЧЕСКОГО ЛИЦА В РЕЕСТРЕ ВЛАДЕЛЬЦЕВ ИНВЕСТИЦИОННЫХ ПАЕВ';
        self::save($documentName, $user, $xmlDom, $search_hash);
    }
}
