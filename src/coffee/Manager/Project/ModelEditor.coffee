Ext.define 'Manager.Project.ModelEditor',
    extend: 'Ext.window.Window',
    layout: 'vbox'
    modal: true
    initComponent: ->
        @title = "Edit model #{@modelName}"
        @items = [
            @getEditor()
        ,
            @getChangesTextContainer()
        ]
        @callParent arguments
        @show()
        Project.getModelData mngr.project.data.nick, @modelName, (data) =>
            @getEditor().initByModel data

    getEditor: ->
        unless @editor
            @editor = Ext.create 'Manager.Project.ModelEditor.Editor'
        @editor


    getChangesTextContainer: ->
        unless @changesText
            @changesText = Ext.create 'Ext.Container',
                style:
                    padding: '5px'
                html: ""
                height: 200
        @changesText

    onChange: () ->
        strings = []
        changes = @collectChanges()
        for propertyName, propertyChanges of changes
            for field, value of propertyChanges
                if field is 'pk'
                    if value is true
                        strings.push "Added field \"#{propertyName}\" to PK"
                    else if value is false
                        strings.push "Removed field \"#{propertyName}\" from PK"
                else
                    strings.push "Changed #{field} of field \"#{propertyName}\" to #{value}"
        @getChangesTextContainer().update(strings.join("<br/>"))

    collectChanges: ->
        changes = {}
        @editor.store.queryBy (record) ->
            recordChanges = record.getChanges()
            changes[record.data.name] = recordChanges unless Ext.Object.isEmpty(recordChanges)
        return changes



