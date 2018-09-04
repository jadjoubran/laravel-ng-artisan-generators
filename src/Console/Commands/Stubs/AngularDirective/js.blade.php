class {{$studly_name}}Controller{
    constructor(){
        'ngInject';

        //
    }
}

export function {{$studly_name}}Directive(){
    return {
        controller: {{$studly_name}}Controller,
        link: function(scope, element, attrs, controllers){
            //
        }
    }
}
