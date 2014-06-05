
Ext.define('Manager.Project.ModelEditor.Editor', {
  extend: 'Ext.grid.Panel',
  height: 400,
  width: 800,
  initComponent: function() {
    this.store = Ext.create('Ext.data.Store', {
      fields: ['name', 'comment'],
      proxy: 'memory'
    });
    this.columns = [
      {
        dataIndex: 'name',
        header: 'Name',
        width: 150
      }, {
        header: 'PK',
        width: 40,
        renderer: function(v, m, r) {
          if (Ext.Array.indexOf(this.schemaModel.pk, r.data.name) !== -1) {
            return 'X';
          } else {
            return "";
          }
        }
      }
    ];
    return this.callParent(arguments);
  },
  initByModel: function(model) {
    var fields, name, property, _ref;
    this.schemaModel = model;
    console.log(model);
    fields = [];
    _ref = this.schemaModel.properties;
    for (name in _ref) {
      property = _ref[name];
      property.name = name;
      fields.push(property);
    }
    return this.store.loadData(fields);
  }
});


Ext.define('Manager.Project.ModelEditor', {
  extend: 'Ext.window.Window',
  modal: true,
  initComponent: function() {
    var _this = this;
    this.title = "Edit model " + this.modelName;
    this.items = [this.getEditor()];
    this.callParent(arguments);
    this.show();
    return Project.getModelData(mngr.project.data.nick, this.modelName, function(data) {
      return _this.getEditor().initByModel(data);
    });
  },
  getEditor: function() {
    if (!this.editor) {
      this.editor = Ext.create('Manager.Project.ModelEditor.Editor');
    }
    return this.editor;
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
        header: "Name",
        flex: 1
      }
    ];
    this.listeners = {
      itemdblclick: function(view, record) {
        return _this.openModelEditor(record);
      }
    };
    this.callParent(arguments);
    return this.load();
  },
  load: function() {
    var _this = this;
    return Project.getModels(mngr.project.data.nick, function(models) {
      return _this.store.loadData(models);
    });
  },
  openModelEditor: function(model) {
    return Ext.create('Manager.Project.ModelEditor', {
      modelName: model.data.name
    });
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
        html: 'Some content'
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
    return this.callParent(arguments);
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

