Ext.define 'Manager.Project.Schema.Property',
  constructor: (config) ->
    console.log config
    Ext.apply this, config
    this.originalName = this.name
    this

