Ext.define 'Manager.Application',

    extend: 'Ext.Viewport'
    layout:'border'

    initComponent:->

        @renderTo = Ext.getBody()

        @items = [
            Ext.create 'Manager.ProjectList',
                region:'center'
                border:false
        ]

        @callParent arguments