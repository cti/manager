
Ext.define('Manager.Project.Models.Editor.PropertyModel', {
  extend: 'Ext.data.Model',
  fields: ['originalName', 'name', 'pk', 'foreign', 'type', 'comment', 'notNull'],
  idProperty: 'originalName'
});


Ext.define('Manager.Project.Models.Editor.Grid', {
  extend: 'Ext.grid.Panel',
  requires: ['Manager.Project.Models.Editor.PropertyModel'],
  xtype: 'cell-editing',
  initComponent: function() {
    var types,
      _this = this;
    this.cellEditing = new Ext.grid.plugin.CellEditing({
      clicksToEdit: 1
    });
    this.plugins = [this.cellEditing];
    types = [['integer', 'integer'], ['string', 'string'], ['datetime', 'datetime'], ['boolean', 'boolean'], ['clob', 'clob']];
    this.store = Ext.create('Ext.data.Store', {
      model: 'Manager.Project.Models.Editor.PropertyModel',
      proxy: 'memory'
    });
    this.columns = [
      {
        header: 'Name',
        dataIndex: 'name',
        editor: {
          allowBlank: false
        }
      }, {
        xtype: 'checkcolumn',
        header: 'PK',
        dataIndex: 'pk',
        width: 40,
        listeners: {
          checkchange: function(self, rowIndex, checked) {
            var record;
            if (checked) {
              record = _this.store.getAt(rowIndex);
              return record.set('notNull', true);
            }
          }
        }
      }, {
        xtype: 'booleancolumn',
        header: 'FK',
        dataIndex: 'foreign',
        width: 40,
        trueText: 'X',
        falseText: ''
      }, {
        xtype: 'checkcolumn',
        header: 'Not null',
        dataIndex: 'notNull',
        width: 80,
        listeners: {
          beforecheckchange: function(self, rowIndex) {
            var record;
            record = _this.store.getAt(rowIndex);
            if (record.data.pk) {
              return false;
            }
          }
        }
      }, {
        header: 'Type',
        dataIndex: 'type',
        editor: new Ext.form.field.ComboBox({
          typeAhead: true,
          triggerAction: 'all',
          store: types
        })
      }, {
        header: 'Comment',
        dataIndex: 'comment',
        flex: 1,
        editor: {
          allowBlank: true
        }
      }
    ];
    this.dockedItems = [
      {
        xtype: 'toolbar',
        items: [
          {
            text: 'Add field',
            iconCls: 'icon-add',
            handler: function() {
              return _this.store.add({
                name: 'newfield'
              });
            }
          }, {
            name: 'delete_button',
            text: 'Delete field',
            iconCls: 'icon-remove',
            disabled: true,
            handler: function() {
              return _this.store.remove(_this.getSelection()[0]);
            }
          }
        ]
      }
    ];
    this.listeners = {
      selectionchange: function(self, record) {
        return _this.down('[name=delete_button]').setDisabled(!record.length);
      }
    };
    this.callParent(arguments);
    return this.load();
  },
  load: function() {
    var data, property, _i, _len, _ref;
    data = [];
    if (this.model) {
      _ref = this.model.getProperties();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        property = _ref[_i];
        data.push(Ext.clone(property));
      }
    }
    return this.store.loadData(data);
  },
  collectChanges: function() {
    return {
      updated: this.store.getUpdatedRecords(),
      "new": this.store.getNewRecords(),
      removed: this.store.getRemovedRecords()
    };
  }
});


