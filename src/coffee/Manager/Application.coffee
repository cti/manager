Ext.direct.Manager.on
  exception: (e) ->
    text = if e.type is 'exception' then e.result else ("#{e.message}\n#{if e.xhr then e.xhr.responseText else ''}")
    alert text
    console.log text

Ext.direct.Manager.getProvider(0).on({
    call: ->
        Ext.getBody().mask("Загрузка")
    data: ->
        Ext.getBody().unmask()
});


# Global container
window.mngr = {}

Ext.define 'Manager.Application',

    extend: 'Ext.Viewport'
    layout:'border'

    initComponent:->

        @renderTo = Ext.getBody()

        @items = [
            @getContainerPanel()
        ]

        @callParent arguments

        mngr.app = this
        @openProjectList()

    getContainerPanel: ->
        unless @containerPanel
            @containerPanel = Ext.create 'Ext.panel.Panel',
                region: 'center'
                layout: 'fit'
        @containerPanel

    setContent: (item) ->
        @getContainerPanel().removeAll()
        @getContainerPanel().add item


    openProjectList: ->
        list = Ext.create 'Manager.ProjectList',
            border:false
        @setContent list