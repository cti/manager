Ext.define 'Manager.Project.Models',
  extend: 'Ext.grid.Panel'
  title: 'Models'
  initComponent: ->
    @store = Ext.create 'Ext.data.Store',
      fields: ['name']
      proxy: 'memory'
    @columns = [
      dataIndex: 'name'
      header: 'Name'
      flex: 1
    ]
    @listeners =
      itemdblclick: (view, record) =>
        @openModelEditor mngr.project.schema.getModel record.data.name

    @tools = [
      id: 'plus'
      handler: =>
        @openModelEditor null
    ]

    @callParent arguments

  load: ->
    models = mngr.project.schema.getModels()
    data = []
    for model in models
      data.push
        name: model.getName()
    @store.loadData data

  openModelEditor: (model) ->
    Ext.create 'Manager.Project.Models.Editor',
      model: model