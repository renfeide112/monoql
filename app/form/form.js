Ext.ns('monoql.form');
monoql.form.form = function() {
	var cls = 'monoql-form-form';
	var Class = Ext.extend(Ext.form.FormPanel, {
		defaults:{
			xtype:'textfield',
			anchor:'-8'
		},
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();