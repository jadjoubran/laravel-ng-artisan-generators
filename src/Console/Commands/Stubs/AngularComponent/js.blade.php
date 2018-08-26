class {{$studly_name}}Controller{
    constructor(){
        'ngInject';

        //
    }

    $onInit(){
    }
}

export const {{$studly_name}}Component = {
@if($use_mix)
    template: require('./{{$name}}.component.html'),
@else
    templateUrl: './views/app/components/{{$name}}/{{$name}}.component.html',
@endif
    controller: {{$studly_name}}Controller,
    controllerAs: 'vm',
    bindings: {}
};
