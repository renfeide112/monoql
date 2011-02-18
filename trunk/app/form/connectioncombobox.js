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
		initComponent: function() {
			this.store = ui.connectionstore;
			this.on('expand', this.onConnectionComboBoxExpand, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onConnectionComboBoxExpand:function(combo) {
			combo.getStore().sort(combo.displayField, 'ASC');
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();