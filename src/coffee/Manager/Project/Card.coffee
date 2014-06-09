Ext.define 'Manager.Project.Card',
  extend: 'Ext.panel.Panel'
  title: 'Project Card'
  layout:
    type: 'hbox'
    pack: 'start'
    align: 'stretch'
  initComponent: ->
    @title = @project.data.nick + " card"
    @items = [
      @getModelsGrid()
    ,
      xtype: 'container'
      html: ''
    ]
    @tools = [
      type: 'prev'
      callback: ->
        mngr.app.openProjectList()
    ]
    @callParent arguments
    mngr.project = this
    @loadSchema()

  loadSchema: ->
    Project.getSchema @project.data.nick, (schemaConfig) =>
      @schema = Ext.create 'Manager.Project.Schema.Schema', schemaConfig
      @getModelsGrid().load()

  getModelsGrid: ->
    unless @modelsGrid
      @modelsGrid = Ext.create 'Manager.Project.Models',
        width: 300
    @modelsGrid