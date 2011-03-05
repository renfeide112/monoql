Ext.ns('monoql.form');
monoql.form.databasecombobox = function() {
	var cls = 'monoql-form-databasecombobox';
	
	var Store = Ext.extend(Ext.data.Store, {
		constructor: function(config) {
			config = Ext.apply({
				proxy:new Ext.data.DirectProxy({
					api:{
						read:monoql.direct.Connection.getDatabases
					},
					paramsAsHash:true
				}),
				reader:new Ext.data.JsonReader({
					root:'records',
					fields:['id', 'name']
				})
			}, config);
			Store.superclass.constructor.call(this, config);
		}
	});
	
	var Class = Ext.extend(monoql.form.combobox, {
		emptyText:'Set Active Database...',
		forceSelection:true,
		editable:false,
		displayField:'name',
		valueField:'id',
		hiddenName:'activeDatabase',
		mode:'remote',
		triggerAction:'all',
		initComponent: function() {
			this.store = new Store();
			this.store.on('beforeload', this.onDatabaseComboBoxStoreBeforeLoad, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onDatabaseComboBoxStoreBeforeLoad:function(store, options) {
			store.baseParams.connection = ui.connectionstore.getById(ui.toolbar.connectioncombobox.getValue()).data;
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();