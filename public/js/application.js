
Ext.define('Manager.Project.ModelEditor.Editor', {
  extend: 'Ext.grid.Panel',
  height: 400,
  width: 800,
  xtype: 'cell-editing',
  initComponent: function() {
    var _this = this;
    this.cellEditing = new Ext.grid.plugin.CellEditing({
      clicksToEdit: 1
    });
    this.cellEditing.on('edit', this.onEditComplete, this);
    this.plugins = [this.cellEditing];
    this.dockedItems = [
      {
        xtype: 'toolbar',
        items: [
          {
            text: 'Add field',
            iconCls: 'icon-add',
            handler: function() {
              return _this.store.add({});
            }
          }, {
            text: 'Delete field',
            iconCls: 'icon-remove',
            disabled: true
          }, '->', {
            text: 'Save',
            iconCls: 'icon-save',
            disabled: true
          }
        ]
      }
    ];
    this.store = Ext.create('Ext.data.Store', {
      fields: ['name', 'comment'],
      proxy: 'memory',
      listeners: {
        update: function(store, record, operation, modifiedFields) {
          return _this.ownerCt.onChange(record, modifiedFields[0], record.get(modifiedFields[0]));
        }
      }
    });
    this.columns = [
      {
        dataIndex: 'name',
        header: 'Name',
        width: 150,
        editor: {
          allowBlank: false
        }
      }, {
        dataIndex: 'type',
        header: 'Type',
        editor: new Ext.form.field.ComboBox({
          typeAhead: true,
          triggerAction: 'all',
          editable: false,
          store: [['integer', 'integer'], ['string', 'string'], ['datetime', 'datetime']]
        })
      }, {
        xtype: 'checkcolumn',
        header: 'PK',
        dataIndex: 'pk',
        width: 50
      }, {
        xtype: 'checkcolumn',
        header: 'NotNull',
        dataIndex: 'required',
        width: 100
      }, {
        header: "FK",
        dataIndex: 'fk',
        width: 50
      }, {
        header: 'Comment',
        flex: 1,
        dataIndex: 'comment',
        editor: {
          allowBlank: true
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
      property.pk = Ext.Array.indexOf(model.pk, name) !== -1;
      fields.push(property);
    }
    return this.store.loadData(fields);
  },
  onEditComplete: function(editor, context) {
    return this.getView().focusRow(context.record);
  }
});


Ext.define('Manager.Project.ModelEditor', {
  extend: 'Ext.window.Window',
  layout: 'vbox',
  modal: true,
  initComponent: function() {
    var _this = this;
    this.title = "Edit model " + this.modelName;
    this.items = [this.getEditor(), this.getChangesTextContainer()];
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
  },
  getChangesTextContainer: function() {
    if (!this.changesText) {
      this.changesText = Ext.create('Ext.Container', {
        style: {
          padding: '5px'
        },
        html: "Changes will appear here",
        height: 200
      });
    }
    return this.changesText;
  },
  onChange: function() {
    var changes, field, propertyChanges, propertyName, strings, value;
    strings = [];
    changes = this.collectChanges();
    for (propertyName in changes) {
      propertyChanges = changes[propertyName];
      for (field in propertyChanges) {
        value = propertyChanges[field];
        if (field === 'pk') {
          if (value === true) {
            strings.push("Added field \"" + propertyName + "\" to PK");
          } else if (value === false) {
            strings.push("Removed field \"" + propertyName + "\" from PK");
          }
        } else {
          strings.push("Changed " + field + " of field \"" + propertyName + "\" to " + value);
        }
      }
    }
    return this.getChangesTextContainer().update(strings.join("<br/>"));
  },
  collectChanges: function() {
    var changes;
    changes = {};
    this.editor.store.queryBy(function(record) {
      var recordChanges;
      recordChanges = record.getChanges();
      if (!Ext.Object.isEmpty(recordChanges)) {
        return changes[record.data.name] = recordChanges;
      }
    });
    return changes;
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