Ext.define('Manager.Project.Models.Editor', {
  extend: "Ext.window.Window",
  resizable: false,
  modal: true,
  layout: 'fit',
  initComponent: function() {
    var _this = this;
    this.title = "Edit model " + (this.model ? this.model.getName() : "newmodel");
    this.items = [this.getForm(), this.getGrid()];
    this.buttons = [
      {
        text: 'Save',
        handler: function() {
          return _this.save();
        }
      }
    ];
    this.callParent(arguments);
    return this.show();
  },
  getGrid: function() {
    if (!this.grid) {
      this.grid = Ext.create('Manager.Project.Models.Editor.Grid', {
        model: this.model,
        height: 400,
        width: 600
      });
    }
    return this.grid;
  },
  getForm: function() {
    if (!this.form) {
      this.form = Ext.create('Ext.form.Panel', {
        width: '100%',
        bodyPadding: 5,
        items: [
          {
            xtype: 'textfield',
            name: 'model_name_field',
            value: this.model ? this.model.getName() : "",
            fieldLabel: 'Model name',
            allowBlank: false
          }
        ]
      });
    }
    return this.form;
  },
  save: function(changes) {
    var name;
    name = this.down('[name=model_name_field]').getValue();
    changes = this.getGrid().collectChanges();
    mngr.project.schema.applyChanges((this.model ? this.model.getName() : null), name, changes);
    this.getGrid().store.queryBy(function(record) {
      return record.commit();
    });
    return this.close();
  }
});


Ext.define('Manager.Project.Models', {
  extend: 'Ext.grid.Panel',
  title: 'Models',
  initComponent: function() {
    var _this = this;
    this.store = Ext.create('Ext.data.Store', {
      fields: ['name'],
      proxy: 'memory'
    });
    this.columns = [
      {
        dataIndex: 'name',
        header: 'Name',
        flex: 1
      }
    ];
    this.listeners = {
      itemdblclick: function(view, record) {
        return _this.openModelEditor(mngr.project.schema.getModel(record.data.name));
      }
    };
    this.tools = [
      {
        id: 'plus',
        handler: function() {
          return _this.openModelEditor(null);
        }
      }
    ];
    return this.callParent(arguments);
  },
  load: function() {
    var data, model, models, _i, _len;
    models = mngr.project.schema.getModels();
    data = [];
    for (_i = 0, _len = models.length; _i < _len; _i++) {
      model = models[_i];
      data.push({
        name: model.getName()
      });
    }
    return this.store.loadData(data);
  },
  openModelEditor: function(model) {
    return Ext.create('Manager.Project.Models.Editor', {
      model: model
    });
  }
});


Ext.define('Manager.Project.Schema.Property', {
  constructor: function(config) {
    console.log(config);
    Ext.apply(this, config);
    this.originalName = this.name;
    return this;
  }
});


Ext.define('Manager.Project.Schema.Model', {
  constructor: function(config) {
    var propertyConfig, referenceConfig, _i, _j, _len, _len1, _ref, _ref1;
    this.name = config.name;
    this.originalName = config.originalName;
    this.properties = {};
    this.pk = config.pk;
    this.references = [];
    _ref = config.properties;
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      propertyConfig = _ref[_i];
      propertyConfig.pk = (Ext.Array.indexOf(config.pk, propertyConfig.name)) !== -1;
      this.properties[propertyConfig.name] = Ext.create('Manager.Project.Schema.Property', propertyConfig);
    }
    _ref1 = config.references;
    for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
      referenceConfig = _ref1[_j];
      this.references.push(referenceConfig);
    }
    return this;
  },
  getName: function() {
    return this.name;
  },
  setName: function(name) {
    return this.name = name;
  },
  getProperties: function() {
    var name, property, _ref, _results;
    _ref = this.properties;
    _results = [];
    for (name in _ref) {
      property = _ref[name];
      _results.push(property);
    }
    return _results;
  },
  getProperty: function(name) {
    return this.properties[name];
  },
  getPk: function() {
    return this.pk;
  },
  applyChanges: function(changes) {
    var key, oldName, property, record, value, _i, _j, _k, _len, _len1, _len2, _ref, _ref1, _ref2, _results;
    _ref = changes["new"];
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      record = _ref[_i];
      property = Ext.create('Manager.Project.Schema.Property', record.data);
      this.properties[record.data.name] = property;
      if (property.pk) {
        this.pk.push(property.name);
      }
    }
    _ref1 = changes.removed;
    for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
      record = _ref1[_j];
      delete this.properties[record.data.name];
      Ext.Array.remove(this.pk, record.data.name);
    }
    _ref2 = changes.updated;
    _results = [];
    for (_k = 0, _len2 = _ref2.length; _k < _len2; _k++) {
      record = _ref2[_k];
      changes = record.getChanges();
      if (changes.name) {
        oldName = record.getModified('name');
        Ext.Array.remove(this.pk, oldName);
        this.properties[record.data.name] = this.properties[oldName];
        delete this.properties[oldName];
      }
      if (changes.pk === true) {
        this.pk.push(record.data.name);
      } else if (changes.pk === false) {
        Ext.Array.remove(this.pk, record.data.name);
      }
      property = this.getProperty(record.data.name);
      _results.push((function() {
        var _results1;
        _results1 = [];
        for (key in changes) {
          value = changes[key];
          _results1.push(property[key] = value);
        }
        return _results1;
      })());
    }
    return _results;
  }
});


