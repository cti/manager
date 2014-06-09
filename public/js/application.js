
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
        return true;
      }
    };
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
  }
});


Ext.define('Manager.Project.Schema.Property', {
  constructor: function(config) {
    Ext.apply(this, config);
    return this;
  }
});


Ext.define('Manager.Project.Schema.Model', {
  properties: {},
  constructor: function(config) {
    var propertyConfig, _i, _len, _ref;
    this.name = config.name;
    this.pk = config.pk;
    _ref = config.properties;
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      propertyConfig = _ref[_i];
      this.properties[propertyConfig.name] = Ext.create('Manager.Project.Schema.Property', propertyConfig);
    }
    return this;
  },
  getName: function() {
    return this.name;
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
  }
});


Ext.define('Manager.Project.Schema.Schema', {
  models: {},
  constructor: function(config) {
    var model, modelConfig, _i, _len, _ref;
    _ref = config.models;
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      modelConfig = _ref[_i];
      model = Ext.create('Manager.Project.Schema.Model', modelConfig);
      this.models[model.name] = model;
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

