Ext.define 'Manager.ProjectList',

    title: 'Projects'
    extend: 'Ext.grid.Panel'

    store: fields: ['nick', 'path']
    columns: [
        dataIndex:'nick'
        header:'Nick'
        width:120
    ,
        dataIndex:'path'
        header:'Path'
        width:350
    ,
        menuDisabled: true
        width: 40
        xtype: 'actioncolumn'
        items: [
            iconCls: 'open-project-col'
            tooltip: '123'
            handler: (grid, rowIndex) ->
                record = grid.getStore().getAt rowIndex
                grid.ownerCt.openProject record


        ]
    ]

    initComponent:->
        @tools = [
            id:'plus'
            handler:=>
                Ext.create 'Manager.ProjectList.Form',
                    projectList: this
        ]

        @callParent arguments
        @load()

    load: ->
        Project.getList (response) => @store.loadData response

    openProject: (project) ->
        mngr.app.setContent Ext.create 'Manager.Project.Card',
            project: project