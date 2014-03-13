(function(angular){
    "use strict";

    var skeletonData = {
            global: {
                isCombined: false,
                viewMode: 'map',
                filterEntity: null,
                locationFilters: {
                    enabled: null, // circle, address, neighborhood
                    circle: {
                        center: {
                            lat: null,
                            lng: null
                        },
                        radius: null,
                    },
                    neighborhood: {
                        center: {
                            lat: null,
                            lng: null
                        },
                        radius: 2000
                    },
                    address: {
                        text: '',
                        center: {
                            lat: null,
                            lng: null
                        },
                    }
                },
                map: {
                    zoom: null,
                    center: {
                        lat: null,
                        lng: null
                    }
                },
                enabled: {
                    agent: false,
                    space: false,
                    event: false
                }
            },
            agent: {
                isVerified: false,
                keyword: '',
                areas: [],
                type: null,
                id: null
            },
            space: {
                keyword: '',
                areas: [],
                types: [],
                acessibilidade: false,
                id: null
            },
            event: {
                keyword: '',
                linguagens: [],
                from: null,
                to: null,
                classificacaoEtaria: null
            }
        };

    var diffFilter = function (input) {
        return _diffFilter(input, skeletonData);
    };

    var isEmpty = function (value) {
        if(typeof value === 'undefined' ||
           value === null) return true;

        if(angular.isObject(value)) {
            if(angular.equals(value, {}) ||
               angular.equals(value, []))
                return true;
        }

        return false;
    };

    var _diffFilter = function (input, skeleton) {
        // returns the difference from the input structure and skeleton
        // don't include nulls

        if(typeof input === 'undefined' || typeof skeleton === 'undefined' || input === skeleton) return;

        if(!angular.isObject(input)|| angular.isArray(skeleton)) {
            return input;
        }

        var output = {};

        angular.forEach(input, function(value, key){
            var currVal = _diffFilter(value, skeleton[key]);

            if(isEmpty(currVal)) return;
            this[key] = currVal;
        }, output);

        return output;
    };

    var app = angular.module('search', ['ng-mapasculturais', 'SearchService', 'SearchMap', 'SearchSpatial', 'rison']);

    app.controller('SearchController', ['$scope', '$rootScope', '$location', '$rison', '$window', '$timeout', 'SearchService', function($scope, $rootScope, $location, $rison, $window, $timeout, SearchService){
        $scope.data = angular.copy(skeletonData);
        $scope.data.global.filterEntity = 'agent';
        $scope.data.global.enabled.agent = true;

        $scope.areas = MapasCulturais.taxonomyTerms.area.map(function(el, i){ return {id: i, name: el}; });
        MapasCulturais.entityTypes.agent.push({id:null, name: 'Todos'});
        $scope.types = MapasCulturais.entityTypes;

        $scope.dataChange = function(newValue, oldValue){
            newValue = $scope.data;
            if(newValue === undefined) return;
            var serialized = $rison.stringify(diffFilter(newValue));

            if($location.hash() !== serialized){
                $location.hash(serialized);
                $timeout.cancel($scope.timer);
                $scope.timer = $timeout(function() {
                    $rootScope.$emit('searchDataChange', $scope.data);
                }, 1000);
            }
        };
        $scope.$watch('data', $scope.dataChange, true);

        $rootScope.$on('$locationChangeSuccess', function(){
            var newValue = $location.hash();

            if(newValue && newValue !== $rison.stringify(diffFilter($scope.data))){
                $scope.data = angular.extend(angular.copy(skeletonData), $rison.parse(newValue));
                $rootScope.$emit('searchDataChange', $scope.data);

            }
        });

        $rootScope.$on('searchDataChange', function(ev, data) {
            console.log('searchDataChange emitted', data);
            SearchService($scope.data);
        });

        $scope.getName = function(valores, id){
            return valores.filter(function(e){if(e.id === id) return true;})[0].name;
        };

        $scope.isSelected = function(array, id){
            return (array.indexOf(id) !== -1);
        };

        $scope.toggleSelection = function(array, id){
            var index = array.indexOf(id);
            if(index !== -1){
                array.splice(index, 1);
            } else {
                array.push(id);
            }
        };

        $scope.hasFilter = function() {
            return !angular.equals(_diffFilter($scope.data.agent, skeletonData.agent), {});
        };

        $scope.cleanAllFilters = function () {
            $scope.data.agent = angular.copy(skeletonData.agent);
        };

        $scope.tabClick = function(entity){
            $scope.data.global.filterEntity = entity;
        };

    }]);
})(angular);
