Ext.define 'Manager.Project.Schema.Schema',
  models: {}
  constructor: (config) ->
    for modelConfig in config.models
      model = Ext.create 'Manager.Project.Schema.Model', modelConfig
      @models[model.name] = model
    this

  getModels: -> model for name, model of @models