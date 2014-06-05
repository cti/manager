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
            flex: 1
        ]
        @listeners =
            itemdblclick: (view, record) =>
                @openModelEditor record
        @callParent arguments
        @load()

    load: ->
        Project.getModels mngr.project.data.nick, (models) =>
            @store.loadData models

    openModelEditor: (model) ->
        Ext.create 'Manager.Project.ModelEditor',
            modelName: model.data.name
