Ext.define 'Manager.Project.Schema.Schema',
  constructor: (config) ->
    @models = {}
    for modelConfig in config.models
      model = Ext.create 'Manager.Project.Schema.Model', modelConfig
      @models[model.getName()] = model
    this

  getModels: -> model for name, model of @models

  getModel: (modelName) -> @models[modelName]

  applyChanges: (modelName, newModelName, changes) ->
    if not modelName # model is new
      return @createModel newModelName, changes

    if modelName != newModelName
      @models[newModelName] = @models[modelName]
      delete @models[modelName]
      @models[newModelName].setName newModelName
      mngr.project.getModelsGrid().load()

    model = @getModel newModelName
    model.applyChanges changes

  createModel: (name, properties) ->
    pk = []
    propertiesConfig = []
    for property in properties.new
      pk.push(property.data.name) if property.data.pk
      propertiesConfig.push Ext.clone(property.data)
    model = Ext.create 'Manager.Project.Schema.Model',
      name: name
      properties: propertiesConfig
      pk: pk
      references: []
    @models[name] = model
    mngr.project.getModelsGrid().load()

