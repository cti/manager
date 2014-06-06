Ext.define 'Manager.Project.ModelEditor.Editor',
    extend: 'Ext.grid.Panel'
    height: 400
    width: 800
    xtype: 'cell-editing'
    initComponent: ->
        @cellEditing  = new Ext.grid.plugin.CellEditing
            clicksToEdit: 1
        @cellEditing.on 'edit', @onEditComplete, this
        @plugins = [@cellEditing]
        @dockedItems = [
            xtype: 'toolbar'
            items: [
                text: 'Add field'
                iconCls: 'icon-add'
                handler: =>
                    @store.add {}
            ,
                text: 'Delete field'
                iconCls: 'icon-remove'
                disabled: true
            ,
                '->'
            ,
                text: 'Save'
                iconCls: 'icon-save'
                disabled: true
            ]
        ]
        @store = Ext.create 'Ext.data.Store',
            fields: ['name','comment']
            proxy: 'memory'
            listeners:
                update: (store, record, operation, modifiedFields) =>
                    @ownerCt.onChange record, modifiedFields[0], record.get(modifiedFields[0])

        @columns = [
            dataIndex: 'name'
            header: 'Name'
            width: 150
            editor:
                allowBlank: false
        ,
            dataIndex: 'type'
            header: 'Type'
            editor: new Ext.form.field.ComboBox
                typeAhead: true
                triggerAction: 'all'
                editable: false
                store: [
                    ['integer', 'integer'],
                    ['string', 'string'],
                    ['datetime', 'datetime']
                ]
        ,
            xtype: 'checkcolumn'
            header: 'PK'
            dataIndex: 'pk'
            width: 50
        ,
            xtype: 'checkcolumn'
            header: 'NotNull'
            dataIndex: 'required'
            width: 100
        ,
            header: "FK"
            dataIndex: 'fk',
            width: 50
        ,
            header: 'Comment'
            flex: 1
            dataIndex: 'comment'
            editor:
                allowBlank: true

        ]
        @callParent arguments

    initByModel: (model) ->
        this.schemaModel = model
        console.log model
        fields = []
        for name, property of this.schemaModel.properties
            property.name = name
            property.pk = Ext.Array.indexOf(model.pk, name) isnt -1
            fields.push property
        this.store.loadData fields

    onEditComplete: (editor, context) ->
        this.getView().focusRow context.record