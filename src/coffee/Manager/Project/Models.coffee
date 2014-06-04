Ext.define 'Manager.Project.Models',
    extend: 'Ext.grid.Panel'
    title: 'Models'
    initComponent: ->
        @store = Ext.create 'Ext.data.Store',
            fields: ['name']
            proxy: 'memory'
        @columns = [
            dataIndex: 'name'
            header: "Name"
        ]
        @callParent arguments

    load: ->
