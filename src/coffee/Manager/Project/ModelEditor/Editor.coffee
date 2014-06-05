Ext.define 'Manager.Project.ModelEditor.Editor',
    extend: 'Ext.grid.Panel'
    height: 400
    width: 800
    initComponent: ->
        @store = Ext.create 'Ext.data.Store',
            fields: ['name','comment']
            proxy: 'memory'
        @columns = [
            dataIndex: 'name'
            header: 'Name'
            width: 150
        ,
            header: 'PK'
            width: 40
            renderer: (v, m, r) ->
                if Ext.Array.indexOf(this.schemaModel.pk, r.data.name) isnt -1
                    'X'
                else
                    ""
        ]
        @callParent arguments

    initByModel: (model) ->
        this.schemaModel = model
        console.log model
        fields = []
        for name, property of this.schemaModel.properties
            property.name = name
            fields.push property
        this.store.loadData fields
