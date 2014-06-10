Ext.define 'Manager.Project.Schema.Model',
  constructor: (config) ->
    @properties = {}
    @name = config.name
    @pk = config.pk
    for propertyConfig in config.properties
      propertyConfig.pk = (Ext.Array.indexOf config.pk, propertyConfig.name) isnt -1
      @properties[propertyConfig.name] = Ext.create 'Manager.Project.Schema.Property', propertyConfig
    this

  getName: -> @name
  getProperties: -> property for name, property of @properties
  getProperty: (name) -> @properties[name]
  getPk: -> @pk

  applyChanges: (changes) ->
    for record in changes.new
      property = Ext.create 'Manager.Project.Schema.Property', record.data
      @properties[record.data.name] = property
      if property.pk
        @pk.push(property.name)

    for record in changes.removed
      delete @properties[record.data.name]
      Ext.Array.remove @pk, record.data.name

    for record in changes.updated
      changes = record.getChanges()

      if changes.name
        # Move column to another name
        oldName = record.getModified 'name'
        Ext.Array.remove @pk, oldName
        @properties[record.data.name] = @properties[oldName]
        delete @properties[oldName]

      if changes.pk is true
        @pk.push record.data.name
      else if changes.pk is false
        Ext.Array.remove @pk, record.data.name

      property = @getProperty(record.data.name)
      for key, value of changes
        property[key] = value


