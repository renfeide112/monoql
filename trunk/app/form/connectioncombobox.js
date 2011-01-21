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
		initComponent: function() {
			this.store = new Ext.data.Store({
				reader:new Ext.data.JsonReader({
					fields:['id', 'name']
				})
			});
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();