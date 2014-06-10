Ext.define 'Manager.Project.Models.Editor',
  extend: "Ext.window.Window"
  resizable: false
  modal: true
  layout: 'fit'
  initComponent: ->
    @title = "Edit model #{@model.getName()}"
    @items = [
      @getGrid()
    ]
    @callParent arguments
    @show()
  getGrid: ->
    unless @grid
      @grid = Ext.create 'Manager.Project.Models.Editor.Grid',
        model: @model
        height: 400
        width: 600
    @grid

  save: (changes) ->
    mngr.project.schema.applyChanges @model.getName(), changes
