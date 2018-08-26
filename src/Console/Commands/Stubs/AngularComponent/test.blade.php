ngDescribe({
    name: 'Test {{$ng_component}} component',
    modules: 'app',
    element: '<{{$ng_component}}></{{$ng_component}}>',
    tests: function (deps) {

        it('basic test', () => {
            //
        });
    }
});
