class drawUpShares {

    openDialogClass = 'action-dialog--visible';
    openModalId = false;
    name = 'buy';
    fund;
    token;
    userData = {};

    constructor(accounts, fund) {
        this.accounts = accounts;
        this.fund = fund;
        this.setup();
        let blocks = document.getElementsByName('csrf-token');
        this.token = blocks[0].getAttribute('content');
    }

    block(id) {
        let block = document.getElementById(id);
        if (block) {
            return block;
        }
        return false;
    }

    openWindow(id) {
        let modal = this.block(id);
        if (modal) {
            modal.classList.add(this.openDialogClass);
            this.openModalId = id;
        }
        return modal;
    }

    closeWindow(id) {
        let modal = this.block(id);
        if (modal) {
            modal.classList.remove(this.openDialogClass);
            this.openModalId = false;
        }
        return modal;
    }

    closeActivWindow() {
        this.closeWindow(this.openModalId);
    }

    buyStep1Id = 'buyDialog1';
    buyStep2Id = 'buyDialog2';
    buyStep3Id = 'buyDialog3';
    buyStep4Id = 'buyDialog4';
    accountListId = 'accountList';
    inputAccountId = 'accountName';
    account;
    documents;

    buyStep1() {
        this.hasDelay();
        this.openWindow(this.buyStep1Id);
    }

    openQueryAccount() {
        this.block(this.accountListId).style.height = 'auto';
    }

    buttonStep1Id = 'buttonStep1';

    async selectAccount(block) {
        let account = JSON.parse(block.getAttribute('data'));
        let text = account.bank_name + ' ' + account.payment_account;
        this.account = account;
        this.block(this.inputAccountId).value = text;
        this.block(this.accountListId).style.height = 0;
        this.hasDelay();
        if (await this.getDocuments()) {
            this.buyStep2();
        }
    }

    hasDelay() {
        if (this.block(this.inputAccountId).value) {
            this.block(this.buttonStep1Id).style.display = 'block';
        } else {
            this.block(this.buttonStep1Id).style.display = 'none';
        }
    }

    appFileId = 'appFile';
    anketaFileId = 'anketaFile';
    requestFileId = 'requestFile';

    setFiles() {
        let appFile = this.block(this.appFileId);
        let anketaFile = this.block(this.anketaFileId);
        let requestFile = this.block(this.requestFileId);
        console.log(this.userData.body);
        if(this.userData.body.purchaseRequest){
            appFile.href = this.userData.body.purchaseRequest.path;
        }
        anketaFile.href = this.userData.body.blank.path;
        requestFile.href = this.userData.body.appOpenPersonalAccount.path;
        return true;
    }

    async getDocuments() {
        let data = [];
        data.push(this.account);
        data.push(user);
        data.push(this.fund);
        let response = await this.send('/api/pay/funds', data);
        let params = await response.json();
        if (!params.error) {
            this.userData = params;
            return this.setFiles();
        }
    }

    async send(url, data, method = 'POST') {
        return await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-TOKEN': this.token
            },
            body: JSON.stringify(data)
        });
    }

    checkInfoByMoneyId = 'InfoByMoney';
    sendCodeButtonId = 'sendCodeButton';
    buyStep2() {
        this.closeActivWindow();
        this.openWindow(this.buyStep2Id);
        this.openButtonSendCode();
    }

    openButtonSendCode() {
        if (this.block(this.checkInfoByMoneyId).checked) {
            this.block(this.sendCodeButtonId).style.display = '';
        } else {
            this.block(this.sendCodeButtonId).style.display = 'none';
        }
        this.block(this.inputCodeBlockId).style.display = 'none';
    }

    inputCodeBlockId = 'inputCode';

    async sendCode() {
        let data = [];
        data.push(this.userData.body);
        data.push(user);
        let response = await this.send('/api/send/codeDocuments', data);
        let result = await response.json();
        if (!result.errors) {
            this.block(this.sendCodeButtonId).style.display = 'none';
            this.block(this.inputCodeBlockId).style.display = 'block';
        }else{

        }
    }

    messageBlockId = 'message';
    async signDocuments() {
        let code = this.block('buy_code').value;
        let data = [];
        if(code.length > 5){
            this.block(this.messageBlockId).textContent = '';
            data.push(this.userData.body);
            data.push(code);
            data.push(user);
            data.push(this.fund);
            data.push(this.accounts);
            let response = await this.send('/api/sign/documents', data);
            let result = await response.json();
            console.log(result)
            if (result.error) {
                this.block(this.messageBlockId).textContent = result.error;
            } else {
                this.buyStep3();
            }
        }else{
            this.block(this.messageBlockId).textContent = 'Вы не полностью ввели проверочный код';
        }
    }

    buyStep3() {
        this.closeActivWindow();
        this.openWindow(this.buyStep3Id);
    }

    rekvisiteBlockId = 'rekvisite';
    qrCodeBlockId = 'qr-code';
    notActivColorText = '#909090';
    activColorText = '#2d3a2e';
    rekvisiteBlockTextId = 'rekvizitText';
    qrCodeBlockTextId = 'qrText';

    async openRekvisite() {
        this.block(this.qrCodeBlockId).style.display = 'none';
        this.block(this.rekvisiteBlockId).style.display = 'block';
        this.block(this.qrCodeBlockTextId).style.color = this.notActivColorText;
        this.block(this.rekvisiteBlockTextId).style.color = this.activColorText;
    }
    openQrCode() {
        this.block(this.qrCodeBlockId).style.display = 'block';
        this.block(this.rekvisiteBlockId).style.display = 'none';
        this.block(this.qrCodeBlockTextId).style.color = this.activColorText;
        this.block(this.rekvisiteBlockTextId).style.color = this.notActivColorText;
    }

    endStep() {
        this.closeActivWindow();
        this.buyStep4();
    }

    buyStep4() {
        this.closeActivWindow();
        this.openWindow(this.buyStep4Id);

    }

    setup() {
        document.addEventListener('DOMContentLoaded', this.click());
    }

    click() {
        let blocks = [
            this.buyStep1Id,
            this.buyStep2Id,
            this.buyStep3Id,
            this.buyStep4Id
        ];
        for (let el of blocks) {
            document.addEventListener('click', (e) => {
                var modal = this.block(el);
                if (e.target === modal) {
                    this.closeActivWindow();
                }
            });
        }
    }

}



