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
    ]

    tools:[
        id:'plus'
        handler:->
            console.log arguments
    ]

    initComponent:->
        @callParent arguments

        Project.getList (response) => @store.loadData response.data