Ext.define('Manager.Project.Schema.Schema', {
  constructor: function(config) {
    var model, modelConfig, _i, _len, _ref;
    this.models = {};
    _ref = config.models;
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      modelConfig = _ref[_i];
      modelConfig.originalName = modelConfig.name;
      model = Ext.create('Manager.Project.Schema.Model', modelConfig);
      this.models[model.getName()] = model;
    }
    return this;
  },
  getModels: function() {
    var model, name, _ref, _results;
    _ref = this.models;
    _results = [];
    for (name in _ref) {
      model = _ref[name];
      _results.push(model);
    }
    return _results;
  },
  getModel: function(modelName) {
    return this.models[modelName];
  },
  applyChanges: function(modelName, newModelName, changes) {
    var model;
    if (!modelName) {
      return this.createModel(newModelName, changes);
    }
    if (modelName !== newModelName) {
      this.models[newModelName] = this.models[modelName];
      delete this.models[modelName];
      this.models[newModelName].setName(newModelName);
      mngr.project.getModelsGrid().load();
    }
    model = this.getModel(newModelName);
    return model.applyChanges(changes);
  },
  createModel: function(name, properties) {
    var model, pk, propertiesConfig, property, _i, _len, _ref;
    pk = [];
    propertiesConfig = [];
    _ref = properties["new"];
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      property = _ref[_i];
      if (property.data.pk) {
        pk.push(property.data.name);
      }
      propertiesConfig.push(Ext.clone(property.data));
    }
    model = Ext.create('Manager.Project.Schema.Model', {
      name: name,
      properties: propertiesConfig,
      pk: pk,
      references: []
    });
    this.models[name] = model;
    return mngr.project.getModelsGrid().load();
  }
});


Ext.define('Manager.Project.Card', {
  extend: 'Ext.panel.Panel',
  title: 'Project Card',
  layout: {
    type: 'hbox',
    pack: 'start',
    align: 'stretch'
  },
  initComponent: function() {
    var _this = this;
    this.title = this.project.data.nick + " card";
    this.items = [
      this.getModelsGrid(), {
        xtype: 'container',
        html: ''
      }
    ];
    this.tools = [
      {
        type: 'prev',
        callback: function() {
          return mngr.app.openProjectList();
        }
      }, {
        type: 'save',
        callback: function() {
          return _this.saveSchema();
        }
      }
    ];
    this.callParent(arguments);
    mngr.project = this;
    return this.loadSchema();
  },
  loadSchema: function() {
    var _this = this;
    return Project.getSchema(this.project.data.nick, function(schemaConfig) {
      _this.schema = Ext.create('Manager.Project.Schema.Schema', schemaConfig);
      return _this.getModelsGrid().load();
    });
  },
  getModelsGrid: function() {
    if (!this.modelsGrid) {
      this.modelsGrid = Ext.create('Manager.Project.Models', {
        width: 300
      });
    }
    return this.modelsGrid;
  },
  saveSchema: function() {
    return Project.saveSchema(this.project.data.nick, this.schema);
  }
});


