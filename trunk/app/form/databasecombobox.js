Ext.ns('monoql.form');
monoql.form.databasecombobox = function() {
	var cls = 'monoql-form-databasecombobox';
	var Class = Ext.extend(monoql.form.combobox, {
		emptyText:'Set Active Database...',
		forceSelection:true,
		editable:false,
		displayField:'name',
		valueField:'id',
		hiddenName:'activeDatabase',
		initComponent: function() {
			this.store = new Ext.data.Store({
				reader:new Ext.data.JsonReader(['id', 'name'])
			});
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();