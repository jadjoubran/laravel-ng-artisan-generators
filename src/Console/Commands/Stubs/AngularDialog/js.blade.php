export class {{$studly_name}}Controller{
    constructor(DialogService){
        'ngInject';

        this.DialogService = DialogService;
    }

    save(){
        //Logic here
        this.DialogService.hide();
    }

    cancel(){
        this.DialogService.cancel();
    }
}