Ext.define('Manager.ProjectList.Form', {
  extend: 'Ext.window.Window',
  title: 'Create project',
  modal: true,
  resizable: false,
  initComponent: function() {
    var _this = this;
    this.items = [this.getForm()];
    this.buttons = [
      {
        text: 'Save',
        handler: function() {
          return _this.save();
        }
      }
    ];
    this.callParent(arguments);
    return this.show();
  },
  getForm: function() {
    if (!this.form) {
      this.form = Ext.create('Ext.form.Panel', {
        bodyPadding: 10,
        layout: 'anchor',
        items: [
          {
            xtype: 'textfield',
            fieldLabel: 'Nick',
            allowBlank: false,
            name: 'nick'
          }, {
            xtype: 'textfield',
            fieldLabel: 'Project root in DocumentRoot',
            emptyText: 'somefolder/myapp',
            allowBlank: false,
            name: 'path'
          }
        ]
      });
    }
    return this.form;
  },
  save: function() {
    var data,
      _this = this;
    if (!this.getForm().isValid()) {
      return;
    }
    data = this.getForm().getValues();
    return Project.add(data, function(response) {
      if (response === true) {
        _this.close();
        return _this.projectList.load();
      }
    });
  }
});


Ext.define('Manager.ProjectList', {
  title: 'Projects',
  extend: 'Ext.grid.Panel',
  store: {
    fields: ['nick', 'path']
  },
  columns: [
    {
      dataIndex: 'nick',
      header: 'Nick',
      width: 120
    }, {
      dataIndex: 'path',
      header: 'Path',
      width: 350
    }, {
      menuDisabled: true,
      width: 40,
      xtype: 'actioncolumn',
      items: [
        {
          iconCls: 'open-project-col',
          tooltip: '123',
          handler: function(grid, rowIndex) {
            var record;
            record = grid.getStore().getAt(rowIndex);
            return grid.ownerCt.openProject(record);
          }
        }
      ]
    }
  ],
  initComponent: function() {
    var _this = this;
    this.tools = [
      {
        id: 'plus',
        handler: function() {
          return Ext.create('Manager.ProjectList.Form', {
            projectList: _this
          });
        }
      }
    ];
    this.callParent(arguments);
    return this.load();
  },
  load: function() {
    var _this = this;
    return Project.getList(function(response) {
      var project;
      _this.store.loadData(response);
      project = _this.store.getAt(0);
      if (project) {
        return _this.openProject(project);
      }
    });
  },
  openProject: function(project) {
    mngr.project = project;
    return mngr.app.setContent(Ext.create('Manager.Project.Card', {
      project: project
    }));
  }
});


Ext.direct.Manager.on({
  exception: function(e) {
    var text;
    text = e.type === 'exception' ? e.result : "" + e.message + "\n" + (e.xhr ? e.xhr.responseText : '');
    alert(text);
    return console.log(text);
  }
});

Ext.direct.Manager.getProvider(0).on({
  call: function() {
    return Ext.getBody().mask("Загрузка");
  },
  data: function() {
    return Ext.getBody().unmask();
  }
});

window.mngr = {};

Ext.define('Manager.Application', {
  extend: 'Ext.Viewport',
  layout: 'border',
  initComponent: function() {
    this.renderTo = Ext.getBody();
    this.items = [this.getContainerPanel()];
    this.callParent(arguments);
    mngr.app = this;
    return this.openProjectList();
  },
  getContainerPanel: function() {
    if (!this.containerPanel) {
      this.containerPanel = Ext.create('Ext.panel.Panel', {
        region: 'center',
        layout: 'fit'
      });
    }
    return this.containerPanel;
  },
  setContent: function(item) {
    this.getContainerPanel().removeAll();
    return this.getContainerPanel().add(item);
  },
  openProjectList: function() {
    var list;
    list = Ext.create('Manager.ProjectList', {
      border: false
    });
    return this.setContent(list);
  }
});


Ext.onReady(function() {
  return Ext.create('Manager.Application');
});

