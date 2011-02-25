Ext.ns('monoql.form');
monoql.form.connectioncombobox = function() {
	var cls = 'monoql-form-connectioncombobox';
	var Class = Ext.extend(monoql.form.combobox, {
		emptyText:'Set Active Connection...',
		forceSelection:true,
		editable:false,
		displayField:'name',
		valueField:'id',
		hiddenName:'activeConnection',
		mode:'local',
		triggerAction:'all',
		lazyInit:false,
		initComponent: function() {
			this.store = ui.connectionstore;
			this.store.on('update', this.onConnectionComboBoxStoreUpdate, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onConnectionComboBoxStoreUpdate:function(store, record, index) {
			if (parseInt(this.getValue())===parseInt(record.id)) {
				this.setRawValue(record.get(this.displayField));
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();