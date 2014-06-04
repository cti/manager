Ext.define 'Manager.Project.Card',
    extend: 'Ext.panel.Panel'
    title: 'Project Card'
    initComponent: ->
        @title = @project.data.nick + " card"
        @items = []
        @tools = [
            type: 'prev'
            callback: ->
                mngr.app.openProjectList()
        ]
        @callParent arguments