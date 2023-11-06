class Payment {

    accountName = 'accountName';
    appFile = 'appFile';
    anketaFile = 'anketaFile';
    requestFile = 'requestFile';
    buyDialog1 = 'buy_dialog_1';
    buyDialog2 = 'buy_dialog_2';
    userData = false;
    openDialogClass = 'action-dialog--visible';
    childBlockClass = 'buy__dialog-column';
    user = false;
    activWindow = false;
    childIndex;
    activBlockIndex;
    sendCodeButton = 'buttonSendCode';
    checkboxConfirmationId = 'confirmation';
    send = false;
    checkboxBlock = 'checkboxBlock';
    sendButtonBlock = 'sendButtonBlock';
    hiddenClass = 'hidden';
    inputCodeBlock = 'inputCodeBlock';
    codeMessageBlock = 'codeMessage';

    constructor(user) {
        this.user = user;
        if (this.activWindow) {
            this.activBlockIndex = this.activWindow.style.zIndex;
            this.childIndex = this.activBlockIndex + 1;
        }

    }

    block(id) {
        let block = document.getElementById(id);
        if (block) {
            return block;
        } else {
            console.log('Блок с идентификатором ' + id + ' не найден');
            return false;
        }
    }

    inputAccount() {
        this.block('accountList').style.height = 'auto';
    }

    async setAccount(account) {
        this.closeActivDialog();
        this.openModalWindow(this.buyDialog2);
        let accountName = this.block(this.accountName);
        accountName.value = account.BankName + ' ' + account.PaymentAccount;
        let data = new Array();
        data.push(account);
        data.push(user);
        let response = await fetch('/api/pay/funds', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify(data)
        });
        if (response.ok) {
            this.userData = await response.json();
        }
        this.setFiles();
    }

    setFiles() {
        let appFile = this.block(this.appFile);
        let anketaFile = this.block(this.anketaFile);
        let requestFile = this.block(this.requestFile);
        appFile.href = this.userData.body.applicationForOpeningPersonalAccountFile;
        anketaFile.href = this.userData.body.anketaFile;
        requestFile.href = this.userData.body.applicationForOpeningPersonalAccountFile;
    }
    dialogExitClass = 'dialog__exit';

    closeActivDialog() {
        if (this.activWindow) {
            this.activWindow.classList.remove(this.openDialogClass);
            this.activWindow.classList.add(this.dialogExitClass);
        }
        this.activWindow = false;
    }

    openModalWindow(id) {
        let block = this.block(id);
        block.classList.add(this.openDialogClass);
        this.activWindow = block;
    }

    openDialog() {
//        let accounts = this.user.RubleAccounts;
//        if (accounts.length > 1) {
        this.openModalWindow(this.buyDialog1);
//        } else {
//            this.setAccount(accounts[0]);
//        }
    }

    openButtonSendCode(input) {
        let button = this.block(this.sendCodeButton);
        if (input.checked) {
            button.style.display = 'block';
        } else {
            button.style.display = 'none';
        }
    }

    async sendCode(button) {
        let input = this.block(this.checkboxConfirmationId);
        if (input.checked && !this.send) {
            this.send = true;
            button.style.display = 'none';
            this.block(this.checkboxBlock).style.display = 'none';
            this.block(this.sendButtonBlock).classList.add(this.hiddenClass);
            this.block(this.inputCodeBlock).classList.remove(this.hiddenClass);
        }
    }

    async signDocuments() {
        this.send = false;
        this.block(this.inputCodeBlock).classList.add(this.hiddenClass);
        this.closeActivDialog();
        this.openModalWindow('buy_dialog_3');

    }
    
    goodPay(){
        this.closeActivDialog();
        this.openModalWindow('buy_dialog_4');
    }

    set() {
        let blocks = document.getElementsByClassName('action-dialog');
        for (let block of blocks) {
//            block.addEventListener('click', this.closeActivDialog());
//            let index = block.style.zIndex;
        }
    }

}
