Ext.define 'Manager.Project.Schema.Property',
  constructor: (config) ->
    Ext.apply this, config
    this.originalName = this.name
    this

