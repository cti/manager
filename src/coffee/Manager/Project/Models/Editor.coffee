Ext.define 'Manager.Project.Models.Editor',
  extend: "Ext.window.Window"
  resizable: false
  modal: true
  layout: 'fit'
  initComponent: ->
    @title = "Edit model #{if @model then @model.getName() else "newmodel"}"
    @items = [
      @getForm()
    ,
      @getGrid()
    ]
    @buttons = [
      text: 'Save'
      handler: => @save()
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

  getForm: ->
    unless @form
      @form = Ext.create 'Ext.form.Panel',
        width: '100%'
        bodyPadding: 5
        items: [
          xtype: 'textfield'
          name: 'model_name_field'
          value: if @model then @model.getName() else ""
          fieldLabel: 'Model name'
          allowBlank: false
        ]
    @form

  save: (changes) ->
    name = @down('[name=model_name_field]').getValue()
    changes = @getGrid().collectChanges()
    mngr.project.schema.applyChanges (if @model then @model.getName() else null), name, changes
    @getGrid().store.queryBy (record) -> record.commit()
    @close()