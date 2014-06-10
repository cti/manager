Ext.define 'Manager.Project.Models.Editor.Grid',
  extend: 'Ext.grid.Panel'
  requires: ['Manager.Project.Models.Editor.PropertyModel']
  xtype: 'cell-editing'
  initComponent: ->
    this.cellEditing = new Ext.grid.plugin.CellEditing
      clicksToEdit: 1

    @plugins = [this.cellEditing]

    types = [
      ['integer', 'integer'],
      ['string', 'string'],
      ['datetime', 'datetime'],
      ['boolean', 'boolean'],
      ['clob', 'clob'],
    ]

    @store = Ext.create 'Ext.data.Store',
      model: 'Manager.Project.Models.Editor.PropertyModel'
      proxy: 'memory'

    @columns = [
      header: 'Name'
      dataIndex: 'name'
      editor:
        allowBlank: false
    ,
      xtype: 'checkcolumn'
      header: 'PK'
      dataIndex: 'pk'
      width: 40
    ,
      header: 'Type'
      dataIndex: 'type'
      editor: new Ext.form.field.ComboBox
        typeAhead: true
        triggerAction: 'all'
        store: types
    ,
      header: 'Comment'
      dataIndex: 'comment'
      flex: 1
      editor:
        allowBlank: true
    ]

    @dockedItems = [
      xtype: 'toolbar'
      items: [
        text: 'Add field'
        iconCls: 'icon-add'
        handler: => @store.add name: 'newfield'
      ,
        name: 'delete_button'
        text: 'Delete field'
        iconCls: 'icon-remove'
        disabled: true
        handler: => @store.remove @getSelection()[0]
      ,
        '->'
      ,
        text: 'Save'
        iconCls: 'icon-save'
        handler: =>
          @ownerCt.save @collectChanges()
          @store.queryBy (record) -> record.commit()

      ]
    ]

    @listeners =
      selectionchange: (self, record) =>
        @down('[name=delete_button]').setDisabled not record.length

    @callParent arguments
    @load()

  load: ->
    data = []
    for property in @model.getProperties()
      data.push Ext.clone property
    @store.loadData data

  collectChanges: ->
    updated: @store.getUpdatedRecords()
    new: @store.getNewRecords()
    removed: @store.getRemovedRecords()