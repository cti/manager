Ext.define 'Manager.Project.Schema.Model',
  properties: {}
  constructor: (config) ->
    @name = config.name
    @pk = config.pk
    for propertyConfig in config.properties
      @properties[propertyConfig.name] = Ext.create 'Manager.Project.Schema.Property', propertyConfig
    this

  getName: -> @name
  getProperties: -> property for name, property of @properties
