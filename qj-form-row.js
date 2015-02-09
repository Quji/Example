define([
    'app'
], function ( app )
{
    app.directive('qjFormRow', function ()
    {
        return {
            require: '^qjForm',
            scope: {
                name: '@qjName',
                has_error: '@'
            },
            compile: function ()
            {
                return {
                    pre: function ( $scope, element, iAttrs, _controller )
                    {
                        $scope.controller   = _controller;
                        $scope.Model = $scope.controller.getModel();
                    }
                };
            },
            controller: {
                methods: {
                    registerInput: function( name )
                    {
                        this.$.controller.registerInput(name, this);
                    },
                    getName: function ()
                    {
                        return this.$.name;
                    },
                    getModel: function ()
                    {
                        return this.$.Model;
                    },
                    clearError: function ()
                    {
                        this.$.has_error = '';
                    },
                    setError: function ( error )
                    {
                        this.$.has_error = error;
                    }
                }
            },
            restrict: 'AE',
            transclude: true,
            template: '<div class="form-group" ng-class="{\'has-error\': has_error}"><div ng-transclude></div><span class="help-block">{{ has_error }}</span></div>'
        };
    });
});
