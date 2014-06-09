Ext.define 'Manager.ProjectList.Form',
  extend: 'Ext.window.Window'
  title: 'Create project'
  modal: true
  resizable: false
  initComponent: ->
    @items = [
      @getForm()
    ]
    @buttons = [
      text: 'Save'
      handler: =>
        @save()
    ]
    @callParent arguments
    @show()

  getForm: ->
    unless @form
      @form = Ext.create 'Ext.form.Panel',
        bodyPadding: 10
        layout: 'anchor'
        items: [
          xtype: 'textfield'
          fieldLabel: 'Nick'
          allowBlank: false
          name: 'nick'
        ,
          xtype: 'textfield'
          fieldLabel: 'Project root in DocumentRoot'
          emptyText: 'somefolder/myapp'
          allowBlank: false
          name: 'path'
        ]
    @form


  save: ->
    return unless @getForm().isValid()
    data = @getForm().getValues()
    Project.add data, (response) =>
      if response is true
        @close()
        @projectList.load()

