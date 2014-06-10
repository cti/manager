Ext.define 'Manager.Project.Models.Editor.PropertyModel',
  extend: 'Ext.data.Model'
  fields: ['originalName', 'name', 'pk', 'foreign', 'type', 'comment', 'notNull']
  idProperty: 'originalName'