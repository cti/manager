Ext.define 'Manager.Project.Models.Editor.PropertyModel',
  extend: 'Ext.data.Model'
  fields: ['originalName', 'name', 'pk', 'type', 'comment']
  idProperty: 'originalName'