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
            html: 'Some content'
        ]
        @tools = [
            type: 'prev'
            callback: ->
                mngr.app.openProjectList()
        ]
        @callParent arguments

    getModelsGrid: ->
        unless @modelsGrid
            @modelsGrid = Ext.create 'Manager.Project.Models',
                card: this
                width: 300
        @modelsGrid