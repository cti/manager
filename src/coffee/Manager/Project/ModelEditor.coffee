Ext.define 'Manager.Project.ModelEditor',
    extend: 'Ext.window.Window',
    modal: true
    initComponent: ->
        @title = "Edit model #{@modelName}"
        @items = [
            @getEditor()
        ]
        @callParent arguments
        @show()
        Project.getModelData mngr.project.data.nick, @modelName, (data) =>
            @getEditor().initByModel data

    getEditor: ->
        unless @editor
            @editor = Ext.create 'Manager.Project.ModelEditor.Editor'
        @editor




