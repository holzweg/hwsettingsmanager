function RealTypeOf(v) {
  if (typeof(v) == "object") {
    if (v === null) return "null";
    if (v.constructor == (new Array).constructor) return "array";
    if (v.constructor == (new Date).constructor) return "date";
    if (v.constructor == (new RegExp).constructor) return "regex";
    return "object";
  }
  return typeof(v);
}

Ext.Loader.setConfig({enabled: true});
Ext.Loader.setPath('Ext.ux', 'http://cdn.sencha.io/ext-4.1.0-gpl/examples/ux');
Ext.require([
    'Ext.ux.grid.FiltersFeature'
]);

Ext.onReady(function(){

    Ext.tip.QuickTipManager.init();

    if(hwsm_SelectedIniFile == "") {
        return;
    }

    Ext.Ajax.request({
        url: '/settingsmanager/data',
        params: {
            inifile: hwsm_SelectedIniFile
        },
        success: function(response){
            var text = response.responseText;
            var json = Ext.JSON.decode(text);

            // Define grid column value renderer
            var ezGridValueRenderer = function(value, meta, record, rowIndex, colIndex) {
                var type = RealTypeOf(value);

                if(type == "array") {
                    var resultArray = [];
                    for (var k in value) {
                        resultArray.push("<span class='hwsm-array'>" + $('<div/>').text(value[k]).html() + "</span>");
                    }
                    return resultArray.join(",<br />");
                }else if(type == "object") {
                    var resultArray = [];
                    for (var k in value) {
                        resultArray.push("<span class='hwsm-key'>" + $('<div/>').text(k).html() + ":</span> <span class='hwsm-array'>" + $('<div/>').text(value[k]).html() + "</span>");
                    }
                    return resultArray.join(",<br />");
                }

                return $('<div/>').text(value).html();
            }

            // Define model and columns
            var modelFields = [
                {name: 'index', type: 'int'},
                {name: 'key', type: 'string'},
                {name: 'group', type: 'string'},
                {name: 'type', type: 'string'},

                {name: 'default'},
                {name: 'override'}
            ];

            // Define column fields
            var gridColumns = [{
                text: 'Key',
                width: 200,
                tdCls: 'task',
                dataIndex: 'key',
                hideable: false,
                filter: {type: 'string'}
            },{
                header: 'override',
                flex: 1,
                dataIndex: 'override',
                renderer: ezGridValueRenderer,
                filter: {type: 'string'}
            }];

            // Prepend siteaccesses to model and columns
            for(var sa in json.siteaccesses) {
                modelFields.push({
                    name: "sa_" + json.siteaccesses[sa]
                });
                gridColumns.push({
                    header: json.siteaccesses[sa],
                    flex: 1,
                    dataIndex: "sa_" + json.siteaccesses[sa],
                    renderer: ezGridValueRenderer,
                    filter: {type: 'string'}
                });
            }
            gridColumns.push({
                header: 'default',
                flex: 1,
                dataIndex: 'default',
                renderer: ezGridValueRenderer,
                filter: {type: 'string'}
            });

            // Create model
            Ext.define('Settings', {
                extend: 'Ext.data.Model',
                idProperty: 'key',
                fields: modelFields
            });

            // Define store
            var store = Ext.create('Ext.data.Store', {
                model: 'Settings',
                data: json.data,
                sorters: {property: 'index', direction: 'ASC'},
                groupField: 'group'
            });

            // Create grid
            var grid = Ext.create('Ext.grid.Panel', {
                width: 'auto',
                height: 'auto',
                frame: false,
                iconCls: 'icon-grid',
                renderTo: "settingsmanager-container",
                store: store,
                sortableColumns: false,
                selModel: {
                    selType: 'cellmodel'
                },
                features: [{
                    id: 'group',
                    ftype: 'groupingsummary',
                    groupHeaderTpl: '{name}',
                    hideGroupedHeader: false,
                    enableGroupingMenu: false
                },{
                    ftype: 'filters',
                    autoReload: false,
                    local: true
                }],
                columns: gridColumns,
                listeners: {
                    celldblclick: function(dataview, td, cellIndex, record, tr, rowIndex, e) {
                        var dataindex = this.columns[cellIndex].dataIndex;
                        if(dataindex == "key") {
                            return false;
                        }
                        var key = record.get("key");
                        var group = record.get("group");
                        Ext.Ajax.request({
                            url: '/settingsmanager/textmate',
                            params: {
                                inifile: hwsm_SelectedIniFile,
                                dataindex: dataindex,
                                group: group,
                                key: key
                            },
                            success: function(response) {
                                if(response.responseText != "") {
                                    window.location = response.responseText;
                                }
                            }
                        });
                    }
                }
            });

        }
    });

});