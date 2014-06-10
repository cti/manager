Ext.define 'Manager.Project.Schema.Schema',
  constructor: (config) ->
    @models = {}
    for modelConfig in config.models
      model = Ext.create 'Manager.Project.Schema.Model', modelConfig
      @models[model.name] = model
    this

  getModels: -> model for name, model of @models

  getModel: (modelName) -> @models[modelName]

  applyChanges: (modelName, changes) ->
    model = @getModel modelName
    model.applyChanges changes